<?php

//version 2.1.2

class base_type
{
    var $_pool_id;
    var $item;

    function base_type($item)
    {
        $this->_pool_id = 0;
        $this->item = $item;
    }

    function fromForm(&$value)
    {
        return 1;
    }

    function toForm($value)
    {
        return $value;
    }

    function toHTML($value)
    {
        return $value;
    }

    function toDB($value)
    {
        return arrayMapRecursive('addslashes', $value);
    }

    function get($name)
    {
        $ca =& pool_get($this->_pool_id, 'object');

        return $ca->define[$this->item][$name];
    }

    function set($name, $value)
    {
        $ca =& pool_get($this->_pool_id, 'object');
        $ca->data[$name] = $value;
    }
}//end of class base_type

//------------------------------------------------------------------------------
function ca_text_url_handler($m)
{
    $url = $m[1];
    $name = $url;
    if (strlen($name) > 70) {
        $t = strpos($name, '/', 8) + 1;
        if ($t === false) {
            $t = 50;
        }
        $rest = substr($name, $t);
        $name = substr($name, 0, $t) . '...';
        if (preg_match_all('/[^a-zA-Z0-9]+[a-zA-Z0-9]+/u', $rest, $rest_a)) {
            $rest_a = array_reverse($rest_a[0]);
            $rest_str = array();
            $restr_cnt = 0;
            foreach ($rest_a as $item) {
                $t = strlen($item);
                if ($restr_cnt + $t > 60) {
                    if ($restr_cnt < 15) {
                        $rest_str[] = substr($item, $restr_cnt - 60);
                    }
                    break;
                }
                $restr_cnt += $t;
                $rest_str[] = $item;
            }
            $name .= implode('', array_reverse($rest_str));
        } else {
            $name .= substr($rest, $t - 70);
        }
    }

    return '<a href="' . $url . '">' . $name . '</a>';
}

class ca_text extends base_type
{
    function fromForm(&$value)
    {
        $prop = $this->get('base_type_param');

        if (!isset($prop['typo'])) {
            $prop['typo'] = array();
        }
        if (!isset($prop['no-typo'])) {
            $value = typo($value, $prop['typo']);
        }
        $value = htmlspecialchars($value, ENT_QUOTES);
        if (!isset($prop['no-br'])) {
            $value = str_replace(array("\r", "\n"), array('', '<br />'), $value);
        }

        //if(!isset($prop['no-links'])) $value=trim(preg_replace_callback('/(http:\/\/[^\s<]+[^\s\.,\!\?:;)<\]])/u','ca_text_url_handler',' '.$value.' '));
        return 1;
    }

    function toForm($value)
    {
        $prop = $this->get('base_type_param');
        if (!isset($prop['no-br'])) {
            $value = str_replace('<br />', "\n", $value);
        }
        if (!isset($prop['no-links'])) {
            $value = preg_replace('/<a href="([^"]+)">[^<]+<\/a>/u', '\1', $value);
        }

        return $value;
    }
}

//------------------------------------------------------------------------------
class ca_email extends base_type
{
    function fromForm(&$value)
    {
        $r = ca_text::fromForm($value);
        if ($value == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }

            return 1;
        }
        if (!preg_match('/^[a-z0-9]{1}[a-z0-9._-]*\@[a-z0-9._-]+\.[a-z]{1,5}$/ui', $value, $match)) {
            $value = $this->get('default');

            return 0;
        }
        $value = $match[0];

        return $r;
    }
}

//------------------------------------------------------------------------------

class ca_url_key extends base_type
{
    function fromForm(&$value)
    {
        $prop = $this->get('base_type_param');

        $ca =& pool_get($this->_pool_id, 'object');
        if (is_array($prop['to-url'])) {
            foreach ($prop['to-url'] as $t) {
                $value[] = $ca->data[$t];
            }
            $value = implode('_', $value);
        } else {
            $value = $ca->data[$prop['to-url']];
        }

        $value = strToUrl($value);

        if ($value == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

        return 1;
    }

    function toForm($value)
    {
        return htmlspecialchars($value, ENT_QUOTES);
    }
}

//------------------------------------------------------------------------------

class ca_code extends base_type
{
    function fromForm(&$value)
    {
        if ($value == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

        return 1;
    }

    function toForm($value)
    {
        return htmlspecialchars($value, ENT_QUOTES);
    }
}

//------------------------------------------------------------------------------
class ca_enum extends base_type
{
    function fromForm(&$value)
    {
        if ($value == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }

            return 1;
        }

        if (substr($value, 0, 5) != "//php") {
            $rez = array();
            $t = explode("\n", $value);
            $tt = 0;
            foreach ($t as $item) {
                if (($t = strpos($item, '=')) !== false) {
                    $key = trim(substr($item, 0, $t));
                    $rez[$key] = trim(substr($item, $t + 1));
                } else {
                    $item = trim($item);
                    $rez[$item] = $item;
                }
            }

            $value = '$data=' . var_export($rez, true) . ';';
        }

        return 1;
    }

    function toForm($value)
    {
        $rez = '';
        $data = array();
        if (substr($value, 0, 5) != "//php") {
            eval($value);
            foreach ($data as $k => $v) {
                if ($k == $v) {
                    $rez .= $v . "\n";
                } else {
                    $rez .= $k . ' = ' . $v . "\n";
                }
            }
            $value = $rez;
        }

        return $value;
    }
}

//------------------------------------------------------------------------------

class ca_html extends base_type
{
    function fromForm(&$value)
    {
        $prop = $this->get('base_type_param');
        $t = trim(str_replace('&nbsp;', '', strip_tags($value, '<img><object><iframe>')));
        if ($t == '') {
            $value = $this->get('default');
            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

//	if(!isset($prop['no-br']))	$value=str_replace("\n",'<br />',$value);
        if (!isset($prop['typo'])) {
            $prop['typo'] = array();
        }
        if (!isset($prop['no-typo'])) {
            $value = typo($value, $prop['typo']);
        }

        return 1;
    }

    function toForm($value)
    {
        $prop = $this->get('base_type_param');

//	if(!isset($prop['no-br']))	$value=str_replace('<br />',"\n",$value);

        return htmlspecialchars($value, ENT_QUOTES);
    }
}

//------------------------------------------------------------------------------

class ca_string extends ca_text
{
    function fromForm(&$value)
    {
        $r = ca_text::fromForm($value);
        if (strpos($value, "<br />") !== false) {
            $value = $this->get('default');

            return 0;
        }

        return $r;
    }
}

//------------------------------------------------------------------------------

class ca_int extends ca_text
{
    function fromForm(&$value)
    {
        $r = ca_text::fromForm($value);
        if (!is_numeric($value)) {
            $value = $this->get('default');

            return 0;
        }

        return $r;
    }
}

//------------------------------------------------------------------------------

class ca_flags extends base_type
{
    function fromForm(&$value)
    {
        $value = serialize($value);

        return 1;
    }

    function toForm($value)
    {
        if ($value == '') {
            $value = array();
        } else {
            $value = unserialize($value);
        }

        return $value;
    }

    function toHTML($value)
    {
        if ($value == '') {
            $value = array();
        } else {
            $value = unserialize($value);
        }

        return $value;
    }
}

//------------------------------------------------------------------------------

class ca_date extends base_type
{
    function fromForm(&$value)
    {
        if ($value == '--' || $value == '-') {
            $value = 0;

            return 1;
        }
        if (!$value = $this->parseDate($value)) {
            $value = $this->get('default');

            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

        return 1;
    }

    function parseDate($value)
    {
        $value = str_replace(array(' ', ',', '-', '/'), '.', $value);
        $value = explode('.', $value);
        if (count($value) < 3) {
            return false;
        }

        return mktime(0, 0, 0, $value[1], $value[0], $value[2]);
    }

    function toForm($value)
    {
        if ($value == 0) {
            return '--';
        }
        if (@!$value = date('d.m.Y', $value)) {
            $value = date('d.m.Y');
        }

        return $value;
    }

    function toHTML($value)
    {
        if ($value == 0) {
            return '--';
        }

        return tsToDate($value);
    }
}

//------------------------------------------------------------------------------

class ca_date_time extends base_type
{
    function fromForm(&$value)
    {
        if ($value == '--' || $value == '-') {
            $value = 0;

            return 1;
        }
        if (!$value = $this->parseDate($value)) {
            $value = $this->get('default');

            if ($this->get('necessary') == 1) {
                return 0;
            }
        }

        return 1;
    }

    function parseDate($value)
    {
        $value = str_replace(array(' ', ',', '-', '/', ':'), '.', $value);
        $value = explode('.', $value);
        if (count($value) < 3) {
            return false;
        }
        $value[] = 0;
        $value[] = 0;
        $value[] = 0;

        return mktime($value[3], $value[4], $value[5], $value[1], $value[0], $value[2]);
    }

    function toForm($value)
    {
        if ($value == 0) {
            return '--';
        }
        if (@!$value = date('d.m.Y H:i:s', $value)) {
            $value = date('d.m.Y H:i:s');
        }

        return $value;
    }

    function toHTML($value)
    {
        if ($value == 0) {
            return '--';
        }

        return date('d.m.Y H:i:s', $value);
    }
}

//------------------------------------------------------------------------------

/*- bbCode Tags -b*/
$bbcode_in = array(

    '/\[b\]/'              => '<b>',
    '/\[\/b\]/'            => '</b>',
    '/\[i\]/'              => '<i>',
    '/\[\/i\]/'            => '</i>',
    '/\[u\]/'              => '<u>',
    '/\[\/u\]/'            => '</u>',
    '/\[color ([#\w]+)\]/' => '<font color="\1">',
    '/\[\/color\]/'        => '</font>',
);

$bbcode_out = array(

    '/<b>/'                     => '[b]',
    '/<\/b>/'                   => '[/b]',
    '/<i>/'                     => '[i]',
    '/<\/i>/'                   => '[/i]',
    '/<u>/'                     => '[i]',
    '/<\/u>/'                   => '[/u]',
    '/<font color="([#\w]+)">/' => '[color \1]',
    '/<\/font>/'                => '[/color]',
);

/*- bbCode Tags -e*/

class ca_bbcode extends base_type
{
    function fromForm(&$value)
    {
        global $bbcode_in, $bbcode_out;

        $value = htmlspecialchars($value, ENT_QUOTES);
        $value = str_replace("\n", '<br>', $value);

        $value = preg_replace(array_keys($bbcode_in), array_values($bbcode_in), $value);

        return 1;
    }

    function toForm($value)
    {
        global $bbcode_out, $bbcode_in;

        $value = preg_replace(array_keys($bbcode_out), array_values($bbcode_out), $value);
        $value = str_replace('<br>', "\n", $value);

        return $value;
    }
}

//------------------------------------------------------------------------------

class ca_file extends base_type
{
    function fromForm(&$value)
    {
        if (!$t = handleUploadedFile('_file_' . $this->get('name'))) {
            if (!is_file(FILE_DIR . $value)) {
                $value = $this->get('default');
                if ($this->get('necessary') == 1) {
                    return 0;
                }
            }
        } else {
            $value = $t;
        }

        return 1;
    }

    function toForm($value)
    {
        if (!is_file(FILE_DIR . $value)) {
            $value = '';
        }

        return $value;
    }
}

//------------------------------------------------------------------------------

class ca_image extends ca_file
{
    function fromForm(&$value)
    {
        $ret = ca_file::fromForm($value);

        if ($ret == 1 && is_file(FILE_DIR . $value)) {
            if (!$size = getimagesize(FILE_DIR . $value)) {
                $value = $this->get('default');
                if ($this->get('necessary') == 1) {
                    return 0;
                }
            } else {
                $prop = $this->get('base_type_param');
                if (isset($prop['previews'])) {
                    include_once LIB_DIR . 'ext/images.php';
                    foreach ($prop['previews'] as $t) {
                        $tt = explode('x', $t);
                        if (!isset($tt[1])) {
                            $tt[1] = 0;
                        }
                        if (!isset($tt[2])) {
                            $tt[2] = 0;
                        }
                        createPreview(FILE_DIR . $value, FILE_DIR . $value . '_' . $t, $tt[0], $tt[1], $tt[2]);
                    }
                }
            }
        }

        return $ret;
    }
}

//------------------------------------------------------------------------------

class ca_files extends base_type
{
    function fromForm(&$value)
    {

        $rez = 0;
        $r = array();
        $cnt = 0;
        $name = $this->get('name');

        if (is_array($value)) {
            foreach ($value as $temp) {
                if (isset($temp['file']) && isset($temp['real']) && isset($temp['desc'])) {
                    $t = 0;
                    if (isset($temp['delete'])) {
                        $t = 1;
                        $ca->error['_file_deleted_ok'] = 1;
                        unlink(FILE_DIR . $temp['file']);
                    }
                    if (!is_file(FILE_DIR . $temp['file'])) {
                        $t = 1;
                    }
                    if ($t == 0) {
                        $r[$cnt]['file'] = htmlspecialchars($temp['file'], ENT_QUOTES);
                        $r[$cnt]['real'] = htmlspecialchars($temp['real'], ENT_QUOTES);
                        $r[$cnt]['desc'] = htmlspecialchars(typo($temp['desc']), ENT_QUOTES);
                        $cnt++;
                        $rez = 1;
                    }
                }
            }
        }

        if ($_add = handleUploadedFile('_files_add_' . $name)) {

            $r[$cnt]['file'] = $_add;
            $r[$cnt]['real'] = $_FILES['_files_add_' . $name]['name'];
            $r[$cnt]['desc'] = htmlspecialchars($_POST['_files_add_desc_' . $name], ENT_QUOTES);
            $cnt++;
            $rez = 1;
            $ca =& pool_get($this->_pool_id, 'object');
            $ca->error['_file_added_ok'] = 1;
        }

        $value = serialize($r);

        if ($this->get('necessary') == 0) {
            $rez = 1;
        }

        return $rez;
    }

    function toForm($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }

    function toHTML($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }
}

//------------------------------------------------------------------------------

class ca_image_list extends base_type
{
    function fromForm(&$value)
    {
        $rez = 0;
        $r = array();
        $cnt = 0;
        $ca = &pool_get($this->_pool_id, 'object');
        if (is_array($value)) {
            $value = json_decode($value['files'], true);
            foreach ($value as $temp) {
                if (isset($temp['file']) && isset($temp['real']) && isset($temp['desc'])) {
                    $t = 0;
                    if (isset($temp['delete'])) {
                        $t = 1;
//						unlink(FILE_DIR . $temp['file']);
                    }
                    if (!is_file(FILE_DIR . $temp['file'])) {
                        $t = 1;
                    }
                    if ($t == 0) {
                        $r[$cnt]['file'] = htmlspecialchars($temp['file'], ENT_QUOTES);
                        $r[$cnt]['real'] = htmlspecialchars($temp['real'], ENT_QUOTES);
                        $r[$cnt]['desc'] = htmlspecialchars(typo($temp['desc']), ENT_QUOTES);
                        $cnt++;
                        $rez = 1;
                    }
                }
            }
        }
        $value = serialize($r);
        if ($this->get('necessary') == 0) {
            $rez = 1;
        }

        return $rez;
    }

    function toForm($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }

    function toHTML($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }
}

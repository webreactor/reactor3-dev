<?php

namespace reactor\base_type;

class ca_image_list extends base_type
{
    public function fromForm(&$value)
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
//                        unlink(FILE_DIR . $temp['file']);
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

    public function toForm($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }

    public function toHTML($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }
}

<?php

###############
# Error Codes #
###############
/*
Error 3: Wrong HELO request.
Error 5: Wrong login\password.
Error 10: Can't open file.
Error 12: Wrong field 'FROM' in header.
Error 14: Unknown recipent or user is not local.
Error 20: Can't QUIT right now.
*/

require_once 'socket_client.php';

function encode_field_t($match)
{
    return $match[1] . '="' . encode_field($match[2]) . '"';
}

function encode_field($fld)
{
    
    $t   = explode(')|_', wordwrap($fld, 40, ')|_ '));
    $r   = '';
    $cnt = count($t);
    for ($i = 0; $i < $cnt; $i++) {
        $r .= '=?utf-8?B?' . base64_encode($t[$i]) . "?=\r\n\t";
    }
    
    return substr($r, 0, -3);
}

class smtp extends socket_client
{
    var $srv;
    var $login;
    var $pwd;
    var $last_reply;
    var $letter;
    var $text;
    var $header_fields = array(
        'from',
        'date',
        'to',
        'cc',
        'return-path',
        'subject',
        'mime-version',
        'content-type',
        'content-transfer-encoding',
        'reply-to',
        'sender',
        'content-disposition',
        'content-id',
    );
    
    function smtp($srv, $login, $pwd)
    {
        $this->srv   = $srv;
        $this->login = $login;
        $this->pwd   = $pwd;
        $this->connect($srv, 25);
        $this->put('HELO ' . $this->get_answ(1));
        if ($this->get_answ() != '250') {
            $this->error = 3;
        } //Error 3: Wrong HELO request.
        else {
            $this->put('AUTH LOGIN');
            if ($this->get_answ() == '334') {
                $this->put(base64_encode($login));
                $this->get();
                $this->put(base64_encode($pwd));
                if ($this->get_answ() != '235') {
                    $this->error = 5;
                } //Error 5: Wrong login\password.
            }
        }
        $this->error = 0;
    }
    
    function get_answ($i = 0)
    {
        $this->last_reply = explode(' ', $this->get());
        
        return $this->last_reply[$i];
    }
    
    function new_letter($text, $from, $subj = 'None', $type = 0)
    {
        $this->letter                           = array();
        $bound                                  = md5(microtime());
        $this->letter['header']['from']         = $from;
        $this->letter['header']['sender']       = $from;
        $this->letter['header']['reply-to']     = $from;
        $this->letter['header']['return-path']  = $from;
        $this->letter['header']['subject']      = $subj;
        $this->letter['header']['content-type'] = 'multipart/related; boundary="' . $bound . '"';
        if ($type == 0) {
            $this->letter['data'][] = array(
                'header' =>
                    array('content-type' => 'text/plain'),
                'data'   => UTF8tocp1251($text),
            );
        } else {
            $this->letter['data'][] = array(
                'header' =>
                    array('content-type' => 'text/html'),
                'data'   => UTF8tocp1251($text),
            );
        }
    }
    
    function inline($fname, $disposition = 'inline')
    {
        $this->attach($fname, $disposition);
    }
    
    function attach($fname, $disposition = 'attachment')
    {
        if (!is_file($fname)) {
            $this->error = 10;
        } //Error 10: Can't open file.
        $fd   = fopen($fname, 'r');
        $file = fread($fd, filesize($fname));
        fclose($fd);
        $type                   = mime_content_type($fname);
        $fname                  = basename($fname);
        $this->letter['data'][] = array(
            'header' =>
                array(
                    'content-type'              => $type . '; name="' . $fname . '"',
                    'content-transfer-encoding' => 'base64',
                    'content-id'                => '<' . $fname . '>',
                    'content-disposition'       => $disposition . '; filename="' . $fname . '"',
                ),
            'data'   => $file,
        );
    }
    
    function send($to)
    {
        $this->put('NOOP');
        if ($this->get_answ() == '250') {
            $this->letter['header']['to'] = $to;
            $data                         = $this->get_raw($this->letter);
            $this->put('MAIL FROM: ' . $this->letter['header']['from']);
            if ($this->get_answ() != '250') {
                $this->error = 12;
            } //Error 12: Wrong field 'FROM' in header.
            $this->put('RCPT TO: ' . $to);
            if ($this->get_answ() == '250') {
                $this->put('DATA');
                $this->get();
                $this->put($data);
                $this->put('.');
                if ($this->get_answ() == '250') {
                    $this->put('RSET');
                    $this->get();
                    
                    return 1;
                }
            } else {
                $this->error = 14;
            } //Error 14: Unknown recipent or user is not local.
        } else {
            $this->smtp($this->srv, $this->login, $this->pwd);
            $this->send($to);
        }
    }
    
    function quit()
    {
        $this->put("QUIT");
        if ($this->get_answ() != '221') {
            $this->error = 20;
        } //Error 20: Can't QUIT right now.
        $this->close();
    }
    
    function combine_head(&$header)
    {
        $bound = md5(microtime());
        
        $type = substr($header['content-type'], 0, 4);
        if ($type == 'text') {
            if (strpos($header['content-type'], 'charset') > 0) {
                $header['content-type'] = preg_replace(
                    '/charset="?([^;"]+)"?/is',
                    'charset="windows-1251"',
                    $header['content-type']
                );
            } else {
                $header['content-type'] .= '; charset="windows-1251"';
            }
            
            unset($header['content-transfer-encoding']);
        } else {
            if ($type == 'mult') {
                $header['content-type'] = 'multipart/mixed;  boundary="' . $bound . '"';
            } else {
                $header['content-transfer-encoding'] = 'base64';
            }
        }
        
        $header['content-type'] = preg_replace_callback(
            '/(name)="?([^;"]+)"?/is',
            'encode_field_t',
            $header['content-type']
        );
        
        if (isset($header['content-disposition'])) {
            $header['content-disposition'] = preg_replace_callback(
                '/(filename)="?([^;"]+)"?/is',
                'encode_field_t',
                $header['content-disposition']
            );
        }
        
        foreach ($header as $key => $val) {
            if (in_array($key, $this->header_fields)) {
                $this->text .= ucfirst($key) . ': ' . wordwrap($val, 75, "\r\n\t") . "\r\n";
            }
        }
        $this->text .= "\r\n";
        
        return $bound;
    }
    
    function combine_part_r($part)
    {
        
        $bound = $this->combine_head($part['header']);
        
        if (substr($part['header']['content-type'], 0, 5) == 'multi') {
            
            foreach ($part['data'] as $multi) {
                $this->text .= '--' . $bound . "\r\n";
                $this->text .= $this->combine_part_r($multi) . "\r\n";
            }
            $this->text .= '--' . $bound . '--';
        } else {
            if (isset($part['header']['content-transfer-encoding'])) {
                $this->text .= chunk_split(base64_encode($part['data']));
            } else {
                $this->text .= $part['data'];
            }
        }
    }
    
    function get_raw($data)
    {
        $this->text = '';
        if (!isset($data['header']['from'])/* or !isset($data['header']['to'])*/) {
            return 0;
        }
        
        if (!isset($data['header']['date'])) {
            $data['header']['date'] = date("r");
        }
        if (!isset($data['header']['mime-version'])) {
            $data['header']['mime-version'] = "1.0";
        }
        if (!isset($data['header']['content-type'])) {
            $data['header']['content-type'] = "text/plain";
        }
        
        if (!isset($data['header']['subject'])) {
            $data['header']['subject'] = 'None';
        } else {
            $data['header']['subject'] = encode_field($data['header']['subject']);
        }
        
        $this->combine_part_r($data) . "\r\n";
        
        return $this->text;
    }
}

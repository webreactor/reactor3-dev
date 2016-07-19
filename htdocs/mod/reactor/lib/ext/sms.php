<?php

class sms_sender
{
    var $login;
    var $pass;
    var $from;
    var $host;
    var $messages = array();
    
    function sms_sender($login, $pass, $from, $host)
    {
        $this->login = $login;
        $this->pass  = $pass;
        $this->from  = $from;
        $this->host  = $host;
    }
    
    function prepare_phone($str)
    {
        $num_chars = '';
        foreach (str_split($str) as $char) {
            if (is_numeric($char)) {
                $num_chars .= $char;
            }
        }
        if (strlen($num_chars) == 10) {
            if ($num_chars[0] == '9') {
                $num_chars = '7' . $num_chars;
            }// десятизначный ввод
        }
        if (strlen($num_chars) == 11) {
            if (substr($num_chars, 0, 4) == '8800') {
                return 0;
            }// 8-800-***** нафиг
            if ($num_chars[1] != '9') {
                return 0;
            }// только федеральные мобильники - коды 9ХХ
            if ($num_chars[0] == '8') {
                $num_chars[0] = '7';
            }// к общему формату
            $num_chars = '+' . $num_chars;
        } else {
            return 0;
        }
        
        return $num_chars;
    }
    
    function add_sms($to, $text)
    {
        $to = $this->prepare_phone($to);
        if ($to === 0) {
            return 0;
        }
        $this->messages[] = array('to' => $to, 'text' => $text);
    }
    
    function send($to = '', $text = '')
    {
        ini_set('error_log', SITE_DIR . '../sms.log');
        error_log('-----------------------------');
        if ($to != '' && $text != '') {
            $this->add_sms($to, $text);
        }
        
        if (empty($this->messages)) {
            return 0;
        }
        
        $req = '<?xml version="1.0" encoding="utf-8" ?>' . "\r\n";
        $req .= '<package login="' . $this->login . '" password="' . $this->pass . '">' . "\r\n";
        $req .= "\t<message>\r\n";
        $req .= "\t\t" . '<default sender="change_it" type="0" />' . "\r\n";
        foreach ($this->messages as $sms) {
            $req .= "\t\t" . '<msg recipient="' . $sms['to'] . '">' . htmlspecialchars(
                    $sms['text'],
                    ENT_QUOTES
                ) . '</msg>' . "\r\n";
        }
        $req .= "\t</message>\r\n";
        $req .= '</package>';
        
        $h = fsockopen($this->host, 80);
        if ($h) {
            $answer = '';
            $post   = 'POST / HTTP/1.1' . "\r\n" .
                'Host: ' . $this->host . " \r\n" .
                //'Content-Type: application/x-www-form-urlencoded'."\r\n".
                'Content-Length: ' . strlen($req) . "\r\n" .
                'Connection: close' . "\r\n" .
                "\r\n" . $req;
            
            error_log($post);
            
            fwrite($h, $post);
            while (!feof($h)) {
                $answer .= fgets($h, 1024);
            }
            fclose($h);
            
            $answer = explode("\r\n\r\n", $answer);
            if (stripos($answer[0], 'chunked') !== false) {
                $answer[1] = http_chunked_decode($answer[1]);
            }
            
            error_log('-------- answer:');
            error_log(print_r($answer, true));
        }
    }
}

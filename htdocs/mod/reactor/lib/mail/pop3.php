<?php

###############
# Error Codes #
###############
/*
Error 3: Wrong server reply on connect.
Error 5: Wrong login\password.
Error 11: There's no message with this ($i) number.
*/

require_once 'socket_client.php';

function decode_quoted($str)
{
    return quoted_printable_decode(str_replace('_', ' ', $str));
}

function decode_field_r($match)
{

    $str = $match[3];
    if ($match[2][0] == 'Q' || $match[2][0] == 'q') {
        $str = decode_quoted($str);
    }
    if ($match[2][0] == 'B' || $match[2][0] == 'b') {
        $str = base64_decode($str);
    }

    decode_to_utf8($str, $match[1]);

    return trim($str);
}

function decode_to_utf8(&$str, $charset)
{
    if ($charset == 'windows-1251') {
        $charset = 'cp1251';
    }
    $str = iconv($charset, 'UTF-8', $str);
}

class pop3 extends socket_client
{
    public $data;
    public $text;
    public $html;
    public $inline;
    public $attachment;
    public $srv;
    public $login;
    public $pwd;
    public $last_reply;
    public $error;
    public $header;

    function pop3($srv, $login, $pwd)
    {
        $this->srv   = $srv;
        $this->login = $login;
        $this->pwd   = $pwd;
        $this->connect($srv, 110);
        if ($this->get_answ() != '+OK') {
            $this->error = 3;
        } //Error 3: Wrong server reply on connect.
        else {
            $this->put('AUTH LOGIN');
            if ($this->get_answ() == '+') {
                $this->put(base64_encode($login));
                $this->get();
                $this->put(base64_encode($pwd));
                if ($this->get_answ() != '+OK') {
                    $this->error = 5;
                } //Error 5: Wrong login\password.
            } else {
                $this->put('USER ' . $login);
                $this->get();
                $this->put('PASS ' . $pwd);
                if ($this->get_answ() != '+OK') {
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

    function count_messages()
    {
        $this->put('STAT');

        return $this->get_answ(1);
    }

    function get_header($i)
    {
        $this->put('TOP ' . $i . ' 0');
        if ($this->get_answ() == '+OK') {
            $hdr = '';
            while (1) {
                $t = $this->get();
                if ($t == ".\r\n") {
                    break;
                }
                $hdr .= $t;
            }
        } else {
            $this->error = 11; //Error 11: There's no message with this ($i) number.
            return 0;
        }

        return $hdr;
    }

    function get_list()
    {
        $res = array();
        $c   = $this->count_messages();
        for ($i = 1; $i <= $c; $i++) {
            $this->check_connect();
            $res[$i] = $this->parce_header($this->get_header($i));
        }

        return $res;
    }

    function get_messages()
    {
        $let = array();
        $c   = $this->count_messages();
        for ($i = 1; $i <= $c; $i++) {
            $this->check_connect();
            $l = $this->get_header($i);
            $this->put('RETR ' . $i);
            if ($this->get_answ() == '+OK') {
                while (1) {
                    $t = $this->get();
                    if ($t == ".\r\n") {
                        break;
                    }
                    $l .= $t;
                }
            }
            $let[$i] = $this->parce_r($l);
            $this->put('DELE ' . $i);
            $this->get();
        }

        return $let;
    }

    function check_connect()
    {
        $this->put('NOOP');
        if ($this->get_answ() != "+OK\r\n") {
            $this->pop3($this->srv, $this->login, $this->pwd);
        }
    }

    function quit()
    {
        $this->put('QUIT');
        $this->get();
        $this->close();
    }

    function decode_field($str)
    {
        $str = str_replace("\r\n\t", ' ', $str);
        $str = str_replace("\r\n ", '', $str);

        return preg_replace_callback('/=\?(.+)\?(.)\?(.*)\?=/Uis', 'decode_field_r', $str);
    }

    function parce_header($str)
    {
        preg_match_all('/([\w-]+):(?:\s+)(.+)\r\n(?!\s)/Umis', $str . "\r\nx", $m);

        $header = array();

        foreach ($m[2] as $k => $item) {
            $header[strtolower($m[1][$k])] = $this->decode_field($item);
        }

        if (!isset($header['content-type'])) {
            $header['content-type'] = 'text/plain; charset=windows-1251';
        }

        return $header;
    }

    function decode_body(&$body, &$header)
    {
        if (isset($header['content-transfer-encoding'])) {
            if ($header['content-transfer-encoding'][0] == 'q') {
                $body = quoted_printable_decode($body);
            }
            if ($header['content-transfer-encoding'][0] == 'b') {
                $body = base64_decode($body);
            }
        }

        if ($header['c/t-content'][0] == 't') {
            $body = str_replace("\r\n..", "\r\n.", $body);
            preg_match('/charset="?([^;"]+)"?/is', $header['content-type'], $charset);
            if (isset($charset[1])) {
                $charset = $charset[1];
            } else {
                $charset = 'windows-1251';
            }
            decode_to_utf8($body, $charset);

            return $body;
        }

        preg_match('/name="?([^;"]+)"?/is', $header['content-type'], $name);
        if (isset($charset[1])) {
            $name = $name[1];
        } else {
            $name = 'none';
        }

        if (isset($header['content-disposition'])) {
            preg_match('/"?([^;\b]+)"?;?/is', $header['content-disposition'], $disposition);
        }

        return $body;
    }

    function parce_body_multi(&$body)
    {
        $data     = array();
        $boundary = substr($body, $now = strpos($body, '--'), strpos($body, "\r\n", $now) - $now);

        $blen = strlen($boundary);

        $now = $now + $blen + 2;
        while (($next = strpos($body, $boundary, $now)) !== false) {
            $data[] = $this->parce_r($t = substr($body, $now, $next - $now));
            $now    = $next + $blen + 2;
        }

        return $data;
    }

    function parce_r(&$str)
    {
//echo $str,'----------------------------------------------------------';
        $data           = array();
        $header_stream  = substr($str, 0, $t = strpos($str, "\r\n\r\n"));
        $data['header'] = $this->parce_header($header_stream);

        $body_stream = substr($str, $t + 4);

        $data['header']['c/t-content'] = substr(
            $data['header']['content-type'],
            0,
            $t = strpos($data['header']['content-type'], '/')
        );
        $data['header']['c/t-type']    = substr(
            $data['header']['content-type'],
            $t += 1,
            $t = strpos($data['header']['content-type'] . ';', ';') - $t
        );

        if ($data['header']['c/t-content'] == 'multipart') {
            $data['data'] = $this->parce_body_multi($body_stream);

            return $data;
        }

        $data['data'] = $this->decode_body($body_stream, $data['header']);

        return $data;
    }

    function parce(&$str)
    {
        $this->data = $this->parce_r($str);
        $header     = array(
            'date',
            'subject',
            'from',
            'to',
            'reply',
            'subject',
        );

        foreach ($header as $item) {
            if (isset($this->data['header'][$item])) {
                $this->header[$item] = $this->data['header'][$item];
            } else {
                $this->header[$item] = 'none';
            }
        }
    }
}

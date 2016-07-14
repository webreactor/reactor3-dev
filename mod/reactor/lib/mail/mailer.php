<?php

//version 1.1
class mailer
{
    var $text;
    var $server;
    var $boundary;
    var $from;
    var $ans;

    function mailer($server, $from, $user = '', $pass = '')
    {
//$this->server = fopen ('log.log', 'w');
        $this->server = fsockopen($server, 25);
        if (!$this->server) {
            return "<br><b>Mailer Error:</b> Can't open $server!<br>";
        }
        $t = fgets($this->server);
        $t = explode(' ', $t);
        $t = $t[1];
        fputs($this->server, "HELO $t\r\n");
        $this->ans = fgets($this->server);

        if ($user != '') {
            fputs($this->server, "AUTH LOGIN\r\n");
        }
        $this->ans .= fgets($this->server);

        fputs($this->server, base64_encode($user) . "\r\n");
        $this->ans .= fgets($this->server);

        fputs($this->server, base64_encode($pass) . "\r\n");
        $this->ans .= fgets($this->server);

        $this->from = $from;
        $this->text = '';

        return 1;
    }

    function letter($subject = '', $message = '', $type = 0)
    {
        $this->boundary = md5(time());
        $boundary = $this->boundary;
        $this->text = "Subject: $subject\r\n";

        $this->text .= "Content-Type: multipart/related; boundary=\"$boundary\"\r\n\r\n";
        $this->text .= "--$boundary\r\n";
        if ($type == 0) {
            $this->text .= "Content-Type: text/plain; charset=windows-1251\r\n";
        } else {
            $this->text .= "Content-Type: text/html; charset=windows-1251\r\n";
        }
        $this->text .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $this->text .= $message . "\r\n\r\n";
    }

    function file($fname, $type)
    {
        $boundary = $this->boundary;
        if (!is_file($fname)) {
            return "<br><b>Mailer Error:</b> Can't open file $fname!<br>";
        }
        $fd = fopen($fname, 'r');
        $file = fread($fd, filesize($fname));
        $filedata = chunk_split(base64_encode($file));
        fclose($fd);
        $fname = basename($fname);
        $this->text .= "--$boundary\r\n";
        $this->text .= "Content-Type: $type; name=\"$fname\"\r\n";
        $this->text .= "Content-Transfer-Encoding: base64\r\n";
        $this->text .= "Content-ID: <$fname>\r\n";
        $this->text .= "Content-Disposition: inline; filename=\"$fname\"\r\n\r\n";
        $this->text .= "$filedata\r\n";
    }

    function send($to)
    {
        $boundary = $this->boundary;
        $from = $this->from;
        if ($this->text == '') {
            return "<br><b>Mailer Error:</b> Don't create letter<br>";
        }
        fputs($this->server, "MAIL FROM: $from\r\n");
        $this->ans .= fgets($this->server);
        fputs($this->server, "RCPT TO: $to\r\n");
        $this->ans .= fgets($this->server);
        fputs($this->server, "DATA\r\n");
        $this->ans .= fgets($this->server);
        fputs($this->server, "Date: " . date("r") . "\r\n");
        fputs($this->server, "From: $from\r\n");
        fputs($this->server, "MIME-Version: 1.0\r\n");
        fputs($this->server, $this->text);
        fputs($this->server, "--$boundary--\r\n");
        fputs($this->server, ".\r\nRSET\r\n");
        $this->ans .= fgets($this->server);

        return $this->ans;
    }

    function close()
    {
        fputs($this->server, "QUIT\r\n");
        fclose($this->server);
    }

    function subscribe($to)
    {

        foreach ($to as $item) {
            $this->send($item);
        }

        return $this->ans;
    }
}

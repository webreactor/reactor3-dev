<?php

class socket_client
{
    var $log;
    var $socket;

    function socket_client()
    {
        $this->log = array();
        $this->log[] = 'socket create';
        $this->socket = null;
        $this->log[] = 'ok';
    }

    function connect($server, $port)
    {

        $this->log[] = 'connect to ' . $server . ' ' . $port;
        $this->socket = fsockopen($server, $port);
        stream_set_timeout($this->socket, 2);
//stream_set_blocking($this->socket,true);
        if ($this->socket) {
            $this->log[] = 'ok';
        } else {
            $this->log[] = 'failed';
        }

        return $this->socket;
    }

    function put($str)
    {
        $this->log[] = 'put&lt;' . $str;
        fwrite($this->socket, $str . "\r\n");
    }

    function get()
    {
        $str = fgets($this->socket);
        $this->log[] = 'get&gt;' . $str;

        return $str;
    }

    function write($str)
    {
        $this->log[] = 'write&lt;' . $str;
        fwrite($this->socket, $str);
    }

    function read($len = 1024)
    {
        $str = '';
        while ($tlen = $len - strlen($str) + 1 > 0) {
            $str .= fread($this->socket, $tlen);
        }
//$str=fread($this->socket,$len);
        $this->log[] = 'read' . $len . '&gt;' . $str;

        return $str;
    }

    function close()
    {
        fclose($this->socket);
        $this->log[] = 'close';
    }
}//end of class socket_client
?>
<?php

class retailrocket
{
    var $partnerId;
    var $socket;
    var $error;
    const base_url_api = '/api/1.0/Recomendation/';
    const host = 'api.retailrocket.ru';
    const service_port = 80;

    function __construct($partnerId)
    {
        $this->partnerId = $partnerId;
    }

    function requestRemoteAPI($rocketFunct, $rocketValue = '', $paramArr = null)
    {
        $getSendStr = 'GET http://' . self::host . self::base_url_api . $rocketFunct . '/' . $this->partnerId . '/' . $rocketValue;
        if (is_array($paramArr)) {
            $getSendStr .= '?' . http_build_query($paramArr);
        }
        $getSendStr .= " HTTP/1.0\r\n";
        $getSendStr .= "Host: " . self::host . "\r\n";
        $getSendStr .= "Accept: application/json\r\n";
        $getSendStr .= "\r\n";

        $this->socket = fsockopen(self::host, self::service_port, $error, $errorStr);
        if (!$this->socket) {
            $this->error = 'connection error ' . $error . ' - ' . $errorStr;

            return false;
        }
        fwrite($this->socket, $getSendStr);
        $answerStr = '';
        while (!feof($this->socket)) {
            $answerStr .= fgets($this->socket);
        }
        fclose($this->socket);
        $answerStr = explode("\r\n\r\n", $answerStr);
        if (stripos($answerStr[0], 'chunked') !== false) {
            $answerStr[1] = http_chunked_decode($answerStr[1]);
        }

        return json_decode($answerStr[1], true);
    }

    function getByCategory($category_id)
    {
        return $this->requestRemoteAPI('CategoryToItems', $category_id);
    }

    function getForMain()
    {
        return $this->requestRemoteAPI('ItemsToMain', $category_id);
    }

    function getByGood($item_id)
    {
        return $this->requestRemoteAPI('ItemToItems', $item_id);
    }

    function getForBasket($item_ids)
    {
        if (is_array($item_ids)) {
            $item_ids = implode($item_ids, ',');
        }

        return $this->requestRemoteAPI('CrossSellItemToItems', $item_ids);
    }

    function getSameGoods($item_id)
    {
        return $this->requestRemoteAPI('UpSellItemToItems', $item_id);
    }

    function getByReferer($referer = '')
    {
        if ($referer == '') {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        return $this->requestRemoteAPI('SearchToItems', null, array('referrer' => $referer));
    }

    function getByText($keyword)
    {
        return $this->requestRemoteAPI('SearchToItems', null, array('keyword' => $keyword));
    }

    function getByRocketUser($cookie = '')
    {
        if ($cookie == '') {
            $cookie = $_COOKIE['rrpusid'];
        }

        return $this->requestRemoteAPI('PersonalRecommendation', null, array('rrUserId' => $cookie));
    }
}

?>
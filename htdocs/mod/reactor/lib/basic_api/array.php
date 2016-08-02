<?php

function arrayMapRecursive($handle, $data)
{
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = arrayMapRecursive($handle, $v);
        }
    } else {
        $data = $handle($data);
    }

    return $data;
}

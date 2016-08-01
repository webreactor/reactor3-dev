<?php

$_resource = array();

function &resource($name)
{
    global $_resource;

    clearstatcache();

    if (!isset($_resource[$name])) {
        if (file_exists(RES_DIR . $name)) {
            $_resource[$name] = resourceRestore($name);
        } else {
            $resources = resourceRestore('reactor_resource');

            if (!isset($data)) {
                $data = null;
            }

            if (isset($resources[$name])) {
                eval($resources[$name]['source']);

                if ($resources[$name]['store']) {
                    resourceStore($name, $data);
                }
            }

            $_resource[$name] = $data;
        }
    }

    return $_resource[$name];
}

function resourceStore($name, &$data)
{
    reactor_trace('resourceStore - ' . $name);

    $f = fopen(RES_DIR . $name, 'w');

    fwrite($f, serialize($data));

    fclose($f);
}

function resourceRestore($name)
{
    reactor_trace('resourceRestore - ' . $name);

    return unserialize(file_get_contents(RES_DIR . $name));
}

function resourceClear($name)
{
    reactor_trace('resourceClear - ' . $name);

    @unlink(RES_DIR . $name);
}

<?php

namespace reactor\base_type;

class ca_image extends ca_file
{
    public function fromForm(&$value)
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
                    require_once LIB_DIR . 'ext/images.php';

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

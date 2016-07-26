<?php

namespace reactor\base_type;

class ca_file extends base_type
{
    public function fromForm(&$value)
    {
        if (!$t = handleUploadedFile('_file_' . $this->get('name'))) {
            if (!is_file(FILE_DIR . $value)) {
                $value = $this->get('default');
                
                if ($this->get('necessary') == 1) {
                    return 0;
                }
            }
        } else {
            $value = $t;
        }

        return 1;
    }

    public function toForm($value)
    {
        if (!is_file(FILE_DIR . $value)) {
            $value = '';
        }

        return $value;
    }
}

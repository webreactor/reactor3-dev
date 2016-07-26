<?php

namespace reactor\base_type;

class ca_files extends base_type
{
    public function fromForm(&$value)
    {

        $rez = 0;

        $r = array();

        $cnt = 0;

        $name = $this->get('name');

        if (is_array($value)) {
            foreach ($value as $temp) {
                if (isset($temp['file']) && isset($temp['real']) && isset($temp['desc'])) {
                    $t = 0;

                    if (isset($temp['delete'])) {
                        $t = 1;

                        $ca->error['_file_deleted_ok'] = 1;

                        unlink(FILE_DIR . $temp['file']);
                    }

                    if (!is_file(FILE_DIR . $temp['file'])) {
                        $t = 1;
                    }

                    if ($t == 0) {
                        $r[$cnt]['file'] = htmlspecialchars($temp['file'], ENT_QUOTES);
                        $r[$cnt]['real'] = htmlspecialchars($temp['real'], ENT_QUOTES);
                        $r[$cnt]['desc'] = htmlspecialchars(typo($temp['desc']), ENT_QUOTES);

                        $cnt++;
                        $rez = 1;
                    }
                }
            }
        }

        if ($_add = handleUploadedFile('_files_add_' . $name)) {
            $r[$cnt]['file'] = $_add;
            $r[$cnt]['real'] = $_FILES['_files_add_' . $name]['name'];
            $r[$cnt]['desc'] = htmlspecialchars($_POST['_files_add_desc_' . $name], ENT_QUOTES);

            $cnt++;

            $rez = 1;

            $ca = &pool_get($this->_pool_id, 'object');
            
            $ca->error['_file_added_ok'] = 1;
        }

        $value = serialize($r);

        if ($this->get('necessary') == 0) {
            $rez = 1;
        }

        return $rez;
    }

    public function toForm($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }

    public function toHTML($value)
    {
        if ($value == '') {
            return array();
        }

        return unserialize($value);
    }
}

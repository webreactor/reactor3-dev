<?php

include_once LIB_DIR . 'config_write.php';

class reactor_interface_edit extends basic_object {
    function store($form) {
        $t = basic_object::store($form);
        interfacesCompile();

        return $t;
    }
}//end of class

?>
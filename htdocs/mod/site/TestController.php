<?php

namespace mod\site;

use Reactor\Database\Interfaces\QueryInterface;

class TestController
{
    public function testAction()
    {
        echo "<h1>TestController->testAction()</h1>";

        global $_db;
        
        /**
         * @var $query QueryInterface
         */
        $query = $_db->sql('select pk_interface, name from ' . T_REACTOR_INTERFACE);
        $data  = $query->matr('pk_interface', 'name');
        var_dump($data);
    }
}
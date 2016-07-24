<?php

namespace mod\site;

class TestController
{
    public function testAction()
    {
        echo "<h1>TestController->testAction()</h1>";

        global $_db;

        $query = $_db->select(T_REACTOR_INTERFACE);

        var_dump($query->matr('pk_interface', 'name'));
    }
}

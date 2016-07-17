<?php

require_once LIB_DIR . 'config_write.php';

class reactor_table extends basic_object
{
    function getList($page, $per_page, $where = '')
    {
        $modules = $this->_db->sql('select * from ' . T_REACTOR_MODULE);
        $modules = $this->_db->matr('pk_module', 'name');
        
        $r = basic_object::getList($page, $per_page, $where);
        foreach ($r['data'] as $k => $v) {
            $r['data'][$k]['name'] = 'T_' . strtoupper($modules[$v['fk_module']]) . '_' . strtoupper($v['name']);
        }
        
        return $r;
    }
    
    function store(&$form)
    {
        $r = basic_object::store($form);
        tablesCompile();
        
        return $r;
    }
}

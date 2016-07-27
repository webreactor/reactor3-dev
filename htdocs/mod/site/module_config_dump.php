<?php
return array(
    'module' => array(
        'name' => 'site',
        'install' => '',
        'uninstall' => '',
        'to_core' => '',
        'depend' => '',
        'descrip' => '',
    ),
    'interfaces' => array(
        array(
            'name' => 'site',
            'class' => 'basic_tree',
            'source' => '',
            'pkey' => 'pk_site_tree',
            'configurators' => 'fk_site_tree',
            'constructor' => '',
            'descrip' => '',
            '_define' => array(
            ),
            '_actions' => array(
                array(
                    'sort' => '0',
                    'name' => 'getMainMenu',
                    'call' => 'Главное меню',
                    'description' => '',
                    'method' => 'realArmUp',
                    'param' => '$_reactor[\'show\'][\'pk_site_tree\']',
                    'cptpl' => '',
                    'cptpl_mod' => '',
                    'public' => '0',
                    'handler' => '0',
                    'tpl_param' => '',
                    'confirm' => '0',
                ),
                array(
                    'sort' => '0',
                    'name' => 'site',
                    'call' => '',
                    'description' => '',
                    'method' => 'configure',
                    'param' => 'T_SITE_TREE,\'sort\',inputGetNum(\'fk_site_tree\',0)',
                    'cptpl' => '',
                    'cptpl_mod' => '',
                    'public' => '0',
                    'handler' => '0',
                    'tpl_param' => '',
                    'confirm' => '0',
                ),
            ),
        ),
        array(
            'name' => 'TestController',
            'class' => 'mod\\site\\TestController',
            'source' => '',
            'pkey' => '',
            'configurators' => '',
            'constructor' => '',
            'descrip' => '',
            '_define' => array(
            ),
            '_actions' => array(
                array(
                    'sort' => '4',
                    'name' => 'testAction',
                    'call' => '',
                    'description' => '',
                    'method' => 'testAction',
                    'param' => '',
                    'cptpl' => '',
                    'cptpl_mod' => '',
                    'public' => '0',
                    'handler' => '0',
                    'tpl_param' => '',
                    'confirm' => '0',
                ),
            ),
        ),
    ),
    'tables' => array(
        array(
            'name' => 'tree',
            'db_name' => 'site_tree',
            'mlng' => '0',
            'creates' => array(
                'site_tree' => 'CREATE TABLE `site_tree` (
  `pk_site_tree` int(11) NOT NULL AUTO_INCREMENT,
  `fk_site_tree` int(11) NOT NULL DEFAULT \'0\',
  `modified` varchar(100) NOT NULL DEFAULT \'0\',
  `param` varchar(100) NOT NULL DEFAULT \'\',
  `sort` int(11) NOT NULL DEFAULT \'0\',
  `public` tinyint(1) NOT NULL DEFAULT \'0\',
  `interface` varchar(100) NOT NULL DEFAULT \'\',
  `action` varchar(100) NOT NULL DEFAULT \'\',
  `name` varchar(100) NOT NULL DEFAULT \'\',
  `module` varchar(100) NOT NULL DEFAULT \'\',
  `template` varchar(100) NOT NULL DEFAULT \'\',
  `call` varchar(100) NOT NULL DEFAULT \'\',
  `call_e` varchar(100) NOT NULL DEFAULT \'\',
  `description` varchar(100) NOT NULL DEFAULT \'\',
  `handle` int(11) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`pk_site_tree`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8',
            ),
        ),
    ),
    'config' => array(
    ),
    'resources' => array(
    ),
    'base_types' => array(
    ),
    'action_relations' => array(
    ),
);
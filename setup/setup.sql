-- MySQL dump 10.13  Distrib 5.6.20, for Win32 (x86)
--
-- Host: localhost    Database: reactor
-- ------------------------------------------------------
-- Server version	5.6.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `reactor_base_type`
--

DROP TABLE IF EXISTS `reactor_base_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_base_type` (
  `pk_base_type` int(11) NOT NULL AUTO_INCREMENT,
  `fk_module` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `call` varchar(100) NOT NULL DEFAULT '',
  `check_enum` tinyint(1) NOT NULL DEFAULT '1',
  `check_array` tinyint(1) NOT NULL DEFAULT '1',
  `handle` tinyint(1) NOT NULL DEFAULT '0',
  `input` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_base_type`),
  UNIQUE KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=213 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_base_type`
--

LOCK TABLES `reactor_base_type` WRITE;
/*!40000 ALTER TABLE `reactor_base_type` DISABLE KEYS */;
INSERT INTO `reactor_base_type` VALUES (183,3,'select_image','select_image',1,1,0,'select_image','ca_text'),(182,3,'code_line','code_line',1,1,0,'text','ca_code'),(181,3,'select','select',1,1,0,'select','ca_text'),(180,3,'code','code',1,1,0,'textarea','ca_code'),(179,3,'flags','flags',0,2,0,'checkboxes','ca_flags'),(178,3,'label','label',1,1,0,'disabled','base_type'),(177,3,'save','save',1,1,2,'null','base_type'),(176,3,'files','files',1,2,0,'files','ca_files'),(175,3,'file','file',1,1,0,'file','ca_file'),(174,3,'images','images',0,2,0,'images','ca_files'),(173,3,'image','image',1,1,0,'image','ca_image'),(172,3,'mail','mail',1,1,0,'mail','ca_mail'),(171,3,'date','date',1,1,0,'date','ca_date'),(170,3,'int','int',1,1,0,'text','ca_int'),(169,3,'flag','flag',1,1,0,'checkbox','ca_int'),(168,3,'bbcode','bbcode',1,1,0,'bbcode','ca_bbcode'),(167,3,'text','text',1,1,0,'textarea','ca_text'),(166,3,'html','html',1,1,0,'html','ca_html'),(165,3,'string','string',1,1,0,'text','ca_string'),(184,3,'check_images','check_images',1,2,0,'check_images','ca_flags'),(185,3,'url_key','url_key',0,0,3,'null','ca_url_key'),(186,3,'hidden','hidden',1,1,0,'null','ca_string'),(187,3,'select_interactive','select_interactive',0,1,0,'select_interactive','ca_string'),(188,3,'date_time','date_time',0,0,0,'date_time','ca_date_time'),(189,3,'enum','enum',0,0,0,'textarea','ca_enum'),(190,3,'email','email',0,0,0,'text','ca_email'),(211,3,'radiobutton','radiobutton',1,1,0,'radiobutton','ca_text');
/*!40000 ALTER TABLE `reactor_base_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_config`
--

DROP TABLE IF EXISTS `reactor_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_config` (
  `pk_config` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `fk_module` int(11) NOT NULL DEFAULT '0',
  `group` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `descrip` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_config`),
  UNIQUE KEY `idx_name` (`name`,`fk_module`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_config`
--

LOCK TABLES `reactor_config` WRITE;
/*!40000 ALTER TABLE `reactor_config` DISABLE KEYS */;
INSERT INTO `reactor_config` VALUES (47,'FORMAT_DATE',1,'','\'d.m.Y\'',''),(46,'COOKIE_LIVE',1,'','60*60*24*30+time()',''),(45,'DEBUG_SQL',1,'','0',''),(44,'DEF_LANGUAGE',1,'','\'ru\'',''),(50,'default_interface',3,'','\'reactor_module\'',''),(43,'DEBUG_TPL',1,'','0',''),(48,'VERSION',1,'','\'3.8\'',''),(58,'recall_manager',5,'','38','Дежурный менеджер обратной связи'),(77,'format_datetime',1,'','\'d.m.Y, H:i\'',''),(82,'mobile_app_version',1,'','1','');
/*!40000 ALTER TABLE `reactor_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_help`
--

DROP TABLE IF EXISTS `reactor_help`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_help` (
  `pk_help` int(11) NOT NULL AUTO_INCREMENT,
  `interface` varchar(100) NOT NULL DEFAULT '',
  `define` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `topic` text NOT NULL,
  PRIMARY KEY (`pk_help`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_help`
--

LOCK TABLES `reactor_help` WRITE;
/*!40000 ALTER TABLE `reactor_help` DISABLE KEYS */;
/*!40000 ALTER TABLE `reactor_help` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_interface`
--

DROP TABLE IF EXISTS `reactor_interface`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_interface` (
  `pk_interface` int(11) NOT NULL AUTO_INCREMENT,
  `fk_interface` int(11) NOT NULL DEFAULT '0',
  `fk_module` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `class` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(255) NOT NULL DEFAULT '',
  `pkey` varchar(255) NOT NULL DEFAULT '',
  `configurators` varchar(255) NOT NULL DEFAULT '',
  `constructor` varchar(255) NOT NULL DEFAULT '',
  `descrip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_interface`),
  KEY `idx_name` (`fk_module`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=445 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_interface`
--

LOCK TABLES `reactor_interface` WRITE;
/*!40000 ALTER TABLE `reactor_interface` DISABLE KEYS */;
INSERT INTO `reactor_interface` VALUES (357,0,6,'reactor_base_type','reactor_base_type','base_type.php','pk_base_type','fk_module','',''),(358,0,6,'reactor_config','reactor_config','config.php','pk_config','fk_module','',''),(353,0,1,'reactor_help','basic_object','','pk_help','','',''),(354,0,3,'cp','cp','cp.php','','','',''),(362,0,6,'reactor_module','reactor_module','module.php','pk_module','','',''),(359,0,6,'reactor_interface','reactor_interface_edit','interface/interface.php','pk_interface','fk_module','',''),(360,0,6,'reactor_interface_action','reactor_interface_action','interface/interface_action.php','pk_action','fk_interface','',''),(361,0,6,'reactor_interface_define','reactor_interface_define','interface/interface_define.php','pk_define','fk_interface','',''),(363,0,6,'reactor_table','reactor_table','table.php','pk_table','fk_module','',''),(364,0,6,'reactor_user','reactor_user','user_rights/reactor_user.php','pk_user','','',''),(352,0,1,'content_adapter','content_adapter','','','','',''),(365,0,6,'reactor_user_group','reactor_user_group','user_rights/reactor_user_group.php','pk_ugroup','','',''),(366,0,6,'reactor_user_group_rights','reactor_user_group_rights','user_rights/reactor_user_group_rights.php','','','',''),(356,0,5,'site','basic_tree','','pk_site_tree','fk_site_tree','',''),(367,0,6,'site_tree','site_tree','site_tree.php','pk_site_tree','fk_site_tree','','');
/*!40000 ALTER TABLE `reactor_interface` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_interface_action`
--

DROP TABLE IF EXISTS `reactor_interface_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_interface_action` (
  `pk_action` int(11) NOT NULL AUTO_INCREMENT,
  `fk_action` int(11) NOT NULL DEFAULT '0',
  `fk_interface` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `call` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `method` varchar(100) NOT NULL DEFAULT '',
  `param` varchar(255) NOT NULL DEFAULT '',
  `cptpl` varchar(100) NOT NULL DEFAULT '',
  `cptpl_mod` varchar(100) NOT NULL DEFAULT '',
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `handler` tinyint(1) NOT NULL DEFAULT '0',
  `tpl_param` varchar(255) NOT NULL DEFAULT '',
  `confirm` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk_action`),
  UNIQUE KEY `idx_name` (`fk_interface`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4385 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_interface_action`
--

LOCK TABLES `reactor_interface_action` WRITE;
/*!40000 ALTER TABLE `reactor_interface_action` DISABLE KEYS */;
INSERT INTO `reactor_interface_action` VALUES (3614,0,359,2,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(3613,0,362,6,'config','Наcтройки','','','','reactor_config','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(3611,0,358,6,'reactor_config','','','configure','T_REACTOR_CONFIG,\'name\',inputGetNum(\'fk_module\')','','',0,0,'',0),(3612,0,362,2,'interfaces','Интерфейсы','','','','reactor_interface','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(3610,0,358,10,'getOne','','','getOne','inputGetNum(\"pk_config\",0)','','',0,0,'',0),(3609,0,358,8,'getList','Список','','getList','inputGetNum(\"reactor_config_page\",1),30','list.tpl','cp',0,0,'',0),(3608,0,358,12,'delete','Удалить','','delete','inputGetNum(\"pk_config\")','','',1,1,'',1),(3606,0,358,14,'store','','','store','$param','','',0,0,'',0),(3607,0,358,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(3605,0,359,4,'reactor_interface','','','configure','T_REACTOR_INTERFACE,\'name\',inputGetNum(\'fk_module\')','','',0,0,'',0),(3511,0,354,0,'show','','','show','inputGetStr(\'interface\',CP_DEFAULT_INTERFACE),inputGetStr(\'action\',\'getList\')','','',0,0,'',0),(3604,0,358,16,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(3603,0,359,6,'getList','Список','','getList','inputGetNum(\"reactor_interface_page\",1),30','list.tpl','cp',0,0,'',0),(3602,0,359,24,'delete','Удалить','','delete','inputGetNum(\"pk_interface\")','','',1,1,'',1),(3601,0,359,8,'getOne','','','getOne','inputGetNum(\"pk_interface\",0)','','cp',0,0,'',0),(3599,0,359,10,'store','','','store','$param','','',0,0,'',0),(3600,0,359,14,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(3598,0,359,12,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(3597,0,362,12,'getList','Модули','','getList','inputGetNum(\"reactor_module_page\",1),30','list.tpl','cp',3,0,'',0),(3596,0,362,14,'getOne','','','getOne','inputGetNum(\"pk_module\",0)','','',0,0,'',0),(3595,0,362,10,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(3593,0,362,22,'store','','','store','$param','','',0,0,'',0),(3594,0,362,16,'uninstall_rmp','Uninstall','','uninstall_rmp','inputGetNum(\"pk_module\")','','',1,1,'',1),(3592,0,362,20,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(3591,0,362,24,'reactor_module','','','configure','T_REACTOR_MODULE','','',0,0,'',0),(3590,0,361,4,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(3588,0,361,12,'store','','','store','$param','','',0,0,'',0),(3589,0,361,6,'reactor_interface_define','','','configure','T_REACTOR_INTERFACE_DEFINE,\'sort\',inputGetNum(\'fk_interface\')','','',0,0,'',0),(3587,0,361,8,'getList','Список','','getList','inputGetNum(\"reactor_interface_define_page\",1),30','list.tpl','cp',0,0,'',0),(3586,0,361,20,'delete','Удалить','','delete','inputGetNum(\"pk_define\")','','',1,1,'',1),(3585,0,361,10,'getOne','','','getOne','inputGetNum(\"pk_define\",0)','','',0,0,'',0),(3584,0,361,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(3510,0,354,0,'description','','','description','$param','tree.tpl','',0,0,'',0),(3508,0,354,0,'menu','Меню','','menu','','tree.tpl','',1,0,'',0),(3509,0,354,0,'path','Путь','','path','','','',1,0,'',0),(3583,0,361,14,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(3582,0,360,4,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(3581,0,360,6,'reactor_interface_action','','','configure','T_REACTOR_INTERFACE_ACTION,\'sort\',inputGetNum(\'fk_interface\')','','',0,0,'',0),(3580,0,360,8,'getList','Список','','getList','inputGetNum(\"reactor_interface_action_page\",1),30','list.tpl','cp',0,0,'',0),(3579,0,360,28,'delete','Удалить','','delete','inputGetNum(\"pk_action\")','','',1,1,'',1),(3578,0,360,10,'getOne','','','getOne','inputGetNum(\"pk_action\",0)','','',0,0,'',0),(3577,0,360,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(3576,0,360,12,'store','','','store','$param','','',0,0,'',0),(3575,0,362,26,'create_rmp','Создать дистрибутив','','create_rmp','inputGetNum(\'pk_module\')','','',1,1,'',0),(3574,0,360,14,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(3573,0,359,16,'defines','Свойства','','','','reactor_interface_define','getList',1,1,'\'fk_interface=\'.inputGetNum(\'pk_interface\')',0),(3572,0,359,18,'methods','Экшены','','','','reactor_interface_action','getList',1,1,'\'fk_interface=\'.inputGetNum(\'pk_interface\')',0),(3571,0,360,16,'back','Назад','','back','','reactor_interface','getList',2,1,'\'fk_module=\'.$data',0),(3570,0,358,18,'back','Назад','','','','reactor_module','getList',2,1,'',0),(3568,0,359,20,'back','Назад','','','','reactor_module','getList',2,1,'',0),(3569,0,361,16,'back','Назад','','back','','reactor_interface','getList',2,1,'\'fk_module=\'.$data',0),(3567,0,360,18,'addStandartActions','Стандартные actions','','addStandartActions','','','',2,1,'',1),(3565,0,361,18,'onRestore','','','onRestore','','','',0,0,'',0),(3566,0,360,22,'addStandartJump','Переход','','addStandartJump','','','',2,1,'',1),(3503,0,353,0,'getOne','Один элемент','','getOne','inputGetNum(\'pk_help\',0)','','',0,0,'',0),(3504,0,353,0,'show','Справка','','getOne','inputGetNum(\'pk_help\')','help.tpl','reactor',0,0,'',0),(3505,3507,353,0,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(3564,0,360,20,'addBasicObjConfigure','basic_object->configure','','addBasicObjConfigure','','','',2,1,'',1),(3563,0,359,22,'onRestore','','','onRestore','','','',0,0,'',0),(3561,0,360,26,'addUpDownActions','moveUpDown','','addUpDownActions','','','',2,1,'',1),(3562,0,358,20,'onRestore','','','onRestore','','','',0,0,'',0),(3560,0,362,30,'onRestore','','','onRestore','','','',0,0,'',0),(3559,0,360,24,'onRestore','','','onRestore','','','',0,0,'',0),(3557,0,361,22,'moveUp','Вверх','','moveUp','inputGetNum(\'pk_define\')','','',1,1,'',0),(3558,0,361,24,'moveDown','Вниз','','moveDown','inputGetNum(\'pk_define\')','','',1,1,'',0),(3556,0,360,32,'moveDown','Вниз','','moveDown','inputGetNum(\'pk_action\')','','',1,1,'',0),(3555,0,364,4,'reactor_user','','','configure','T_REACTOR_USER,\'active, login\'','','',0,0,'',0),(3553,0,364,18,'delete','Удалить','','delete','inputGetNum(\'pk_user\')','','',1,1,'',1),(3554,0,360,30,'moveUp','Вверх','','moveUp','inputGetNum(\'pk_action\')','','',1,1,'',0),(3552,0,364,8,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(3551,0,364,6,'getOne','Один элемент','','getOne','inputGetNum(\'pk_user\',0)','','',0,0,'',0),(3550,0,364,10,'store','Сохранение','','store','$param','','',0,0,'',0),(3549,0,364,12,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(3548,0,364,14,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3547,0,364,16,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3546,0,365,6,'reactor_user_group','','','configure','T_REACTOR_UGROUP,\'name\'','','',0,0,'',0),(3545,0,365,4,'getOne','Один элемент','','getOne','inputGetNum(\'pk_ugroup\',0)','','',0,0,'',0),(3544,0,365,20,'delete','Удалить','','delete','inputGetNum(\'pk_ugroup\')','','',1,1,'',1),(3543,0,365,8,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(3512,0,356,0,'site','','','configure','T_SITE_TREE,\'sort\',inputGetNum(\'fk_site_tree\',0)','','',0,0,'',0),(3513,0,356,0,'getMainMenu','Главное меню','','realArmUp','$_reactor[\'show\'][\'pk_site_tree\']','','',0,0,'',0),(3498,0,353,0,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3499,0,353,0,'delete','Удалить','','delete','inputGetNum(\'pk_help\')','','',1,1,'',1),(3500,0,353,0,'store','Сохранение','','store','$param','','',0,0,'',0),(3501,0,353,0,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3502,0,353,0,'reactor_help','','','configure','T_REACTOR_HELP,\'title\'','','',0,0,'',0),(3494,0,352,0,'handleForm','','','_handleForm','inputGetStr(\'_so\',\'none\'),inputGetStr(\'interface\',\'none\'),inputGetStr(\'action\',\'none\')','','',0,1,'',0),(3497,0,352,0,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3495,0,352,0,'null','null','','_time','','','',0,0,'',0),(3496,0,352,0,'ajax_request','ajax_request','','_execute','inputGetStr(\'interface\',\'content_adapter\'),inputGetStr(\'action\',\'null\'),inputGetStr(\'template\',\'ajax_data.tpl\'),inputGetStr(\'module\',\'reactor\')','','',0,0,'',0),(3542,0,365,10,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(3541,0,365,12,'store','Сохранение','','store','$param','','',0,0,'',0),(3540,0,365,16,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3539,0,365,14,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3538,0,366,0,'getOne','Один элемент','','getOne','inputGetNum(\'fk_ugroup\')','','',0,0,'',0),(3537,0,365,18,'jump_rights','Права','','','','reactor_user_group_rights','edit',1,1,'\'fk_ugroup=\'.inputGetNum(\'pk_ugroup\')',0),(3536,0,366,0,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','reactor_user_group_rights.tpl','constructor',1,0,'',0),(3535,0,366,0,'store','Сохранение','','store','$param','','',0,0,'',0),(3534,0,366,0,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3533,0,367,10,'site_tree','','','configure','T_SITE_TREE,\'sort\',inputGetNum(\'fk_site_tree\',0)','','',0,0,'',0),(3532,0,367,12,'getOne','Один элемент','','getOne','inputGetNum(\'pk_site_tree\',0)','','',0,0,'',0),(3531,0,367,24,'delete','Удалить','','delete','inputGetNum(\'pk_site_tree\')','','',1,1,'',1),(3530,0,367,14,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3529,0,357,6,'reactor_base_type','','','configure','T_REACTOR_BASE_TYPE,\'name\',inputGetNum(\'fk_module\',0)','','',0,0,'',0),(3528,0,367,18,'moveDown','Вниз','','moveDown','inputGetNum(\'pk_site_tree\')','','',1,1,'',0),(3527,0,367,16,'moveUp','Вверх','','moveUp','inputGetNum(\'pk_site_tree\')','','',1,1,'',0),(3526,0,357,8,'store','Сохранение','','store','$param','','',0,0,'',0),(3525,0,367,22,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3524,0,367,20,'store','Сохранение','','store','$param','','',0,0,'',0),(3523,0,357,10,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3522,0,357,12,'getOne','Один элемент','','getOne','inputGetNum(\'pk_base_type\',0)','','',0,0,'',0),(3520,0,357,14,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3521,0,357,20,'delete','Удалить','','delete','inputGetNum(\'pk_base_type\')','','',1,1,'',1),(3519,0,362,8,'jump_base_type','Базовые типы','','','','reactor_base_type','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(3506,3507,353,0,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(3507,3597,353,0,'getList','Справка','','getList','inputGetNum(\'reactor_help_page\',1),20/*,\'where\'*/','list.tpl','cp',3,0,'',0),(3615,0,358,4,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(3616,0,367,8,'jump_add','Добавить','','','','site_tree','add',1,1,'\'fk_site_tree=\'.inputGetNum(\'pk_site_tree\')',0),(3617,0,363,18,'reactor_table','','','configure','T_REACTOR_TABLE,\'name\',inputGetNum(\'fk_module\',0)','','',0,0,'',0),(3618,0,363,14,'getList','Таблицы','','getList','inputGetNum(\'reactor_table_page\',1),20/*,\'where\'*/','list.tpl','cp',0,0,'',0),(3619,0,363,20,'delete','Удалить','','delete','inputGetNum(\'pk_table\')','','',1,1,'',1),(3620,0,363,12,'store','Сохранение','','store','$param','','',0,0,'',0),(3621,0,363,8,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(3622,0,363,6,'jump_back','Назад','','','','reactor_module','getList',2,1,'',0),(3623,0,362,4,'jump_table','Таблицы','','','','reactor_table','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(3624,0,360,34,'addIntSelect','ajax select','','addIntSelect','','','',2,1,'',1),(3625,0,363,16,'getOne','Один элемент','','getOne','inputGetNum(\'pk_table\',0)','','',0,0,'',0),(3626,0,363,10,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(3627,0,357,4,'jump_back','Назад','','','','reactor_module','getList',2,1,'',0),(3628,0,362,28,'update','Обновить','','update_module','inputGetNum(\'pk_module\')','','',1,1,'',1),(3629,3618,363,4,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(3630,3618,363,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(3631,3597,362,18,'add','Добавить','Описание причем модет содержать php код  -  ваш логин <?php echo $_user[\'login\'];?>','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(3632,3597,364,2,'getList','Пользователи','','getList','inputGetNum(\'reactor_user_page\',1),30,inputGetStr(\'filter\',\'\'),inputGetNum(\'fk_ugroup\',0)','user_list.tpl','cp',3,0,'',0),(3633,3597,367,6,'realTree','Дерево сайта','','realTree','','tree.tpl','cp',3,0,'',0),(3634,3597,365,2,'getList','Группы','','getList','inputGetNum(\'reactor_user_group_page\',1),30/*,\'where\'*/','list.tpl','cp',3,0,'',0),(3635,3597,357,2,'getList','Базовые типы','','getList','inputGetNum(\'reactor_base_type_page\',1),20/*,\'where\'*/','list.tpl','cp',0,0,'',0),(3636,3635,357,16,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(3637,3635,357,18,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(3638,3633,367,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(3639,3633,367,4,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(3917,0,352,0,'jsonp_request','jsonp_request','','_execute','inputGetStr(\'interface\',\'content_adapter\'),inputGetStr(\'action\',\'null\'),inputGetStr(\'template\',\'jsonp_data.tpl\'),inputGetStr(\'module\',\'reactor\')','','',0,0,'',0),(3926,0,352,0,'json_request','json_request','','_execute','inputGetStr(\'interface\',\'content_adapter\'),inputGetStr(\'action\',\'null\'),inputGetStr(\'template\',\'json_data.tpl\'),inputGetStr(\'module\',\'reactor\')','','',0,0,'',0),(3999,0,364,0,'getSelectGroup','Интерактивный select по группе','','getSelectGroup','\'name\',inputGetStr(\'filter\',\'\'),inputGetNum(\'fk_ugroup\',1),inputGetNum(\'getOne\',0)','','',0,0,'',0);
/*!40000 ALTER TABLE `reactor_interface_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_interface_define`
--

DROP TABLE IF EXISTS `reactor_interface_define`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_interface_define` (
  `pk_define` int(11) NOT NULL AUTO_INCREMENT,
  `fk_interface` int(11) NOT NULL DEFAULT '0',
  `base_type` varchar(100) NOT NULL DEFAULT '',
  `base_type_param` varchar(255) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `call` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `inlist` tinyint(1) NOT NULL DEFAULT '0',
  `necessary` tinyint(1) NOT NULL DEFAULT '0',
  `default` text NOT NULL,
  `enum` text NOT NULL,
  PRIMARY KEY (`pk_define`),
  UNIQUE KEY `idx_name` (`fk_interface`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1572 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_interface_define`
--

LOCK TABLES `reactor_interface_define` WRITE;
/*!40000 ALTER TABLE `reactor_interface_define` DISABLE KEYS */;
INSERT INTO `reactor_interface_define` VALUES (1286,357,'select','',8,'check_array','Обработка значения','',0,0,'','$data=array(\r\n0=>\'Это точно не массив\',\r\n1=>\'Это может быть массив и проверять его рекурсивно\',\r\n2=>\'На входе массив но проверять его как единую сушность\',\r\n);'),(1285,364,'code_line','',8,'email','Эл. почта','',1,0,'',''),(1282,359,'code_line','',2,'name','Reactor interface','',1,1,'',''),(1283,364,'flag','',10,'active','Активирован','',1,0,'$data=0;',''),(1284,361,'code_line','',2,'name','Имя свойства','',1,1,'',''),(1279,358,'code_line','',8,'descrip','Описание','',0,0,'',''),(1280,358,'code_line','',2,'name','Имя','',1,1,'',''),(1281,358,'code','',6,'value','Значение','',1,1,'',''),(1276,361,'flag','',6,'inlist','В списке','',0,0,'$data=0;',''),(1277,362,'code_line','',2,'name','Имя модуля','test',1,1,'',''),(1278,358,'code_line','',4,'group','Группа','',0,0,'',''),(1274,359,'code_line','',10,'pkey','pkey - для списков','',0,0,'',''),(1275,359,'code_line','',6,'source','Источник','',0,0,'',''),(1273,359,'code_line','',4,'class','User class','',0,1,'',''),(1272,361,'code','',10,'default','По умолчанию равно','',0,0,'',''),(1270,359,'code_line','',8,'constructor','Параметры конструктора user class','',0,0,'',''),(1271,359,'code_line','',12,'configurators','конфигураторы класса в url','',0,0,'',''),(1269,361,'code_line','',4,'call','Русское название','',1,1,'',''),(1268,362,'code','',4,'descrip','Описание','',0,0,'',''),(1223,354,'code_line','',0,'name','Тоже имя','',1,0,'',''),(1222,354,'code_line','',0,'call','Имя','',1,0,'',''),(1267,362,'code','',8,'to_core','В ядро','',0,0,'',''),(1266,364,'code_line','',4,'pass','Пароль','',0,1,'',''),(1265,361,'flag','',8,'necessary','Обязательное','',0,0,'$data=0;',''),(1263,360,'flag','',12,'confirm','Подтверждение действия','',0,0,'$data=0;',''),(1264,364,'select','',12,'fk_ugroup','Группа','',1,1,'','global $_db;\r\n$_db->sql(\'select * from \'.T_REACTOR_UGROUP);\r\n$data=$_db->matr(\'pk_ugroup\',\'name\');'),(1262,367,'select','',10,'handle','Обработчик','',0,0,'','$data=array(\r\n0=>\'В шаблон index.tpl\',\r\n1=>\'Самостоятельно\',\r\n);'),(1261,367,'code_line','',14,'action','Action','',0,0,'',''),(1260,364,'code_line','',2,'login','Логин','',1,1,'',''),(1258,367,'code_line','',2,'name','Имя для URL','',1,1,'',''),(1259,367,'flag','',8,'public','public','',0,0,'$data=0;',''),(1257,367,'code_line','',6,'call','Имя','',1,0,'',''),(1256,367,'code_line','',4,'param','Параметры','',1,0,'',''),(1255,362,'code','',6,'depend','Зависимости','',0,0,'include_once LIB_DIR.\'reactor_ver.php\';\n$data=reactor_ver();',''),(1253,362,'code','',10,'install','Инсталяция','',0,0,'',''),(1254,362,'code','',12,'uninstall','Деинсталяция','',0,0,'',''),(1252,360,'code','',24,'description','Описание','',0,0,'',''),(1251,360,'select','',22,'fk_action','Привязка','',0,0,'','global $_db;\n$_db->sql(\'select pk_action,concat(i.name,\">\",a.call) as calling from \'.T_REACTOR_INTERFACE_ACTION.\' a,\'.T_REACTOR_INTERFACE.\' i where a.fk_interface=i.pk_interface and public>0 order by pk_interface,a.call\');\n\n$data=$_db->matr(\'pk_action\',\'calling\');\n$data[0]=\'----------\';'),(1249,365,'text','',4,'descrip','Описание','',0,0,'',''),(1250,366,'int','',2,'rights','Права','',0,0,'',''),(1248,365,'code_line','',2,'name','Имя группы','',1,1,'',''),(1247,361,'code_line','',18,'description','Описание','',0,0,'',''),(1246,360,'code_line','',6,'call','Русское название','',1,0,'',''),(1245,360,'code_line','',2,'name','Имя экшена','',1,1,'',''),(1244,361,'code','',14,'enum','Множество значений','',0,0,'',''),(1243,361,'select','',12,'base_type','Базовый тип поля','',0,1,'$data=\'string\';','global $_db;\r\n$_db->sql(\'select * from \'.T_REACTOR_BASE_TYPE.\' order by `call`\');\r\n$data=$_db->matr(\'name\',\'call\');'),(1242,360,'code_line','',16,'cptpl','Шаблон вывода в cp','',0,0,'',''),(1220,353,'string','',2,'title','Статья','',1,0,'',''),(1218,353,'string','',8,'action','Экшен','',1,0,'',''),(1219,353,'select','',6,'interface','Интерфейс','',1,0,'','global $_db;\r\n$_db->sql(\'select `name` from \'.T_REACTOR_INTERFACE.\' order by name\');\r\n$data=$_db->matr(\'name\',\'name\');'),(1238,360,'select','',18,'handler','Обработчик','',0,1,'','$data=array(\r\n0=>\'в шаблон\',\r\n1=>\'location\',\r\n2=>\'store location\',\r\n);'),(1239,360,'code_line','',8,'param','Параметры','',0,0,'',''),(1240,360,'code_line','',14,'cptpl_mod','Модуль шаблона','',0,0,'',''),(1241,360,'code_line','',4,'method','Метод user class','',0,0,'',''),(1236,367,'select','',12,'interface','Reactor interface','',0,0,'','global $_db;\r\n$_db->sql(\'select * from \'.T_REACTOR_INTERFACE.\' order by name\');\r\n$data=$_db->matr(\'name\',\'name\');\r\n$data[\'\']=\'----empty----\';'),(1237,360,'select','',10,'public','В контрольной панели','',0,1,'','$data=array(\r\n0=>\'скрыт\',\r\n1=>\'выпадающее меню\',\r\n2=>\'правое меню\',\r\n3=>\'левое меню\',\r\n);'),(1234,367,'code_line','',18,'template','Template','',0,0,'',''),(1235,367,'select','',16,'module','Module for template','',0,0,'','global $_db;\r\n$_db->sql(\'select * from \'.T_REACTOR_MODULE.\' order by name\');\r\n$data=$_db->matr(\'name\',\'name\');\r\n$data[\'\']=\'----empty----\';'),(1233,367,'code','',20,'description','Description','',0,0,'',''),(1231,357,'code_line','',2,'name','Имя','',1,1,'',''),(1232,361,'code','',16,'base_type_param','Параметры базового типа','',0,0,'',''),(1230,357,'code_line','',4,'call','Имя по русски','',1,1,'',''),(1229,357,'flag','',10,'check_enum','Проверять на множестве значений','',0,0,'$data=0;',''),(1221,353,'html','',4,'topic','Содержание','',0,1,'',''),(1287,360,'code_line','',20,'tpl_param','Параметры обработчика','',0,0,'',''),(1288,357,'select','',6,'handle','Обработчик формы','',0,0,'','$data=array(\r\n0=>\'Обычная проверка\',\r\n1=>\'Удалять значение при проверке с формы\',\r\n2=>\'Отвергать значение c формы, сохраняя ранее заданное\',\r\n3=>\'Пост-обработчик для внутреннего поля\',\r\n);'),(1289,357,'code_line','',14,'input','Имя контрола для тега input','',0,0,'',''),(1290,357,'code_line','',12,'type','Класс обработчик','',0,0,'',''),(1291,363,'code_line','',2,'name','Имя','На основе имени генерируется константа T_имямодуля_имятаблицы',1,1,'',''),(1292,363,'code_line','',4,'db_name','Реальное имя таблицы','',1,1,'',''),(1293,363,'flag','',8,'mlng','Мультиязычная таблица','в мульти таблице реальное имя таблицы не содержит языкового постфикса',1,0,'$data=0;',''),(1294,363,'flag','',6,'rmp_data','Сохранять данные при создании дистрибутива','',0,0,'$data=0;',''),(1295,364,'string','',6,'name','Имя','',1,1,'','');
/*!40000 ALTER TABLE `reactor_interface_define` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_module`
--

DROP TABLE IF EXISTS `reactor_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_module` (
  `pk_module` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `install` text NOT NULL,
  `uninstall` text NOT NULL,
  `to_core` text NOT NULL,
  `depend` text NOT NULL,
  `descrip` text NOT NULL,
  PRIMARY KEY (`pk_module`),
  UNIQUE KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_module`
--

LOCK TABLES `reactor_module` WRITE;
/*!40000 ALTER TABLE `reactor_module` DISABLE KEYS */;
INSERT INTO `reactor_module` VALUES (1,'reactor','','','include LIB_DIR.\'ca_base_types.php\';','none','reactor modules'),(3,'cp','','','','reactor/lib/basic_object.php = 2.1.2\r\nreactor/lib/basic_tree.php = 3.1.0\r\nreactor/lib/ca_base_types.php = 2.1.1\r\nreactor/lib/content_adapter.php = 2.1.2\r\nreactor/lib/core_api.php = 1.2.1\r\nreactor/lib/reactor_interface.php = 1.2.0\r\nreactor/lib/Gekkon/Gekkon.php = 3.13\r\nreactor/lib/db/mysql.php = 1.3.0',''),(6,'constructor','','','','reactor/lib/basic_object.php = 2.1.2\r\nreactor/lib/basic_tree.php = 3.1.0\r\nreactor/lib/ca_base_types.php = 2.1.1\r\nreactor/lib/content_adapter.php = 2.1.2\r\nreactor/lib/core_api.php = 1.2.1\r\nreactor/lib/reactor_interface.php = 1.2.0\r\nreactor/lib/Gekkon/Gekkon.php = 3.13\r\nreactor/lib/db/mysql.php = 1.3.0\r\ncp/tpl/form.tpl = 2.0\r\ncp/tpl/list.tpl = 2.0.1\r\ncp/tpl/tree.tpl = 2.0.1\r\ncp/js/form.js = 1.1.1\r\ncp/js/list.js = 2.0.1','constructor'),(5,'site','','','','none','');
/*!40000 ALTER TABLE `reactor_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_resource`
--

DROP TABLE IF EXISTS `reactor_resource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_resource` (
  `pk_resource` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `fk_module` int(11) NOT NULL DEFAULT '0',
  `store` tinyint(1) NOT NULL DEFAULT '0',
  `source` text NOT NULL,
  PRIMARY KEY (`pk_resource`),
  UNIQUE KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_resource`
--

LOCK TABLES `reactor_resource` WRITE;
/*!40000 ALTER TABLE `reactor_resource` DISABLE KEYS */;
INSERT INTO `reactor_resource` VALUES (9,'reactor_interfaces',3,0,'$_db->sql(\'select pk_interface,name from \'.T_REACTOR_INTERFACE);\r\n$data=$_db->matr(\'pk_interface\',\'name\');');
/*!40000 ALTER TABLE `reactor_resource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_table`
--

DROP TABLE IF EXISTS `reactor_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_table` (
  `pk_table` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `fk_module` int(11) NOT NULL DEFAULT '0',
  `db_name` varchar(30) NOT NULL DEFAULT '',
  `rmp_data` tinyint(1) NOT NULL DEFAULT '0',
  `mlng` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk_table`),
  UNIQUE KEY `idx_uname` (`name`,`fk_module`)
) ENGINE=MyISAM AUTO_INCREMENT=335 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_table`
--

LOCK TABLES `reactor_table` WRITE;
/*!40000 ALTER TABLE `reactor_table` DISABLE KEYS */;
INSERT INTO `reactor_table` VALUES (265,'up_module',1,'reactor_up_module',0,0),(264,'ugroup_action',1,'reactor_ugroup_action',1,0),(263,'ugroup',1,'reactor_ugroup',1,0),(262,'interface_define',1,'reactor_interface_define',0,0),(261,'interface_action',1,'reactor_interface_action',0,0),(260,'resource',1,'reactor_resource',0,0),(259,'module',1,'reactor_module',0,0),(258,'config',1,'reactor_config',0,0),(257,'base_type',1,'reactor_base_type',0,0),(256,'up_value',1,'reactor_up_value',1,0),(255,'up_name',1,'reactor_up_name',1,0),(270,'tree',5,'site_tree',0,0),(254,'user',1,'reactor_user',1,0),(253,'table',1,'reactor_table',0,0),(266,'form',1,'reactor_form',0,0),(267,'interface',1,'reactor_interface',0,0),(268,'tree_sup',1,'reactor_tree_sup',0,0),(269,'help',1,'reactor_help',0,0),(302,'messenger_buffer',5,'messenger_buffer',0,0),(319,'mailer_data',5,'site_mailer_data',0,0),(329,'messenger_income',5,'messenger_income',0,0);
/*!40000 ALTER TABLE `reactor_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_tree_sup`
--

DROP TABLE IF EXISTS `reactor_tree_sup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_tree_sup` (
  `pk_node` int(11) unsigned NOT NULL DEFAULT '0',
  `childs` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk_node`) USING BTREE
) ENGINE=MEMORY DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_tree_sup`
--

LOCK TABLES `reactor_tree_sup` WRITE;
/*!40000 ALTER TABLE `reactor_tree_sup` DISABLE KEYS */;
/*!40000 ALTER TABLE `reactor_tree_sup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_ugroup`
--

DROP TABLE IF EXISTS `reactor_ugroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_ugroup` (
  `pk_ugroup` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `descrip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_ugroup`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_ugroup`
--

LOCK TABLES `reactor_ugroup` WRITE;
/*!40000 ALTER TABLE `reactor_ugroup` DISABLE KEYS */;
INSERT INTO `reactor_ugroup` VALUES (1,'guest','guest'),(2,'root','root'),(6,'admin','');
/*!40000 ALTER TABLE `reactor_ugroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_ugroup_action`
--

DROP TABLE IF EXISTS `reactor_ugroup_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_ugroup_action` (
  `fk_ugroup` int(11) NOT NULL DEFAULT '0',
  `fk_action` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fk_ugroup`,`fk_action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_ugroup_action`
--

LOCK TABLES `reactor_ugroup_action` WRITE;
/*!40000 ALTER TABLE `reactor_ugroup_action` DISABLE KEYS */;
INSERT INTO `reactor_ugroup_action` VALUES (1,3494),(1,3495),(1,3496),(1,3497),(1,3512),(1,3513),(1,3917),(1,3926),(3,24),(3,30),(3,680),(3,681),(3,682),(3,891),(3,1080),(3,1081),(3,1082),(3,1083),(3,1084),(3,1085),(3,1086),(3,1087),(3,1088),(3,1089),(3,1090),(3,1091),(3,1092),(3,1093),(3,1094),(3,1095),(3,1096),(3,1097),(3,1098),(3,1099),(3,1100),(3,1101),(3,1102),(3,1103),(3,1104),(3,1105),(3,1106),(3,1107),(3,1108),(3,1109),(3,1110),(3,1111),(3,1112),(3,1115),(3,1116),(3,1117),(3,1118),(3,1119),(3,1120),(4,24),(4,30),(4,680),(4,681),(4,682),(4,891),(4,1080),(4,1081),(4,1082),(4,1083),(4,1084),(4,1085),(4,1086),(4,1087),(4,1088),(4,1089),(4,1090),(4,1091),(4,1092),(4,1093),(4,1094),(4,1095),(4,1096),(4,1097),(4,1098),(4,1099),(4,1100),(4,1101),(4,1102),(4,1103),(4,1104),(4,1105),(4,1106),(4,1107),(4,1108),(4,1109),(4,1110),(4,1111),(4,1112),(4,1115),(4,1116),(4,1117),(4,1118),(4,1119),(4,1120),(5,24),(5,30),(5,680),(5,681),(5,682),(5,891),(5,1080),(5,1081),(5,1082),(5,1083),(5,1084),(5,1085),(5,1086),(5,1087),(5,1088),(5,1089),(5,1090),(5,1091),(5,1092),(5,1093),(5,1094),(5,1095),(5,1096),(5,1097),(5,1098),(5,1099),(5,1100),(5,1101),(5,1102),(5,1103),(5,1104),(5,1105),(5,1106),(5,1107),(5,1108),(5,1109),(5,1110),(5,1111),(5,1112),(5,1115),(5,1116),(5,1117),(5,1118),(5,1119),(5,1120),(6,3494),(6,3495),(6,3496),(6,3497),(6,3498),(6,3499),(6,3500),(6,3501),(6,3502),(6,3503),(6,3504),(6,3505),(6,3506),(6,3507),(6,3508),(6,3509),(6,3510),(6,3511),(6,3512),(6,3513),(6,3547),(6,3555),(6,3917),(6,3926),(6,3999),(7,3484),(7,3485),(7,3486),(7,3487),(7,3488),(7,3490),(7,3491),(7,3494),(7,3495),(7,3496),(7,3497),(7,3498),(7,3501),(7,3502),(7,3503),(7,3504),(7,3507),(7,3508),(7,3509),(7,3510),(7,3511),(7,3512),(7,3513),(7,3514),(7,3515),(7,3516),(7,3517),(7,3518),(7,3640),(7,3641),(7,3643),(7,3644),(7,3646),(7,3647),(7,3648),(7,3649),(7,3651),(7,3652),(7,3653),(7,3655),(7,3656),(7,3657),(7,3658),(7,3659),(7,3660),(7,3661),(7,3662),(7,3663),(7,3665),(7,3666),(7,3667),(7,3668),(7,3669),(7,3674),(7,3676),(7,3677),(7,3678),(7,3679),(7,3680),(7,3681),(7,3682),(7,3683),(7,3684),(7,3686),(7,3687),(7,3689),(7,3690),(7,3691),(7,3692),(7,3693),(7,3694),(7,3695),(7,3697),(7,3698),(7,3700),(7,3701),(7,3702),(7,3703),(7,3704),(7,3705),(7,3706),(7,3708),(7,3710),(7,3711),(7,3712),(7,3714),(7,3715),(7,3717),(7,3718),(7,3720),(7,3723),(7,3724),(7,3726),(7,3730),(7,3732),(7,3733),(7,3737),(7,3740),(7,3741),(7,3743),(7,3745),(7,3757),(7,3758),(7,3759),(7,3760),(7,3761),(7,3764),(7,3765),(7,3766),(7,3767),(7,3768),(7,3769),(7,3770),(7,3771),(7,3772),(7,3773),(7,3774),(7,3775),(7,3776),(7,3777),(7,3778),(7,3779),(7,3781),(7,3782),(7,3783),(7,3784),(7,3785),(7,3786),(7,3787),(7,3789),(7,3790),(7,3791),(7,3792),(7,3793),(7,3794),(7,3796),(7,3797),(7,3799),(7,3800),(7,3801),(7,3802),(7,3804),(7,3805),(7,3806),(7,3807),(7,3809),(7,3810),(7,3811),(7,3812),(7,3813),(7,3814),(7,3816),(7,3817),(7,3819),(7,3820),(7,3821),(7,3823),(7,3826),(7,3829),(7,3834),(7,3835),(7,3836),(7,3837),(7,3839),(7,3840),(7,3841),(7,3842),(7,3843),(7,3846),(7,3847),(7,3848),(7,3849),(7,3850),(7,3851),(7,3852),(7,3853),(7,3854),(7,3855),(7,3856),(7,3857),(7,3858),(7,3861),(7,3889),(7,3890),(7,3891),(7,3897),(7,3902),(7,3913),(7,3914),(7,3915),(7,3917),(7,3918),(7,3920),(7,3921),(7,3922),(7,3923),(7,3924),(7,3925),(7,3926),(7,3927),(7,3929),(7,3930),(7,3931),(7,3932),(7,3936),(7,3937),(7,3938),(7,3939),(7,3940),(7,3941),(7,3942),(7,3944),(7,3945),(7,3946),(7,3949),(7,3950),(7,3951),(7,3952),(7,3959),(7,3961),(7,3962),(7,3971),(7,3972),(7,3973),(7,3979),(7,3980),(7,3981),(7,3982),(7,3984),(7,3985),(7,3986),(7,3987),(7,3988),(7,3989),(7,3991),(7,3992),(7,3993),(7,3994),(7,3995),(7,3996),(7,3997),(7,3998),(7,4000),(7,4001),(7,4002),(7,4003),(7,4004),(7,4005),(7,4006),(7,4025),(7,4027),(7,4028),(7,4029),(7,4030),(7,4031),(7,4032),(7,4033),(7,4039),(7,4043),(7,4044),(7,4045),(7,4046),(7,4047),(7,4048),(7,4056),(7,4058),(7,4059),(7,4064),(7,4065),(7,4066),(7,4068),(7,4069),(7,4070),(7,4071),(7,4073),(7,4101),(7,4102),(7,4117),(7,4118),(7,4119),(7,4138),(7,4139),(7,4140),(7,4141),(7,4142),(7,4143),(7,4146),(7,4147),(7,4148),(7,4149),(7,4151),(7,4152),(7,4153),(7,4160),(7,4161),(7,4162),(7,4165),(7,4166),(7,4167),(7,4185),(7,4189),(7,4190),(7,4191),(7,4192),(7,4193),(7,4194),(7,4226),(7,4234),(7,4236),(7,4237),(7,4241),(7,4242),(7,4243),(7,4244),(7,4266),(7,4270),(7,4271),(7,4272),(7,4273),(7,4274),(7,4276),(7,4277),(7,4278),(7,4279),(7,4281),(7,4282),(7,4283),(7,4291),(7,4296),(7,4299),(7,4301),(7,4302),(7,4303),(7,4304),(7,4306),(7,4307),(7,4311),(7,4321),(7,4325),(7,4329),(7,4337),(7,4338),(7,4339),(7,4340),(7,4347),(7,4348),(7,4349),(7,4350),(7,4351),(7,4355),(7,4356),(7,4366),(7,4367),(7,4368),(7,4369),(7,4370),(7,4371),(7,4372),(7,4382),(7,4383),(7,4384),(8,3494),(8,3495),(8,3496),(8,3497),(8,3498),(8,3499),(8,3500),(8,3501),(8,3502),(8,3503),(8,3504),(8,3505),(8,3506),(8,3507),(8,3508),(8,3509),(8,3510),(8,3511),(8,3514),(8,3515),(8,3516),(8,3517),(8,3518),(8,3640),(8,3643),(8,3646),(8,3648),(8,3649),(8,3651),(8,3652),(8,3653),(8,3655),(8,3656),(8,3657),(8,3658),(8,3659),(8,3660),(8,3663),(8,3665),(8,3668),(8,3669),(8,3676),(8,3677),(8,3679),(8,3681),(8,3682),(8,3683),(8,3686),(8,3687),(8,3690),(8,3691),(8,3694),(8,3695),(8,3697),(8,3698),(8,3699),(8,3700),(8,3702),(8,3703),(8,3704),(8,3706),(8,3709),(8,3710),(8,3711),(8,3713),(8,3720),(8,3723),(8,3724),(8,3726),(8,3730),(8,3732),(8,3741),(8,3754),(8,3757),(8,3758),(8,3759),(8,3760),(8,3761),(8,3764),(8,3765),(8,3766),(8,3767),(8,3768),(8,3769),(8,3770),(8,3771),(8,3772),(8,3773),(8,3774),(8,3775),(8,3776),(8,3777),(8,3778),(8,3779),(8,3781),(8,3783),(8,3784),(8,3785),(8,3786),(8,3787),(8,3789),(8,3790),(8,3792),(8,3793),(8,3794),(8,3796),(8,3797),(8,3799),(8,3800),(8,3801),(8,3802),(8,3805),(8,3806),(8,3807),(8,3808),(8,3809),(8,3810),(8,3811),(8,3812),(8,3813),(8,3817),(8,3819),(8,3820),(8,3823),(8,3827),(8,3829),(8,3830),(8,3831),(8,3834),(8,3835),(8,3836),(8,3837),(8,3838),(8,3839),(8,3840),(8,3841),(8,3842),(8,3843),(8,3844),(8,3845),(8,3866),(8,3867),(8,3868),(8,3869),(8,3871),(8,3872),(8,3873),(8,3874),(8,3875),(8,3889),(8,3890),(8,3891),(8,3892),(8,3893),(8,3894),(8,3895),(8,3896),(8,3897),(8,3901),(8,3902),(8,3903),(8,3904),(8,3908),(8,3911),(8,3913),(8,3914),(8,3915),(8,3917),(8,3918),(8,3920),(8,3921),(8,3922),(8,3923),(8,3926),(8,3927),(8,3928),(8,3929),(8,3930),(8,3931),(8,3932),(8,3936),(8,3937),(8,3938),(8,3939),(8,3940),(8,3941),(8,3942),(8,3944),(8,3945),(8,3946),(8,3947),(8,3948),(8,3949),(8,3950),(8,3951),(8,3952),(8,3959),(8,3960),(8,3961),(8,3962),(8,3971),(8,3972),(8,3973),(8,3974),(8,3975),(8,3976),(8,3977),(8,3978),(8,3979),(8,3980),(8,3981),(8,3982),(8,3984),(8,3985),(8,3986),(8,3987),(8,3994),(8,3995),(8,3996),(8,3997),(8,3998),(8,4000),(8,4001),(8,4002),(8,4003),(8,4004),(8,4005),(8,4006),(8,4025),(8,4027),(8,4028),(8,4030),(8,4031),(8,4032),(8,4033),(8,4039),(8,4043),(8,4044),(8,4045),(8,4046),(8,4047),(8,4048),(8,4056),(8,4058),(8,4059),(8,4064),(8,4065),(8,4066),(8,4068),(8,4069),(8,4070),(8,4071),(8,4073),(8,4101),(8,4102),(8,4117),(8,4118),(8,4119),(8,4138),(8,4139),(8,4140),(8,4141),(8,4142),(8,4143),(8,4146),(8,4147),(8,4148),(8,4149),(8,4151),(8,4152),(8,4153),(8,4160),(8,4161),(8,4162),(8,4165),(8,4166),(8,4167),(8,4185),(8,4189),(8,4190),(8,4191),(8,4192),(8,4193),(8,4194),(8,4226),(8,4234),(8,4236),(8,4237),(8,4241),(8,4242),(8,4243),(8,4244),(8,4266),(8,4270),(8,4271),(8,4272),(8,4273),(8,4274),(8,4276),(8,4277),(8,4278),(8,4279),(8,4281),(8,4282),(8,4283),(8,4291),(8,4296),(8,4299),(8,4301),(8,4302),(8,4303),(8,4304),(8,4306),(8,4307),(8,4311),(8,4321),(8,4325),(8,4329),(8,4337),(8,4338),(8,4339),(8,4340),(8,4347),(8,4348),(8,4349),(8,4350),(8,4351),(8,4355),(8,4356),(8,4366),(8,4367),(8,4368),(8,4369),(8,4370),(8,4371),(8,4372),(8,4382),(8,4383),(8,4384),(9,3484),(9,3485),(9,3486),(9,3487),(9,3490),(9,3491),(9,3494),(9,3495),(9,3496),(9,3497),(9,3498),(9,3501),(9,3502),(9,3503),(9,3504),(9,3507),(9,3508),(9,3509),(9,3510),(9,3511),(9,3512),(9,3513),(9,3514),(9,3515),(9,3516),(9,3517),(9,3518),(9,3640),(9,3641),(9,3643),(9,3644),(9,3646),(9,3647),(9,3648),(9,3649),(9,3651),(9,3652),(9,3653),(9,3655),(9,3656),(9,3657),(9,3658),(9,3659),(9,3660),(9,3661),(9,3662),(9,3663),(9,3665),(9,3666),(9,3667),(9,3668),(9,3669),(9,3674),(9,3676),(9,3677),(9,3678),(9,3679),(9,3680),(9,3681),(9,3682),(9,3683),(9,3684),(9,3686),(9,3687),(9,3689),(9,3690),(9,3691),(9,3692),(9,3693),(9,3694),(9,3695),(9,3697),(9,3698),(9,3700),(9,3701),(9,3702),(9,3703),(9,3704),(9,3705),(9,3706),(9,3708),(9,3710),(9,3711),(9,3712),(9,3714),(9,3715),(9,3717),(9,3718),(9,3720),(9,3723),(9,3724),(9,3726),(9,3730),(9,3732),(9,3733),(9,3737),(9,3740),(9,3741),(9,3743),(9,3745),(9,3746),(9,3747),(9,3748),(9,3750),(9,3751),(9,3752),(9,3757),(9,3758),(9,3759),(9,3760),(9,3761),(9,3764),(9,3765),(9,3766),(9,3767),(9,3768),(9,3769),(9,3770),(9,3771),(9,3772),(9,3773),(9,3774),(9,3775),(9,3776),(9,3777),(9,3778),(9,3779),(9,3781),(9,3782),(9,3783),(9,3784),(9,3785),(9,3789),(9,3790),(9,3791),(9,3792),(9,3793),(9,3794),(9,3796),(9,3797),(9,3799),(9,3800),(9,3801),(9,3802),(9,3804),(9,3805),(9,3806),(9,3807),(9,3809),(9,3810),(9,3811),(9,3812),(9,3813),(9,3814),(9,3816),(9,3817),(9,3819),(9,3820),(9,3823),(9,3826),(9,3834),(9,3835),(9,3836),(9,3839),(9,3840),(9,3841),(9,3842),(9,3843),(9,3846),(9,3847),(9,3850),(9,3851),(9,3852),(9,3853),(9,3854),(9,3855),(9,3856),(9,3857),(9,3858),(9,3861),(9,3889),(9,3890),(9,3891),(9,3897),(9,3902),(9,3913),(9,3914),(9,3915),(9,3917),(9,3918),(9,3920),(9,3921),(9,3922),(9,3923),(9,3925),(9,3926),(9,3927),(9,3929),(9,3930),(9,3931),(9,3932),(9,3936),(9,3937),(9,3938),(9,3939),(9,3940),(9,3941),(9,3942),(9,3944),(9,3945),(9,3946),(9,3949),(9,3950),(9,3951),(9,3952),(9,3959),(9,3961),(9,3962),(9,3971),(9,3972),(9,3979),(9,3980),(9,3981),(9,3982),(9,3984),(9,3985),(9,3986),(9,3987),(9,3988),(9,3989),(9,3990),(9,3991),(9,3992),(9,3993),(9,3994),(9,3995),(9,3996),(9,3997),(9,3998),(9,4000),(9,4001),(9,4002),(9,4003),(9,4004),(9,4005),(9,4006),(9,4027),(9,4028),(9,4029),(9,4030),(9,4031),(9,4032),(9,4033),(9,4039),(9,4043),(9,4044),(9,4045),(9,4046),(9,4047),(9,4048),(9,4056),(9,4058),(9,4059),(9,4064),(9,4065),(9,4066),(9,4068),(9,4069),(9,4070),(9,4071),(9,4073),(9,4101),(9,4102),(9,4117),(9,4118),(9,4119),(9,4138),(9,4139),(9,4140),(9,4141),(9,4142),(9,4143),(9,4146),(9,4147),(9,4148),(9,4149),(9,4151),(9,4152),(9,4153),(9,4160),(9,4161),(9,4162),(9,4165),(9,4166),(9,4167),(9,4185),(9,4189),(9,4190),(9,4191),(9,4192),(9,4193),(9,4194),(9,4226),(9,4234),(9,4236),(9,4237),(9,4241),(9,4242),(9,4243),(9,4244),(9,4266),(9,4270),(9,4271),(9,4272),(9,4273),(9,4274),(9,4276),(9,4277),(9,4278),(9,4279),(9,4281),(9,4282),(9,4283),(9,4291),(9,4296),(9,4299),(9,4301),(9,4302),(9,4303),(9,4304),(9,4306),(9,4307),(9,4311),(9,4321),(9,4325),(9,4329),(9,4337),(9,4338),(9,4339),(9,4340),(9,4347),(9,4348),(9,4349),(9,4350),(9,4351),(9,4355),(9,4356),(9,4366),(9,4367),(9,4368),(9,4370),(9,4384),(10,3484),(10,3485),(10,3486),(10,3487),(10,3494),(10,3495),(10,3496),(10,3497),(10,3508),(10,3509),(10,3510),(10,3511),(10,3512),(10,3513),(10,3514),(10,3515),(10,3516),(10,3517),(10,3518),(10,3640),(10,3641),(10,3643),(10,3644),(10,3646),(10,3647),(10,3648),(10,3649),(10,3651),(10,3652),(10,3653),(10,3655),(10,3657),(10,3658),(10,3659),(10,3660),(10,3661),(10,3662),(10,3663),(10,3665),(10,3666),(10,3667),(10,3668),(10,3669),(10,3676),(10,3677),(10,3678),(10,3679),(10,3681),(10,3682),(10,3683),(10,3684),(10,3686),(10,3687),(10,3688),(10,3689),(10,3690),(10,3691),(10,3692),(10,3693),(10,3694),(10,3695),(10,3697),(10,3698),(10,3700),(10,3701),(10,3702),(10,3703),(10,3704),(10,3705),(10,3706),(10,3708),(10,3710),(10,3711),(10,3712),(10,3713),(10,3714),(10,3715),(10,3717),(10,3718),(10,3720),(10,3723),(10,3724),(10,3726),(10,3730),(10,3732),(10,3733),(10,3737),(10,3740),(10,3741),(10,3742),(10,3745),(10,3757),(10,3758),(10,3759),(10,3760),(10,3761),(10,3762),(10,3764),(10,3765),(10,3766),(10,3767),(10,3768),(10,3769),(10,3770),(10,3771),(10,3772),(10,3773),(10,3774),(10,3775),(10,3776),(10,3777),(10,3778),(10,3779),(10,3781),(10,3782),(10,3783),(10,3784),(10,3785),(10,3788),(10,3789),(10,3790),(10,3791),(10,3792),(10,3793),(10,3794),(10,3795),(10,3796),(10,3797),(10,3798),(10,3799),(10,3800),(10,3801),(10,3802),(10,3803),(10,3804),(10,3805),(10,3806),(10,3807),(10,3809),(10,3810),(10,3811),(10,3812),(10,3813),(10,3814),(10,3816),(10,3817),(10,3818),(10,3819),(10,3820),(10,3821),(10,3823),(10,3826),(10,3829),(10,3834),(10,3835),(10,3836),(10,3837),(10,3840),(10,3841),(10,3843),(10,3846),(10,3847),(10,3850),(10,3851),(10,3852),(10,3853),(10,3854),(10,3855),(10,3856),(10,3857),(10,3858),(10,3861),(10,3866),(10,3867),(10,3868),(10,3872),(10,3873),(10,3874),(10,3877),(10,3878),(10,3879),(10,3880),(10,3883),(10,3884),(10,3885),(10,3888),(10,3889),(10,3890),(10,3891),(10,3895),(10,3896),(10,3897),(10,3902),(10,3903),(10,3904),(10,3905),(10,3909),(10,3910),(10,3911),(10,3913),(10,3914),(10,3915),(10,3917),(10,3918),(10,3920),(10,3921),(10,3922),(10,3923),(10,3926),(10,3927),(10,3930),(10,3931),(10,3939),(10,3941),(10,3944),(10,3945),(10,3946),(10,3949),(10,3950),(10,3951),(10,3952),(10,3959),(10,3960),(10,3961),(10,3962),(10,3971),(10,3972),(10,3973),(10,3979),(10,3980),(10,3981),(10,3982),(10,3984),(10,3985),(10,4000),(10,4001),(10,4002),(10,4003),(10,4004),(10,4005),(10,4006),(10,4027),(10,4028),(10,4029),(10,4030),(10,4031),(10,4032),(10,4033),(10,4034),(10,4035),(10,4036),(10,4037),(10,4039),(10,4043),(10,4044),(10,4045),(10,4046),(10,4047),(10,4048),(10,4049),(10,4050),(10,4053),(10,4054),(10,4055),(10,4056),(10,4057),(10,4058),(10,4059),(10,4064),(10,4065),(10,4066),(10,4068),(10,4069),(10,4070),(10,4071),(10,4073),(10,4101),(10,4102),(10,4103),(10,4105),(10,4108),(10,4112),(10,4113),(10,4114),(10,4115),(10,4116),(10,4117),(10,4118),(10,4119),(10,4138),(10,4139),(10,4140),(10,4141),(10,4142),(10,4143),(10,4146),(10,4147),(10,4148),(10,4149),(10,4151),(10,4152),(10,4153),(10,4160),(10,4161),(10,4162),(10,4165),(10,4166),(10,4167),(10,4185),(10,4189),(10,4190),(10,4191),(10,4192),(10,4193),(10,4194),(10,4216),(10,4220),(10,4221),(10,4222),(10,4223),(10,4224),(10,4225),(10,4226),(10,4234),(10,4236),(10,4237),(10,4241),(10,4242),(10,4243),(10,4244),(10,4266),(10,4270),(10,4271),(10,4272),(10,4273),(10,4274),(10,4276),(10,4277),(10,4278),(10,4279),(10,4281),(10,4282),(10,4283),(10,4291),(10,4296),(10,4299),(10,4301),(10,4302),(10,4303),(10,4304),(10,4306),(10,4307),(10,4308),(10,4311),(10,4321),(10,4325),(10,4326),(10,4329),(10,4337),(10,4338),(10,4339),(10,4340),(10,4347),(10,4348),(10,4349),(10,4350),(10,4351),(10,4355),(10,4356),(10,4366),(10,4367),(10,4368),(10,4369),(10,4370),(10,4371),(10,4372),(10,4382),(10,4383),(10,4384),(11,3484),(11,3485),(11,3486),(11,3487),(11,3494),(11,3495),(11,3496),(11,3497),(11,3498),(11,3502),(11,3503),(11,3504),(11,3507),(11,3508),(11,3509),(11,3510),(11,3511),(11,3512),(11,3513),(11,3514),(11,3515),(11,3516),(11,3517),(11,3518),(11,3640),(11,3643),(11,3647),(11,3648),(11,3649),(11,3652),(11,3655),(11,3657),(11,3658),(11,3668),(11,3669),(11,3678),(11,3679),(11,3682),(11,3683),(11,3686),(11,3687),(11,3688),(11,3689),(11,3690),(11,3691),(11,3694),(11,3698),(11,3702),(11,3704),(11,3706),(11,3711),(11,3720),(11,3723),(11,3724),(11,3726),(11,3730),(11,3732),(11,3733),(11,3744),(11,3757),(11,3760),(11,3764),(11,3765),(11,3767),(11,3768),(11,3769),(11,3770),(11,3772),(11,3773),(11,3775),(11,3777),(11,3781),(11,3783),(11,3784),(11,3785),(11,3790),(11,3792),(11,3793),(11,3796),(11,3797),(11,3801),(11,3802),(11,3805),(11,3806),(11,3807),(11,3809),(11,3810),(11,3811),(11,3812),(11,3813),(11,3817),(11,3819),(11,3820),(11,3823),(11,3834),(11,3835),(11,3840),(11,3841),(11,3843),(11,3846),(11,3851),(11,3852),(11,3853),(11,3854),(11,3855),(11,3856),(11,3861),(11,3866),(11,3874),(11,3889),(11,3890),(11,3897),(11,3901),(11,3902),(11,3903),(11,3904),(11,3911),(11,3913),(11,3914),(11,3915),(11,3917),(11,3918),(11,3920),(11,3921),(11,3922),(11,3923),(11,3926),(11,3927),(11,3928),(11,3930),(11,3931),(11,3940),(11,3941),(11,3942),(11,3944),(11,3945),(11,3946),(11,3949),(11,3950),(11,3951),(11,3952),(11,3959),(11,3961),(11,3962),(11,3963),(11,3970),(11,3971),(11,3979),(11,3980),(11,3981),(11,3982),(11,3984),(11,3985),(11,3986),(11,3994),(11,4000),(11,4001),(11,4002),(11,4003),(11,4004),(11,4005),(11,4006),(11,4007),(11,4008),(11,4015),(11,4016),(11,4017),(11,4024),(11,4027),(11,4028),(11,4029),(11,4030),(11,4031),(11,4032),(11,4033),(11,4039),(11,4043),(11,4044),(11,4045),(11,4046),(11,4047),(11,4048),(11,4056),(11,4058),(11,4059),(11,4062),(11,4063),(11,4064),(11,4065),(11,4066),(11,4067),(11,4068),(11,4069),(11,4070),(11,4071),(11,4072),(11,4073),(11,4074),(11,4075),(11,4076),(11,4077),(11,4078),(11,4079),(11,4091),(11,4092),(11,4093),(11,4094),(11,4095),(11,4096),(11,4097),(11,4098),(11,4099),(11,4100),(11,4101),(11,4102),(11,4106),(11,4107),(11,4115),(11,4117),(11,4118),(11,4119),(11,4120),(11,4121),(11,4128),(11,4129),(11,4137),(11,4138),(11,4139),(11,4140),(11,4141),(11,4142),(11,4143),(11,4146),(11,4147),(11,4148),(11,4149),(11,4151),(11,4152),(11,4153),(11,4160),(11,4161),(11,4162),(11,4165),(11,4166),(11,4167),(11,4185),(11,4189),(11,4190),(11,4191),(11,4192),(11,4193),(11,4194),(11,4226),(11,4234),(11,4236),(11,4237),(11,4241),(11,4242),(11,4243),(11,4244),(11,4266),(11,4270),(11,4271),(11,4272),(11,4273),(11,4274),(11,4276),(11,4277),(11,4278),(11,4279),(11,4281),(11,4282),(11,4283),(11,4291),(11,4296),(11,4299),(11,4301),(11,4302),(11,4303),(11,4304),(11,4306),(11,4307),(11,4311),(11,4321),(11,4325),(11,4329),(11,4337),(11,4338),(11,4339),(11,4340),(11,4347),(11,4348),(11,4349),(11,4350),(11,4351),(11,4355),(11,4356),(11,4366),(11,4367),(11,4368),(11,4369),(11,4370),(11,4371),(11,4372),(11,4382),(11,4383),(11,4384),(12,3484),(12,3485),(12,3486),(12,3487),(12,3494),(12,3495),(12,3496),(12,3497),(12,3498),(12,3502),(12,3503),(12,3504),(12,3507),(12,3508),(12,3509),(12,3510),(12,3511),(12,3512),(12,3513),(12,3514),(12,3515),(12,3516),(12,3517),(12,3518),(12,3640),(12,3643),(12,3647),(12,3648),(12,3649),(12,3652),(12,3655),(12,3657),(12,3658),(12,3665),(12,3668),(12,3669),(12,3678),(12,3679),(12,3681),(12,3682),(12,3683),(12,3686),(12,3687),(12,3688),(12,3689),(12,3690),(12,3691),(12,3694),(12,3697),(12,3698),(12,3702),(12,3704),(12,3706),(12,3710),(12,3711),(12,3720),(12,3723),(12,3724),(12,3730),(12,3732),(12,3733),(12,3757),(12,3759),(12,3760),(12,3764),(12,3765),(12,3767),(12,3768),(12,3769),(12,3770),(12,3772),(12,3773),(12,3774),(12,3775),(12,3777),(12,3778),(12,3779),(12,3781),(12,3783),(12,3784),(12,3785),(12,3789),(12,3790),(12,3792),(12,3793),(12,3794),(12,3796),(12,3797),(12,3800),(12,3801),(12,3802),(12,3805),(12,3806),(12,3807),(12,3809),(12,3810),(12,3811),(12,3812),(12,3813),(12,3817),(12,3819),(12,3820),(12,3823),(12,3834),(12,3835),(12,3836),(12,3840),(12,3841),(12,3843),(12,3846),(12,3851),(12,3852),(12,3853),(12,3854),(12,3855),(12,3856),(12,3861),(12,3866),(12,3867),(12,3874),(12,3889),(12,3890),(12,3897),(12,3901),(12,3902),(12,3913),(12,3914),(12,3915),(12,3917),(12,3918),(12,3920),(12,3921),(12,3922),(12,3923),(12,3926),(12,3927),(12,3928),(12,3930),(12,3931),(12,3932),(12,3939),(12,3940),(12,3941),(12,3942),(12,3944),(12,3945),(12,3946),(12,3949),(12,3950),(12,3951),(12,3952),(12,3959),(12,3961),(12,3962),(12,3963),(12,3970),(12,3971),(12,3972),(12,3979),(12,3980),(12,3981),(12,3982),(12,3984),(12,3985),(12,3986),(12,3994),(12,4000),(12,4001),(12,4002),(12,4003),(12,4004),(12,4005),(12,4006),(12,4007),(12,4008),(12,4015),(12,4016),(12,4017),(12,4024),(12,4027),(12,4028),(12,4029),(12,4030),(12,4031),(12,4032),(12,4033),(12,4039),(12,4043),(12,4044),(12,4045),(12,4046),(12,4047),(12,4048),(12,4049),(12,4056),(12,4058),(12,4059),(12,4063),(12,4064),(12,4065),(12,4066),(12,4068),(12,4069),(12,4070),(12,4071),(12,4073),(12,4101),(12,4102),(12,4117),(12,4118),(12,4119),(12,4120),(12,4121),(12,4128),(12,4138),(12,4139),(12,4140),(12,4141),(12,4142),(12,4143),(12,4144),(12,4146),(12,4147),(12,4148),(12,4149),(12,4150),(12,4151),(12,4152),(12,4153),(12,4154),(12,4155),(12,4156),(12,4157),(12,4158),(12,4159),(12,4160),(12,4161),(12,4162),(12,4163),(12,4164),(12,4165),(12,4166),(12,4167),(12,4185),(12,4189),(12,4190),(12,4191),(12,4192),(12,4193),(12,4194),(12,4226),(12,4234),(12,4236),(12,4237),(12,4241),(12,4242),(12,4243),(12,4244),(12,4266),(12,4270),(12,4271),(12,4272),(12,4273),(12,4274),(12,4276),(12,4277),(12,4278),(12,4279),(12,4281),(12,4282),(12,4283),(12,4291),(12,4296),(12,4299),(12,4301),(12,4302),(12,4303),(12,4304),(12,4306),(12,4307),(12,4311),(12,4321),(12,4325),(12,4329),(12,4337),(12,4338),(12,4339),(12,4340),(12,4347),(12,4348),(12,4349),(12,4350),(12,4351),(12,4355),(12,4356),(12,4366),(12,4367),(12,4368),(12,4369),(12,4370),(12,4371),(12,4372),(12,4382),(12,4383),(12,4384),(13,1918),(13,1919),(13,1920),(13,1921),(13,1922),(13,1923),(13,1924),(13,1925),(13,1926),(13,1927),(13,3484),(13,3485),(13,3486),(13,3487),(13,3488),(13,3489),(13,3490),(13,3491),(13,3492),(13,3493),(13,3494),(13,3495),(13,3496),(13,3497),(13,3498),(13,3499),(13,3500),(13,3501),(13,3502),(13,3503),(13,3504),(13,3505),(13,3506),(13,3507),(13,3508),(13,3509),(13,3510),(13,3511),(13,3512),(13,3513),(13,3514),(13,3515),(13,3516),(13,3517),(13,3518),(13,3547),(13,3555),(13,3640),(13,3641),(13,3643),(13,3646),(13,3647),(13,3648),(13,3649),(13,3651),(13,3652),(13,3653),(13,3654),(13,3655),(13,3656),(13,3657),(13,3658),(13,3659),(13,3660),(13,3661),(13,3662),(13,3663),(13,3664),(13,3665),(13,3666),(13,3667),(13,3668),(13,3669),(13,3674),(13,3676),(13,3677),(13,3678),(13,3679),(13,3680),(13,3681),(13,3682),(13,3683),(13,3684),(13,3685),(13,3686),(13,3687),(13,3688),(13,3689),(13,3690),(13,3691),(13,3692),(13,3693),(13,3694),(13,3695),(13,3696),(13,3697),(13,3698),(13,3701),(13,3702),(13,3703),(13,3704),(13,3705),(13,3706),(13,3708),(13,3709),(13,3710),(13,3711),(13,3712),(13,3713),(13,3714),(13,3715),(13,3717),(13,3718),(13,3720),(13,3723),(13,3724),(13,3725),(13,3726),(13,3729),(13,3730),(13,3732),(13,3733),(13,3736),(13,3737),(13,3739),(13,3740),(13,3741),(13,3742),(13,3743),(13,3744),(13,3745),(13,3746),(13,3747),(13,3748),(13,3749),(13,3750),(13,3751),(13,3752),(13,3753),(13,3754),(13,3755),(13,3756),(13,3757),(13,3758),(13,3759),(13,3760),(13,3761),(13,3762),(13,3763),(13,3764),(13,3765),(13,3766),(13,3767),(13,3768),(13,3769),(13,3770),(13,3771),(13,3772),(13,3773),(13,3774),(13,3775),(13,3776),(13,3777),(13,3778),(13,3779),(13,3780),(13,3781),(13,3782),(13,3783),(13,3784),(13,3785),(13,3786),(13,3787),(13,3788),(13,3789),(13,3790),(13,3791),(13,3792),(13,3793),(13,3794),(13,3795),(13,3796),(13,3797),(13,3798),(13,3799),(13,3800),(13,3801),(13,3802),(13,3803),(13,3804),(13,3805),(13,3806),(13,3807),(13,3808),(13,3809),(13,3810),(13,3811),(13,3812),(13,3813),(13,3814),(13,3816),(13,3817),(13,3818),(13,3819),(13,3820),(13,3821),(13,3822),(13,3823),(13,3826),(13,3827),(13,3828),(13,3829),(13,3830),(13,3831),(13,3832),(13,3833),(13,3834),(13,3835),(13,3836),(13,3837),(13,3838),(13,3839),(13,3840),(13,3841),(13,3842),(13,3843),(13,3844),(13,3845),(13,3846),(13,3847),(13,3848),(13,3849),(13,3850),(13,3851),(13,3852),(13,3853),(13,3854),(13,3855),(13,3856),(13,3857),(13,3858),(13,3859),(13,3860),(13,3861),(13,3862),(13,3863),(13,3864),(13,3865),(13,3866),(13,3867),(13,3868),(13,3869),(13,3871),(13,3872),(13,3873),(13,3874),(13,3875),(13,3877),(13,3878),(13,3879),(13,3880),(13,3883),(13,3884),(13,3885),(13,3888),(13,3889),(13,3890),(13,3891),(13,3892),(13,3893),(13,3894),(13,3895),(13,3896),(13,3897),(13,3899),(13,3900),(13,3901),(13,3902),(13,3903),(13,3904),(13,3905),(13,3906),(13,3907),(13,3908),(13,3909),(13,3910),(13,3911),(13,3913),(13,3914),(13,3915),(13,3917),(13,3918),(13,3920),(13,3921),(13,3922),(13,3923),(13,3924),(13,3925),(13,3926),(13,3927),(13,3928),(13,3929),(13,3930),(13,3931),(13,3932),(13,3936),(13,3937),(13,3938),(13,3939),(13,3940),(13,3941),(13,3942),(13,3944),(13,3945),(13,3946),(13,3949),(13,3950),(13,3951),(13,3952),(13,3954),(13,3957),(13,3958),(13,3959),(13,3960),(13,3961),(13,3962),(13,3963),(13,3965),(13,3968),(13,3969),(13,3970),(13,3971),(13,3972),(13,3973),(13,3974),(13,3975),(13,3976),(13,3977),(13,3978),(13,3979),(13,3980),(13,3981),(13,3982),(13,3984),(13,3985),(13,3986),(13,3987),(13,3988),(13,3989),(13,3990),(13,3991),(13,3992),(13,3993),(13,3994),(13,3995),(13,3996),(13,3997),(13,3998),(13,3999),(13,4000),(13,4001),(13,4002),(13,4003),(13,4004),(13,4005),(13,4006),(13,4007),(13,4008),(13,4010),(13,4012),(13,4013),(13,4014),(13,4015),(13,4016),(13,4017),(13,4018),(13,4019),(13,4020),(13,4021),(13,4022),(13,4023),(13,4024),(13,4026),(13,4027),(13,4028),(13,4029),(13,4030),(13,4031),(13,4032),(13,4033),(13,4034),(13,4035),(13,4036),(13,4037),(13,4039),(13,4043),(13,4044),(13,4045),(13,4046),(13,4047),(13,4048),(13,4049),(13,4050),(13,4053),(13,4054),(13,4055),(13,4056),(13,4057),(13,4058),(13,4059),(13,4062),(13,4063),(13,4064),(13,4065),(13,4066),(13,4067),(13,4068),(13,4069),(13,4070),(13,4071),(13,4072),(13,4073),(13,4074),(13,4075),(13,4076),(13,4077),(13,4078),(13,4079),(13,4091),(13,4092),(13,4093),(13,4094),(13,4095),(13,4096),(13,4097),(13,4098),(13,4099),(13,4100),(13,4101),(13,4102),(13,4103),(13,4105),(13,4106),(13,4107),(13,4117),(13,4118),(13,4119),(13,4120),(13,4121),(13,4122),(13,4123),(13,4124),(13,4125),(13,4126),(13,4127),(13,4128),(13,4129),(13,4130),(13,4131),(13,4132),(13,4133),(13,4134),(13,4135),(13,4136),(13,4137),(13,4138),(13,4139),(13,4140),(13,4141),(13,4142),(13,4143),(13,4144),(13,4146),(13,4147),(13,4148),(13,4149),(13,4150),(13,4151),(13,4152),(13,4153),(13,4154),(13,4155),(13,4156),(13,4157),(13,4158),(13,4159),(13,4160),(13,4161),(13,4162),(13,4163),(13,4164),(13,4165),(13,4166),(13,4167),(13,4168),(13,4172),(13,4173),(13,4174),(13,4175),(13,4176),(13,4180),(13,4181),(13,4182),(13,4183),(13,4184),(13,4185),(13,4189),(13,4190),(13,4191),(13,4192),(13,4193),(13,4194),(13,4216),(13,4220),(13,4221),(13,4222),(13,4223),(13,4224),(13,4225),(13,4226),(13,4227),(13,4229),(13,4232),(13,4233),(13,4234),(13,4235),(13,4236),(13,4237),(13,4241),(13,4242),(13,4243),(13,4244),(13,4245),(13,4246),(13,4247),(13,4248),(13,4249),(13,4250),(13,4251),(13,4252),(13,4253),(13,4254),(13,4255),(13,4256),(13,4257),(13,4258),(13,4259),(13,4260),(13,4261),(13,4262),(13,4263),(13,4264),(13,4266),(13,4270),(13,4271),(13,4272),(13,4273),(13,4274),(13,4275),(13,4276),(13,4277),(13,4278),(13,4279),(13,4281),(13,4282),(13,4283),(13,4284),(13,4289),(13,4290),(13,4291),(13,4294),(13,4295),(13,4296),(13,4298),(13,4299),(13,4301),(13,4302),(13,4303),(13,4304),(13,4305),(13,4306),(13,4307),(13,4308),(13,4309),(13,4310),(13,4311),(13,4312),(13,4313),(13,4314),(13,4315),(13,4316),(13,4317),(13,4318),(13,4319),(13,4320),(13,4321),(13,4325),(13,4326),(13,4329),(13,4330),(13,4331),(13,4332),(13,4333),(13,4334),(13,4335),(13,4336),(13,4337),(13,4338),(13,4339),(13,4340),(13,4341),(13,4342),(13,4343),(13,4344),(13,4345),(13,4346),(13,4347),(13,4348),(13,4349),(13,4350),(13,4351),(13,4352),(13,4353),(13,4355),(13,4356),(13,4359),(13,4360),(13,4366),(13,4367),(13,4368),(13,4369),(13,4370),(13,4371),(13,4372),(13,4373),(13,4374),(13,4375),(13,4376),(13,4377),(13,4378),(13,4379),(13,4380),(13,4381),(13,4382),(13,4383),(13,4384);
/*!40000 ALTER TABLE `reactor_ugroup_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactor_user`
--

DROP TABLE IF EXISTS `reactor_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reactor_user` (
  `pk_user` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fk_ugroup` int(11) NOT NULL DEFAULT '0',
  `login` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `visited` int(11) unsigned NOT NULL DEFAULT '0',
  `cookie` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `ip_allowed` varchar(45) NOT NULL,
  PRIMARY KEY (`pk_user`),
  UNIQUE KEY `idx_unic` (`login`),
  KEY `idx_login` (`login`,`pass`),
  KEY `idx_cookie` (`cookie`,`active`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=249875 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_user`
--

LOCK TABLES `reactor_user` WRITE;
/*!40000 ALTER TABLE `reactor_user` DISABLE KEYS */;
INSERT INTO `reactor_user` VALUES (1,1,'guest','no','',0,'','Гостевой аккаунт',1,''),(2,2,'root','anabios','toecto@gmail.com',1,'','Popov Maxim S.',1,'');
/*!40000 ALTER TABLE `reactor_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_tree`
--

DROP TABLE IF EXISTS `site_tree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_tree` (
  `pk_site_tree` int(11) NOT NULL AUTO_INCREMENT,
  `fk_site_tree` int(11) NOT NULL DEFAULT '0',
  `modified` varchar(100) NOT NULL DEFAULT '0',
  `param` varchar(100) NOT NULL DEFAULT '',
  `sort` int(11) NOT NULL DEFAULT '0',
  `public` tinyint(1) NOT NULL DEFAULT '0',
  `interface` varchar(100) NOT NULL DEFAULT '',
  `action` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `module` varchar(100) NOT NULL DEFAULT '',
  `template` varchar(100) NOT NULL DEFAULT '',
  `call` varchar(100) NOT NULL DEFAULT '',
  `call_e` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `handle` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk_site_tree`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=170 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_tree`
--

LOCK TABLES `site_tree` WRITE;
/*!40000 ALTER TABLE `site_tree` DISABLE KEYS */;
INSERT INTO `site_tree` VALUES (1,0,'none','',4,0,'','','index','','','reactor','','',0),(2,1,'none','',14,0,'','','404','site','404.tpl','404','404','',1),(3,1,'none','interface;action',2,0,'cp','show','cp','cp','index.tpl','','','',1),(4,1,'none','',4,0,'','','login','cp','login.tpl','','','',1),(5,1,'none','interface;action',6,0,'content_adapter','handleForm','handleForm','','','','','',1),(45,3,'none','',0,0,'','','html_preview','cp','html_preview.tpl','','','',1),(57,1,'none','',8,0,'content_adapter','ajax_request','ajax_request','reactor','null.tpl','','','',1);
/*!40000 ALTER TABLE `site_tree` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-19 23:56:21

-- MySQL dump 10.13  Distrib 5.7.13, for Linux (x86_64)
--
-- Host: localhost    Database: ecto_reactor_3_9
-- ------------------------------------------------------
-- Server version	5.7.13-0ubuntu0.16.04.2

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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_base_type`
--

LOCK TABLES `reactor_base_type` WRITE;
/*!40000 ALTER TABLE `reactor_base_type` DISABLE KEYS */;
INSERT INTO `reactor_base_type` VALUES (1,3,'bbcode','bbcode',1,1,0,'bbcode','ca_bbcode'),(2,3,'check_images','check_images',1,2,0,'check_images','ca_flags'),(3,3,'code','code',1,1,0,'textarea','ca_code'),(4,3,'code_line','code_line',1,1,0,'text','ca_code'),(5,3,'date','date',1,1,0,'date','ca_date'),(6,3,'date_time','date_time',0,0,0,'date_time','ca_date_time'),(7,3,'email','email',0,0,0,'text','ca_email'),(8,3,'enum','enum',0,0,0,'textarea','ca_enum'),(9,3,'file','file',1,1,0,'file','ca_file'),(10,3,'files','files',1,2,0,'files','ca_files'),(11,3,'flag','flag',1,1,0,'checkbox','ca_int'),(12,3,'flags','flags',0,2,0,'checkboxes','ca_flags'),(13,3,'hidden','hidden',1,1,0,'null','ca_string'),(14,3,'html','html',1,1,0,'html','ca_html'),(15,3,'image','image',1,1,0,'image','ca_image'),(16,3,'images','images',0,2,0,'images','ca_files'),(17,3,'int','int',1,1,0,'text','ca_int'),(18,3,'label','label',1,1,0,'disabled','base_type'),(19,3,'mail','mail',1,1,0,'mail','ca_mail'),(20,3,'radiobutton','radiobutton',1,1,0,'radiobutton','ca_text'),(21,3,'save','save',1,1,2,'null','base_type'),(22,3,'select','select',1,1,0,'select','ca_text'),(23,3,'select_image','select_image',1,1,0,'select_image','ca_text'),(24,3,'select_interactive','select_interactive',0,1,0,'select_interactive','ca_string'),(25,3,'string','string',1,1,0,'text','ca_string'),(26,3,'text','text',1,1,0,'textarea','ca_text'),(27,3,'url_key','url_key',0,0,3,'null','ca_url_key');
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_config`
--

LOCK TABLES `reactor_config` WRITE;
/*!40000 ALTER TABLE `reactor_config` DISABLE KEYS */;
INSERT INTO `reactor_config` VALUES (1,'COOKIE_LIVE',1,'','60*60*24*30+time()',''),(2,'DEBUG_SQL',1,'','0',''),(3,'DEBUG_TPL',1,'','0',''),(4,'DEF_LANGUAGE',1,'','\'ru\'',''),(5,'FORMAT_DATE',1,'','\'d.m.Y\'',''),(6,'format_datetime',1,'','\'d.m.Y, H:i\'',''),(7,'mobile_app_version',1,'','1',''),(8,'VERSION',1,'','\'3.8\'',''),(9,'default_interface',3,'','\'reactor_module\'','');
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
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
  `pkey` varchar(255) NOT NULL DEFAULT '',
  `configurators` varchar(255) NOT NULL DEFAULT '',
  `constructor` varchar(255) NOT NULL DEFAULT '',
  `descrip` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_interface`),
  KEY `idx_name` (`fk_module`,`name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_interface`
--

LOCK TABLES `reactor_interface` WRITE;
/*!40000 ALTER TABLE `reactor_interface` DISABLE KEYS */;
INSERT INTO `reactor_interface` VALUES (1,0,1,'content_adapter','reactor\\content_adapter','','','',''),(2,0,1,'reactor_help','reactor\\basic_object','pk_help','','',''),(3,0,2,'reactor_base_type','mod\\constructor\\reactor_base_type','pk_base_type','fk_module','',''),(4,0,2,'reactor_config','mod\\constructor\\reactor_config','pk_config','fk_module','',''),(5,0,2,'reactor_interface','mod\\constructor\\reactor_interface_edit','pk_interface','fk_module','',''),(6,0,2,'reactor_interface_action','mod\\constructor\\reactor_interface_action','pk_action','fk_interface','',''),(7,0,2,'reactor_interface_define','mod\\constructor\\reactor_interface_define','pk_define','fk_interface','',''),(8,0,2,'reactor_module','mod\\constructor\\reactor_module','pk_module','','',''),(9,0,2,'reactor_table','mod\\constructor\\reactor_table','pk_table','fk_module','',''),(10,0,2,'reactor_user','mod\\constructor\\user_rights\\reactor_user','pk_user','','',''),(11,0,2,'reactor_user_group','mod\\constructor\\user_rights\\reactor_user_group','pk_ugroup','','',''),(12,0,2,'reactor_user_group_rights','mod\\constructor\\user_rights\\reactor_user_group_rights','','','',''),(13,0,2,'site_tree','mod\\constructor\\site_tree','pk_site_tree','fk_site_tree','',''),(14,0,3,'cp','mod\\cp\\cp','','','',''),(15,0,4,'site','reactor\\basic_tree','pk_site_tree','fk_site_tree','',''),(16,0,4,'TestController','mod\\site\\TestController','','','','');
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
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_interface_action`
--

LOCK TABLES `reactor_interface_action` WRITE;
/*!40000 ALTER TABLE `reactor_interface_action` DISABLE KEYS */;
INSERT INTO `reactor_interface_action` VALUES (1,0,1,0,'ajax_request','ajax_request','','_execute','inputGetStr(\'interface\',\'content_adapter\'),inputGetStr(\'action\',\'null\'),inputGetStr(\'template\',\'ajax_data.tpl\'),inputGetStr(\'module\',\'reactor\')','','',0,0,'',0),(2,0,1,0,'handleForm','','','_handleForm','inputGetStr(\'_so\',\'none\'),inputGetStr(\'interface\',\'none\'),inputGetStr(\'action\',\'none\')','','',0,1,'',0),(3,0,1,0,'jsonp_request','jsonp_request','','_execute','inputGetStr(\'interface\',\'content_adapter\'),inputGetStr(\'action\',\'null\'),inputGetStr(\'template\',\'jsonp_data.tpl\'),inputGetStr(\'module\',\'reactor\')','','',0,0,'',0),(4,0,1,0,'json_request','json_request','','_execute','inputGetStr(\'interface\',\'content_adapter\'),inputGetStr(\'action\',\'null\'),inputGetStr(\'template\',\'json_data.tpl\'),inputGetStr(\'module\',\'reactor\')','','',0,0,'',0),(5,0,1,0,'null','null','','_time','','','',0,0,'',0),(6,0,1,0,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(7,0,2,0,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(8,0,2,0,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(9,0,2,0,'delete','Удалить','','delete','inputGetNum(\'pk_help\')','','',1,1,'',1),(10,0,2,0,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(11,82,2,0,'getList','Справка','','getList','inputGetNum(\'reactor_help_page\',1),20/*,\'where\'*/','list.tpl','cp',3,0,'',0),(12,0,2,0,'getOne','Один элемент','','getOne','inputGetNum(\'pk_help\',0)','','',0,0,'',0),(13,0,2,0,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(14,0,2,0,'reactor_help','','','configure','T_REACTOR_HELP,\'title\'','','',0,0,'',0),(15,0,2,0,'show','Справка','','getOne','inputGetNum(\'pk_help\')','help.tpl','reactor',0,0,'',0),(16,0,2,0,'store','Сохранение','','store','$param','','',0,0,'',0),(17,0,3,16,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(18,0,3,10,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(19,0,3,20,'delete','Удалить','','delete','inputGetNum(\'pk_base_type\')','','',1,1,'',1),(20,0,3,18,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(21,0,3,2,'getList','Базовые типы','','getList','inputGetNum(\'reactor_base_type_page\',1),20/*,\'where\'*/','list.tpl','cp',0,0,'',0),(22,0,3,12,'getOne','Один элемент','','getOne','inputGetNum(\'pk_base_type\',0)','','',0,0,'',0),(23,0,3,4,'jump_back','Назад','','','','reactor_module','getList',2,1,'',0),(24,0,3,14,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(25,0,3,6,'reactor_base_type','','','configure','T_REACTOR_BASE_TYPE,\'name\',inputGetNum(\'fk_module\',0)','','',0,0,'',0),(26,0,3,8,'store','Сохранение','','store','$param','','',0,0,'',0),(27,0,4,16,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(28,0,4,18,'back','Назад','','','','reactor_module','getList',2,1,'',0),(29,0,4,4,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(30,0,4,12,'delete','Удалить','','delete','inputGetNum(\"pk_config\")','','',1,1,'',1),(31,0,4,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(32,0,4,8,'getList','Список','','getList','inputGetNum(\"reactor_config_page\",1),30','list.tpl','cp',0,0,'',0),(33,0,4,10,'getOne','','','getOne','inputGetNum(\"pk_config\",0)','','',0,0,'',0),(34,0,4,20,'onRestore','','','onRestore','','','',0,0,'',0),(35,0,4,6,'reactor_config','','','configure','T_REACTOR_CONFIG,\'name\',inputGetNum(\'fk_module\')','','',0,0,'',0),(36,0,4,14,'store','','','store','$param','','',0,0,'',0),(37,0,5,14,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(38,0,5,20,'back','Назад','','','','reactor_module','getList',2,1,'',0),(39,0,5,2,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(40,0,5,16,'defines','Свойства','','','','reactor_interface_define','getList',1,1,'\'fk_interface=\'.inputGetNum(\'pk_interface\')',0),(41,0,5,24,'delete','Удалить','','delete','inputGetNum(\"pk_interface\")','','',1,1,'',1),(42,0,5,12,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(43,0,5,6,'getList','Список','','getList','inputGetNum(\"reactor_interface_page\",1),30','list.tpl','cp',0,0,'',0),(44,0,5,8,'getOne','','','getOne','inputGetNum(\"pk_interface\",0)','','cp',0,0,'',0),(45,0,5,18,'methods','Экшены','','','','reactor_interface_action','getList',1,1,'\'fk_interface=\'.inputGetNum(\'pk_interface\')',0),(46,0,5,22,'onRestore','','','onRestore','','','',0,0,'',0),(47,0,5,4,'reactor_interface','','','configure','T_REACTOR_INTERFACE,\'name\',inputGetNum(\'fk_module\')','','',0,0,'',0),(48,0,5,10,'store','','','store','$param','','',0,0,'',0),(49,0,6,14,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(50,0,6,20,'addBasicObjConfigure','basic_object->configure','','addBasicObjConfigure','','','',2,1,'',1),(51,0,6,34,'addIntSelect','ajax select','','addIntSelect','','','',2,1,'',1),(52,0,6,18,'addStandartActions','Стандартные actions','','addStandartActions','','','',2,1,'',1),(53,0,6,22,'addStandartJump','Переход','','addStandartJump','','','',2,1,'',1),(54,0,6,26,'addUpDownActions','moveUpDown','','addUpDownActions','','','',2,1,'',1),(55,0,6,16,'back','Назад','','back','','reactor_interface','getList',2,1,'\'fk_module=\'.$data',0),(56,0,6,4,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(57,0,6,28,'delete','Удалить','','delete','inputGetNum(\"pk_action\")','','',1,1,'',1),(58,0,6,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(59,0,6,8,'getList','Список','','getList','inputGetNum(\"reactor_interface_action_page\",1),30','list.tpl','cp',0,0,'',0),(60,0,6,10,'getOne','','','getOne','inputGetNum(\"pk_action\",0)','','',0,0,'',0),(61,0,6,32,'moveDown','Вниз','','moveDown','inputGetNum(\'pk_action\')','','',1,1,'',0),(62,0,6,30,'moveUp','Вверх','','moveUp','inputGetNum(\'pk_action\')','','',1,1,'',0),(63,0,6,24,'onRestore','','','onRestore','','','',0,0,'',0),(64,0,6,6,'reactor_interface_action','','','configure','T_REACTOR_INTERFACE_ACTION,\'sort\',inputGetNum(\'fk_interface\')','','',0,0,'',0),(65,0,6,12,'store','','','store','$param','','',0,0,'',0),(66,0,7,14,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(67,0,7,16,'back','Назад','','back','','reactor_interface','getList',2,1,'\'fk_module=\'.$data',0),(68,0,7,4,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(69,0,7,20,'delete','Удалить','','delete','inputGetNum(\"pk_define\")','','',1,1,'',1),(70,0,7,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(71,0,7,8,'getList','Список','','getList','inputGetNum(\"reactor_interface_define_page\",1),30','list.tpl','cp',0,0,'',0),(72,0,7,10,'getOne','','','getOne','inputGetNum(\"pk_define\",0)','','',0,0,'',0),(73,0,7,24,'moveDown','Вниз','','moveDown','inputGetNum(\'pk_define\')','','',1,1,'',0),(74,0,7,22,'moveUp','Вверх','','moveUp','inputGetNum(\'pk_define\')','','',1,1,'',0),(75,0,7,18,'onRestore','','','onRestore','','','',0,0,'',0),(76,0,7,6,'reactor_interface_define','','','configure','T_REACTOR_INTERFACE_DEFINE,\'sort\',inputGetNum(\'fk_interface\')','','',0,0,'',0),(77,0,7,12,'store','','','store','$param','','',0,0,'',0),(78,0,8,18,'add','Добавить','Описание причем модет содержать php код  -  ваш логин <?php echo $_user[\'login\'];?>','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',2,0,'',0),(79,0,8,6,'config','Наcтройки','','','','reactor_config','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(80,0,8,20,'create_form','','','_createForm','$this->_pool_id,\"store\",\"getOne\"','','',0,0,'',0),(81,0,8,10,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\"_so\",\"none\"),\"create_form\"','form.tpl','cp',1,0,'',0),(82,0,8,12,'getList','Модули','','getList','inputGetNum(\"reactor_module_page\",1),30','list.tpl','cp',3,0,'',0),(83,0,8,14,'getOne','','','getOne','inputGetNum(\"pk_module\",0)','','',0,0,'',0),(84,0,8,2,'interfaces','Интерфейсы','','','','reactor_interface','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(85,0,8,8,'jump_base_type','Базовые типы','','','','reactor_base_type','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(86,0,8,4,'jump_table','Таблицы','','','','reactor_table','getList',1,1,'\'fk_module=\'.inputGetNum(\'pk_module\')',0),(87,0,8,30,'onRestore','','','onRestore','','','',0,0,'',0),(88,0,8,24,'reactor_module','','','configure','T_REACTOR_MODULE','','',0,0,'',0),(89,0,8,22,'store','','','store','$param','','',0,0,'',0),(90,0,9,4,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(91,0,9,10,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(92,0,9,20,'delete','Удалить','','delete','inputGetNum(\'pk_table\')','','',1,1,'',1),(93,0,9,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(94,0,9,14,'getList','Таблицы','','getList','inputGetNum(\'reactor_table_page\',1),20/*,\'where\'*/','list.tpl','cp',0,0,'',0),(95,0,9,16,'getOne','Один элемент','','getOne','inputGetNum(\'pk_table\',0)','','',0,0,'',0),(96,0,9,6,'jump_back','Назад','','','','reactor_module','getList',2,1,'',0),(97,0,9,8,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(98,0,9,18,'reactor_table','','','configure','T_REACTOR_TABLE,\'name\',inputGetNum(\'fk_module\',0)','','',0,0,'',0),(99,0,9,12,'store','Сохранение','','store','$param','','',0,0,'',0),(100,0,10,8,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(101,0,10,14,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(102,0,10,18,'delete','Удалить','','delete','inputGetNum(\'pk_user\')','','',1,1,'',1),(103,0,10,12,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(104,82,10,2,'getList','Пользователи','','getList','inputGetNum(\'reactor_user_page\',1),30,inputGetStr(\'filter\',\'\'),inputGetNum(\'fk_ugroup\',0)','user_list.tpl','cp',3,0,'',0),(105,0,10,6,'getOne','Один элемент','','getOne','inputGetNum(\'pk_user\',0)','','',0,0,'',0),(106,0,10,0,'getSelectGroup','Интерактивный select по группе','','getSelectGroup','\'name\',inputGetStr(\'filter\',\'\'),inputGetNum(\'fk_ugroup\',1),inputGetNum(\'getOne\',0)','','',0,0,'',0),(107,0,10,16,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(108,0,10,4,'reactor_user','','','configure','T_REACTOR_USER,\'active, login\'','','',0,0,'',0),(109,0,10,10,'store','Сохранение','','store','$param','','',0,0,'',0),(110,0,11,8,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(111,0,11,16,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(112,0,11,20,'delete','Удалить','','delete','inputGetNum(\'pk_ugroup\')','','',1,1,'',1),(113,0,11,10,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(114,82,11,2,'getList','Группы','','getList','inputGetNum(\'reactor_user_group_page\',1),30/*,\'where\'*/','list.tpl','cp',3,0,'',0),(115,0,11,4,'getOne','Один элемент','','getOne','inputGetNum(\'pk_ugroup\',0)','','',0,0,'',0),(116,0,11,18,'jump_rights','Права','','','','reactor_user_group_rights','edit',1,1,'\'fk_ugroup=\'.inputGetNum(\'pk_ugroup\')',0),(117,0,11,14,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(118,0,11,6,'reactor_user_group','','','configure','T_REACTOR_UGROUP,\'name\'','','',0,0,'',0),(119,0,11,12,'store','Сохранение','','store','$param','','',0,0,'',0),(120,0,12,0,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(121,0,12,0,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','reactor_user_group_rights.tpl','constructor',1,0,'',0),(122,0,12,0,'getOne','Один элемент','','getOne','inputGetNum(\'fk_ugroup\')','','',0,0,'',0),(123,0,12,0,'store','Сохранение','','store','$param','','',0,0,'',0),(124,0,13,4,'add','Добавить','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',2,0,'',0),(125,0,13,22,'createForm','Создание формы','','_createForm','$this->_pool_id,\'store\',\'getOne\'','','',0,0,'',0),(126,0,13,24,'delete','Удалить','','delete','inputGetNum(\'pk_site_tree\')','','',1,1,'',1),(127,0,13,2,'edit','Редактировать','','_isForm','$this->_pool_id,inputGetStr(\'_so\',\'none\'),\'createForm\'','form.tpl','cp',1,0,'',0),(128,0,13,12,'getOne','Один элемент','','getOne','inputGetNum(\'pk_site_tree\',0)','','',0,0,'',0),(129,0,13,8,'jump_add','Добавить','','','','site_tree','add',1,1,'\'fk_site_tree=\'.inputGetNum(\'pk_site_tree\')',0),(130,0,13,18,'moveDown','Вниз','','moveDown','inputGetNum(\'pk_site_tree\')','','',1,1,'',0),(131,0,13,16,'moveUp','Вверх','','moveUp','inputGetNum(\'pk_site_tree\')','','',1,1,'',0),(132,0,13,14,'onRestore','onRestore','','onRestore','','','',0,0,'',0),(133,82,13,6,'realTree','Дерево сайта','','realTree','','tree.tpl','cp',3,0,'',0),(134,0,13,10,'site_tree','','','configure','T_SITE_TREE,\'sort\',inputGetNum(\'fk_site_tree\',0)','','',0,0,'',0),(135,0,13,20,'store','Сохранение','','store','$param','','',0,0,'',0),(136,0,14,0,'description','','','description','$param','tree.tpl','',0,0,'',0),(137,0,14,0,'menu','Меню','','menu','','tree.tpl','',1,0,'',0),(138,0,14,0,'path','Путь','','path','','','',1,0,'',0),(139,0,14,0,'show','','','show','inputGetStr(\'interface\',CP_DEFAULT_INTERFACE),inputGetStr(\'action\',\'getList\')','','',0,0,'',0),(140,0,15,0,'getMainMenu','Главное меню','','realArmUp','$_reactor[\'show\'][\'pk_site_tree\']','','',0,0,'',0),(141,0,15,0,'site','','','configure','T_SITE_TREE,\'sort\',inputGetNum(\'fk_site_tree\',0)','','',0,0,'',0),(142,0,16,4,'testAction','','','testAction','','','',0,0,'',0);
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
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_interface_define`
--

LOCK TABLES `reactor_interface_define` WRITE;
/*!40000 ALTER TABLE `reactor_interface_define` DISABLE KEYS */;
INSERT INTO `reactor_interface_define` VALUES (1,2,'string','',8,'action','Экшен','',1,0,'',''),(2,2,'select','',6,'interface','Интерфейс','',1,0,'','global $_db;\n$query = $_db->sql(\'select `name` from \'.T_REACTOR_INTERFACE.\' order by name\');\n$data=$query->matr(\'name\',\'name\');'),(3,2,'string','',2,'title','Статья','',1,0,'',''),(4,2,'html','',4,'topic','Содержание','',0,1,'',''),(5,3,'code_line','',4,'call','Имя по русски','',1,1,'',''),(6,3,'select','',8,'check_array','Обработка значения','',0,0,'','$data=array(\n0=>\'Это точно не массив\',\n1=>\'Это может быть массив и проверять его рекурсивно\',\n2=>\'На входе массив но проверять его как единую сушность\',\n);'),(7,3,'flag','',10,'check_enum','Проверять на множестве значений','',0,0,'$data=0;',''),(8,3,'select','',6,'handle','Обработчик формы','',0,0,'','$data=array(\n0=>\'Обычная проверка\',\n1=>\'Удалять значение при проверке с формы\',\n2=>\'Отвергать значение c формы, сохраняя ранее заданное\',\n3=>\'Пост-обработчик для внутреннего поля\',\n);'),(9,3,'code_line','',14,'input','Имя контрола для тега input','',0,0,'',''),(10,3,'code_line','',2,'name','Имя','',1,1,'',''),(11,3,'code_line','',12,'type','Класс обработчик','',0,0,'',''),(12,4,'code_line','',8,'descrip','Описание','',0,0,'',''),(13,4,'code_line','',4,'group','Группа','',0,0,'',''),(14,4,'code_line','',2,'name','Имя','',1,1,'',''),(15,4,'code','',6,'value','Значение','',1,1,'',''),(16,5,'code_line','',4,'class','User class','',0,1,'',''),(17,5,'code_line','',12,'configurators','конфигураторы класса в url','',0,0,'',''),(18,5,'code_line','',8,'constructor','Параметры конструктора user class','',0,0,'',''),(19,5,'code_line','',2,'name','Reactor interface','',1,1,'',''),(20,5,'code_line','',10,'pkey','pkey - для списков','',0,0,'',''),(21,6,'code_line','',6,'call','Русское название','',1,0,'',''),(22,6,'flag','',12,'confirm','Подтверждение действия','',0,0,'$data=0;',''),(23,6,'code_line','',16,'cptpl','Шаблон вывода в cp','',0,0,'',''),(24,6,'code_line','',14,'cptpl_mod','Модуль шаблона','',0,0,'',''),(25,6,'code','',24,'description','Описание','',0,0,'',''),(26,6,'select','',22,'fk_action','Привязка','',0,0,'','global $_db;\n$query = $_db->sql(\'select pk_action,concat(i.name,\">\",a.call) as calling from \'.T_REACTOR_INTERFACE_ACTION.\' a,\'.T_REACTOR_INTERFACE.\' i where a.fk_interface=i.pk_interface and public>0 order by pk_interface,a.call\');\n\n$data=$query->matr(\'pk_action\',\'calling\');\n$data[0]=\'----------\';'),(27,6,'select','',18,'handler','Обработчик','',0,1,'','$data=array(\n0=>\'в шаблон\',\n1=>\'location\',\n2=>\'store location\',\n);'),(28,6,'code_line','',4,'method','Метод user class','',0,0,'',''),(29,6,'code_line','',2,'name','Имя экшена','',1,1,'',''),(30,6,'code_line','',8,'param','Параметры','',0,0,'',''),(31,6,'select','',10,'public','В контрольной панели','',0,1,'','$data=array(\n0=>\'скрыт\',\n1=>\'выпадающее меню\',\n2=>\'правое меню\',\n3=>\'левое меню\',\n);'),(32,6,'code_line','',20,'tpl_param','Параметры обработчика','',0,0,'',''),(33,7,'select','',12,'base_type','Базовый тип поля','',0,1,'$data=\'string\';','global $_db;\n$query = $_db->sql(\'select * from \'.T_REACTOR_BASE_TYPE.\' order by `call`\');\n$data=$query->matr(\'name\',\'call\');'),(34,7,'code','',16,'base_type_param','Параметры базового типа','',0,0,'',''),(35,7,'code_line','',4,'call','Русское название','',1,1,'',''),(36,7,'code','',10,'default','По умолчанию равно','',0,0,'',''),(37,7,'code_line','',18,'description','Описание','',0,0,'',''),(38,7,'code','',14,'enum','Множество значений','',0,0,'',''),(39,7,'flag','',6,'inlist','В списке','',0,0,'$data=0;',''),(40,7,'code_line','',2,'name','Имя свойства','',1,1,'',''),(41,7,'flag','',8,'necessary','Обязательное','',0,0,'$data=0;',''),(42,8,'code','',6,'depend','Зависимости','',0,0,'include_once LIB_DIR.\'reactor_ver.php\';\n$data=reactor_ver();',''),(43,8,'code','',4,'descrip','Описание','',0,0,'',''),(44,8,'code','',10,'install','Инсталяция','',0,0,'',''),(45,8,'code_line','',2,'name','Имя модуля','test',1,1,'',''),(46,8,'code','',8,'to_core','В ядро','',0,0,'',''),(47,8,'code','',12,'uninstall','Деинсталяция','',0,0,'',''),(48,9,'code_line','',4,'db_name','Реальное имя таблицы','',1,1,'',''),(49,9,'flag','',8,'mlng','Мультиязычная таблица','в мульти таблице реальное имя таблицы не содержит языкового постфикса',1,0,'$data=0;',''),(50,9,'code_line','',2,'name','Имя','На основе имени генерируется константа T_имямодуля_имятаблицы',1,1,'',''),(51,10,'flag','',10,'active','Активирован','',1,0,'$data=0;',''),(52,10,'code_line','',8,'email','Эл. почта','',1,0,'',''),(53,10,'select','',12,'fk_ugroup','Группа','',1,1,'','global $_db;\n$query = $_db->sql(\'select * from \'.T_REACTOR_UGROUP);\n$data=$query->matr(\'pk_ugroup\',\'name\');'),(54,10,'code_line','',2,'login','Логин','',1,1,'',''),(55,10,'string','',6,'name','Имя','',1,1,'',''),(56,10,'code_line','',4,'pass','Пароль','',0,1,'',''),(57,11,'text','',4,'descrip','Описание','',0,0,'',''),(58,11,'code_line','',2,'name','Имя группы','',1,1,'',''),(59,12,'int','',2,'rights','Права','',0,0,'',''),(60,13,'code_line','',14,'action','Action','',0,0,'',''),(61,13,'code_line','',6,'call','Имя','',1,0,'',''),(62,13,'code','',20,'description','Description','',0,0,'',''),(63,13,'select','',10,'handle','Обработчик','',0,0,'','$data=array(\n0=>\'В шаблон index.tpl\',\n1=>\'Самостоятельно\',\n);'),(64,13,'select','',12,'interface','Reactor interface','',0,0,'','global $_db;\n$query = $_db->sql(\'select * from \'.T_REACTOR_INTERFACE.\' order by name\');\n$data=$query->matr(\'name\',\'name\');\n$data[\'\']=\'----empty----\';'),(65,13,'select','',16,'module','Module for template','',0,0,'','global $_db;\n$query = $_db->sql(\'select * from \'.T_REACTOR_MODULE.\' order by name\');\n$data=$query->matr(\'name\',\'name\');\n$data[\'\']=\'----empty----\';'),(66,13,'code_line','',2,'name','Имя для URL','',1,1,'',''),(67,13,'code_line','',4,'param','Параметры','',1,0,'',''),(68,13,'flag','',8,'public','public','',0,0,'$data=0;',''),(69,13,'code_line','',18,'template','Template','',0,0,'',''),(70,14,'code_line','',0,'call','Имя','',1,0,'',''),(71,14,'code_line','',0,'name','Тоже имя','',1,0,'','');
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_module`
--

LOCK TABLES `reactor_module` WRITE;
/*!40000 ALTER TABLE `reactor_module` DISABLE KEYS */;
INSERT INTO `reactor_module` VALUES (1,'reactor','','','','','reactor modules'),(2,'constructor','','','','','constructor'),(3,'cp','','','','',''),(4,'site','','','','','');
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_resource`
--

LOCK TABLES `reactor_resource` WRITE;
/*!40000 ALTER TABLE `reactor_resource` DISABLE KEYS */;
INSERT INTO `reactor_resource` VALUES (1,'reactor_interfaces',3,0,'$query = $_db->sql(\'select pk_interface,name from \'.T_REACTOR_INTERFACE);\n$data=$query->matr(\'pk_interface\',\'name\');');
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
  `mlng` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pk_table`),
  UNIQUE KEY `idx_uname` (`name`,`fk_module`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactor_table`
--

LOCK TABLES `reactor_table` WRITE;
/*!40000 ALTER TABLE `reactor_table` DISABLE KEYS */;
INSERT INTO `reactor_table` VALUES (1,'base_type',1,'reactor_base_type',0),(2,'config',1,'reactor_config',0),(3,'help',1,'reactor_help',0),(4,'interface',1,'reactor_interface',0),(5,'interface_action',1,'reactor_interface_action',0),(6,'interface_define',1,'reactor_interface_define',0),(7,'module',1,'reactor_module',0),(8,'resource',1,'reactor_resource',0),(9,'table',1,'reactor_table',0),(10,'tree_sup',1,'reactor_tree_sup',0),(11,'ugroup',1,'reactor_ugroup',0),(12,'ugroup_action',1,'reactor_ugroup_action',0),(13,'user',1,'reactor_user',0),(14,'tree',4,'site_tree',0);
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
INSERT INTO `reactor_ugroup_action` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,140),(1,141),(2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),(2,14),(2,15),(2,16),(2,17),(2,18),(2,19),(2,20),(2,21),(2,22),(2,23),(2,24),(2,25),(2,26),(2,37),(2,38),(2,40),(2,41),(2,42),(2,43),(2,44),(2,45),(2,46),(2,47),(2,48),(2,136),(2,137),(2,138),(2,139),(2,140),(2,141),(2,142),(6,1),(6,2),(6,3),(6,4),(6,5),(6,6),(6,7),(6,8),(6,9),(6,10),(6,11),(6,12),(6,13),(6,14),(6,15),(6,16),(6,106),(6,107),(6,108),(6,136),(6,137),(6,138),(6,139),(6,140),(6,141),(7,1),(7,2),(7,3),(7,4),(7,5),(7,6),(7,8),(7,11),(7,12),(7,13),(7,14),(7,15),(7,136),(7,137),(7,138),(7,139),(7,140),(7,141),(8,1),(8,2),(8,3),(8,4),(8,5),(8,6),(8,7),(8,8),(8,9),(8,10),(8,11),(8,12),(8,13),(8,14),(8,15),(8,16),(8,136),(8,137),(8,138),(8,139),(9,1),(9,2),(9,3),(9,4),(9,5),(9,6),(9,8),(9,11),(9,12),(9,13),(9,14),(9,15),(9,136),(9,137),(9,138),(9,139),(9,140),(9,141),(10,1),(10,2),(10,3),(10,4),(10,5),(10,6),(10,136),(10,137),(10,138),(10,139),(10,140),(10,141),(11,1),(11,2),(11,3),(11,4),(11,5),(11,6),(11,11),(11,12),(11,13),(11,14),(11,15),(11,136),(11,137),(11,138),(11,139),(11,140),(11,141),(12,1),(12,2),(12,3),(12,4),(12,5),(12,6),(12,11),(12,12),(12,13),(12,14),(12,15),(12,136),(12,137),(12,138),(12,139),(12,140),(12,141),(13,1),(13,2),(13,3),(13,4),(13,5),(13,6),(13,7),(13,8),(13,9),(13,10),(13,11),(13,12),(13,13),(13,14),(13,15),(13,16),(13,106),(13,107),(13,108),(13,136),(13,137),(13,138),(13,139),(13,140),(13,141);
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
INSERT INTO `reactor_user` VALUES (1,1,'guest','guest','',1469539167,'f734188b2e3e7a98e02550c64f518c0c','Гостевой аккаунт',1,''),(2,2,'root','root','',1469538070,'8d32743b06febd1d132681b9e39d69d7','Администратор',1,'');
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_tree`
--

LOCK TABLES `site_tree` WRITE;
/*!40000 ALTER TABLE `site_tree` DISABLE KEYS */;
INSERT INTO `site_tree` VALUES (1,0,'none','',4,0,'TestController','testAction','index','site','','Homepage','','',1),(2,1,'none','',14,0,'','','404','site','404.tpl','404','404','',1),(3,1,'none','interface;action',2,0,'cp','show','cp','cp','index.tpl','','','',1),(4,3,'none','',0,0,'','','html_preview','cp','html_preview.tpl','','','',1),(5,1,'none','',4,0,'','','login','cp','login.tpl','','','',1),(6,1,'none','interface;action',6,0,'content_adapter','handleForm','handleForm','','','','','',1),(7,1,'none','',8,0,'content_adapter','ajax_request','ajax_request','reactor','null.tpl','','','',1);
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

-- Dump completed on 2016-07-26 16:20:39

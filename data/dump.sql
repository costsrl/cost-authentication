use zf2portal;


DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `code` int(11) NOT NULL,
  `default_language` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


LOCK TABLES `language` WRITE;
INSERT INTO `language`
VALUES
(1,'Italian',0,1),
(2,'English',0,0);
UNLOCK TABLES;



DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY (`id`),
KEY `IDX_B63E2EC7727ACA70` (`parent_id`),
CONSTRAINT `FK_B63E2EC7727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


LOCK TABLES `roles` WRITE;
INSERT INTO `roles`
VALUES (1,NULL,'Guest'),
(2,1,'Member'),
(3,2,'Admin')
UNLOCK TABLES;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `state` int(11) NOT NULL,
  `question` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `answer` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_salt` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_date` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `registration_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_confirmed` tinyint(1) NOT NULL,
PRIMARY KEY (`user_id`),
KEY `IDX_8D93D64982F1BAF4` (`language_id`),
KEY `role_id` (`role_id`),
CONSTRAINT `FK_8D93D64982F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
CONSTRAINT `FK_8D93D649D60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


LOCK TABLES `user` WRITE;
ALTER TABLE `user` DISABLE KEYS
INSERT INTO `user` VALUES
(0,3,1,'admin','admin display_name','admin_nome','admin_cognome','ab10717c59bd84dbded1c2a46a959514','renato.s@cost.it',1,NULL,NULL,NULL,'p)*3\'-)UpSWvyudV\'~U<IG4,95H{+/iz8s/>!7sqiLidBO;INo','2016-04-26 14:47:33','2018-03-18 13:48:12','cc65db3d6513a720b33d41a3dcd557ef',0)
ALTER TABLE `user` ENABLE KEYS
UNLOCK TABLES;


DROP TABLE IF EXISTS `resources`;
CREATE TABLE `resources` (
  `id` int(11) NOT NULL  AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `resources` WRITE;
ALTER TABLE `resources` DISABLE KEYS
INSERT INTO `resources`
VALUES (1,'Application\\Controller\\Index','controller'),
(2,'CostAuthentication\\Controller\\Index','controller'),
(3,'CostAuthentication\\Controller\\Registration','controller'),
(4,'CostAuthorization\\Controller\\Index','controller'),
(5,'CostAdmin\\Controller\\Index','controller')
(6,'Grid_USERS','grid')
ALTER TABLE `resources` ENABLE KEYS;
UNLOCK TABLES;



DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL  AUTO_INCREMENT,
  `resource_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privilege` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permission_allow` int(11) NOT NULL,
  `assert_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `IDX_87209A8789329D25` (`resource_id`),
KEY `IDX_87209A87D60322AC` (`role_id`),
CONSTRAINT `FK_2DEDCC6F89329D25` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
CONSTRAINT `FK_2DEDCC6FD60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
ALTER TABLE `permissions` DISABLE KEYS;
INSERT INTO `permissions`
VALUES (1,1,1,'Application_index','*',1,''),
(2,2,1,'CostAuthentication_Login','login',1,''),
(3,2,2,'CostAuthentication_LogOut','logout',1,''),
(4,3,1,'CostAuthentication_Register','*',1,''),
(5,3,2,'CostAuthentication_Register_Edit','*',1,''),
(6,5,3,'Cost_admin_dashboard','*',1,''),
(7,2,2,'CostAuthentication_Login_Denie','login',2,''),
(8,6,3,'Grid_USERS_permission','view',1,''),
(9,6,3,'Grid_USERS','delete,insert,edit',2,'')
ALTER TABLE `permissions` ENABLE KEYS;
UNLOCK TABLES;


DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `privilege` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `resource` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `params` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `query` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `IDX_727508CF87209A87` (`privilege`),
CONSTRAINT `FK_727508CF87209A87` FOREIGN KEY (`privilege`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
ALTER TABLE `menus` DISABLE KEYS;
INSERT INTO `menus` VALUES
(6,1,0,'Skeleton','Home','noleggi/default','index','dispatchdashboard','Application\\Controller\\Index','','','Dashboard','glyphicon glyphicon-home','',0),
(7,5,0,'UserProfile','Profilo Utente','cost-auth/default','Index','index','CostAuthentication\\Controller\\Registration','','','Application','glyphicon glyphicon-user bold white','',10),
(8,6,0,'Admin','Admin','admin-application','Index','index','CostAdmin\\Controller\\Index','','','Laminas\\Router','glyphicon  glyphicon-wrench','',11),(9,6,8,'Admin_anag_resource','Resource','admin-application/default','Index','resource','CostAdmin\\Controller\\Index',NULL,NULL,'Cost_admin','',NULL,4),
(10,6,8,'Admin_anag_role','Role','admin-application/default','index','role','CostAdmin\\Controller\\Index',NULL,NULL,'Cost_admin','',NULL,5),
(11,6,8,'Admin_anag_permission','Permission','admin-application/default','Index','permission','CostAdmin\\Controller\\Index',NULL,NULL,'Cost_admin','',NULL,6),
(12,6,8,'Admin_anag_user','User','admin-application/default','index','user','CostAdmin\\Controller\\Index',NULL,NULL,'Cost_admin','',NULL,7),
(13,6,8,'Admin_anag_menu','Menu','admin-application/default','index','menu','CostAdmin\\Controller\\Index',NULL,NULL,'Cost_admin','',NULL,8),
(14,6,8,'Admin_anag_language','Language','admin-application/default','Index','language','CostAdmin\\Controller\\Index',NULL,NULL,'Cost_admin','',NULL,9),
(15,3,0,'LogOut','Logout','cost-auth/default','index','logout','CostAuthentication\\Controller\\Index',NULL,NULL,'Cost_authentication','',NULL,12),(16,2,0,'Login','Login','cost-auth/default','index','user','CostAuthentication\\Controller\\Index',NULL,NULL,'Cost_authentication','',NULL,0);
ALTER TABLE `menus` ENABLE KEYS;
UNLOCK TABLES;











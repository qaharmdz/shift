/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE IF NOT EXISTS `shift` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `shift`;

DROP TABLE IF EXISTS `{DB_PREFIX}event`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}event` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codename` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `info` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `emitter` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `listener` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `priority` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}event` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}event` (`event_id`, `codename`, `info`, `emitter`, `listener`, `priority`, `status`) VALUES
	(1, 'test', '', 'admin/page/dashboard::before', 'extension/module/method', 0, 0),
	(2, 'test', '', 'admin/page/dashboard::after', 'extension/module/myMethod', 2, 0);
/*!40000 ALTER TABLE `{DB_PREFIX}event` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension` (
  `extension_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `codename` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `version` varchar(16) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `author` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `install` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`extension_id`),
  UNIQUE KEY `codename_type` (`codename`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension` (`extension_id`, `codename`, `type`, `name`, `version`, `author`, `link`, `install`) VALUES
	(6, 'banner', 'module', '', '', '', '', 0),
	(7, 'carousel', 'module', '', '', '', '', 0),
	(14, 'account', 'module', '', '', '', '', 0),
	(19, 'slideshow', 'module', '', '', '', '', 0),
	(25, 'online', 'dashboard', '', '', '', '', 0),
	(27, 'html', 'module', '', '', '', '', 0),
	(32, 'base', 'theme', '', '', '', '', 1),
	(33, 'architect', 'plugin', 'Architect', '1.0.0', 'Shift', 'https://example.com', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}extension` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension_data`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension_data` (
  `extension_data_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'plugin_id, module_id, theme_id',
  `extension_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_data_id`) USING BTREE,
  KEY `ext_id` (`extension_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension_data` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension_data` (`extension_data_id`, `extension_id`, `name`, `setting`, `status`, `created`, `updated`) VALUES
	(1, 33, 'Architect', '[]', 0, NULL, NULL),
	(2, 6, 'Home Page', '[]', 1, NULL, NULL),
	(3, 7, 'Home Page', '[]', 0, NULL, NULL),
	(4, 19, 'Home Page', '[]', 0, NULL, NULL),
	(5, 7, 'Banner', '[]', 0, NULL, NULL);
/*!40000 ALTER TABLE `{DB_PREFIX}extension_data` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}information`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}information` (
  `information_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bottom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`information_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}information` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}information` (`information_id`, `bottom`, `sort_order`, `status`) VALUES
	(3, 1, 3, 1),
	(4, 1, 1, 1),
	(5, 1, 4, 1),
	(6, 1, 2, 1),
	(7, 1, 10, 1),
	(9, 1, 11, 1);
/*!40000 ALTER TABLE `{DB_PREFIX}information` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}information_description`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}information_description` (
  `information_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `language_id` bigint(20) unsigned NOT NULL DEFAULT '1',
  `title` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`information_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}information_description` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(3, 1, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
	(4, 1, 'About Us', '&lt;p&gt;\r\n	About Us 2&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/logo.png&quot;&gt;&lt;/p&gt;&lt;p&gt;Test&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 130px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/demo/manufacturer/disney.png&quot;&gt;&lt;/p&gt;&lt;p&gt;test&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 799.359px; height: 266.453px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/demo/banners/MacBookAir.jpg&quot;&gt;&lt;br&gt;&lt;/p&gt;', 'About Us', '', ''),
	(5, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
	(6, 1, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;', 'Delivery Information', '', ''),
	(9, 1, 'New Information', '&lt;p&gt;Info Desc&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/logo.png&quot;&gt;&lt;br&gt;&lt;/p&gt;', 'New Info', '', '');
/*!40000 ALTER TABLE `{DB_PREFIX}information_description` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}information_to_layout`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}information_to_layout` (
  `information_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`information_id`,`site_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}information_to_layout` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}information_to_layout` (`information_id`, `site_id`, `layout_id`) VALUES
	(4, 0, 0),
	(4, 1, 0),
	(6, 0, 0),
	(6, 1, 0),
	(9, 0, 0),
	(9, 1, 0);
/*!40000 ALTER TABLE `{DB_PREFIX}information_to_layout` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}information_to_site`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}information_to_site` (
  `information_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`information_id`,`site_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}information_to_site` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}information_to_site` (`information_id`, `site_id`) VALUES
	(3, 0),
	(4, 0),
	(5, 0),
	(6, 0),
	(9, 0);
/*!40000 ALTER TABLE `{DB_PREFIX}information_to_site` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}language`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}language` (
  `language_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `locale` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `flag` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}language` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}language` (`language_id`, `name`, `code`, `locale`, `flag`, `sort_order`, `status`) VALUES
	(1, 'English', 'en', 'en-US,en_US.UTF-8,en_US,en-gb,english', 'uk.png', 1, 1),
	(2, 'Indonesia', 'id', 'ID, en-ID,en_ID.UTF-8,indonesia', 'id.png', 1, 1);
/*!40000 ALTER TABLE `{DB_PREFIX}language` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout` (
  `layout_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `structure` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `style` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout` (`layout_id`, `name`, `type`, `structure`, `style`, `status`) VALUES
	(1, 'Default', 'all', '', '', 1),
	(2, 'Home', 'route', '', '', 1),
	(6, 'Account', 'route', '', '', 1),
	(8, 'Contact', 'route', '', '', 1),
	(9, 'Sitemap', 'route', '', '', 1),
	(11, 'Information', 'route', '', '', 1),
	(12, 'Info About', 'specific', '', '', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}layout` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout_module`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout_module` (
  `layout_module_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `module_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `position` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout_module` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout_module` (`layout_module_id`, `layout_id`, `module_id`, `position`, `sort_order`) VALUES
	(20, 5, 0, 'column_left', 2),
	(66, 2, 0, 'content_top', 1),
	(67, 2, 0, 'content_top', 3),
	(73, 3, 0, 'column_left', 2),
	(80, 6, 0, 'column_right', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}layout_module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout_route`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout_route` (
  `layout_route_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `route` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`layout_route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout_route` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout_route` (`layout_route_id`, `layout_id`, `site_id`, `route`) VALUES
	(24, 11, 0, 'information/information'),
	(31, 8, 0, 'information/contact'),
	(32, 9, 0, 'information/sitemap'),
	(42, 2, 0, 'common/home'),
	(54, 4, 0, ''),
	(56, 2, 1, 'common/home'),
	(57, 11, 1, 'information/information'),
	(58, 8, 1, 'information/contact'),
	(59, 9, 1, 'information/sitemap'),
	(60, 4, 1, ''),
	(73, 6, 0, 'account/%'),
	(74, 6, 1, 'account/%');
/*!40000 ALTER TABLE `{DB_PREFIX}layout_route` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}setting`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}setting` (
  `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) NOT NULL DEFAULT '0',
  `group` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `key` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`),
  KEY `group` (`site_id`,`group`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}setting` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}setting` (`setting_id`, `site_id`, `group`, `code`, `key`, `value`, `encoded`) VALUES
	(1296, 0, 'system', 'alias_distinct', 'information/information', 'information_id', 0),
	(1297, 0, 'system', 'alias_distinct', 'content/post', 'post_id', 0),
	(1298, 0, 'system', 'alias_multi', 'content/category', 'category_id', 0),
	(2707, 1, 'system', 'site', 'name', 'Site Name 1', 0),
	(2708, 1, 'system', 'site', 'url_host', 'https://example.com/', 0),
	(2709, 1, 'system', 'site', 'email', 'admin@example.com', 0),
	(2710, 1, 'system', 'site', 'meta_title', '{"1":"","2":""}', 1),
	(2711, 1, 'system', 'site', 'meta_description', '{"1":"","2":""}', 1),
	(2712, 1, 'system', 'site', 'meta_keyword', '{"1":"","2":""}', 1),
	(2713, 1, 'system', 'site', 'logo', 'image/logo.png', 0),
	(2714, 1, 'system', 'site', 'favicon', 'image/favicon.png', 0),
	(2715, 1, 'system', 'site', 'language', 'en', 0),
	(2716, 1, 'system', 'site', 'layout_id', '1', 0),
	(2717, 1, 'system', 'site', 'theme', 'base', 0),
	(2718, 1, 'system', 'site', 'maintenance', '1', 0),
	(2719, 1, 'system', 'site', 'timezone', 'Asia/Jakarta', 0),
	(2764, 0, 'system', 'setting', 'compression', '0', 0),
	(2765, 0, 'system', 'setting', 'admin_language', 'en', 0),
	(2766, 0, 'system', 'setting', 'admin_limit', '25', 0),
	(2767, 0, 'system', 'setting', 'mail_engine', 'mail', 0),
	(2768, 0, 'system', 'setting', 'smtp_host', '', 0),
	(2769, 0, 'system', 'setting', 'smtp_username', '', 0),
	(2770, 0, 'system', 'setting', 'smtp_password', '', 0),
	(2771, 0, 'system', 'setting', 'smtp_port', '25', 0),
	(2772, 0, 'system', 'setting', 'smtp_timeout', '300', 0),
	(2773, 0, 'system', 'setting', 'error_display', '1', 0),
	(2774, 0, 'system', 'setting', 'development', '1', 0),
	(2775, 0, 'system', 'setting', 'mail_smtp_hostname', 'hostname.example', 0),
	(2776, 0, 'system', 'setting', 'mail_smtp_username', 'john', 0),
	(2777, 0, 'system', 'setting', 'mail_smtp_password', 'password', 0),
	(2778, 0, 'system', 'setting', 'mail_smtp_port', '25', 0),
	(2779, 0, 'system', 'setting', 'mail_smtp_timeout', '300', 0),
	(2780, 0, 'system', 'setting', 'timezone', 'Asia/Jakarta', 0),
	(2794, 0, 'system', 'site', 'name', 'Shift Site', 0),
	(2795, 0, 'system', 'site', 'url_host', 'https://localhost/mdzGit/shift/public/', 0),
	(2796, 0, 'system', 'site', 'email', 'admin@example.com', 0),
	(2797, 0, 'system', 'site', 'meta_title', '{"1":"Cool Shift Site","2":"Cool Shift Site"}', 1),
	(2798, 0, 'system', 'site', 'meta_description', '{"1":"","2":""}', 1),
	(2799, 0, 'system', 'site', 'meta_keyword', '{"1":"","2":""}', 1),
	(2800, 0, 'system', 'site', 'logo', 'image/logo.png', 0),
	(2801, 0, 'system', 'site', 'favicon', 'image/favicon.png', 0),
	(2802, 0, 'system', 'site', 'language', 'en', 0),
	(2803, 0, 'system', 'site', 'layout_id', '1', 0),
	(2804, 0, 'system', 'site', 'theme', 'base', 0),
	(2805, 0, 'system', 'site', 'maintenance', '0', 0),
	(2806, 0, 'system', 'site', 'timezone', 'Asia/Jakarta', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}setting` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}site`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}site` (
  `site_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `url_host` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`site_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}site` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}site` (`site_id`, `name`, `url_host`) VALUES
	(0, 'Shift Site', 'https://localhost/mdzGit/shift/public/'),
	(1, 'Site Name 1', 'https://example.com/');
/*!40000 ALTER TABLE `{DB_PREFIX}site` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}url_alias`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}url_alias` (
  `url_alias_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `language_id` bigint(20) unsigned NOT NULL DEFAULT '1',
  `route` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `param` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `alias` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`url_alias_id`) USING BTREE,
  UNIQUE KEY `alias` (`site_id`,`alias`) USING BTREE,
  KEY `route_param_value` (`route`,`param`,`value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}url_alias` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}url_alias` (`url_alias_id`, `site_id`, `language_id`, `route`, `param`, `value`, `alias`) VALUES
	(1, 0, 1, 'information/information', 'information_id', '3', 'privacy'),
	(3, 0, 1, 'information/information', 'information_id', '5', 'terms'),
	(5, 0, 1, 'information/information', 'information_id', '6', 'delivery'),
	(6, 0, 1, 'information/contact', '', '', 'contact-us'),
	(7, 0, 1, 'common/home', '', '', '/'),
	(14, 0, 1, 'information/information', 'information_id', '4', 'about-us'),
	(15, 0, 2, 'information/information', 'information_id', '3', 'privacy2'),
	(16, 1, 2, 'information/information', 'information_id', '3', 'privacy2'),
	(17, 1, 1, 'information/information', 'information_id', '3', 'privacy');
/*!40000 ALTER TABLE `{DB_PREFIX}url_alias` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}user`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `email` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `username` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `firstname` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `lastname` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}user` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}user` (`user_id`, `user_group_id`, `email`, `password`, `username`, `firstname`, `lastname`, `status`, `last_login`, `created`, `updated`) VALUES
	(1, 1, 'admin@example.com', '$2y$10$UNMe2ToTfZEP3KpyLfUj/O2/1PLxquHygdUCJgG9cg3bfD53WgTJy', 'admin', 'John', 'Doe', 1, '2022-12-18 05:23:13', '2022-01-30 16:17:31', '2022-03-20 12:17:31'),
	(3, 2, 'user@example.com1', '$2y$10$NeYYCLxL.tttyffQzKmliOazCa9vCnJx5EkSerZwvEXtCaCrtqRaC', 'username', 'User1', 'Doe1', 0, '2022-11-15 11:57:27', '2022-01-30 16:17:31', '2022-12-10 16:41:26'),
	(4, 2, 'jane@example.com', '$2y$10$NeYYCLxL.tttyffQzKmliOazCa9vCnJx5EkSerZwvEXtCaCrtqRaC', 'janedoe', 'Jane', 'Doe', 0, '2022-10-07 20:57:27', '2022-01-30 16:17:31', '2022-12-10 20:14:48');
/*!40000 ALTER TABLE `{DB_PREFIX}user` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}user_group`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}user_group` (
  `user_group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `backend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `permission` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}user_group` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}user_group` (`user_group_id`, `name`, `backend`, `permission`, `status`, `created`, `updated`) VALUES
	(1, 'Super Admin', 1, '{"access":["account\\/user","account\\/usergroup","catalog\\/information","common\\/filemanager","design\\/banner","design\\/layout","extension\\/dashboard\\/online","extension\\/event","extension\\/extension","extension\\/extension\\/dashboard","extension\\/extension\\/module","extension\\/extension\\/theme","extension\\/installer","extension\\/language","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/theme\\/base","setting\\/setting","setting\\/site","tool\\/backup","tool\\/log"],"modify":["account\\/user","account\\/usergroup","catalog\\/information","common\\/filemanager","design\\/banner","design\\/layout","extension\\/dashboard\\/online","extension\\/event","extension\\/extension","extension\\/extension\\/dashboard","extension\\/extension\\/module","extension\\/extension\\/theme","extension\\/installer","extension\\/language","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/theme\\/base","setting\\/setting","setting\\/site","tool\\/backup","tool\\/log"]}', 1, '2022-10-29 14:37:53', '2022-11-25 16:21:12'),
	(2, 'Register', 0, '{"access":["catalog\\/information"],"modify":["catalog\\/information"]}', 0, '2022-10-21 14:37:53', '2022-12-10 20:14:42');
/*!40000 ALTER TABLE `{DB_PREFIX}user_group` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}user_meta`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}user_meta` (
  `user_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_meta_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}user_meta` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}user_meta` (`user_meta_id`, `user_id`, `key`, `value`, `encoded`) VALUES
	(1, 1, 'twitter', '@example', 0),
	(3, 3, 'bio', 'Awesome1', 0),
	(9, 4, 'bio', '', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}user_meta` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

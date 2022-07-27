/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE IF NOT EXISTS `shift` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `shift`;

DROP TABLE IF EXISTS `{DB_PREFIX}banner`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}banner` (
  `banner_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}banner` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}banner` (`banner_id`, `name`, `status`) VALUES
	(6, 'HP Products', 1),
	(7, 'Home Page Slideshow', 1),
	(8, 'Manufacturers', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}banner` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}banner_image`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}banner_image` (
  `banner_image_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `language_id` bigint(20) unsigned NOT NULL DEFAULT '1',
  `title` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banner_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}banner_image` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}banner_image` (`banner_image_id`, `banner_id`, `language_id`, `title`, `link`, `image`, `sort_order`) VALUES
	(88, 8, 1, 'Harley Davidson', '', 'catalog/demo/manufacturer/harley.png', 0),
	(89, 8, 1, 'Dell', '', 'catalog/demo/manufacturer/dell.png', 0),
	(90, 8, 1, 'Disney', '', 'catalog/demo/manufacturer/disney.png', 0),
	(91, 8, 1, 'Coca Cola', '', 'catalog/demo/manufacturer/cocacola.png', 0),
	(92, 8, 1, 'Burger King', '', 'catalog/demo/manufacturer/burgerking.png', 0),
	(93, 8, 1, 'Canon', '', 'catalog/demo/manufacturer/canon.png', 0),
	(94, 8, 1, 'NFL', '', 'catalog/demo/manufacturer/nfl.png', 0),
	(95, 8, 1, 'RedBull', '', 'catalog/demo/manufacturer/redbull.png', 0),
	(96, 8, 1, 'Sony', '', 'catalog/demo/manufacturer/sony.png', 0),
	(97, 8, 1, 'Starbucks', '', 'catalog/demo/manufacturer/starbucks.png', 0),
	(98, 8, 1, 'Nintendo', '', 'catalog/demo/manufacturer/nintendo.png', 0),
	(101, 6, 1, 'HP Banner', 'index.php?route=product/manufacturer/info&amp;manufacturer_id=7', '', 0),
	(102, 7, 1, 'iPhone 6', 'index.php?route=product/product&amp;path=57&amp;product_id=49', 'catalog/demo/banners/iPhone6.jpg', 0),
	(103, 7, 1, 'MacBookAir', '', 'catalog/demo/banners/MacBookAir.jpg', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}banner_image` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}event`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}event` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `trigger` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `action` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}event` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}event` (`event_id`, `code`, `trigger`, `action`, `status`, `created`, `updated`) VALUES
	(1, 'test', 'admin/test', 'admin/test/check', 0, '2022-05-20 23:53:20', '2022-05-21 21:53:25');
/*!40000 ALTER TABLE `{DB_PREFIX}event` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension` (
  `extension_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension` (`extension_id`, `type`, `code`) VALUES
	(6, 'module', 'banner'),
	(7, 'module', 'carousel'),
	(14, 'module', 'account'),
	(19, 'module', 'slideshow'),
	(25, 'dashboard', 'online'),
	(27, 'module', 'html'),
	(32, 'theme', 'base');
/*!40000 ALTER TABLE `{DB_PREFIX}extension` ENABLE KEYS */;

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
	(4, 1, 'About Us', '&lt;p&gt;\r\n	About Us 2&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/image/catalog/logo.png&quot;&gt;&lt;/p&gt;&lt;p&gt;Test&lt;/p&gt;', 'About Us', '', ''),
	(5, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
	(6, 1, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;', 'Delivery Information', '', ''),
	(9, 1, 'New Information', '&lt;p&gt;Info Desc&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/image/catalog/logo.png&quot;&gt;&lt;br&gt;&lt;/p&gt;', 'New Info', '', '');
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
  `image` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `directory` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}language` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `sort_order`, `status`) VALUES
	(1, 'English', 'en-gb', 'en-US,en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 1, 1);
/*!40000 ALTER TABLE `{DB_PREFIX}language` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout` (
  `layout_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout` (`layout_id`, `type`, `name`, `status`) VALUES
	(1, '', 'Home', 0),
	(4, '', 'Default', 0),
	(6, '', 'Account', 0),
	(8, '', 'Contact', 0),
	(9, '', 'Sitemap', 0),
	(11, '', 'Information', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}layout` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout_module`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout_module` (
  `layout_module_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `module_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `code` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `position` varchar(14) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout_module` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout_module` (`layout_module_id`, `layout_id`, `module_id`, `code`, `position`, `sort_order`) VALUES
	(20, 5, 0, '0', 'column_left', 2),
	(66, 1, 0, 'slideshow.27', 'content_top', 1),
	(67, 1, 0, 'carousel.29', 'content_top', 3),
	(73, 3, 0, 'banner.30', 'column_left', 2),
	(80, 6, 0, 'banner.31', 'column_right', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}layout_module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout_route`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout_route` (
  `layout_route_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `route` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `priority` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout_route` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout_route` (`layout_route_id`, `layout_id`, `site_id`, `route`, `priority`) VALUES
	(24, 11, 0, 'information/information', 0),
	(31, 8, 0, 'information/contact', 0),
	(32, 9, 0, 'information/sitemap', 0),
	(42, 1, 0, 'common/home', 0),
	(54, 4, 0, '', 0),
	(56, 1, 1, 'common/home', 0),
	(57, 11, 1, 'information/information', 0),
	(58, 8, 1, 'information/contact', 0),
	(59, 9, 1, 'information/sitemap', 0),
	(60, 4, 1, '', 0),
	(73, 6, 0, 'account/%', 0),
	(74, 6, 1, 'account/%', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}layout_route` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}module`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}module` (
  `module_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ext_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `publish` datetime DEFAULT NULL,
  `unpublish` datetime DEFAULT NULL,
  PRIMARY KEY (`module_id`),
  KEY `ext_id` (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}module` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}module` (`module_id`, `ext_id`, `name`, `code`, `setting`, `status`, `created`, `updated`, `publish`, `unpublish`) VALUES
	(27, 0, 'Home Page', 'slideshow', '{"name":"Home Page","banner_id":"7","width":"1140","height":"380","status":"1"}', 0, NULL, NULL, NULL, NULL),
	(28, 0, 'Home Page', 'featured', '{"name":"Home Page","product":["43","40","42","30"],"limit":"4","width":"200","height":"200","status":"1"}', 0, NULL, NULL, NULL, NULL),
	(29, 0, 'Home Page', 'carousel', '{"name":"Home Page","banner_id":"8","width":"130","height":"100","status":"1"}', 0, NULL, NULL, NULL, NULL),
	(30, 0, 'Category', 'banner', '{"name":"Category","banner_id":"6","width":"182","height":"182","status":"1"}', 0, NULL, NULL, NULL, NULL),
	(31, 0, 'Banner 1', 'banner', '{"name":"Banner 1","banner_id":"6","width":"182","height":"182","status":"1"}', 0, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `{DB_PREFIX}module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}setting`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}setting` (
  `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) NOT NULL DEFAULT '0',
  `group` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `key` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`),
  KEY `group` (`site_id`,`group`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}setting` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}setting` (`setting_id`, `site_id`, `group`, `code`, `key`, `value`, `encoded`) VALUES
	(415, 0, 'dashboard', 'dashboard_online', 'dashboard_online_width', '12', 0),
	(416, 0, 'dashboard', 'dashboard_online', 'dashboard_online_status', '1', 0),
	(417, 0, 'dashboard', 'dashboard_online', 'dashboard_online_sort_order', '2', 0),
	(950, 0, 'module', 'account', 'account_status', '0', 0),
	(1272, 1, 'theme', 'base', 'status', '1', 0),
	(1296, 0, 'system', 'alias_distinct', 'information/information', 'information_id', 0),
	(1297, 0, 'system', 'alias_distinct', 'content/post', 'post_id', 0),
	(1298, 0, 'system', 'alias_multi', 'content/category', 'category_id', 0),
	(1855, 0, 'system', 'setting', 'compression', '0', 0),
	(1856, 0, 'system', 'setting', 'admin_language', 'en-gb', 0),
	(1857, 0, 'system', 'setting', 'admin_limit', '25', 0),
	(1858, 0, 'system', 'setting', 'mail_protocol', 'mail', 0),
	(1859, 0, 'system', 'setting', 'mail_parameter', '', 0),
	(1860, 0, 'system', 'setting', 'mail_smtp_hostname', '', 0),
	(1861, 0, 'system', 'setting', 'mail_smtp_username', '', 0),
	(1862, 0, 'system', 'setting', 'mail_smtp_password', '', 0),
	(1863, 0, 'system', 'setting', 'mail_smtp_port', '25', 0),
	(1864, 0, 'system', 'setting', 'mail_smtp_timeout', '5', 0),
	(1865, 0, 'system', 'setting', 'error_display', '1', 0),
	(1866, 0, 'system', 'setting', 'development', '1', 0),
	(1879, 0, 'theme', 'base', 'status', '1', 0),
	(1880, 0, 'system', 'site', 'name', 'Your Site', 0),
	(1881, 0, 'system', 'site', 'url_host', 'https://localhost/mdzGit/shift/public/', 0),
	(1882, 0, 'system', 'site', 'email', 'admin@example.com', 0),
	(1883, 0, 'system', 'site', 'meta_title', 'Your Site', 0),
	(1884, 0, 'system', 'site', 'meta_description', 'Meta Tag Description', 0),
	(1885, 0, 'system', 'site', 'meta_keyword', 'Meta Tag Keywords', 0),
	(1886, 0, 'system', 'site', 'logo', 'catalog/logo.png', 0),
	(1887, 0, 'system', 'site', 'icon', 'catalog/favicon.png', 0),
	(1888, 0, 'system', 'site', 'language', 'en-gb', 0),
	(1889, 0, 'system', 'site', 'layout_id', '4', 0),
	(1890, 0, 'system', 'site', 'maintenance', '0', 0),
	(1891, 0, 'system', 'site', 'theme', 'base', 0),
	(1904, 1, 'system', 'site', 'name', 'Site Name 1', 0),
	(1905, 1, 'system', 'site', 'url_host', 'https://example.com/', 0),
	(1906, 1, 'system', 'site', 'email', 'admin@example.com', 0),
	(1907, 1, 'system', 'site', 'meta_title', 'Meta Site Name 1', 0),
	(1908, 1, 'system', 'site', 'meta_description', 'Meta Tag Description', 0),
	(1909, 1, 'system', 'site', 'meta_keyword', 'Meta Tag Keywords', 0),
	(1910, 1, 'system', 'site', 'logo', 'https://localhost/mdzGit/shift/public/image/cache/no-image-100x100.png', 0),
	(1911, 1, 'system', 'site', 'icon', 'https://localhost/mdzGit/shift/public/image/cache/no-image-100x100.png', 0),
	(1912, 1, 'system', 'site', 'language', 'en-gb', 0),
	(1913, 1, 'system', 'site', 'layout_id', '4', 0),
	(1914, 1, 'system', 'site', 'maintenance', '1', 0),
	(1915, 1, 'system', 'site', 'theme', 'base', 0);
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
	(0, 'Your Site', 'https://localhost/mdzGit/shift/public/'),
	(1, 'Site Name 1', 'https://example.com/');
/*!40000 ALTER TABLE `{DB_PREFIX}site` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}upload`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}upload` (
  `upload_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `filename` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `date_added` datetime DEFAULT NULL,
  PRIMARY KEY (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `{DB_PREFIX}upload` ENABLE KEYS */;

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
  UNIQUE KEY `alias` (`site_id`,`language_id`,`alias`) USING BTREE,
  KEY `route_param_value` (`route`,`param`,`value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}url_alias` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}url_alias` (`url_alias_id`, `site_id`, `language_id`, `route`, `param`, `value`, `alias`) VALUES
	(1, 0, 1, 'information/information', 'information_id', '3', 'privacy'),
	(3, 0, 1, 'information/information', 'information_id', '5', 'terms'),
	(5, 0, 1, 'information/information', 'information_id', '6', 'delivery'),
	(6, 0, 1, 'information/contact', '', '', 'contact-us'),
	(7, 0, 1, 'common/home', '', '', '/'),
	(12, 0, 1, 'information/information', 'information_id', '4', 'about-us');
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
  `code` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}user` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}user` (`user_id`, `user_group_id`, `email`, `password`, `username`, `firstname`, `lastname`, `code`, `status`, `created`, `updated`, `last_login`) VALUES
	(1, 1, 'admin@example.com', '$2y$10$hNWx9oo3LCUiVe058qT1/ORSbFdbym9h53vh3B.M2.heEpkfkCQFS', 'admin', 'John', 'Doe', '', 1, '2022-01-30 16:17:31', NULL, '2022-07-27 06:26:24');
/*!40000 ALTER TABLE `{DB_PREFIX}user` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}user_group`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}user_group` (
  `user_group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `super_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `backend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `permission` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}user_group` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}user_group` (`user_group_id`, `name`, `super_admin`, `backend`, `permission`, `status`, `created`, `updated`) VALUES
	(1, 'Administrator', 1, 1, '{"access":["catalog\\/information","common\\/columnleft","common\\/filemanager","design\\/banner","design\\/layout","error\\/notfound","extension\\/dashboard\\/online","extension\\/event","extension\\/extension","extension\\/extension\\/dashboard","extension\\/extension\\/module","extension\\/extension\\/theme","extension\\/installer","extension\\/language","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/theme\\/themedefault","setting\\/setting","setting\\/site","startup\\/event","startup\\/kernel","startup\\/login","startup\\/permission","startup\\/startup","tool\\/backup","tool\\/log","tool\\/upload","user\\/user","user\\/userpermission","extension\\/theme\\/default","extension\\/theme\\/base","extension\\/theme\\/base","extension\\/theme\\/base"],"modify":["catalog\\/information","common\\/columnleft","common\\/filemanager","design\\/banner","design\\/layout","error\\/notfound","extension\\/dashboard\\/online","extension\\/event","extension\\/extension","extension\\/extension\\/dashboard","extension\\/extension\\/module","extension\\/extension\\/theme","extension\\/installer","extension\\/language","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/theme\\/themedefault","setting\\/setting","setting\\/site","startup\\/event","startup\\/kernel","startup\\/login","startup\\/permission","startup\\/startup","tool\\/backup","tool\\/log","tool\\/upload","user\\/user","user\\/userpermission","extension\\/theme\\/default","extension\\/theme\\/base","extension\\/theme\\/base","extension\\/theme\\/base"]}', 1, NULL, NULL),
	(10, 'Demonstration', 0, 0, '{"access":["catalog\\/information"],"modify":["catalog\\/information"]}', 0, NULL, NULL);
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
	(1, 1, 'twitter', '@example', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}user_meta` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

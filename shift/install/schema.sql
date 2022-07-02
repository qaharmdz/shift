/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE IF NOT EXISTS `shift` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `shift`;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}banner`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}banner` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}banner` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}banner` (`banner_id`, `name`, `status`) VALUES
	(6, 'HP Products', 1),
	(7, 'Home Page Slideshow', 1),
	(8, 'Manufacturers', 1);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}banner` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}banner_image`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}banner_image` (
  `banner_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banner_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}banner_image` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}banner_image` (`banner_image_id`, `banner_id`, `language_id`, `title`, `link`, `image`, `sort_order`) VALUES
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
	(99, 7, 1, 'iPhone 6', 'index.php?route=product/product&amp;path=57&amp;product_id=49', 'catalog/demo/banners/iPhone6.jpg', 0),
	(100, 7, 1, 'MacBookAir', '', 'catalog/demo/banners/MacBookAir.jpg', 0),
	(101, 6, 1, 'HP Banner', 'index.php?route=product/manufacturer/info&amp;manufacturer_id=7', '', 0);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}banner_image` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}event`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `trigger` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `action` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}event` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}event` (`event_id`, `code`, `trigger`, `action`, `status`, `created`, `updated`) VALUES
	(1, 'test', 'admin/test', 'admin/test/check', 0, '2022-05-20 23:53:20', '2022-05-21 21:53:25');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}event` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}extension`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}extension` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}extension` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}extension` (`extension_id`, `type`, `code`) VALUES
	(6, 'module', 'banner'),
	(7, 'module', 'carousel'),
	(14, 'module', 'account'),
	(19, 'module', 'slideshow'),
	(25, 'dashboard', 'online'),
	(27, 'module', 'html'),
	(28, 'theme', 'themedefault');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}extension` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}information`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}information` (
  `information_id` int(11) NOT NULL AUTO_INCREMENT,
  `bottom` int(1) NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`information_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}information` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}information` (`information_id`, `bottom`, `sort_order`, `status`) VALUES
	(3, 1, 3, 1),
	(4, 1, 1, 1),
	(5, 1, 4, 1),
	(6, 1, 2, 1),
	(7, 1, 10, 1),
	(9, 1, 11, 1);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}information` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}information_description`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}information_description` (
  `information_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`information_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}information_description` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(3, 1, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
	(4, 1, 'About Us', '&lt;p&gt;\r\n	About Us 2&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/image/catalog/logo.png&quot;&gt;&lt;/p&gt;&lt;p&gt;Test&lt;/p&gt;', 'About Us', '', ''),
	(5, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
	(6, 1, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;', 'Delivery Information', '', ''),
	(9, 1, 'New Information', '&lt;p&gt;Info Desc&lt;/p&gt;', 'New Info', '', '');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}information_description` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}information_to_layout`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}information_to_layout` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`information_id`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}information_to_layout` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}information_to_layout` (`information_id`, `store_id`, `layout_id`) VALUES
	(4, 0, 0),
	(4, 1, 0),
	(6, 0, 0),
	(6, 1, 0),
	(9, 0, 0),
	(9, 1, 0);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}information_to_layout` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}information_to_store`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}information_to_store` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`information_id`,`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}information_to_store` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}information_to_store` (`information_id`, `store_id`) VALUES
	(3, 0),
	(4, 0),
	(5, 0),
	(6, 0),
	(9, 0);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}information_to_store` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}language`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `locale` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `directory` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}language` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `sort_order`, `status`) VALUES
	(1, 'English', 'en-gb', 'en-US,en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 1, 1);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}language` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}layout`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}layout` (
  `layout_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}layout` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}layout` (`layout_id`, `name`) VALUES
	(1, 'Home'),
	(4, 'Default'),
	(6, 'Account'),
	(8, 'Contact'),
	(9, 'Sitemap'),
	(11, 'Information');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}layout` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}layout_module`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}layout_module` (
  `layout_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `code` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `position` varchar(14) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`layout_module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}layout_module` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES
	(20, 5, '0', 'column_left', 2),
	(66, 1, 'slideshow.27', 'content_top', 1),
	(67, 1, 'carousel.29', 'content_top', 3),
	(73, 3, 'banner.30', 'column_left', 2),
	(78, 6, 'banner.31', 'column_right', 1);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}layout_module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}layout_route`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}layout_route` (
  `layout_route_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `route` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`layout_route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}layout_route` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}layout_route` (`layout_route_id`, `layout_id`, `store_id`, `route`) VALUES
	(24, 11, 0, 'information/information'),
	(31, 8, 0, 'information/contact'),
	(32, 9, 0, 'information/sitemap'),
	(42, 1, 0, 'common/home'),
	(54, 4, 0, ''),
	(56, 1, 1, 'common/home'),
	(57, 11, 1, 'information/information'),
	(58, 8, 1, 'information/contact'),
	(59, 9, 1, 'information/sitemap'),
	(60, 4, 1, ''),
	(69, 6, 0, 'account/%'),
	(70, 6, 1, 'account/%');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}layout_route` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}module`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `setting` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}module` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}module` (`module_id`, `name`, `code`, `setting`) VALUES
	(27, 'Home Page', 'slideshow', '{"name":"Home Page","banner_id":"7","width":"1140","height":"380","status":"1"}'),
	(28, 'Home Page', 'featured', '{"name":"Home Page","product":["43","40","42","30"],"limit":"4","width":"200","height":"200","status":"1"}'),
	(29, 'Home Page', 'carousel', '{"name":"Home Page","banner_id":"8","width":"130","height":"100","status":"1"}'),
	(30, 'Category', 'banner', '{"name":"Category","banner_id":"6","width":"182","height":"182","status":"1"}'),
	(31, 'Banner 1', 'banner', '{"name":"Banner 1","banner_id":"6","width":"182","height":"182","status":"1"}');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}session`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}session` (
  `session_id` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `hash` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `session_data` blob NOT NULL,
  `session_expire` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}session` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}session` (`session_id`, `hash`, `session_data`, `session_expire`) VALUES
	('4o6b8rk8hr83ij1g6s01d76clb', '66137625e09ccc9c428b52558361b8ea', _binary '', 1645786641),
	('lrj21uc3gd6lkhaour1ej5raak', '66137625e09ccc9c428b52558361b8ea', _binary '', 1645785595);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}session` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}setting`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}setting` (
  `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) NOT NULL DEFAULT '0',
  `group` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `key` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}setting` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}setting` (`setting_id`, `store_id`, `group`, `code`, `key`, `value`, `encoded`) VALUES
	(415, 0, '', 'dashboard_online', 'dashboard_online_width', '12', 0),
	(416, 0, '', 'dashboard_online', 'dashboard_online_status', '1', 0),
	(417, 0, '', 'dashboard_online', 'dashboard_online_sort_order', '2', 0),
	(950, 0, '', 'account', 'account_status', '0', 0),
	(1021, 1, '', 'config', 'config_url', 'http://example.com', 0),
	(1022, 1, '', 'config', 'config_ssl', '', 0),
	(1023, 1, '', 'config', 'config_meta_title', 'meta', 0),
	(1024, 1, '', 'config', 'config_meta_description', '', 0),
	(1025, 1, '', 'config', 'config_meta_keyword', '', 0),
	(1026, 1, '', 'config', 'config_theme', 'theme_default', 0),
	(1027, 1, '', 'config', 'config_layout_id', '6', 0),
	(1028, 1, '', 'config', 'config_name', 'store name', 0),
	(1029, 1, '', 'config', 'config_owner', 'store owner', 0),
	(1030, 1, '', 'config', 'config_address', 'Alamat', 0),
	(1031, 1, '', 'config', 'config_email', 'admin@example.com', 0),
	(1032, 1, '', 'config', 'config_telephone', '124124', 0),
	(1033, 1, '', 'config', 'config_fax', '', 0),
	(1034, 1, '', 'config', 'config_image', '', 0),
	(1035, 1, '', 'config', 'config_language', 'en-gb', 0),
	(1036, 1, '', 'config', 'config_logo', '', 0),
	(1037, 1, '', 'config', 'config_icon', '', 0),
	(1186, 0, '', 'config', 'config_meta_title', 'Your Store', 0),
	(1187, 0, '', 'config', 'config_meta_description', 'My Store', 0),
	(1188, 0, '', 'config', 'config_meta_keyword', '', 0),
	(1189, 0, '', 'config', 'config_theme', 'theme_default', 0),
	(1190, 0, '', 'config', 'config_layout_id', '4', 0),
	(1191, 0, '', 'config', 'config_name', 'Your Store', 0),
	(1192, 0, '', 'config', 'config_owner', 'Your Name', 0),
	(1193, 0, '', 'config', 'config_address', 'Address 1', 0),
	(1194, 0, '', 'config', 'config_email', 'admin@example.com', 0),
	(1195, 0, '', 'config', 'config_telephone', '123456789', 0),
	(1196, 0, '', 'config', 'config_fax', '', 0),
	(1197, 0, '', 'config', 'config_image', '', 0),
	(1198, 0, '', 'config', 'config_language', 'en-gb', 0),
	(1199, 0, '', 'config', 'config_admin_language', 'en-gb', 0),
	(1200, 0, '', 'config', 'config_limit_admin', '25', 0),
	(1201, 0, '', 'config', 'config_logo', 'catalog/logo.png', 0),
	(1202, 0, '', 'config', 'config_icon', 'catalog/favicon.png', 0),
	(1203, 0, '', 'config', 'config_mail_protocol', 'mail', 0),
	(1204, 0, '', 'config', 'config_mail_parameter', '', 0),
	(1205, 0, '', 'config', 'config_mail_smtp_hostname', '', 0),
	(1206, 0, '', 'config', 'config_mail_smtp_username', '', 0),
	(1207, 0, '', 'config', 'config_mail_smtp_password', '', 0),
	(1208, 0, '', 'config', 'config_mail_smtp_port', '25', 0),
	(1209, 0, '', 'config', 'config_mail_smtp_timeout', '5', 0),
	(1210, 0, '', 'config', 'config_mail_alert_email', '', 0),
	(1211, 0, '', 'config', 'config_maintenance', '0', 0),
	(1212, 0, '', 'config', 'config_seo_url', '1', 0),
	(1213, 0, '', 'config', 'config_robots', 'abot\r\ndbot\r\nebot\r\nhbot\r\nkbot\r\nlbot\r\nmbot\r\nnbot\r\nobot\r\npbot\r\nrbot\r\nsbot\r\ntbot\r\nvbot\r\nybot\r\nzbot\r\nbot.\r\nbot/\r\n_bot\r\n.bot\r\n/bot\r\n-bot\r\n:bot\r\n(bot\r\ncrawl\r\nslurp\r\nspider\r\nseek\r\naccoona\r\nacoon\r\nadressendeutschland\r\nah-ha.com\r\nahoy\r\naltavista\r\nananzi\r\nanthill\r\nappie\r\narachnophilia\r\narale\r\naraneo\r\naranha\r\narchitext\r\naretha\r\narks\r\nasterias\r\natlocal\r\natn\r\natomz\r\naugurfind\r\nbackrub\r\nbannana_bot\r\nbaypup\r\nbdfetch\r\nbig brother\r\nbiglotron\r\nbjaaland\r\nblackwidow\r\nblaiz\r\nblog\r\nblo.\r\nbloodhound\r\nboitho\r\nbooch\r\nbradley\r\nbutterfly\r\ncalif\r\ncassandra\r\nccubee\r\ncfetch\r\ncharlotte\r\nchurl\r\ncienciaficcion\r\ncmc\r\ncollective\r\ncomagent\r\ncombine\r\ncomputingsite\r\ncsci\r\ncurl\r\ncusco\r\ndaumoa\r\ndeepindex\r\ndelorie\r\ndepspid\r\ndeweb\r\ndie blinde kuh\r\ndigger\r\nditto\r\ndmoz\r\ndocomo\r\ndownload express\r\ndtaagent\r\ndwcp\r\nebiness\r\nebingbong\r\ne-collector\r\nejupiter\r\nemacs-w3 search engine\r\nesther\r\nevliya celebi\r\nezresult\r\nfalcon\r\nfelix ide\r\nferret\r\nfetchrover\r\nfido\r\nfindlinks\r\nfireball\r\nfish search\r\nfouineur\r\nfunnelweb\r\ngazz\r\ngcreep\r\ngenieknows\r\ngetterroboplus\r\ngeturl\r\nglx\r\ngoforit\r\ngolem\r\ngrabber\r\ngrapnel\r\ngralon\r\ngriffon\r\ngromit\r\ngrub\r\ngulliver\r\nhamahakki\r\nharvest\r\nhavindex\r\nhelix\r\nheritrix\r\nhku www octopus\r\nhomerweb\r\nhtdig\r\nhtml index\r\nhtml_analyzer\r\nhtmlgobble\r\nhubater\r\nhyper-decontextualizer\r\nia_archiver\r\nibm_planetwide\r\nichiro\r\niconsurf\r\niltrovatore\r\nimage.kapsi.net\r\nimagelock\r\nincywincy\r\nindexer\r\ninfobee\r\ninformant\r\ningrid\r\ninktomisearch.com\r\ninspector web\r\nintelliagent\r\ninternet shinchakubin\r\nip3000\r\niron33\r\nisraeli-search\r\nivia\r\njack\r\njakarta\r\njavabee\r\njetbot\r\njumpstation\r\nkatipo\r\nkdd-explorer\r\nkilroy\r\nknowledge\r\nkototoi\r\nkretrieve\r\nlabelgrabber\r\nlachesis\r\nlarbin\r\nlegs\r\nlibwww\r\nlinkalarm\r\nlink validator\r\nlinkscan\r\nlockon\r\nlwp\r\nlycos\r\nmagpie\r\nmantraagent\r\nmapoftheinternet\r\nmarvin/\r\nmattie\r\nmediafox\r\nmediapartners\r\nmercator\r\nmerzscope\r\nmicrosoft url control\r\nminirank\r\nmiva\r\nmj12\r\nmnogosearch\r\nmoget\r\nmonster\r\nmoose\r\nmotor\r\nmultitext\r\nmuncher\r\nmuscatferret\r\nmwd.search\r\nmyweb\r\nnajdi\r\nnameprotect\r\nnationaldirectory\r\nnazilla\r\nncsa beta\r\nnec-meshexplorer\r\nnederland.zoek\r\nnetcarta webmap engine\r\nnetmechanic\r\nnetresearchserver\r\nnetscoop\r\nnewscan-online\r\nnhse\r\nnokia6682/\r\nnomad\r\nnoyona\r\nnutch\r\nnzexplorer\r\nobjectssearch\r\noccam\r\nomni\r\nopen text\r\nopenfind\r\nopenintelligencedata\r\norb search\r\nosis-project\r\npack rat\r\npageboy\r\npagebull\r\npage_verifier\r\npanscient\r\nparasite\r\npartnersite\r\npatric\r\npear.\r\npegasus\r\nperegrinator\r\npgp key agent\r\nphantom\r\nphpdig\r\npicosearch\r\npiltdownman\r\npimptrain\r\npinpoint\r\npioneer\r\npiranha\r\nplumtreewebaccessor\r\npogodak\r\npoirot\r\npompos\r\npoppelsdorf\r\npoppi\r\npopular iconoclast\r\npsycheclone\r\npublisher\r\npython\r\nrambler\r\nraven search\r\nroach\r\nroad runner\r\nroadhouse\r\nrobbie\r\nrobofox\r\nrobozilla\r\nrules\r\nsalty\r\nsbider\r\nscooter\r\nscoutjet\r\nscrubby\r\nsearch.\r\nsearchprocess\r\nsemanticdiscovery\r\nsenrigan\r\nsg-scout\r\nshai&#039;hulud\r\nshark\r\nshopwiki\r\nsidewinder\r\nsift\r\nsilk\r\nsimmany\r\nsite searcher\r\nsite valet\r\nsitetech-rover\r\nskymob.com\r\nsleek\r\nsmartwit\r\nsna-\r\nsnappy\r\nsnooper\r\nsohu\r\nspeedfind\r\nsphere\r\nsphider\r\nspinner\r\nspyder\r\nsteeler/\r\nsuke\r\nsuntek\r\nsupersnooper\r\nsurfnomore\r\nsven\r\nsygol\r\nszukacz\r\ntach black widow\r\ntarantula\r\ntempleton\r\n/teoma\r\nt-h-u-n-d-e-r-s-t-o-n-e\r\ntheophrastus\r\ntitan\r\ntitin\r\ntkwww\r\ntoutatis\r\nt-rex\r\ntutorgig\r\ntwiceler\r\ntwisted\r\nucsd\r\nudmsearch\r\nurl check\r\nupdated\r\nvagabondo\r\nvalkyrie\r\nverticrawl\r\nvictoria\r\nvision-search\r\nvolcano\r\nvoyager/\r\nvoyager-hc\r\nw3c_validator\r\nw3m2\r\nw3mir\r\nwalker\r\nwallpaper\r\nwanderer\r\nwauuu\r\nwavefire\r\nweb core\r\nweb hopper\r\nweb wombat\r\nwebbandit\r\nwebcatcher\r\nwebcopy\r\nwebfoot\r\nweblayers\r\nweblinker\r\nweblog monitor\r\nwebmirror\r\nwebmonkey\r\nwebquest\r\nwebreaper\r\nwebsitepulse\r\nwebsnarf\r\nwebstolperer\r\nwebvac\r\nwebwalk\r\nwebwatch\r\nwebwombat\r\nwebzinger\r\nwhizbang\r\nwhowhere\r\nwild ferret\r\nworldlight\r\nwwwc\r\nwwwster\r\nxenu\r\nxget\r\nxift\r\nxirq\r\nyandex\r\nyanga\r\nyeti\r\nyodao\r\nzao\r\nzippp\r\nzyborg', 0),
	(1214, 0, '', 'config', 'config_compression', '0', 0),
	(1215, 0, '', 'config', 'config_secure', '0', 0),
	(1216, 0, '', 'config', 'config_password', '1', 0),
	(1217, 0, '', 'config', 'config_shared', '0', 0),
	(1218, 0, '', 'config', 'config_file_max_size', '300000', 0),
	(1219, 0, '', 'config', 'config_file_ext_allowed', 'zip\r\ntxt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc', 0),
	(1220, 0, '', 'config', 'config_file_mime_allowed', 'text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/tiff\r\nimage/svg+xml\r\napplication/zip\r\n&quot;application/zip&quot;\r\napplication/x-zip\r\n&quot;application/x-zip&quot;\r\napplication/x-zip-compressed\r\n&quot;application/x-zip-compressed&quot;\r\napplication/rar\r\n&quot;application/rar&quot;\r\napplication/x-rar\r\n&quot;application/x-rar&quot;\r\napplication/x-rar-compressed\r\n&quot;application/x-rar-compressed&quot;\r\napplication/octet-stream\r\n&quot;application/octet-stream&quot;\r\naudio/mpeg\r\nvideo/quicktime\r\napplication/pdf', 0),
	(1221, 0, '', 'config', 'config_error_display', '1', 0),
	(1222, 0, '', 'config', 'config_error_log', '1', 0),
	(1223, 0, '', 'config', 'config_error_filename', 'error.log', 0),
	(1248, 0, '', 'theme_default', 'theme_default_directory', 'default', 0),
	(1249, 0, '', 'theme_default', 'theme_default_status', '1', 0),
	(1250, 0, '', 'theme_default', 'theme_default_product_limit', '15', 0),
	(1251, 0, '', 'theme_default', 'theme_default_product_description_length', '100', 0),
	(1252, 0, '', 'theme_default', 'theme_default_image_category_width', '80', 0),
	(1253, 0, '', 'theme_default', 'theme_default_image_category_height', '80', 0),
	(1254, 0, '', 'theme_default', 'theme_default_image_thumb_width', '228', 0),
	(1255, 0, '', 'theme_default', 'theme_default_image_thumb_height', '228', 0),
	(1256, 0, '', 'theme_default', 'theme_default_image_popup_width', '500', 0),
	(1257, 0, '', 'theme_default', 'theme_default_image_popup_height', '500', 0),
	(1258, 0, '', 'theme_default', 'theme_default_image_product_width', '228', 0),
	(1259, 0, '', 'theme_default', 'theme_default_image_product_height', '228', 0),
	(1260, 0, '', 'theme_default', 'theme_default_image_additional_width', '74', 0),
	(1261, 0, '', 'theme_default', 'theme_default_image_additional_height', '74', 0),
	(1262, 0, '', 'theme_default', 'theme_default_image_related_width', '80', 0),
	(1263, 0, '', 'theme_default', 'theme_default_image_related_height', '80', 0),
	(1264, 0, '', 'theme_default', 'theme_default_image_compare_width', '90', 0),
	(1265, 0, '', 'theme_default', 'theme_default_image_compare_height', '90', 0),
	(1266, 0, '', 'theme_default', 'theme_default_image_wishlist_width', '47', 0),
	(1267, 0, '', 'theme_default', 'theme_default_image_wishlist_height', '47', 0),
	(1268, 0, '', 'theme_default', 'theme_default_image_cart_width', '47', 0),
	(1269, 0, '', 'theme_default', 'theme_default_image_cart_height', '47', 0),
	(1270, 0, '', 'theme_default', 'theme_default_image_location_width', '268', 0),
	(1271, 0, '', 'theme_default', 'theme_default_image_location_height', '50', 0),
	(1272, 1, '', 'theme_default', 'theme_default_directory', 'default', 0),
	(1273, 1, '', 'theme_default', 'theme_default_status', '0', 0),
	(1274, 1, '', 'theme_default', 'theme_default_product_limit', '15', 0),
	(1275, 1, '', 'theme_default', 'theme_default_product_description_length', '100', 0),
	(1276, 1, '', 'theme_default', 'theme_default_image_category_width', '80', 0),
	(1277, 1, '', 'theme_default', 'theme_default_image_category_height', '80', 0),
	(1278, 1, '', 'theme_default', 'theme_default_image_thumb_width', '228', 0),
	(1279, 1, '', 'theme_default', 'theme_default_image_thumb_height', '228', 0),
	(1280, 1, '', 'theme_default', 'theme_default_image_popup_width', '500', 0),
	(1281, 1, '', 'theme_default', 'theme_default_image_popup_height', '500', 0),
	(1282, 1, '', 'theme_default', 'theme_default_image_product_width', '228', 0),
	(1283, 1, '', 'theme_default', 'theme_default_image_product_height', '228', 0),
	(1284, 1, '', 'theme_default', 'theme_default_image_additional_width', '74', 0),
	(1285, 1, '', 'theme_default', 'theme_default_image_additional_height', '74', 0),
	(1286, 1, '', 'theme_default', 'theme_default_image_related_width', '80', 0),
	(1287, 1, '', 'theme_default', 'theme_default_image_related_height', '80', 0),
	(1288, 1, '', 'theme_default', 'theme_default_image_compare_width', '90', 0),
	(1289, 1, '', 'theme_default', 'theme_default_image_compare_height', '90', 0),
	(1290, 1, '', 'theme_default', 'theme_default_image_wishlist_width', '47', 0),
	(1291, 1, '', 'theme_default', 'theme_default_image_wishlist_height', '47', 0),
	(1292, 1, '', 'theme_default', 'theme_default_image_cart_width', '47', 0),
	(1293, 1, '', 'theme_default', 'theme_default_image_cart_height', '47', 0),
	(1294, 1, '', 'theme_default', 'theme_default_image_location_width', '268', 0),
	(1295, 1, '', 'theme_default', 'theme_default_image_location_height', '50', 0),
	(1296, 0, 'system', 'alias_distinct', 'information/information', 'information_id', 0),
	(1297, 0, 'system', 'alias_distinct', 'content/post', 'post_id', 0),
	(1298, 0, 'system', 'alias_multi', 'content/category', 'category_id', 0);
/*!40000 ALTER TABLE `{{ DB_PREFIX }}setting` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}store`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}store` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ssl` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}store` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}store` (`store_id`, `name`, `url`, `ssl`) VALUES
	(1, 'store name', 'http://example.com', '');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}store` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}theme`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `theme` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `route` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `code` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}theme` DISABLE KEYS */;
/*!40000 ALTER TABLE `{{ DB_PREFIX }}theme` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}upload`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}upload` (
  `upload_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `{{ DB_PREFIX }}upload` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}url_alias`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}url_alias` (
  `url_alias_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `language_id` bigint(20) unsigned NOT NULL DEFAULT '1',
  `route` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `param` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `alias` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`url_alias_id`) USING BTREE,
  UNIQUE KEY `alias` (`language_id`,`alias`,`site_id`) USING BTREE,
  KEY `route_param_value` (`route`,`param`,`value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}url_alias` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}url_alias` (`url_alias_id`, `site_id`, `language_id`, `route`, `param`, `value`, `alias`) VALUES
	(1, 0, 1, 'information/information', 'information_id', '3', 'privacy'),
	(2, 0, 1, 'information/information', 'information_id', '4', 'about-us'),
	(3, 0, 1, 'information/information', 'information_id', '5', 'terms'),
	(5, 0, 1, 'information/information', 'information_id', '6', 'delivery'),
	(6, 0, 1, 'information/contact', '', '', 'contact-us'),
	(7, 0, 1, 'common/home', '', '', '/');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}url_alias` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}user`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `salt` varchar(9) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `firstname` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `lastname` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `email` varchar(96) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ip` varchar(40) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}user` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}user` (`user_id`, `user_group_id`, `username`, `password`, `salt`, `firstname`, `lastname`, `email`, `image`, `code`, `ip`, `status`, `date_added`) VALUES
	(1, 1, 'admin', '069920bcaa461dc9c84e0d93c217c51565dbe44f', 'PdYDbcyRo', 'John', 'Doe', 'admin@example.com', '', '', '::1', 1, '2022-01-30 16:17:31');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}user` ENABLE KEYS */;

DROP TABLE IF EXISTS `{{ DB_PREFIX }}user_group`;
CREATE TABLE IF NOT EXISTS `{{ DB_PREFIX }}user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `permission` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{{ DB_PREFIX }}user_group` DISABLE KEYS */;
INSERT INTO `{{ DB_PREFIX }}user_group` (`user_group_id`, `name`, `permission`) VALUES
	(1, 'Administrator', '{"access":["catalog\\/information","common\\/column_left","common\\/filemanager","design\\/banner","design\\/language","design\\/layout","extension\\/dashboard\\/map","extension\\/dashboard\\/online","extension\\/event","extension\\/extension","extension\\/extension\\/dashboard","extension\\/extension\\/module","extension\\/extension\\/theme","extension\\/installer","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/slideshow","extension\\/module\\/store","extension\\/theme\\/theme_default","localisation\\/language","setting\\/setting","setting\\/store","startup\\/compatibility","startup\\/event","startup\\/login","startup\\/permission","startup\\/router","startup\\/startup","tool\\/backup","tool\\/log","tool\\/upload","user\\/user","user\\/userpermission","extension\\/theme\\/themedefault"],"modify":["catalog\\/information","common\\/column_left","common\\/filemanager","design\\/banner","design\\/language","design\\/layout","extension\\/dashboard\\/map","extension\\/dashboard\\/online","extension\\/event","extension\\/extension","extension\\/extension\\/dashboard","extension\\/extension\\/module","extension\\/extension\\/theme","extension\\/installer","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/slideshow","extension\\/module\\/store","extension\\/theme\\/theme_default","localisation\\/language","setting\\/setting","setting\\/store","startup\\/compatibility","startup\\/event","startup\\/login","startup\\/permission","startup\\/router","startup\\/startup","tool\\/backup","tool\\/log","tool\\/upload","user\\/user","user\\/userpermission","extension\\/theme\\/themedefault"]}'),
	(10, 'Demonstration', '');
/*!40000 ALTER TABLE `{{ DB_PREFIX }}user_group` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

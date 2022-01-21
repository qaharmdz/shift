-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table shift.oc_banner
DROP TABLE IF EXISTS `oc_banner`;
CREATE TABLE IF NOT EXISTS `oc_banner` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_banner: 3 rows
/*!40000 ALTER TABLE `oc_banner` DISABLE KEYS */;
INSERT INTO `oc_banner` (`banner_id`, `name`, `status`) VALUES
	(6, 'HP Products', 1),
	(7, 'Home Page Slideshow', 1),
	(8, 'Manufacturers', 1);
/*!40000 ALTER TABLE `oc_banner` ENABLE KEYS */;

-- Dumping structure for table shift.oc_banner_image
DROP TABLE IF EXISTS `oc_banner_image`;
CREATE TABLE IF NOT EXISTS `oc_banner_image` (
  `banner_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `link` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`banner_image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_banner_image: 14 rows
/*!40000 ALTER TABLE `oc_banner_image` DISABLE KEYS */;
INSERT INTO `oc_banner_image` (`banner_image_id`, `banner_id`, `language_id`, `title`, `link`, `image`, `sort_order`) VALUES
	(79, 7, 1, 'iPhone 6', 'index.php?route=product/product&amp;path=57&amp;product_id=49', 'catalog/demo/banners/iPhone6.jpg', 0),
	(87, 6, 1, 'HP Banner', 'index.php?route=product/manufacturer/info&amp;manufacturer_id=7', 'catalog/demo/compaq_presario.jpg', 0),
	(94, 8, 1, 'NFL', '', 'catalog/demo/manufacturer/nfl.png', 0),
	(95, 8, 1, 'RedBull', '', 'catalog/demo/manufacturer/redbull.png', 0),
	(96, 8, 1, 'Sony', '', 'catalog/demo/manufacturer/sony.png', 0),
	(91, 8, 1, 'Coca Cola', '', 'catalog/demo/manufacturer/cocacola.png', 0),
	(92, 8, 1, 'Burger King', '', 'catalog/demo/manufacturer/burgerking.png', 0),
	(93, 8, 1, 'Canon', '', 'catalog/demo/manufacturer/canon.png', 0),
	(88, 8, 1, 'Harley Davidson', '', 'catalog/demo/manufacturer/harley.png', 0),
	(89, 8, 1, 'Dell', '', 'catalog/demo/manufacturer/dell.png', 0),
	(90, 8, 1, 'Disney', '', 'catalog/demo/manufacturer/disney.png', 0),
	(80, 7, 1, 'MacBookAir', '', 'catalog/demo/banners/MacBookAir.jpg', 0),
	(97, 8, 1, 'Starbucks', '', 'catalog/demo/manufacturer/starbucks.png', 0),
	(98, 8, 1, 'Nintendo', '', 'catalog/demo/manufacturer/nintendo.png', 0);
/*!40000 ALTER TABLE `oc_banner_image` ENABLE KEYS */;

-- Dumping structure for table shift.oc_event
DROP TABLE IF EXISTS `oc_event`;
CREATE TABLE IF NOT EXISTS `oc_event` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `trigger` text NOT NULL,
  `action` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_event: 0 rows
/*!40000 ALTER TABLE `oc_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_event` ENABLE KEYS */;

-- Dumping structure for table shift.oc_extension
DROP TABLE IF EXISTS `oc_extension`;
CREATE TABLE IF NOT EXISTS `oc_extension` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  PRIMARY KEY (`extension_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_extension: 9 rows
/*!40000 ALTER TABLE `oc_extension` DISABLE KEYS */;
INSERT INTO `oc_extension` (`extension_id`, `type`, `code`) VALUES
	(6, 'module', 'banner'),
	(7, 'module', 'carousel'),
	(13, 'module', 'category'),
	(14, 'module', 'account'),
	(18, 'module', 'featured'),
	(19, 'module', 'slideshow'),
	(20, 'theme', 'theme_default'),
	(25, 'dashboard', 'online'),
	(26, 'dashboard', 'map');
/*!40000 ALTER TABLE `oc_extension` ENABLE KEYS */;

-- Dumping structure for table shift.oc_information
DROP TABLE IF EXISTS `oc_information`;
CREATE TABLE IF NOT EXISTS `oc_information` (
  `information_id` int(11) NOT NULL AUTO_INCREMENT,
  `bottom` int(1) NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`information_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_information: 4 rows
/*!40000 ALTER TABLE `oc_information` DISABLE KEYS */;
INSERT INTO `oc_information` (`information_id`, `bottom`, `sort_order`, `status`) VALUES
	(3, 1, 3, 1),
	(4, 1, 1, 1),
	(5, 1, 4, 1),
	(6, 1, 2, 1);
/*!40000 ALTER TABLE `oc_information` ENABLE KEYS */;

-- Dumping structure for table shift.oc_information_description
DROP TABLE IF EXISTS `oc_information_description`;
CREATE TABLE IF NOT EXISTS `oc_information_description` (
  `information_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`information_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_information_description: 4 rows
/*!40000 ALTER TABLE `oc_information_description` DISABLE KEYS */;
INSERT INTO `oc_information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(4, 1, 'About Us', '&lt;p&gt;\r\n	About Us&lt;/p&gt;\r\n', 'About Us', '', ''),
	(5, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
	(3, 1, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
	(6, 1, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;\r\n', 'Delivery Information', '', '');
/*!40000 ALTER TABLE `oc_information_description` ENABLE KEYS */;

-- Dumping structure for table shift.oc_information_to_layout
DROP TABLE IF EXISTS `oc_information_to_layout`;
CREATE TABLE IF NOT EXISTS `oc_information_to_layout` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  PRIMARY KEY (`information_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_information_to_layout: 1 rows
/*!40000 ALTER TABLE `oc_information_to_layout` DISABLE KEYS */;
INSERT INTO `oc_information_to_layout` (`information_id`, `store_id`, `layout_id`) VALUES
	(4, 0, 0);
/*!40000 ALTER TABLE `oc_information_to_layout` ENABLE KEYS */;

-- Dumping structure for table shift.oc_information_to_store
DROP TABLE IF EXISTS `oc_information_to_store`;
CREATE TABLE IF NOT EXISTS `oc_information_to_store` (
  `information_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  PRIMARY KEY (`information_id`,`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_information_to_store: 4 rows
/*!40000 ALTER TABLE `oc_information_to_store` DISABLE KEYS */;
INSERT INTO `oc_information_to_store` (`information_id`, `store_id`) VALUES
	(3, 0),
	(4, 0),
	(5, 0),
	(6, 0);
/*!40000 ALTER TABLE `oc_information_to_store` ENABLE KEYS */;

-- Dumping structure for table shift.oc_language
DROP TABLE IF EXISTS `oc_language`;
CREATE TABLE IF NOT EXISTS `oc_language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `code` varchar(5) NOT NULL,
  `locale` varchar(255) NOT NULL,
  `image` varchar(64) NOT NULL,
  `directory` varchar(32) NOT NULL,
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_language: 1 rows
/*!40000 ALTER TABLE `oc_language` DISABLE KEYS */;
INSERT INTO `oc_language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `sort_order`, `status`) VALUES
	(1, 'English', 'en-gb', 'en-US,en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 1, 1);
/*!40000 ALTER TABLE `oc_language` ENABLE KEYS */;

-- Dumping structure for table shift.oc_layout
DROP TABLE IF EXISTS `oc_layout`;
CREATE TABLE IF NOT EXISTS `oc_layout` (
  `layout_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`layout_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_layout: 6 rows
/*!40000 ALTER TABLE `oc_layout` DISABLE KEYS */;
INSERT INTO `oc_layout` (`layout_id`, `name`) VALUES
	(1, 'Home'),
	(4, 'Default'),
	(6, 'Account'),
	(8, 'Contact'),
	(9, 'Sitemap'),
	(11, 'Information');
/*!40000 ALTER TABLE `oc_layout` ENABLE KEYS */;

-- Dumping structure for table shift.oc_layout_module
DROP TABLE IF EXISTS `oc_layout_module`;
CREATE TABLE IF NOT EXISTS `oc_layout_module` (
  `layout_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `code` varchar(64) NOT NULL,
  `position` varchar(14) NOT NULL,
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY (`layout_module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_layout_module: 10 rows
/*!40000 ALTER TABLE `oc_layout_module` DISABLE KEYS */;
INSERT INTO `oc_layout_module` (`layout_module_id`, `layout_id`, `code`, `position`, `sort_order`) VALUES
	(2, 4, '0', 'content_top', 0),
	(3, 4, '0', 'content_top', 1),
	(20, 5, '0', 'column_left', 2),
	(69, 10, 'affiliate', 'column_right', 1),
	(68, 6, 'account', 'column_right', 1),
	(67, 1, 'carousel.29', 'content_top', 3),
	(66, 1, 'slideshow.27', 'content_top', 1),
	(65, 1, 'featured.28', 'content_top', 2),
	(72, 3, 'category', 'column_left', 1),
	(73, 3, 'banner.30', 'column_left', 2);
/*!40000 ALTER TABLE `oc_layout_module` ENABLE KEYS */;

-- Dumping structure for table shift.oc_layout_route
DROP TABLE IF EXISTS `oc_layout_route`;
CREATE TABLE IF NOT EXISTS `oc_layout_route` (
  `layout_route_id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `route` varchar(64) NOT NULL,
  PRIMARY KEY (`layout_route_id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_layout_route: 6 rows
/*!40000 ALTER TABLE `oc_layout_route` DISABLE KEYS */;
INSERT INTO `oc_layout_route` (`layout_route_id`, `layout_id`, `store_id`, `route`) VALUES
	(38, 6, 0, 'account/%'),
	(42, 1, 0, 'common/home'),
	(24, 11, 0, 'information/information'),
	(31, 8, 0, 'information/contact'),
	(32, 9, 0, 'information/sitemap'),
	(34, 4, 0, '');
/*!40000 ALTER TABLE `oc_layout_route` ENABLE KEYS */;

-- Dumping structure for table shift.oc_modification
DROP TABLE IF EXISTS `oc_modification`;
CREATE TABLE IF NOT EXISTS `oc_modification` (
  `modification_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `code` varchar(64) NOT NULL,
  `author` varchar(64) NOT NULL,
  `version` varchar(32) NOT NULL,
  `link` varchar(255) NOT NULL,
  `xml` mediumtext NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`modification_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_modification: 0 rows
/*!40000 ALTER TABLE `oc_modification` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_modification` ENABLE KEYS */;

-- Dumping structure for table shift.oc_module
DROP TABLE IF EXISTS `oc_module`;
CREATE TABLE IF NOT EXISTS `oc_module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `code` varchar(32) NOT NULL,
  `setting` text NOT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_module: 5 rows
/*!40000 ALTER TABLE `oc_module` DISABLE KEYS */;
INSERT INTO `oc_module` (`module_id`, `name`, `code`, `setting`) VALUES
	(30, 'Category', 'banner', '{"name":"Category","banner_id":"6","width":"182","height":"182","status":"1"}'),
	(29, 'Home Page', 'carousel', '{"name":"Home Page","banner_id":"8","width":"130","height":"100","status":"1"}'),
	(28, 'Home Page', 'featured', '{"name":"Home Page","product":["43","40","42","30"],"limit":"4","width":"200","height":"200","status":"1"}'),
	(27, 'Home Page', 'slideshow', '{"name":"Home Page","banner_id":"7","width":"1140","height":"380","status":"1"}'),
	(31, 'Banner 1', 'banner', '{"name":"Banner 1","banner_id":"6","width":"182","height":"182","status":"1"}');
/*!40000 ALTER TABLE `oc_module` ENABLE KEYS */;

-- Dumping structure for table shift.oc_setting
DROP TABLE IF EXISTS `oc_setting`;
CREATE TABLE IF NOT EXISTS `oc_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(32) NOT NULL,
  `key` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `serialized` tinyint(1) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=365 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_setting: 172 rows
/*!40000 ALTER TABLE `oc_setting` DISABLE KEYS */;
INSERT INTO `oc_setting` (`setting_id`, `store_id`, `code`, `key`, `value`, `serialized`) VALUES
	(348, 0, 'config', 'config_compression', '0', 0),
	(349, 0, 'config', 'config_secure', '0', 0),
	(350, 0, 'config', 'config_password', '1', 0),
	(351, 0, 'config', 'config_shared', '0', 0),
	(352, 0, 'config', 'config_encryption', 'T6DbGz6MyL744OXIOBgRis5QXbkizJRBIVVNXE15Dw31ycTKIbDZv9QfNDps9aGEL6lmMJE1RuRzuAfiiZlnUHaqYBGHWlOXdvYUk980JPjwZ8keGA4bnF46x4FCRfKENRJQoWi5hcSt3aRizJ4MBrksAq7GUMkf5BBKJKQ7haULnh8CaZA2p9QTdBjEnu6wTFc91HWYiCW3mme02AhPrHZi07MKw4fixFHEd3QKqIk59ZTP9jeyhJsYdj4NLQWC8ryGoS1Ab6bwLSkWDaOy8ZpMHWPAkl8QlMFenvWYfz2GHpIMpCmPMsVIWB3Z48YqdnHWRIaq6ir1Ateg4NIhokCpxqukFeMCaGyCq92AmALTZ6E6kXJNlhPAJvyfglc8q4EmHCqsfVg0yhSyo8I6wVQKghsoBV2iK9KAEJpgf8knn2wxz98Hkdvki1Hx39Dg4nDn3lE7OZkXZ1611Qa4SsGlgko4kvhYgoUX2gwtY6cWDKjOd1RdhWCkxQ27bG8hH4oqNsPebuTCqJKZm70XkfdwytBfLn5ivU8V4CuXL4DOwGTgNPtDH2yESVvR57dS4CgHFAA3zr1RD3ikTaFo1gecgtGP14XCfqLScpCrUpcoVGJx99GV7Qclvb6YulMN4z9fEY1Y6u6Tv1n2GfeApxmDLuzl2xat6k8fWJNdKk0MeosaqIAqgb3QNSUDcbQBbJBwkKOmJ0OLfm68fy6MC8ftANPM69qkO445ubogdY9gGdprd3sh5vKDUR57YU9ZtRPCPEnI7ERBUZGGzKAnpmlKQcF24TbxCGSAHi3rmVT6erxMvO4h55v2Z6yMReLQjmA74asMu56EIEN19NMuuJkVPU3Jz44vHjzZMBHNH8h9oDUsl0gvDQpascecjmeTpTACcuz7lPzIf2QSSXxRmBATagaxCr2YaIgplpnzg73aDCk1thSdcKXEHdwZXbTwP7R5tl83ueAfRimlHlxrJ57fVQu2zr4YHgo6tfxtPCOitp2pvlwlHsFyjtdRnDQe', 0),
	(4, 0, 'voucher', 'voucher_sort_order', '8', 0),
	(5, 0, 'voucher', 'voucher_status', '1', 0),
	(353, 0, 'config', 'config_file_max_size', '300000', 0),
	(354, 0, 'config', 'config_file_ext_allowed', 'zip\r\ntxt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc', 0),
	(355, 0, 'config', 'config_file_mime_allowed', 'text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/tiff\r\nimage/svg+xml\r\napplication/zip\r\n&quot;application/zip&quot;\r\napplication/x-zip\r\n&quot;application/x-zip&quot;\r\napplication/x-zip-compressed\r\n&quot;application/x-zip-compressed&quot;\r\napplication/rar\r\n&quot;application/rar&quot;\r\napplication/x-rar\r\n&quot;application/x-rar&quot;\r\napplication/x-rar-compressed\r\n&quot;application/x-rar-compressed&quot;\r\napplication/octet-stream\r\n&quot;application/octet-stream&quot;\r\naudio/mpeg\r\nvideo/quicktime\r\napplication/pdf', 0),
	(356, 0, 'config', 'config_error_display', '1', 0),
	(346, 0, 'config', 'config_seo_url', '0', 0),
	(347, 0, 'config', 'config_robots', 'abot\r\ndbot\r\nebot\r\nhbot\r\nkbot\r\nlbot\r\nmbot\r\nnbot\r\nobot\r\npbot\r\nrbot\r\nsbot\r\ntbot\r\nvbot\r\nybot\r\nzbot\r\nbot.\r\nbot/\r\n_bot\r\n.bot\r\n/bot\r\n-bot\r\n:bot\r\n(bot\r\ncrawl\r\nslurp\r\nspider\r\nseek\r\naccoona\r\nacoon\r\nadressendeutschland\r\nah-ha.com\r\nahoy\r\naltavista\r\nananzi\r\nanthill\r\nappie\r\narachnophilia\r\narale\r\naraneo\r\naranha\r\narchitext\r\naretha\r\narks\r\nasterias\r\natlocal\r\natn\r\natomz\r\naugurfind\r\nbackrub\r\nbannana_bot\r\nbaypup\r\nbdfetch\r\nbig brother\r\nbiglotron\r\nbjaaland\r\nblackwidow\r\nblaiz\r\nblog\r\nblo.\r\nbloodhound\r\nboitho\r\nbooch\r\nbradley\r\nbutterfly\r\ncalif\r\ncassandra\r\nccubee\r\ncfetch\r\ncharlotte\r\nchurl\r\ncienciaficcion\r\ncmc\r\ncollective\r\ncomagent\r\ncombine\r\ncomputingsite\r\ncsci\r\ncurl\r\ncusco\r\ndaumoa\r\ndeepindex\r\ndelorie\r\ndepspid\r\ndeweb\r\ndie blinde kuh\r\ndigger\r\nditto\r\ndmoz\r\ndocomo\r\ndownload express\r\ndtaagent\r\ndwcp\r\nebiness\r\nebingbong\r\ne-collector\r\nejupiter\r\nemacs-w3 search engine\r\nesther\r\nevliya celebi\r\nezresult\r\nfalcon\r\nfelix ide\r\nferret\r\nfetchrover\r\nfido\r\nfindlinks\r\nfireball\r\nfish search\r\nfouineur\r\nfunnelweb\r\ngazz\r\ngcreep\r\ngenieknows\r\ngetterroboplus\r\ngeturl\r\nglx\r\ngoforit\r\ngolem\r\ngrabber\r\ngrapnel\r\ngralon\r\ngriffon\r\ngromit\r\ngrub\r\ngulliver\r\nhamahakki\r\nharvest\r\nhavindex\r\nhelix\r\nheritrix\r\nhku www octopus\r\nhomerweb\r\nhtdig\r\nhtml index\r\nhtml_analyzer\r\nhtmlgobble\r\nhubater\r\nhyper-decontextualizer\r\nia_archiver\r\nibm_planetwide\r\nichiro\r\niconsurf\r\niltrovatore\r\nimage.kapsi.net\r\nimagelock\r\nincywincy\r\nindexer\r\ninfobee\r\ninformant\r\ningrid\r\ninktomisearch.com\r\ninspector web\r\nintelliagent\r\ninternet shinchakubin\r\nip3000\r\niron33\r\nisraeli-search\r\nivia\r\njack\r\njakarta\r\njavabee\r\njetbot\r\njumpstation\r\nkatipo\r\nkdd-explorer\r\nkilroy\r\nknowledge\r\nkototoi\r\nkretrieve\r\nlabelgrabber\r\nlachesis\r\nlarbin\r\nlegs\r\nlibwww\r\nlinkalarm\r\nlink validator\r\nlinkscan\r\nlockon\r\nlwp\r\nlycos\r\nmagpie\r\nmantraagent\r\nmapoftheinternet\r\nmarvin/\r\nmattie\r\nmediafox\r\nmediapartners\r\nmercator\r\nmerzscope\r\nmicrosoft url control\r\nminirank\r\nmiva\r\nmj12\r\nmnogosearch\r\nmoget\r\nmonster\r\nmoose\r\nmotor\r\nmultitext\r\nmuncher\r\nmuscatferret\r\nmwd.search\r\nmyweb\r\nnajdi\r\nnameprotect\r\nnationaldirectory\r\nnazilla\r\nncsa beta\r\nnec-meshexplorer\r\nnederland.zoek\r\nnetcarta webmap engine\r\nnetmechanic\r\nnetresearchserver\r\nnetscoop\r\nnewscan-online\r\nnhse\r\nnokia6682/\r\nnomad\r\nnoyona\r\nnutch\r\nnzexplorer\r\nobjectssearch\r\noccam\r\nomni\r\nopen text\r\nopenfind\r\nopenintelligencedata\r\norb search\r\nosis-project\r\npack rat\r\npageboy\r\npagebull\r\npage_verifier\r\npanscient\r\nparasite\r\npartnersite\r\npatric\r\npear.\r\npegasus\r\nperegrinator\r\npgp key agent\r\nphantom\r\nphpdig\r\npicosearch\r\npiltdownman\r\npimptrain\r\npinpoint\r\npioneer\r\npiranha\r\nplumtreewebaccessor\r\npogodak\r\npoirot\r\npompos\r\npoppelsdorf\r\npoppi\r\npopular iconoclast\r\npsycheclone\r\npublisher\r\npython\r\nrambler\r\nraven search\r\nroach\r\nroad runner\r\nroadhouse\r\nrobbie\r\nrobofox\r\nrobozilla\r\nrules\r\nsalty\r\nsbider\r\nscooter\r\nscoutjet\r\nscrubby\r\nsearch.\r\nsearchprocess\r\nsemanticdiscovery\r\nsenrigan\r\nsg-scout\r\nshai\'hulud\r\nshark\r\nshopwiki\r\nsidewinder\r\nsift\r\nsilk\r\nsimmany\r\nsite searcher\r\nsite valet\r\nsitetech-rover\r\nskymob.com\r\nsleek\r\nsmartwit\r\nsna-\r\nsnappy\r\nsnooper\r\nsohu\r\nspeedfind\r\nsphere\r\nsphider\r\nspinner\r\nspyder\r\nsteeler/\r\nsuke\r\nsuntek\r\nsupersnooper\r\nsurfnomore\r\nsven\r\nsygol\r\nszukacz\r\ntach black widow\r\ntarantula\r\ntempleton\r\n/teoma\r\nt-h-u-n-d-e-r-s-t-o-n-e\r\ntheophrastus\r\ntitan\r\ntitin\r\ntkwww\r\ntoutatis\r\nt-rex\r\ntutorgig\r\ntwiceler\r\ntwisted\r\nucsd\r\nudmsearch\r\nurl check\r\nupdated\r\nvagabondo\r\nvalkyrie\r\nverticrawl\r\nvictoria\r\nvision-search\r\nvolcano\r\nvoyager/\r\nvoyager-hc\r\nw3c_validator\r\nw3m2\r\nw3mir\r\nwalker\r\nwallpaper\r\nwanderer\r\nwauuu\r\nwavefire\r\nweb core\r\nweb hopper\r\nweb wombat\r\nwebbandit\r\nwebcatcher\r\nwebcopy\r\nwebfoot\r\nweblayers\r\nweblinker\r\nweblog monitor\r\nwebmirror\r\nwebmonkey\r\nwebquest\r\nwebreaper\r\nwebsitepulse\r\nwebsnarf\r\nwebstolperer\r\nwebvac\r\nwebwalk\r\nwebwatch\r\nwebwombat\r\nwebzinger\r\nwhizbang\r\nwhowhere\r\nwild ferret\r\nworldlight\r\nwwwc\r\nwwwster\r\nxenu\r\nxget\r\nxift\r\nxirq\r\nyandex\r\nyanga\r\nyeti\r\nyodao\r\nzao\r\nzippp\r\nzyborg', 0),
	(345, 0, 'config', 'config_maintenance', '0', 0),
	(344, 0, 'config', 'config_mail_alert_email', '', 0),
	(343, 0, 'config', 'config_mail_alert', '["order"]', 1),
	(342, 0, 'config', 'config_mail_smtp_timeout', '5', 0),
	(341, 0, 'config', 'config_mail_smtp_port', '25', 0),
	(337, 0, 'config', 'config_mail_parameter', '', 0),
	(338, 0, 'config', 'config_mail_smtp_hostname', '', 0),
	(339, 0, 'config', 'config_mail_smtp_username', '', 0),
	(340, 0, 'config', 'config_mail_smtp_password', '', 0),
	(327, 0, 'config', 'config_captcha_page', '["review","return","contact"]', 1),
	(336, 0, 'config', 'config_mail_protocol', 'mail', 0),
	(335, 0, 'config', 'config_ftp_status', '0', 0),
	(334, 0, 'config', 'config_ftp_root', '', 0),
	(333, 0, 'config', 'config_ftp_password', '', 0),
	(332, 0, 'config', 'config_ftp_username', '', 0),
	(331, 0, 'config', 'config_ftp_port', '21', 0),
	(330, 0, 'config', 'config_ftp_hostname', 'localhost', 0),
	(329, 0, 'config', 'config_icon', 'catalog/cart.png', 0),
	(328, 0, 'config', 'config_logo', 'catalog/logo.png', 0),
	(326, 0, 'config', 'config_captcha', '', 0),
	(285, 0, 'config', 'config_language', 'en-gb', 0),
	(325, 0, 'config', 'config_return_status_id', '2', 0),
	(324, 0, 'config', 'config_return_id', '0', 0),
	(323, 0, 'config', 'config_affiliate_id', '4', 0),
	(322, 0, 'config', 'config_affiliate_commission', '5', 0),
	(321, 0, 'config', 'config_affiliate_auto', '0', 0),
	(320, 0, 'config', 'config_affiliate_approval', '0', 0),
	(319, 0, 'config', 'config_stock_checkout', '0', 0),
	(318, 0, 'config', 'config_stock_warning', '0', 0),
	(317, 0, 'config', 'config_stock_display', '0', 0),
	(316, 0, 'config', 'config_api_id', '1', 0),
	(315, 0, 'config', 'config_fraud_status_id', '7', 0),
	(314, 0, 'config', 'config_complete_status', '["5","3"]', 1),
	(95, 0, 'free_checkout', 'free_checkout_status', '1', 0),
	(96, 0, 'free_checkout', 'free_checkout_order_status_id', '1', 0),
	(97, 0, 'shipping', 'shipping_sort_order', '3', 0),
	(98, 0, 'sub_total', 'sub_total_sort_order', '1', 0),
	(99, 0, 'sub_total', 'sub_total_status', '1', 0),
	(100, 0, 'tax', 'tax_status', '1', 0),
	(101, 0, 'total', 'total_sort_order', '9', 0),
	(102, 0, 'total', 'total_status', '1', 0),
	(103, 0, 'tax', 'tax_sort_order', '5', 0),
	(104, 0, 'free_checkout', 'free_checkout_sort_order', '1', 0),
	(105, 0, 'cod', 'cod_sort_order', '5', 0),
	(106, 0, 'cod', 'cod_total', '0.01', 0),
	(107, 0, 'cod', 'cod_order_status_id', '1', 0),
	(108, 0, 'cod', 'cod_geo_zone_id', '0', 0),
	(109, 0, 'cod', 'cod_status', '1', 0),
	(110, 0, 'shipping', 'shipping_status', '1', 0),
	(111, 0, 'shipping', 'shipping_estimator', '1', 0),
	(112, 0, 'coupon', 'coupon_sort_order', '4', 0),
	(113, 0, 'coupon', 'coupon_status', '1', 0),
	(114, 0, 'flat', 'flat_sort_order', '1', 0),
	(115, 0, 'flat', 'flat_status', '1', 0),
	(116, 0, 'flat', 'flat_geo_zone_id', '0', 0),
	(117, 0, 'flat', 'flat_tax_class_id', '9', 0),
	(118, 0, 'flat', 'flat_cost', '5.00', 0),
	(119, 0, 'credit', 'credit_sort_order', '7', 0),
	(120, 0, 'credit', 'credit_status', '1', 0),
	(121, 0, 'reward', 'reward_sort_order', '2', 0),
	(122, 0, 'reward', 'reward_status', '1', 0),
	(123, 0, 'category', 'category_status', '1', 0),
	(124, 0, 'account', 'account_status', '1', 0),
	(125, 0, 'affiliate', 'affiliate_status', '1', 0),
	(126, 0, 'theme_default', 'theme_default_product_limit', '15', 0),
	(127, 0, 'theme_default', 'theme_default_product_description_length', '100', 0),
	(128, 0, 'theme_default', 'theme_default_image_thumb_width', '228', 0),
	(129, 0, 'theme_default', 'theme_default_image_thumb_height', '228', 0),
	(130, 0, 'theme_default', 'theme_default_image_popup_width', '500', 0),
	(131, 0, 'theme_default', 'theme_default_image_popup_height', '500', 0),
	(132, 0, 'theme_default', 'theme_default_image_category_width', '80', 0),
	(133, 0, 'theme_default', 'theme_default_image_category_height', '80', 0),
	(134, 0, 'theme_default', 'theme_default_image_product_width', '228', 0),
	(135, 0, 'theme_default', 'theme_default_image_product_height', '228', 0),
	(136, 0, 'theme_default', 'theme_default_image_additional_width', '74', 0),
	(137, 0, 'theme_default', 'theme_default_image_additional_height', '74', 0),
	(138, 0, 'theme_default', 'theme_default_image_related_width', '200', 0),
	(139, 0, 'theme_default', 'theme_default_image_related_height', '200', 0),
	(140, 0, 'theme_default', 'theme_default_image_compare_width', '90', 0),
	(141, 0, 'theme_default', 'theme_default_image_compare_height', '90', 0),
	(142, 0, 'theme_default', 'theme_default_image_wishlist_width', '47', 0),
	(143, 0, 'theme_default', 'theme_default_image_wishlist_height', '47', 0),
	(144, 0, 'theme_default', 'theme_default_image_cart_height', '47', 0),
	(145, 0, 'theme_default', 'theme_default_image_cart_width', '47', 0),
	(146, 0, 'theme_default', 'theme_default_image_location_height', '50', 0),
	(147, 0, 'theme_default', 'theme_default_image_location_width', '268', 0),
	(148, 0, 'theme_default', 'theme_default_directory', 'default', 0),
	(149, 0, 'theme_default', 'theme_default_status', '1', 0),
	(150, 0, 'dashboard_activity', 'dashboard_activity_status', '1', 0),
	(151, 0, 'dashboard_activity', 'dashboard_activity_sort_order', '7', 0),
	(152, 0, 'dashboard_sale', 'dashboard_sale_status', '1', 0),
	(153, 0, 'dashboard_sale', 'dashboard_sale_width', '3', 0),
	(154, 0, 'dashboard_chart', 'dashboard_chart_status', '1', 0),
	(155, 0, 'dashboard_chart', 'dashboard_chart_width', '6', 0),
	(156, 0, 'dashboard_customer', 'dashboard_customer_status', '1', 0),
	(157, 0, 'dashboard_customer', 'dashboard_customer_width', '3', 0),
	(361, 0, 'dashboard_map', 'dashboard_map_sort_order', '1', 0),
	(360, 0, 'dashboard_map', 'dashboard_map_status', '1', 0),
	(364, 0, 'dashboard_online', 'dashboard_online_sort_order', '2', 0),
	(363, 0, 'dashboard_online', 'dashboard_online_status', '1', 0),
	(162, 0, 'dashboard_order', 'dashboard_order_sort_order', '1', 0),
	(163, 0, 'dashboard_order', 'dashboard_order_status', '1', 0),
	(164, 0, 'dashboard_order', 'dashboard_order_width', '3', 0),
	(165, 0, 'dashboard_sale', 'dashboard_sale_sort_order', '2', 0),
	(166, 0, 'dashboard_customer', 'dashboard_customer_sort_order', '3', 0),
	(362, 0, 'dashboard_online', 'dashboard_online_width', '6', 0),
	(359, 0, 'dashboard_map', 'dashboard_map_width', '6', 0),
	(169, 0, 'dashboard_chart', 'dashboard_chart_sort_order', '6', 0),
	(170, 0, 'dashboard_recent', 'dashboard_recent_status', '1', 0),
	(171, 0, 'dashboard_recent', 'dashboard_recent_sort_order', '8', 0),
	(172, 0, 'dashboard_activity', 'dashboard_activity_width', '4', 0),
	(173, 0, 'dashboard_recent', 'dashboard_recent_width', '8', 0),
	(313, 0, 'config', 'config_processing_status', '["5","1","2","12","3"]', 1),
	(312, 0, 'config', 'config_order_status_id', '1', 0),
	(311, 0, 'config', 'config_checkout_id', '5', 0),
	(310, 0, 'config', 'config_checkout_guest', '1', 0),
	(309, 0, 'config', 'config_cart_weight', '1', 0),
	(308, 0, 'config', 'config_invoice_prefix', 'INV-2013-00', 0),
	(307, 0, 'config', 'config_account_id', '3', 0),
	(306, 0, 'config', 'config_login_attempts', '5', 0),
	(305, 0, 'config', 'config_customer_price', '0', 0),
	(304, 0, 'config', 'config_customer_group_display', '["1"]', 1),
	(303, 0, 'config', 'config_customer_group_id', '1', 0),
	(302, 0, 'config', 'config_customer_search', '0', 0),
	(301, 0, 'config', 'config_customer_activity', '0', 0),
	(300, 0, 'config', 'config_customer_online', '0', 0),
	(299, 0, 'config', 'config_tax_customer', 'shipping', 0),
	(298, 0, 'config', 'config_tax_default', 'shipping', 0),
	(297, 0, 'config', 'config_tax', '1', 0),
	(286, 0, 'config', 'config_admin_language', 'en-gb', 0),
	(287, 0, 'config', 'config_currency', 'USD', 0),
	(288, 0, 'config', 'config_currency_auto', '1', 0),
	(289, 0, 'config', 'config_length_class_id', '1', 0),
	(290, 0, 'config', 'config_weight_class_id', '1', 0),
	(291, 0, 'config', 'config_product_count', '1', 0),
	(292, 0, 'config', 'config_limit_admin', '25', 0),
	(293, 0, 'config', 'config_review_status', '1', 0),
	(294, 0, 'config', 'config_review_guest', '1', 0),
	(295, 0, 'config', 'config_voucher_min', '1', 0),
	(296, 0, 'config', 'config_voucher_max', '1000', 0),
	(281, 0, 'config', 'config_open', '', 0),
	(282, 0, 'config', 'config_comment', '', 0),
	(283, 0, 'config', 'config_country_id', '222', 0),
	(284, 0, 'config', 'config_zone_id', '3563', 0),
	(270, 0, 'config', 'config_meta_keyword', '', 0),
	(271, 0, 'config', 'config_theme', 'theme_default', 0),
	(272, 0, 'config', 'config_layout_id', '4', 0),
	(273, 0, 'config', 'config_name', 'Your Store', 0),
	(274, 0, 'config', 'config_owner', 'Your Name', 0),
	(275, 0, 'config', 'config_address', 'Address 1', 0),
	(276, 0, 'config', 'config_geocode', '', 0),
	(277, 0, 'config', 'config_email', 'admin@example.com', 0),
	(278, 0, 'config', 'config_telephone', '123456789', 0),
	(279, 0, 'config', 'config_fax', '', 0),
	(280, 0, 'config', 'config_image', '', 0),
	(269, 0, 'config', 'config_meta_description', 'My Store', 0),
	(268, 0, 'config', 'config_meta_title', 'Your Store', 0),
	(357, 0, 'config', 'config_error_log', '1', 0),
	(358, 0, 'config', 'config_error_filename', 'error.log', 0);
/*!40000 ALTER TABLE `oc_setting` ENABLE KEYS */;

-- Dumping structure for table shift.oc_store
DROP TABLE IF EXISTS `oc_store`;
CREATE TABLE IF NOT EXISTS `oc_store` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ssl` varchar(255) NOT NULL,
  PRIMARY KEY (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_store: 0 rows
/*!40000 ALTER TABLE `oc_store` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_store` ENABLE KEYS */;

-- Dumping structure for table shift.oc_theme
DROP TABLE IF EXISTS `oc_theme`;
CREATE TABLE IF NOT EXISTS `oc_theme` (
  `theme_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `theme` varchar(64) NOT NULL,
  `route` varchar(64) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_theme: 0 rows
/*!40000 ALTER TABLE `oc_theme` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_theme` ENABLE KEYS */;

-- Dumping structure for table shift.oc_upload
DROP TABLE IF EXISTS `oc_upload`;
CREATE TABLE IF NOT EXISTS `oc_upload` (
  `upload_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`upload_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_upload: 0 rows
/*!40000 ALTER TABLE `oc_upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `oc_upload` ENABLE KEYS */;

-- Dumping structure for table shift.oc_url_alias
DROP TABLE IF EXISTS `oc_url_alias`;
CREATE TABLE IF NOT EXISTS `oc_url_alias` (
  `url_alias_id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`url_alias_id`),
  KEY `query` (`query`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM AUTO_INCREMENT=845 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_url_alias: 4 rows
/*!40000 ALTER TABLE `oc_url_alias` DISABLE KEYS */;
INSERT INTO `oc_url_alias` (`url_alias_id`, `query`, `keyword`) VALUES
	(844, 'information_id=4', 'about_us'),
	(841, 'information_id=6', 'delivery'),
	(842, 'information_id=3', 'privacy'),
	(843, 'information_id=5', 'terms');
/*!40000 ALTER TABLE `oc_url_alias` ENABLE KEYS */;

-- Dumping structure for table shift.oc_user
DROP TABLE IF EXISTS `oc_user`;
CREATE TABLE IF NOT EXISTS `oc_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(9) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `image` varchar(255) NOT NULL,
  `code` varchar(40) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_user: 1 rows
/*!40000 ALTER TABLE `oc_user` DISABLE KEYS */;
INSERT INTO `oc_user` (`user_id`, `user_group_id`, `username`, `password`, `salt`, `firstname`, `lastname`, `email`, `image`, `code`, `ip`, `status`, `date_added`) VALUES
	(1, 1, 'admin', '39052ccfab324575d569c9b17ed0f5ee87c51daf', 'H9DAkrtU8', 'John', 'Doe', 'admin@example.com', '', '', '::1', 1, '2022-01-17 05:22:44');
/*!40000 ALTER TABLE `oc_user` ENABLE KEYS */;

-- Dumping structure for table shift.oc_user_group
DROP TABLE IF EXISTS `oc_user_group`;
CREATE TABLE IF NOT EXISTS `oc_user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Dumping data for table shift.oc_user_group: 2 rows
/*!40000 ALTER TABLE `oc_user_group` DISABLE KEYS */;
INSERT INTO `oc_user_group` (`user_group_id`, `name`, `permission`) VALUES
	(1, 'Administrator', '{"access":["catalog\\/attribute","catalog\\/attribute_group","catalog\\/category","catalog\\/download","catalog\\/filter","catalog\\/information","catalog\\/manufacturer","catalog\\/option","catalog\\/product","catalog\\/recurring","catalog\\/review","common\\/column_left","common\\/filemanager","customer\\/custom_field","customer\\/customer","customer\\/customer_group","design\\/banner","design\\/language","design\\/layout","design\\/menu","design\\/theme","design\\/translation","event\\/compatibility","event\\/theme","extension\\/analytics\\/google_analytics","extension\\/captcha\\/basic_captcha","extension\\/captcha\\/google_captcha","extension\\/dashboard\\/activity","extension\\/dashboard\\/chart","extension\\/dashboard\\/customer","extension\\/dashboard\\/map","extension\\/dashboard\\/online","extension\\/dashboard\\/order","extension\\/dashboard\\/recent","extension\\/dashboard\\/sale","extension\\/event","extension\\/extension","extension\\/extension\\/analytics","extension\\/extension\\/captcha","extension\\/extension\\/dashboard","extension\\/extension\\/feed","extension\\/extension\\/fraud","extension\\/extension\\/menu","extension\\/extension\\/module","extension\\/extension\\/payment","extension\\/extension\\/shipping","extension\\/extension\\/theme","extension\\/extension\\/total","extension\\/feed\\/google_base","extension\\/feed\\/google_sitemap","extension\\/feed\\/openbaypro","extension\\/fraud\\/fraudlabspro","extension\\/fraud\\/ip","extension\\/fraud\\/maxmind","extension\\/installer","extension\\/modification","extension\\/module\\/account","extension\\/module\\/affiliate","extension\\/module\\/amazon_login","extension\\/module\\/amazon_pay","extension\\/module\\/banner","extension\\/module\\/bestseller","extension\\/module\\/carousel","extension\\/module\\/category","extension\\/module\\/divido_calculator","extension\\/module\\/ebay_listing","extension\\/module\\/featured","extension\\/module\\/filter","extension\\/module\\/google_hangouts","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/klarna_checkout_module","extension\\/module\\/latest","extension\\/module\\/laybuy_layout","extension\\/module\\/pilibaba_button","extension\\/module\\/pp_button","extension\\/module\\/pp_login","extension\\/module\\/sagepay_direct_cards","extension\\/module\\/sagepay_server_cards","extension\\/module\\/slideshow","extension\\/module\\/special","extension\\/module\\/store","extension\\/openbay","extension\\/openbay\\/amazon","extension\\/openbay\\/amazon_listing","extension\\/openbay\\/amazon_product","extension\\/openbay\\/amazonus","extension\\/openbay\\/amazonus_listing","extension\\/openbay\\/amazonus_product","extension\\/openbay\\/ebay","extension\\/openbay\\/ebay_profile","extension\\/openbay\\/ebay_template","extension\\/openbay\\/etsy","extension\\/openbay\\/etsy_product","extension\\/openbay\\/etsy_shipping","extension\\/openbay\\/etsy_shop","extension\\/openbay\\/fba","extension\\/payment\\/amazon_login_pay","extension\\/payment\\/authorizenet_aim","extension\\/payment\\/authorizenet_sim","extension\\/payment\\/bank_transfer","extension\\/payment\\/bluepay_hosted","extension\\/payment\\/bluepay_redirect","extension\\/payment\\/cardconnect","extension\\/payment\\/cardinity","extension\\/payment\\/cheque","extension\\/payment\\/cod","extension\\/payment\\/divido","extension\\/payment\\/eway","extension\\/payment\\/firstdata","extension\\/payment\\/firstdata_remote","extension\\/payment\\/free_checkout","extension\\/payment\\/g2apay","extension\\/payment\\/globalpay","extension\\/payment\\/globalpay_remote","extension\\/payment\\/klarna_account","extension\\/payment\\/klarna_checkout","extension\\/payment\\/klarna_invoice","extension\\/payment\\/laybuy","extension\\/payment\\/liqpay","extension\\/payment\\/nochex","extension\\/payment\\/paymate","extension\\/payment\\/paypoint","extension\\/payment\\/payza","extension\\/payment\\/perpetual_payments","extension\\/payment\\/pilibaba","extension\\/payment\\/pp_express","extension\\/payment\\/pp_payflow","extension\\/payment\\/pp_payflow_iframe","extension\\/payment\\/pp_pro","extension\\/payment\\/pp_pro_iframe","extension\\/payment\\/pp_standard","extension\\/payment\\/realex","extension\\/payment\\/realex_remote","extension\\/payment\\/sagepay_direct","extension\\/payment\\/sagepay_server","extension\\/payment\\/sagepay_us","extension\\/payment\\/securetrading_pp","extension\\/payment\\/securetrading_ws","extension\\/payment\\/skrill","extension\\/payment\\/twocheckout","extension\\/payment\\/web_payment_software","extension\\/payment\\/worldpay","extension\\/shipping\\/auspost","extension\\/shipping\\/citylink","extension\\/shipping\\/fedex","extension\\/shipping\\/flat","extension\\/shipping\\/free","extension\\/shipping\\/item","extension\\/shipping\\/parcelforce_48","extension\\/shipping\\/pickup","extension\\/shipping\\/royal_mail","extension\\/shipping\\/ups","extension\\/shipping\\/usps","extension\\/shipping\\/weight","extension\\/store","extension\\/theme\\/theme_default","extension\\/total\\/coupon","extension\\/total\\/credit","extension\\/total\\/handling","extension\\/total\\/klarna_fee","extension\\/total\\/low_order_fee","extension\\/total\\/reward","extension\\/total\\/shipping","extension\\/total\\/sub_total","extension\\/total\\/tax","extension\\/total\\/total","extension\\/total\\/voucher","localisation\\/country","localisation\\/currency","localisation\\/geo_zone","localisation\\/language","localisation\\/length_class","localisation\\/location","localisation\\/order_status","localisation\\/return_action","localisation\\/return_reason","localisation\\/return_status","localisation\\/stock_status","localisation\\/tax_class","localisation\\/tax_rate","localisation\\/weight_class","localisation\\/zone","marketing\\/affiliate","marketing\\/contact","marketing\\/coupon","marketing\\/marketing","report\\/affiliate","report\\/affiliate_activity","report\\/affiliate_login","report\\/customer_activity","report\\/customer_credit","report\\/customer_login","report\\/customer_online","report\\/customer_order","report\\/customer_reward","report\\/customer_search","report\\/marketing","report\\/product_purchased","report\\/product_viewed","report\\/sale_coupon","report\\/sale_order","report\\/sale_return","report\\/sale_shipping","report\\/sale_tax","sale\\/order","sale\\/recurring","sale\\/return","sale\\/voucher","sale\\/voucher_theme","setting\\/setting","setting\\/store","startup\\/compatibility","startup\\/error","startup\\/event","startup\\/login","startup\\/permission","startup\\/router","startup\\/sass","startup\\/startup","tool\\/backup","tool\\/log","tool\\/upload","user\\/api","user\\/user","user\\/user_permission"],"modify":["catalog\\/attribute","catalog\\/attribute_group","catalog\\/category","catalog\\/download","catalog\\/filter","catalog\\/information","catalog\\/manufacturer","catalog\\/option","catalog\\/product","catalog\\/recurring","catalog\\/review","common\\/column_left","common\\/filemanager","customer\\/custom_field","customer\\/customer","customer\\/customer_group","design\\/banner","design\\/language","design\\/layout","design\\/menu","design\\/theme","design\\/translation","event\\/compatibility","event\\/theme","extension\\/analytics\\/google_analytics","extension\\/captcha\\/basic_captcha","extension\\/captcha\\/google_captcha","extension\\/dashboard\\/activity","extension\\/dashboard\\/chart","extension\\/dashboard\\/customer","extension\\/dashboard\\/map","extension\\/dashboard\\/online","extension\\/dashboard\\/order","extension\\/dashboard\\/recent","extension\\/dashboard\\/sale","extension\\/event","extension\\/extension","extension\\/extension\\/analytics","extension\\/extension\\/captcha","extension\\/extension\\/dashboard","extension\\/extension\\/feed","extension\\/extension\\/fraud","extension\\/extension\\/menu","extension\\/extension\\/module","extension\\/extension\\/payment","extension\\/extension\\/shipping","extension\\/extension\\/theme","extension\\/extension\\/total","extension\\/feed\\/google_base","extension\\/feed\\/google_sitemap","extension\\/feed\\/openbaypro","extension\\/fraud\\/fraudlabspro","extension\\/fraud\\/ip","extension\\/fraud\\/maxmind","extension\\/installer","extension\\/modification","extension\\/module\\/account","extension\\/module\\/affiliate","extension\\/module\\/amazon_login","extension\\/module\\/amazon_pay","extension\\/module\\/banner","extension\\/module\\/bestseller","extension\\/module\\/carousel","extension\\/module\\/category","extension\\/module\\/divido_calculator","extension\\/module\\/ebay_listing","extension\\/module\\/featured","extension\\/module\\/filter","extension\\/module\\/google_hangouts","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/klarna_checkout_module","extension\\/module\\/latest","extension\\/module\\/laybuy_layout","extension\\/module\\/pilibaba_button","extension\\/module\\/pp_button","extension\\/module\\/pp_login","extension\\/module\\/sagepay_direct_cards","extension\\/module\\/sagepay_server_cards","extension\\/module\\/slideshow","extension\\/module\\/special","extension\\/module\\/store","extension\\/openbay","extension\\/openbay\\/amazon","extension\\/openbay\\/amazon_listing","extension\\/openbay\\/amazon_product","extension\\/openbay\\/amazonus","extension\\/openbay\\/amazonus_listing","extension\\/openbay\\/amazonus_product","extension\\/openbay\\/ebay","extension\\/openbay\\/ebay_profile","extension\\/openbay\\/ebay_template","extension\\/openbay\\/etsy","extension\\/openbay\\/etsy_product","extension\\/openbay\\/etsy_shipping","extension\\/openbay\\/etsy_shop","extension\\/openbay\\/fba","extension\\/payment\\/amazon_login_pay","extension\\/payment\\/authorizenet_aim","extension\\/payment\\/authorizenet_sim","extension\\/payment\\/bank_transfer","extension\\/payment\\/bluepay_hosted","extension\\/payment\\/bluepay_redirect","extension\\/payment\\/cardconnect","extension\\/payment\\/cardinity","extension\\/payment\\/cheque","extension\\/payment\\/cod","extension\\/payment\\/divido","extension\\/payment\\/eway","extension\\/payment\\/firstdata","extension\\/payment\\/firstdata_remote","extension\\/payment\\/free_checkout","extension\\/payment\\/g2apay","extension\\/payment\\/globalpay","extension\\/payment\\/globalpay_remote","extension\\/payment\\/klarna_account","extension\\/payment\\/klarna_checkout","extension\\/payment\\/klarna_invoice","extension\\/payment\\/laybuy","extension\\/payment\\/liqpay","extension\\/payment\\/nochex","extension\\/payment\\/paymate","extension\\/payment\\/paypoint","extension\\/payment\\/payza","extension\\/payment\\/perpetual_payments","extension\\/payment\\/pilibaba","extension\\/payment\\/pp_express","extension\\/payment\\/pp_payflow","extension\\/payment\\/pp_payflow_iframe","extension\\/payment\\/pp_pro","extension\\/payment\\/pp_pro_iframe","extension\\/payment\\/pp_standard","extension\\/payment\\/realex","extension\\/payment\\/realex_remote","extension\\/payment\\/sagepay_direct","extension\\/payment\\/sagepay_server","extension\\/payment\\/sagepay_us","extension\\/payment\\/securetrading_pp","extension\\/payment\\/securetrading_ws","extension\\/payment\\/skrill","extension\\/payment\\/twocheckout","extension\\/payment\\/web_payment_software","extension\\/payment\\/worldpay","extension\\/shipping\\/auspost","extension\\/shipping\\/citylink","extension\\/shipping\\/fedex","extension\\/shipping\\/flat","extension\\/shipping\\/free","extension\\/shipping\\/item","extension\\/shipping\\/parcelforce_48","extension\\/shipping\\/pickup","extension\\/shipping\\/royal_mail","extension\\/shipping\\/ups","extension\\/shipping\\/usps","extension\\/shipping\\/weight","extension\\/store","extension\\/theme\\/theme_default","extension\\/total\\/coupon","extension\\/total\\/credit","extension\\/total\\/handling","extension\\/total\\/klarna_fee","extension\\/total\\/low_order_fee","extension\\/total\\/reward","extension\\/total\\/shipping","extension\\/total\\/sub_total","extension\\/total\\/tax","extension\\/total\\/total","extension\\/total\\/voucher","localisation\\/country","localisation\\/currency","localisation\\/geo_zone","localisation\\/language","localisation\\/length_class","localisation\\/location","localisation\\/order_status","localisation\\/return_action","localisation\\/return_reason","localisation\\/return_status","localisation\\/stock_status","localisation\\/tax_class","localisation\\/tax_rate","localisation\\/weight_class","localisation\\/zone","marketing\\/affiliate","marketing\\/contact","marketing\\/coupon","marketing\\/marketing","report\\/affiliate","report\\/affiliate_activity","report\\/affiliate_login","report\\/customer_activity","report\\/customer_credit","report\\/customer_login","report\\/customer_online","report\\/customer_order","report\\/customer_reward","report\\/customer_search","report\\/marketing","report\\/product_purchased","report\\/product_viewed","report\\/sale_coupon","report\\/sale_order","report\\/sale_return","report\\/sale_shipping","report\\/sale_tax","sale\\/order","sale\\/recurring","sale\\/return","sale\\/voucher","sale\\/voucher_theme","setting\\/setting","setting\\/store","startup\\/compatibility","startup\\/error","startup\\/event","startup\\/login","startup\\/permission","startup\\/router","startup\\/sass","startup\\/startup","tool\\/backup","tool\\/log","tool\\/upload","user\\/api","user\\/user","user\\/user_permission"]}'),
	(10, 'Demonstration', '');
/*!40000 ALTER TABLE `oc_user_group` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

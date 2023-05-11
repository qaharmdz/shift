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
  `priority` int(11) NOT NULL DEFAULT '0',
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
  `description` varchar(510) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `author` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `install` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_id`),
  UNIQUE KEY `codename_type` (`codename`,`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension` (`extension_id`, `codename`, `type`, `name`, `version`, `description`, `author`, `url`, `setting`, `status`, `install`, `created`, `updated`) VALUES
	(6, 'banner', 'module', 'Banner', '1.0.0', '', '', '', '[]', 0, 1, NULL, NULL),
	(7, 'carousel', 'module', 'Carousel', '1.0.0', '', '', '', '[]', 0, 1, NULL, NULL),
	(19, 'slideshow', 'module', 'SlideShow', '1.0.0', '', '', '', '[]', 0, 0, NULL, NULL),
	(32, 'base', 'theme', 'Theme Base', '1.0.0', '', 'Shift', 'https://example.com', '[]', 0, 1, NULL, NULL),
	(33, 'architect', 'plugin', 'Architect', '1.0.0', '', 'Shift', 'https://example.com', '[]', 1, 1, NULL, '2023-05-11 17:14:56');
/*!40000 ALTER TABLE `{DB_PREFIX}extension` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension_meta`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension_meta` (
  `extension_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extension_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `extension_data_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`extension_meta_id`) USING BTREE,
  KEY `extension` (`extension_id`,`extension_data_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `{DB_PREFIX}extension_meta` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension_module`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension_module` (
  `extension_module_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'plugin_id, module_id, theme_id',
  `extension_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_module_id`) USING BTREE,
  KEY `extension_id` (`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension_module` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension_module` (`extension_module_id`, `extension_id`, `name`, `setting`, `status`, `created`, `updated`) VALUES
	(2, 6, 'Home Page', '[]', 1, NULL, NULL),
	(3, 7, 'Home Page', '[]', 0, NULL, '2023-05-11 19:20:30'),
	(4, 19, 'Home Page', '[]', 0, NULL, NULL),
	(5, 7, 'Banner', '[]', 0, NULL, NULL);
/*!40000 ALTER TABLE `{DB_PREFIX}extension_module` ENABLE KEYS */;

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
  `code` varchar(12) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `locale` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `flag` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
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
  `sort_order` int(11) NOT NULL DEFAULT '0',
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

DROP TABLE IF EXISTS `{DB_PREFIX}post`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}post` (
  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT 'default category',
  `visibility` varchar(12) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'public' COMMENT 'public, usergroup, password',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '0',
  `status` varchar(12) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'draft' COMMENT 'publish, pending, draft, trash',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `publish` datetime DEFAULT NULL,
  `unpublish` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`) USING BTREE,
  KEY `taxonomy_status_publish` (`taxonomy`,`status`,`publish`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}post` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}post` (`post_id`, `parent_id`, `taxonomy`, `user_id`, `term_id`, `visibility`, `sort_order`, `status`, `created`, `updated`, `publish`, `unpublish`) VALUES
	(1, 0, 'post', 1, 17, 'usergroup', 0, 'publish', '2023-01-11 16:27:20', '2023-04-24 14:41:35', '2023-01-01 00:00:00', '2023-01-31 23:59:00');
/*!40000 ALTER TABLE `{DB_PREFIX}post` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}post_content`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}post_content` (
  `post_id` bigint(20) unsigned NOT NULL,
  `language_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`post_id`,`language_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}post_content` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}post_content` (`post_id`, `language_id`, `title`, `excerpt`, `content`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(1, 1, 'Test en', '&lt;p&gt;The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&#039;s no limit to what you can achieve.&lt;/p&gt;', '&lt;p&gt;The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&#039;s no limit to what you can achieve.&amp;nbsp;&lt;/p&gt;&lt;p&gt;The&amp;nbsp;&lt;/p&gt;&lt;blockquote&gt;&lt;p&gt;The Cinema HD features an active-matrix liquid crystal display that produces flicker-free images that deliver twice the brightness, twice the sharpness and twice the contrast ratio of a typical CRT display.&amp;nbsp;&lt;/p&gt;&lt;/blockquote&gt;&lt;p&gt;Unlike other flat panels, it&#039;s designed with a pure digital interface to deliver distortion-free images that never need adjusting. With over 4 million digital pixels, the display is uniquely suited for scientific and technical applications such as visualizing molecular structures or analyzing geological data.&amp;nbsp;&amp;nbsp;&lt;/p&gt;&lt;figure class=&quot;image image_resized&quot; style=&quot;width:37.16%;&quot;&gt;&lt;img src=&quot;https://localhost/mdzGit/shift/public/media/image/demo/minimalist-dark-table.jpg&quot; alt=&quot;minimalist-dark-table.jpg&quot;&gt;&lt;/figure&gt;&lt;p&gt;Offering accurate, brilliant color performance, the Cinema HD delivers up to 16.7 million colors across a wide gamut allowing you to see subtle nuances between colors from soft pastels to rich jewel tones. A wide viewing angle ensures uniform color from edge to edge. Apple&#039;s ColorSync technology allows you to create custom profiles to maintain consistent color onscreen and in print. The result: You can confidently use this display in all your color-critical applications.&amp;nbsp;&lt;/p&gt;&lt;p&gt;Housed in a new aluminum design, the display has a very thin bezel that enhances visual accuracy. Each display features two FireWire 400 ports and two USB 2.0 ports, making attachment of desktop peripherals, such as iSight, iPod, digital and still cameras, hard drives, printers and scanners, even more accessible and convenient. Taking advantage of the much thinner and lighter footprint of an LCD, the new displays support the VESA (Video Electronics Standards Association) mounting interface standard. Customers with the optional Cinema Display VESA Mount Adapter kit gain the flexibility to mount their display in locations most appropriate for their work environment.&amp;nbsp;&lt;/p&gt;&lt;p&gt;The Cinema HD features a single cable design with elegant breakout for the USB 2.0, FireWire 400 and a pure digital connection using the industry standard Digital Video Interface (DVI) interface. The DVI connection allows for a direct pure-digital connection.&lt;/p&gt;&lt;p&gt;Features:&lt;/p&gt;&lt;p&gt;Unrivaled display performance&lt;/p&gt;&lt;ul&gt;&lt;li&gt;30-inch (viewable) active-matrix liquid crystal display provides breathtaking image quality and vivid, richly saturated color.&lt;/li&gt;&lt;li&gt;Support for 2560-by-1600 pixel resolution for display of high definition still and video imagery.&lt;/li&gt;&lt;li&gt;Wide-format design for simultaneous display of two full pages of text and graphics.&lt;/li&gt;&lt;li&gt;Industry standard DVI connector for direct attachment to Mac- and Windows-based desktops and notebooks&lt;/li&gt;&lt;li&gt;Incredibly wide (170 degree) horizontal and vertical viewing angle for maximum visibility and color performance.&lt;/li&gt;&lt;li&gt;Lightning-fast pixel response for full-motion digital video playback.&lt;/li&gt;&lt;li&gt;Support for 16.7 million saturated colors, for use in all graphics-intensive applications.&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;Simple setup and operation&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Single cable with elegant breakout for connection to DVI, USB and FireWire ports&lt;/li&gt;&lt;li&gt;Built-in two-port USB 2.0 hub for easy connection of desktop peripheral devices.&lt;/li&gt;&lt;li&gt;Two FireWire 400 ports to support iSight and other desktop peripherals&lt;/li&gt;&lt;/ul&gt;', '', '', ''),
	(1, 2, 'Test id', '', '', '', '', '');
/*!40000 ALTER TABLE `{DB_PREFIX}post_content` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}post_meta`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}post_meta` (
  `post_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_meta_id`) USING BTREE,
  KEY `post_id` (`post_id`) USING BTREE,
  KEY `key` (`key`(191)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}post_meta` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}post_meta` (`post_meta_id`, `post_id`, `key`, `value`, `encoded`) VALUES
	(424, 1, 'visibility_usergroups', '["1"]', 1),
	(425, 1, 'visibility_password', 'coolpass', 0),
	(426, 1, 'image', 'image/demo/wood-table.jpg', 0),
	(427, 1, 'robots', 'noindex, nofollow', 0),
	(428, 1, 'comment', '', 0),
	(429, 1, 'custom_code', '', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}post_meta` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}route_alias`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}route_alias` (
  `route_alias_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `language_id` bigint(20) unsigned NOT NULL DEFAULT '1',
  `route` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `param` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `alias` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`route_alias_id`) USING BTREE,
  UNIQUE KEY `site_language_alias` (`site_id`,`language_id`,`alias`) USING BTREE,
  KEY `route_param_value` (`route`,`param`,`value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}route_alias` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}route_alias` (`route_alias_id`, `site_id`, `language_id`, `route`, `param`, `value`, `alias`) VALUES
	(1, 0, 1, 'information/information', 'information_id', '3', 'privacy'),
	(3, 0, 1, 'information/information', 'information_id', '5', 'terms'),
	(5, 0, 1, 'information/information', 'information_id', '6', 'delivery'),
	(6, 0, 1, 'information/contact', '', '', 'contact-us'),
	(7, 0, 1, 'common/home', '', '', '/'),
	(14, 0, 1, 'information/information', 'information_id', '4', 'about-us'),
	(15, 0, 2, 'information/information', 'information_id', '3', 'page-en'),
	(16, 1, 1, 'information/information', 'information_id', '3', 'privacy'),
	(209, 0, 1, 'content/category', 'category_id', '14', 'page-en2'),
	(210, 0, 2, 'content/category', 'category_id', '14', 'page-id'),
	(211, 1, 1, 'content/category', 'category_id', '14', 'page-en2'),
	(212, 1, 2, 'content/category', 'category_id', '14', 'page-id'),
	(301, 0, 1, 'content/tag', 'tag_id', '15', 'Tag-one'),
	(302, 0, 2, 'content/tag', 'tag_id', '15', 'Tag-two'),
	(303, 1, 1, 'content/tag', 'tag_id', '15', 'Tag-one'),
	(304, 1, 2, 'content/tag', 'tag_id', '15', 'Tag-two'),
	(305, 0, 1, 'content/tag', 'tag_id', '20', 'test-tag'),
	(306, 0, 2, 'content/tag', 'tag_id', '20', 'test-tag'),
	(307, 1, 1, 'content/tag', 'tag_id', '20', 'test-tag'),
	(308, 1, 2, 'content/tag', 'tag_id', '20', 'test-tag'),
	(309, 0, 1, 'content/tag', 'tag_id', '21', 'foobar'),
	(310, 0, 2, 'content/tag', 'tag_id', '21', 'foobar'),
	(311, 1, 1, 'content/tag', 'tag_id', '21', 'foobar'),
	(312, 1, 2, 'content/tag', 'tag_id', '21', 'foobar'),
	(313, 0, 1, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(314, 0, 2, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(315, 1, 1, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(316, 1, 2, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(333, 0, 1, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(334, 0, 2, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(335, 1, 1, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(336, 1, 2, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(341, 0, 1, 'content/tag', 'tag_id', '23', 'foo-bar'),
	(342, 0, 2, 'content/tag', 'tag_id', '23', 'foo-bar2'),
	(343, 1, 1, 'content/tag', 'tag_id', '23', 'foo-bar'),
	(344, 1, 2, 'content/tag', 'tag_id', '23', 'foo-bar2'),
	(345, 0, 1, 'content/tag', 'tag_id', '25', 'tag-new-1-1'),
	(346, 0, 2, 'content/tag', 'tag_id', '25', 'tag-new-1-2'),
	(347, 1, 1, 'content/tag', 'tag_id', '25', 'tag-new-1-1'),
	(348, 1, 2, 'content/tag', 'tag_id', '25', 'tag-new-1-2'),
	(353, 0, 1, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(354, 0, 2, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(355, 1, 1, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(356, 1, 2, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(529, 0, 1, 'content/post', 'post_id', '1', 'test-en'),
	(530, 0, 2, 'content/post', 'post_id', '1', 'test-id'),
	(531, 1, 1, 'content/post', 'post_id', '1', 'test-en'),
	(532, 1, 2, 'content/post', 'post_id', '1', 'test-id');
/*!40000 ALTER TABLE `{DB_PREFIX}route_alias` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}setting`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}setting` (
  `setting_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) NOT NULL DEFAULT '0',
  `group` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`),
  KEY `group` (`site_id`,`group`,`code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}setting` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}setting` (`setting_id`, `site_id`, `group`, `code`, `key`, `value`, `encoded`) VALUES
	(1296, 0, 'system', 'alias_distinct', 'information/information', 'information_id', 0),
	(1297, 0, 'system', 'alias_distinct', 'content/post', 'post_id', 0),
	(1298, 0, 'system', 'alias_multi', 'content/category', 'category_id', 0),
	(2937, 0, 'system', 'site', 'name', 'Shift Site', 0),
	(2938, 0, 'system', 'site', 'url_host', 'https://localhost/mdzGit/shift/public/', 0),
	(2939, 0, 'system', 'site', 'email', 'admin@example.com', 0),
	(2940, 0, 'system', 'site', 'meta_title', '{"1":"Cool Shift Site","2":"Cool Shift Site"}', 1),
	(2941, 0, 'system', 'site', 'meta_description', '{"1":"","2":""}', 1),
	(2942, 0, 'system', 'site', 'meta_keyword', '{"1":"","2":""}', 1),
	(2943, 0, 'system', 'site', 'logo', 'image/logo.png', 0),
	(2944, 0, 'system', 'site', 'favicon', 'image/favicon.png', 0),
	(2945, 0, 'system', 'site', 'language', 'en', 0),
	(2946, 0, 'system', 'site', 'layout_id', '1', 0),
	(2947, 0, 'system', 'site', 'theme', 'base', 0),
	(2948, 0, 'system', 'site', 'maintenance', '0', 0),
	(2949, 0, 'system', 'site', 'timezone', 'Asia/Jakarta', 0),
	(3091, 0, 'content', 'setting', 'post_robots', 'index, nofollow', 0),
	(3092, 0, 'content', 'setting', 'post_comment', 'register', 0),
	(3093, 0, 'content', 'setting', 'post_custom_code', '', 0),
	(3094, 0, 'content', 'setting', 'category_robots', 'index, follow', 0),
	(3095, 0, 'content', 'setting', 'category_post_per_page', '10', 0),
	(3096, 0, 'content', 'setting', 'category_post_lead', '2', 0),
	(3097, 0, 'content', 'setting', 'category_post_lead_excerpt', '100', 0),
	(3098, 0, 'content', 'setting', 'category_post_column', '2', 0),
	(3099, 0, 'content', 'setting', 'category_post_column_excerpt', '48', 0),
	(3100, 0, 'content', 'setting', 'category_post_order', 'p.publish~desc', 0),
	(3101, 0, 'content', 'setting', 'category_custom_code', '', 0),
	(3102, 1, 'content', 'setting', 'post_robots', 'index, follow', 0),
	(3103, 1, 'content', 'setting', 'post_comment', 'register', 0),
	(3104, 1, 'content', 'setting', 'post_custom_code', '', 0),
	(3105, 1, 'content', 'setting', 'category_robots', 'index, follow', 0),
	(3106, 1, 'content', 'setting', 'category_post_per_page', '10', 0),
	(3107, 1, 'content', 'setting', 'category_post_lead', '2', 0),
	(3108, 1, 'content', 'setting', 'category_post_lead_excerpt', '100', 0),
	(3109, 1, 'content', 'setting', 'category_post_column', '2', 0),
	(3110, 1, 'content', 'setting', 'category_post_column_excerpt', '48', 0),
	(3111, 1, 'content', 'setting', 'category_post_order', 'p.publish~desc', 0),
	(3112, 1, 'content', 'setting', 'category_custom_code', '', 0),
	(3177, 0, 'system', 'setting', 'compression', '0', 0),
	(3178, 0, 'system', 'setting', 'admin_language', 'en', 0),
	(3179, 0, 'system', 'setting', 'admin_limit', '36', 0),
	(3180, 0, 'system', 'setting', 'mail_engine', 'mail', 0),
	(3181, 0, 'system', 'setting', 'smtp_host', '', 0),
	(3182, 0, 'system', 'setting', 'smtp_username', '', 0),
	(3183, 0, 'system', 'setting', 'smtp_password', '', 0),
	(3184, 0, 'system', 'setting', 'smtp_port', '25', 0),
	(3185, 0, 'system', 'setting', 'smtp_timeout', '300', 0),
	(3186, 0, 'system', 'setting', 'error_display', '1', 0),
	(3187, 0, 'system', 'setting', 'development', '1', 0),
	(3188, 0, 'system', 'setting', 'mail_smtp_hostname', '', 0),
	(3189, 0, 'system', 'setting', 'mail_smtp_username', '', 0),
	(3190, 0, 'system', 'setting', 'mail_smtp_password', '', 0),
	(3191, 0, 'system', 'setting', 'mail_smtp_port', '', 0),
	(3192, 0, 'system', 'setting', 'mail_smtp_timeout', '', 0),
	(3229, 1, 'system', 'site', 'name', 'Site Name', 0),
	(3230, 1, 'system', 'site', 'url_host', 'https://example.com/', 0),
	(3231, 1, 'system', 'site', 'email', 'admin@example.com', 0),
	(3232, 1, 'system', 'site', 'meta_title', '{"1":"","2":""}', 1),
	(3233, 1, 'system', 'site', 'meta_description', '{"1":"","2":""}', 1),
	(3234, 1, 'system', 'site', 'meta_keyword', '{"1":"","2":""}', 1),
	(3235, 1, 'system', 'site', 'logo', 'image/logo.png', 0),
	(3236, 1, 'system', 'site', 'favicon', 'image/favicon.png', 0),
	(3237, 1, 'system', 'site', 'language', 'en', 0),
	(3238, 1, 'system', 'site', 'layout_id', '1', 0),
	(3239, 1, 'system', 'site', 'theme', 'base', 0),
	(3240, 1, 'system', 'site', 'maintenance', '1', 0);
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
	(1, 'Site Name', 'https://example.com/');
/*!40000 ALTER TABLE `{DB_PREFIX}site` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}site_relation`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}site_relation` (
  `site_relation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`site_relation_id`) USING BTREE,
  UNIQUE KEY `site_taxonomy_id` (`site_id`,`taxonomy`,`taxonomy_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}site_relation` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}site_relation` (`site_relation_id`, `site_id`, `taxonomy`, `taxonomy_id`) VALUES
	(36, 0, 'content_category', 18),
	(35, 1, 'content_post', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}site_relation` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}term`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}term` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`term_id`) USING BTREE,
  KEY `taxonomy` (`taxonomy`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}term` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}term` (`term_id`, `parent_id`, `taxonomy`, `sort_order`, `status`, `created`, `updated`) VALUES
	(14, 0, 'content_category', 0, 0, '2022-12-29 10:51:06', '2023-02-19 19:55:00'),
	(15, 0, 'content_tag', 0, 1, '2023-02-05 18:25:29', '2023-02-26 07:04:53'),
	(16, 0, 'content_category', 0, 0, '2023-02-21 18:01:09', '2023-02-21 18:01:15'),
	(17, 14, 'content_category', 0, 1, '2023-02-21 18:01:33', '2023-02-28 16:59:52'),
	(18, 17, 'content_category', 0, 1, '2023-02-21 18:01:48', '2023-04-24 16:16:40'),
	(20, 0, 'content_tag', 0, 1, '2023-02-26 07:30:25', '2023-02-26 07:30:25'),
	(21, 0, 'content_tag', 0, 1, '2023-02-26 07:30:26', '2023-02-26 07:30:26'),
	(22, 0, 'content_tag', 0, 1, '2023-02-26 07:30:26', '2023-02-26 07:30:26'),
	(23, 0, 'content_tag', 0, 1, '2023-02-26 07:59:45', '2023-02-26 08:02:29'),
	(24, 0, 'content_tag', 0, 1, '2023-02-26 07:59:45', '2023-05-09 19:54:20'),
	(25, 0, 'content_tag', 0, 1, '2023-02-26 08:04:38', '2023-02-26 08:04:38'),
	(26, 0, 'content_tag', 0, 1, '2023-02-26 08:05:55', '2023-02-26 08:05:55');
/*!40000 ALTER TABLE `{DB_PREFIX}term` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}term_content`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}term_content` (
  `term_id` bigint(20) unsigned NOT NULL,
  `language_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`term_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}term_content` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}term_content` (`term_id`, `language_id`, `title`, `content`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(14, 1, 'Page en', 'test', '', '', ''),
	(14, 2, 'Page', '', '', '', ''),
	(15, 1, 'Tag one', 'Tag one', '', '', ''),
	(15, 2, 'Tag two', 'Tag two', '', '', ''),
	(16, 1, 'Blog', '', '', '', ''),
	(16, 2, 'Blog', '', '', '', ''),
	(17, 1, 'News', '', '', '', ''),
	(17, 2, 'News', '', '', '', ''),
	(18, 1, 'Events', '', '', '', ''),
	(18, 2, 'Events', '', '', '', ''),
	(20, 1, 'test tag', '', '', '', ''),
	(20, 2, 'test tag', '', '', '', ''),
	(21, 1, 'foobar', '', '', '', ''),
	(21, 2, 'foobar', '', '', '', ''),
	(22, 1, 'tips trick', '', '', '', ''),
	(22, 2, 'tips trick', '', '', '', ''),
	(23, 1, 'foo bar', '', '', '', ''),
	(23, 2, 'foo bar', '', '', '', ''),
	(24, 1, 'lorem ipsum', '', '', '', ''),
	(24, 2, 'lorem ipsum', '', '', '', ''),
	(25, 1, 'tag new 1', '', '', '', ''),
	(25, 2, 'tag new 1', '', '', '', ''),
	(26, 1, 'tag new 2', '', '', '', ''),
	(26, 2, 'tag new 2', '', '', '', '');
/*!40000 ALTER TABLE `{DB_PREFIX}term_content` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}term_meta`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}term_meta` (
  `term_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_meta_id`) USING BTREE,
  KEY `term_id` (`term_id`) USING BTREE,
  KEY `key` (`key`(191)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}term_meta` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}term_meta` (`term_meta_id`, `term_id`, `key`, `value`, `encoded`) VALUES
	(161, 14, 'robots', '', 0),
	(162, 14, 'post_per_page', '9', 0),
	(163, 14, 'post_lead', '1', 0),
	(164, 14, 'post_lead_excerpt', '101', 0),
	(165, 14, 'post_column', '2', 0),
	(166, 14, 'post_column_excerpt', '46', 0),
	(167, 14, 'post_order', '', 0),
	(168, 14, 'custom_code', '', 0),
	(177, 16, 'robots', '', 0),
	(178, 16, 'post_per_page', '10', 0),
	(179, 16, 'post_lead', '2', 0),
	(180, 16, 'post_lead_excerpt', '101', 0),
	(181, 16, 'post_column', '2', 0),
	(182, 16, 'post_column_excerpt', '48', 0),
	(183, 16, 'post_order', '', 0),
	(184, 16, 'custom_code', '', 0),
	(185, 17, 'robots', '', 0),
	(186, 17, 'post_per_page', '10', 0),
	(187, 17, 'post_lead', '2', 0),
	(188, 17, 'post_lead_excerpt', '101', 0),
	(189, 17, 'post_column', '2', 0),
	(190, 17, 'post_column_excerpt', '48', 0),
	(191, 17, 'post_order', '', 0),
	(192, 17, 'custom_code', '', 0),
	(225, 18, 'robots', 'noindex, follow', 0),
	(226, 18, 'post_per_page', '10', 0),
	(227, 18, 'post_lead', '2', 0),
	(228, 18, 'post_lead_excerpt', '101', 0),
	(229, 18, 'post_column', '2', 0),
	(230, 18, 'post_column_excerpt', '48', 0),
	(231, 18, 'post_order', 'p.publish~asc', 0),
	(232, 18, 'custom_code', '', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}term_meta` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}term_relation`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}term_relation` (
  `term_relation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_relation_id`) USING BTREE,
  UNIQUE KEY `term_taxonomy_id` (`term_id`,`taxonomy`,`taxonomy_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}term_relation` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}term_relation` (`term_relation_id`, `term_id`, `taxonomy`, `taxonomy_id`) VALUES
	(316, 15, 'content_post', 1),
	(312, 16, 'content_post', 1),
	(311, 18, 'content_post', 1),
	(313, 21, 'content_post', 1),
	(314, 25, 'content_post', 1),
	(315, 26, 'content_post', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}term_relation` ENABLE KEYS */;

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
	(1, 1, 'admin@example.com', '$2y$10$aoN3hKTurGHDmrBbenED/.VeDanhyLQz51/eWUaRkOrUvU2dhuM8K', 'admin', 'John', 'Doe', 1, '2023-05-11 15:18:18', '2022-01-30 16:17:31', '2022-03-20 12:17:31'),
	(3, 2, 'james@example.com', '$2y$10$NeYYCLxL.tttyffQzKmliOazCa9vCnJx5EkSerZwvEXtCaCrtqRaC', 'james', 'James', 'Doe', 1, '2022-11-15 11:57:27', '2022-01-30 16:17:31', '2023-03-02 22:53:07'),
	(4, 2, 'jane@example.com', '$2y$10$NeYYCLxL.tttyffQzKmliOazCa9vCnJx5EkSerZwvEXtCaCrtqRaC', 'janedoe', 'Jane', 'Doe', 0, '2022-10-07 20:57:27', '2022-01-30 16:17:31', '2023-03-05 16:44:48');
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
	(2, 'Register', 0, '{"access":["content\\/category","content\\/post","content\\/setting","content\\/tag","extension\\/dashboard\\/online","extension\\/event","extension\\/installer","extension\\/language","extension\\/manage","extension\\/module","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/plugin","extension\\/theme","extension\\/theme\\/base","setting\\/setting","setting\\/site","tool\\/cache","tool\\/layout","tool\\/log"],"modify":["content\\/category","content\\/post","content\\/setting","content\\/tag","extension\\/dashboard\\/online","extension\\/event","extension\\/installer","extension\\/language","extension\\/manage","extension\\/module","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/plugin","extension\\/theme","extension\\/theme\\/base","setting\\/setting","setting\\/site","tool\\/cache","tool\\/layout","tool\\/log"]}', 0, '2022-10-21 14:37:53', '2023-03-23 16:43:13');
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
	(1, 1, 'bio', 'Test', 0),
	(9, 4, 'bio', '', 0),
	(19, 3, 'bio', 'Awesome', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}user_meta` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

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
  `priority` smallint(4) NOT NULL DEFAULT '0',
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
  `extension_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'plugin_id, theme_id, language_id',
  `codename` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `type` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `version` varchar(16) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `author` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `url` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext COLLATE utf8mb4_unicode_520_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `install` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_id`),
  UNIQUE KEY `codename_type` (`codename`,`type`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension` (`extension_id`, `codename`, `type`, `name`, `version`, `description`, `author`, `url`, `setting`, `status`, `install`, `created`, `updated`) VALUES
	(32, 'base', 'theme', 'Theme Base', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. ', 'Shift CMS', 'https://example.com', '[]', 1, 1, NULL, '2023-08-06 08:03:36'),
	(33, 'architect', 'plugin', 'Architect', '1.1.0', '', 'Shift CMS', 'https://github.com/qaharmdz/shift', '[]', 1, 1, NULL, '2023-08-06 08:04:00'),
	(35, 'en', 'language', 'English', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi quos a, quia rerum voluptates atque nulla nam accusantium nobis debitis. Voluptatibus natus et. Temporibus veniam, ea aspernatur iste enim, aperiam.\r\n', 'Shift CMS', 'https://github.com/qaharmdz/shift', '{"flag":"en.png", "locale":"en-US,en_US.UTF-8,en_US,en-gb,english"}', 1, 1, NULL, NULL),
	(36, 'id', 'language', 'Indonesia', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi quos a, quia rerum voluptates atque nulla nam accusantium nobis debitis. Voluptatibus natus et. Temporibus veniam, ea aspernatur iste enim, aperiam.\r\n', 'Shift CMS', 'https://github.com/qaharmdz/shift', '{"flag":"id.png", "locale":"ID, en-ID,en_ID.UTF-8,indonesia"}', 1, 1, NULL, NULL),
	(37, 'codex', 'module', 'Codex - HTML, Twig and Script', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi quos a, quia rerum voluptates atque nulla nam accusantium nobis debitis. ', 'Shift CMS', 'https://github.com/qaharmdz/shift', '[]', 1, 1, '2023-06-03 08:03:33', '2023-06-11 17:00:57');
/*!40000 ALTER TABLE `{DB_PREFIX}extension` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension_meta`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension_meta` (
  `extension_meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extension_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `extension_module_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`extension_meta_id`) USING BTREE,
  KEY `extension` (`extension_id`,`extension_module_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `{DB_PREFIX}extension_meta` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}extension_module`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}extension_module` (
  `extension_module_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extension_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `visibility` varchar(12) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'public' COMMENT 'public, usergroup',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `publish` datetime DEFAULT NULL,
  `unpublish` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_module_id`) USING BTREE,
  KEY `extension_id` (`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}extension_module` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}extension_module` (`extension_module_id`, `extension_id`, `type`, `name`, `setting`, `visibility`, `status`, `created`, `updated`, `publish`, `unpublish`) VALUES
	(1, 37, 'code', 'Test', '{"editor":"{{ app.document.addStyle(app.config.get(&#039;env.url_site&#039;) ~ &#039;codex_id_1.css&#039;) }}\\r\\n\\r\\n{% set setting = {\\r\\n    &#039;name&#039;  : codex.getUser(&#039;fullname&#039;),\\r\\n    &#039;link&#039; : {\\r\\n        &#039;url&#039;  : app.router.url(&#039;common\\/home&#039;),\\r\\n        &#039;text&#039; : app.language.get(&#039;home&#039;),\\r\\n    },\\r\\n} \\r\\n%}\\r\\n\\r\\nHello {{ setting.name }},\\r\\n&lt;a href=&quot;{{ setting.link.url }}&quot;&gt;{{ setting.link.text }}&lt;\\/a&gt;","description":"desc","visibility":"public","visibility_usergroups":[]}', 'public', 1, '2023-06-10 09:51:33', '2023-08-02 15:43:28', NULL, NULL),
	(2, 37, 'code', 'Slideshow', '{"editor":"&lt;div class=&quot;uk-position-relative uk-visible-toggle uk-light&quot; \\r\\n     tabindex=&quot;-1&quot; \\r\\n     uk-slideshow=&#039;{&quot;autoplay&quot;:true, &quot;max-height&quot;:500, &quot;animation&quot;: &quot;push&quot;}&#039;\\r\\n&gt;\\r\\n  &lt;ul class=&quot;uk-slideshow-items&quot;&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/minimalist-dark-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;x: 100,-100&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;x: 200,-200&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;scale: 1.2,1.2,1&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/art-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;opacity: 0,0,0.2; backgroundColor: #000,#000&quot;&gt;&lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-medium uk-text-center&quot;&gt;\\r\\n        &lt;div uk-slideshow-parallax=&quot;scale: 1,1,0.8&quot;&gt;\\r\\n          &lt;h2 uk-slideshow-parallax=&quot;x: 200,0,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n          &lt;p uk-slideshow-parallax=&quot;x: 400,0,0;&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n        &lt;\\/div&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-top&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/wood-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;y: -50,0,0; opacity: 1,1,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;y: 50,0,0; opacity: 1,1,0&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n  &lt;\\/ul&gt;\\r\\n\\r\\n  &lt;a class=&quot;uk-position-center-left uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-previous uk-slideshow-item=&quot;previous&quot;&gt;&lt;\\/a&gt;\\r\\n  &lt;a class=&quot;uk-position-center-right uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-next uk-slideshow-item=&quot;next&quot;&gt;&lt;\\/a&gt;\\r\\n&lt;\\/div&gt;","description":"test","visibility":"usergroup","visibility_usergroups":["2","1"]}', 'public', 1, '2023-06-10 09:18:01', '2023-06-11 15:27:20', NULL, NULL),
	(3, 37, 'wysiwyg', 'Slideshow wysiwyg', '{"editor":"&lt;div class=&quot;uk-position-relative uk-visible-toggle uk-light&quot; \\r\\n     tabindex=&quot;-1&quot; \\r\\n     uk-slideshow=&#039;{&quot;autoplay&quot;:true, &quot;max-height&quot;:500, &quot;animation&quot;: &quot;push&quot;}&#039;\\r\\n&gt;\\r\\n  &lt;ul class=&quot;uk-slideshow-items&quot;&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/minimalist-dark-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;x: 100,-100&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;x: 200,-200&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;scale: 1.2,1.2,1&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/art-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;opacity: 0,0,0.2; backgroundColor: #000,#000&quot;&gt;&lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-medium uk-text-center&quot;&gt;\\r\\n        &lt;div uk-slideshow-parallax=&quot;scale: 1,1,0.8&quot;&gt;\\r\\n          &lt;h2 uk-slideshow-parallax=&quot;x: 200,0,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n          &lt;p uk-slideshow-parallax=&quot;x: 400,0,0;&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n        &lt;\\/div&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-top&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/wood-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;y: -50,0,0; opacity: 1,1,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;y: 50,0,0; opacity: 1,1,0&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n  &lt;\\/ul&gt;\\r\\n\\r\\n  &lt;a class=&quot;uk-position-center-left uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-previous uk-slideshow-item=&quot;previous&quot;&gt;&lt;\\/a&gt;\\r\\n  &lt;a class=&quot;uk-position-center-right uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-next uk-slideshow-item=&quot;next&quot;&gt;&lt;\\/a&gt;\\r\\n&lt;\\/div&gt;","description":"test","visibility":"usergroup","visibility_usergroups":["2","1"]}', 'public', 1, '2023-06-10 09:18:01', '2023-07-16 07:25:59', NULL, NULL);
/*!40000 ALTER TABLE `{DB_PREFIX}extension_module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}information`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}information` (
  `information_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bottom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_order` int(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`information_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='TODO: remove';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='TODO: remove';

/*!40000 ALTER TABLE `{DB_PREFIX}information_description` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}information_description` (`information_id`, `language_id`, `title`, `description`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(3, 35, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n', 'Privacy Policy', '', ''),
	(4, 35, 'About Us', '&lt;p&gt;\r\n	About Us 2&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/logo.png&quot;&gt;&lt;/p&gt;&lt;p&gt;Test&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 130px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/demo/manufacturer/disney.png&quot;&gt;&lt;/p&gt;&lt;p&gt;test&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 799.359px; height: 266.453px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/demo/banners/MacBookAir.jpg&quot;&gt;&lt;br&gt;&lt;/p&gt;', 'About Us', '', ''),
	(5, 35, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n', 'Terms &amp; Conditions', '', ''),
	(6, 35, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;', 'Delivery Information', '', ''),
	(9, 35, 'New Information', '&lt;p&gt;Info Desc&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;img style=&quot;width: 170px;&quot; src=&quot;http://localhost/mdzGit/shift/public/media/image/logo.png&quot;&gt;&lt;br&gt;&lt;/p&gt;', 'New Info', '', '');
/*!40000 ALTER TABLE `{DB_PREFIX}information_description` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}information_to_layout`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}information_to_layout` (
  `information_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`information_id`,`site_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='TODO: remove';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='TODO: remove';

/*!40000 ALTER TABLE `{DB_PREFIX}information_to_site` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}information_to_site` (`information_id`, `site_id`) VALUES
	(3, 0),
	(4, 0),
	(5, 0),
	(6, 0),
	(9, 0);
/*!40000 ALTER TABLE `{DB_PREFIX}information_to_site` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout` (
  `layout_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `placements` mediumtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `custom_code` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout` (`layout_id`, `name`, `placements`, `custom_code`, `status`) VALUES
	(1, 'Default', '', '', 1),
	(2, 'Home', '{"top":{"setting":{"node_child":"row"},"rows":{"1":{"setting":{"container":"0"},"columns":{"col-hrpm7sa7svgd":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-6rs64urr0hsn":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"content_top":{"setting":{"node_child":"row"},"rows":{"row-nhnbo13qlmoq":{"setting":[],"columns":{"col-e1fsqh8gr88h":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-gqtlj81jjaf3":{"module_id":1,"codename":"codex","name":"Test"}}}}}}}}', '&lt;style&gt;\r\n  .element {\r\n    background: #d00;\r\n  }\r\n&lt;/style&gt;\r\n&lt;script&gt;console.log(&#039;cool&#039;)&lt;/script&gt;', 1),
	(6, 'Account', '{"alpha":{"setting":{"node_child":"row"},"rows":{"row-5mvdfjq0mbjs":{"setting":[],"columns":{"col-r3fd0p4f32vn":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-44ja9f30ctmc":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"topbar":{"setting":{"node_child":"row"},"rows":{"row-2ug25fi9vjok":{"setting":[],"columns":{"col-r6k3l0pphfq2":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-fl3gdmvqdjkp":{"module_id":1,"codename":"codex","name":"Test"}}}}}}},"top":{"setting":{"node_child":"row"},"rows":{"row-thch3s5anq6s":{"setting":{"child_width":"uk-child-width-1-3"},"columns":{"col-skiepg0eqn4k":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-p024kep8oq59":{"module_id":2,"codename":"codex","name":"Slideshow"}}},"col-t8fbrug9javj":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-pfn6ru99uon4":{"module_id":3,"codename":"codex","name":"Slideshow wysiwyg"}}}}}}},"sidebar_left":{"setting":{"node_child":"module"},"rows":{"mod-q9gp8c4338mu":{"module_id":2,"codename":"codex","name":"Slideshow"},"mod-ht161co7bv34":{"module_id":1,"codename":"codex","name":"Test"}}},"content_top":{"setting":{"node_child":"row"},"rows":{"row-i0odcepm6k4s":{"setting":{"child_width":"uk-child-width-1-2"},"columns":{"col-dn3baegtrgnb":{"setting":{"width":""},"modules":{"mod-pj00d8l3m13l":{"module_id":1,"codename":"codex","name":"Test"}}}}}}},"content_left":{"setting":{"node_child":"module"},"rows":{"rowmod-1gsag3bh140d":{"module_id":2,"codename":"codex","name":"Slideshow"}}},"content_right":{"setting":{"node_child":"module"},"rows":{"mod-72chgacinbqd":{"module_id":1,"codename":"codex","name":"Test"}}},"content_bottom":{"setting":{"node_child":"row"},"rows":{"row-gi759ko5qclm":{"setting":[],"columns":{"col-kltse6vj068k":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-c1t8sut9acmo":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"sidebar_right":{"setting":{"node_child":"module"},"rows":{"mod-v3uj99akgro7":{"module_id":2,"codename":"codex","name":"Slideshow"}}},"bottom":{"setting":{"node_child":"row"},"rows":{"row-ncp879cdbmns":{"setting":[],"columns":{"col-9bbgb3f6qie2":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-gf5id45uhnch":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"bottombar":{"setting":{"node_child":"row"},"rows":{"row-r0r7bkkupjm0":{"setting":[],"columns":{"col-p64nrnmdnq0r":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-s50q8vv423sb":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"footer":{"setting":{"node_child":"row"},"rows":{"row-bsp3phek024b":{"setting":[],"columns":{"col-bh04lddpelei":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-l1mldh5bf4to":{"module_id":1,"codename":"codex","name":"Test"}}}}}}},"omega":{"setting":{"node_child":"row"},"rows":{"row-r22gi0mq12j7":{"setting":[],"columns":{"col-8ufcm97l7vdj":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-o3i9rjhqvr3b":{"module_id":3,"codename":"codex","name":"Slideshow wysiwyg"}}}}}}}}', '', 1),
	(8, 'Contact', '', '', 1),
	(9, 'Sitemap', '', '', 1),
	(11, 'Information', '', '', 1),
	(12, 'Info About', '', '', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}layout` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout_module`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout_module` (
  `layout_module_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `extension_module_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `position` varchar(32) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='TODO: remove';

/*!40000 ALTER TABLE `{DB_PREFIX}layout_module` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout_module` (`layout_module_id`, `layout_id`, `extension_module_id`, `position`, `sort_order`) VALUES
	(66, 2, 1, 'content_top', 1),
	(67, 2, 2, 'top', 1);
/*!40000 ALTER TABLE `{DB_PREFIX}layout_module` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}layout_route`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}layout_route` (
  `layout_route_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `route` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `url_params` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `exclude` tinyint(1) NOT NULL DEFAULT '0',
  `priority` smallint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_route_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}layout_route` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}layout_route` (`layout_route_id`, `layout_id`, `site_id`, `route`, `url_params`, `exclude`, `priority`) VALUES
	(24, 11, 0, 'information/information', '', 0, 0),
	(31, 8, 0, 'information/contact', '', 0, 0),
	(32, 9, 0, 'information/sitemap', '', 0, 0),
	(54, 1, 0, '*', '', 0, -255),
	(57, 11, 1, 'information/information', 'information_id=1', 0, 0),
	(58, 8, 1, 'information/contact', '', 0, 0),
	(59, 9, 1, 'information/sitemap', '', 0, 0),
	(60, 1, 1, '*', '', 0, -255),
	(77, 11, 0, 'information/information', '', 0, 0),
	(441, 2, 0, 'common/home', '1a', 0, 0),
	(442, 2, 0, 'page/home', '2a', 1, 0),
	(443, 2, 1, 'common/home', '3ab', 0, 0),
	(470, 6, 0, 'account/logout', '', 1, 11),
	(471, 6, 0, 'account/edit', 'user_id=1', 1, 1),
	(472, 6, 0, 'account/*', '', 0, 0),
	(473, 6, 1, 'account/*', '', 0, 0);
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
	(1, 0, 'post', 1, 17, 'usergroup', 0, 'publish', '2023-01-11 16:27:20', '2023-08-05 08:13:13', NULL, NULL);
/*!40000 ALTER TABLE `{DB_PREFIX}post` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}post_content`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}post_content` (
  `post_id` bigint(20) unsigned NOT NULL,
  `language_id` bigint(20) unsigned NOT NULL COMMENT 'extension.extension_id',
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
	(1, 35, 'Test en', '&lt;p&gt;The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&#039;s no limit to what you can achieve.&lt;/p&gt;', '&lt;p&gt;The 30-inch&amp;nbsp;&lt;span style=&quot;background-color:hsl(0,75%,60%);color:hsl(0, 0%, 100%);&quot;&gt; Apple Cinema HD Display&amp;nbsp;&lt;/span&gt; delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&#039;s no limit to what you can achieve.&amp;nbsp;&lt;/p&gt;&lt;p&gt;&amp;nbsp;&lt;/p&gt;&lt;blockquote&gt;&lt;p&gt;The Cinema HD features an active-matrix liquid crystal display that produces flicker-free images that deliver twice the brightness, twice the sharpness and twice the contrast ratio of a typical CRT display.&amp;nbsp;&lt;/p&gt;&lt;/blockquote&gt;&lt;figure class=&quot;image image_resized&quot; style=&quot;width:55.32%;&quot;&gt;&lt;img src=&quot;https://localhost/mdzGit/shift/public/media/image/demo/banners/iphone68.jpg&quot; alt=&quot;iphone68.jpg&quot;&gt;&lt;/figure&gt;&lt;p&gt;Unlike other flat panels, it&#039;s designed with a pure digital interface to deliver distortion-free images that never need adjusting. With over 4 million digital pixels, the display is uniquely suited for scientific and technical applications such as visualizing molecular structures or analyzing geological data.&amp;nbsp;&amp;nbsp;&lt;/p&gt;&lt;figure class=&quot;image image_resized&quot; style=&quot;width:37.16%;&quot;&gt;&lt;img src=&quot;https://localhost/mdzGit/shift/public/media/image/demo/minimalist-dark-table.jpg&quot; alt=&quot;minimalist-dark-table.jpg&quot;&gt;&lt;/figure&gt;&lt;p&gt;Offering accurate, brilliant color performance, the Cinema HD delivers up to 16.7 million colors across a wide gamut allowing you to see subtle nuances between colors from soft pastels to rich jewel tones. A wide viewing angle ensures uniform color from edge to edge. Apple&#039;s ColorSync technology allows you to create custom profiles to maintain consistent color onscreen and in print. The result: You can confidently use this display in all your color-critical applications.&amp;nbsp;&lt;/p&gt;&lt;p&gt;Housed in a new aluminum design, the display has a very thin bezel that enhances visual accuracy. Each display features two FireWire 400 ports and two USB 2.0 ports, making attachment of desktop peripherals, such as iSight, iPod, digital and still cameras, hard drives, printers and scanners, even more accessible and convenient. Taking advantage of the much thinner and lighter footprint of an LCD, the new displays support the VESA (Video Electronics Standards Association) mounting interface standard. Customers with the optional Cinema Display VESA Mount Adapter kit gain the flexibility to mount their display in locations most appropriate for their work environment.&amp;nbsp;&lt;/p&gt;&lt;p&gt;The Cinema HD features a single cable design with elegant breakout for the USB 2.0, FireWire 400 and a pure digital connection using the industry standard Digital Video Interface (DVI) interface. The DVI connection allows for a direct pure-digital connection.&lt;/p&gt;&lt;p&gt;Features:&lt;/p&gt;&lt;p&gt;Unrivaled display performance&lt;/p&gt;&lt;ul&gt;&lt;li&gt;30-inch (viewable) active-matrix liquid crystal display provides breathtaking image quality and vivid, richly saturated color.&lt;/li&gt;&lt;li&gt;Support for 2560-by-1600 pixel resolution for display of high definition still and video imagery.&lt;/li&gt;&lt;li&gt;Wide-format design for simultaneous display of two full pages of text and graphics.&lt;/li&gt;&lt;li&gt;Industry standard DVI connector for direct attachment to Mac- and Windows-based desktops and notebooks&lt;/li&gt;&lt;li&gt;Incredibly wide (170 degree) horizontal and vertical viewing angle for maximum visibility and color performance.&lt;/li&gt;&lt;li&gt;Lightning-fast pixel response for full-motion digital video playback.&lt;/li&gt;&lt;li&gt;Support for 16.7 million saturated colors, for use in all graphics-intensive applications.&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;Simple setup and operation&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Single cable with elegant breakout for connection to DVI, USB and FireWire ports&lt;/li&gt;&lt;li&gt;Built-in two-port USB 2.0 hub for easy connection of desktop peripheral devices.&lt;/li&gt;&lt;li&gt;Two FireWire 400 ports to support iSight and other desktop peripherals&lt;/li&gt;&lt;/ul&gt;', '', '', ''),
	(1, 36, 'Test id', '', '', '', '', '');
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
	(568, 1, 'visibility_usergroups', '["1"]', 1),
	(569, 1, 'visibility_password', 'coolpass', 0),
	(570, 1, 'image', 'image/demo/wood-table.jpg', 0),
	(571, 1, 'robots', 'noindex, nofollow', 0),
	(572, 1, 'comment', '', 0),
	(573, 1, 'custom_code', '', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}post_meta` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}route_alias`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}route_alias` (
  `route_alias_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `language_id` bigint(20) unsigned NOT NULL DEFAULT '1' COMMENT 'extension.extension_id',
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
	(1, 0, 35, 'information/information', 'information_id', '3', 'privacy'),
	(3, 0, 35, 'information/information', 'information_id', '5', 'terms'),
	(5, 0, 35, 'information/information', 'information_id', '6', 'delivery'),
	(6, 0, 35, 'information/contact', '', '', 'contact-us'),
	(7, 0, 35, 'common/home', '', '', '/'),
	(14, 0, 35, 'information/information', 'information_id', '4', 'about-us'),
	(15, 0, 36, 'information/information', 'information_id', '3', 'page-en'),
	(16, 1, 35, 'information/information', 'information_id', '3', 'privacy'),
	(209, 0, 35, 'content/category', 'category_id', '14', 'page-en2'),
	(210, 0, 36, 'content/category', 'category_id', '14', 'page-id'),
	(211, 1, 35, 'content/category', 'category_id', '14', 'page-en2'),
	(212, 1, 36, 'content/category', 'category_id', '14', 'page-id'),
	(301, 0, 35, 'content/tag', 'tag_id', '15', 'Tag-one'),
	(302, 0, 36, 'content/tag', 'tag_id', '15', 'Tag-two'),
	(303, 1, 35, 'content/tag', 'tag_id', '15', 'Tag-one'),
	(304, 1, 36, 'content/tag', 'tag_id', '15', 'Tag-two'),
	(305, 0, 35, 'content/tag', 'tag_id', '20', 'test-tag'),
	(306, 0, 36, 'content/tag', 'tag_id', '20', 'test-tag'),
	(307, 1, 35, 'content/tag', 'tag_id', '20', 'test-tag'),
	(308, 1, 36, 'content/tag', 'tag_id', '20', 'test-tag'),
	(309, 0, 35, 'content/tag', 'tag_id', '21', 'foobar'),
	(310, 0, 36, 'content/tag', 'tag_id', '21', 'foobar'),
	(311, 1, 35, 'content/tag', 'tag_id', '21', 'foobar'),
	(312, 1, 36, 'content/tag', 'tag_id', '21', 'foobar'),
	(313, 0, 35, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(314, 0, 36, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(315, 1, 35, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(316, 1, 36, 'content/tag', 'tag_id', '22', 'tips-trick'),
	(333, 0, 35, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(334, 0, 36, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(335, 1, 35, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(336, 1, 36, 'content/tag', 'tag_id', '24', 'lorem-ipsum'),
	(341, 0, 35, 'content/tag', 'tag_id', '23', 'foo-bar'),
	(342, 0, 36, 'content/tag', 'tag_id', '23', 'foo-bar2'),
	(343, 1, 35, 'content/tag', 'tag_id', '23', 'foo-bar'),
	(344, 1, 36, 'content/tag', 'tag_id', '23', 'foo-bar2'),
	(345, 0, 35, 'content/tag', 'tag_id', '25', 'tag-new-1-1'),
	(346, 0, 36, 'content/tag', 'tag_id', '25', 'tag-new-1-2'),
	(347, 1, 35, 'content/tag', 'tag_id', '25', 'tag-new-1-1'),
	(348, 1, 36, 'content/tag', 'tag_id', '25', 'tag-new-1-2'),
	(353, 0, 35, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(354, 0, 36, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(355, 1, 35, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(356, 1, 36, 'content/tag', 'tag_id', '26', 'tag-new-2'),
	(617, 0, 35, 'content/tag', 'tag_id', '27', 'abcd-1'),
	(618, 0, 36, 'content/tag', 'tag_id', '27', 'abcd-2'),
	(619, 1, 35, 'content/tag', 'tag_id', '27', 'abcd-1'),
	(620, 1, 36, 'content/tag', 'tag_id', '27', 'abcd-2'),
	(629, 0, 35, 'content/post', 'post_id', '1', 'test-en-cool'),
	(630, 0, 36, 'content/post', 'post_id', '1', 'test-id'),
	(631, 1, 35, 'content/post', 'post_id', '1', 'test-en-cool'),
	(632, 1, 36, 'content/post', 'post_id', '1', 'test-id');
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
	(3289, 0, 'content', 'setting', 'post_robots', 'index, nofollow', 0),
	(3290, 0, 'content', 'setting', 'post_comment', 'register', 0),
	(3291, 0, 'content', 'setting', 'post_custom_code', '', 0),
	(3292, 0, 'content', 'setting', 'category_robots', 'index, follow', 0),
	(3293, 0, 'content', 'setting', 'category_post_per_page', '10', 0),
	(3294, 0, 'content', 'setting', 'category_post_lead', '2', 0),
	(3295, 0, 'content', 'setting', 'category_post_lead_excerpt', '100', 0),
	(3296, 0, 'content', 'setting', 'category_post_column', '2', 0),
	(3297, 0, 'content', 'setting', 'category_post_column_excerpt', '48', 0),
	(3298, 0, 'content', 'setting', 'category_post_order', 'p.publish~desc', 0),
	(3299, 0, 'content', 'setting', 'category_custom_code', '', 0),
	(3353, 1, 'system', 'site', 'name', 'Site Name', 0),
	(3354, 1, 'system', 'site', 'url_host', 'https://example.com/', 0),
	(3355, 1, 'system', 'site', 'email', 'admin@example.com', 0),
	(3356, 1, 'system', 'site', 'meta_title', '{"35":"","36":""}', 1),
	(3357, 1, 'system', 'site', 'meta_description', '{"35":"","36":""}', 1),
	(3358, 1, 'system', 'site', 'meta_keyword', '{"35":"","36":""}', 1),
	(3359, 1, 'system', 'site', 'logo', 'image/logo.png', 0),
	(3360, 1, 'system', 'site', 'favicon', 'image/favicon.png', 0),
	(3361, 1, 'system', 'site', 'language', 'en', 0),
	(3362, 1, 'system', 'site', 'layout_id', '1', 0),
	(3363, 1, 'system', 'site', 'theme', 'base', 0),
	(3364, 1, 'system', 'site', 'maintenance', '1', 0),
	(3461, 0, 'system', 'setting', 'compression', '5', 0),
	(3462, 0, 'system', 'setting', 'admin_language', 'en', 0),
	(3463, 0, 'system', 'setting', 'admin_limit', '36', 0),
	(3464, 0, 'system', 'setting', 'mail_engine', 'mail', 0),
	(3465, 0, 'system', 'setting', 'smtp_host', '', 0),
	(3466, 0, 'system', 'setting', 'smtp_username', '', 0),
	(3467, 0, 'system', 'setting', 'smtp_password', '', 0),
	(3468, 0, 'system', 'setting', 'smtp_port', '25', 0),
	(3469, 0, 'system', 'setting', 'smtp_timeout', '300', 0),
	(3470, 0, 'system', 'setting', 'error_display', '1', 0),
	(3471, 0, 'system', 'setting', 'development', '1', 0),
	(3472, 0, 'system', 'setting', 'mail_smtp_hostname', '', 0),
	(3473, 0, 'system', 'setting', 'mail_smtp_username', '', 0),
	(3474, 0, 'system', 'setting', 'mail_smtp_password', '', 0),
	(3475, 0, 'system', 'setting', 'mail_smtp_port', '', 0),
	(3476, 0, 'system', 'setting', 'mail_smtp_timeout', '', 0),
	(3477, 0, 'system', 'site', 'name', 'Shift Site', 0),
	(3478, 0, 'system', 'site', 'url_host', 'https://localhost/mdzGit/shift/public/', 0),
	(3479, 0, 'system', 'site', 'email', 'admin@example.com', 0),
	(3480, 0, 'system', 'site', 'meta_title', '{"35":"Cool Shift Site","36":"Cool Shift Site"}', 1),
	(3481, 0, 'system', 'site', 'meta_description', '{"35":"","36":""}', 1),
	(3482, 0, 'system', 'site', 'meta_keyword', '{"35":"","36":""}', 1),
	(3483, 0, 'system', 'site', 'logo', 'image/logo.png', 0),
	(3484, 0, 'system', 'site', 'favicon', 'image/favicon.png', 0),
	(3485, 0, 'system', 'site', 'language', 'en', 0),
	(3486, 0, 'system', 'site', 'layout_id', '1', 0),
	(3487, 0, 'system', 'site', 'theme', 'base', 0),
	(3488, 0, 'system', 'site', 'maintenance', '0', 0);
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
	(46, 0, 'content_category', 16),
	(36, 0, 'content_category', 18),
	(61, 1, 'content_post', 1);
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
	(14, 0, 'content_category', 0, 0, '2022-12-29 10:51:06', '2023-05-16 16:04:34'),
	(15, 0, 'content_tag', 0, 1, '2023-02-05 18:25:29', '2023-05-16 16:07:09'),
	(16, 0, 'content_category', 0, 1, '2023-02-21 18:01:09', '2023-06-02 15:34:04'),
	(17, 14, 'content_category', 0, 1, '2023-02-21 18:01:33', '2023-05-14 15:55:54'),
	(18, 17, 'content_category', 0, 1, '2023-02-21 18:01:48', '2023-05-16 16:03:44'),
	(20, 0, 'content_tag', 0, 1, '2023-02-26 07:30:25', '2023-02-26 07:30:25'),
	(21, 0, 'content_tag', 0, 1, '2023-02-26 07:30:26', '2023-05-16 16:19:33'),
	(22, 0, 'content_tag', 0, 1, '2023-02-26 07:30:26', '2023-02-26 07:30:26'),
	(23, 0, 'content_tag', 0, 1, '2023-02-26 07:59:45', '2023-05-16 16:19:33'),
	(24, 0, 'content_tag', 0, 1, '2023-02-26 07:59:45', '2023-05-16 16:16:50'),
	(25, 0, 'content_tag', 0, 1, '2023-02-26 08:04:38', '2023-05-16 16:07:09'),
	(26, 0, 'content_tag', 0, 1, '2023-02-26 08:05:55', '2023-02-26 08:05:55'),
	(27, 0, 'content_tag', 0, 1, '2023-06-15 09:15:14', '2023-06-15 09:15:14');
/*!40000 ALTER TABLE `{DB_PREFIX}term` ENABLE KEYS */;

DROP TABLE IF EXISTS `{DB_PREFIX}term_content`;
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}term_content` (
  `term_id` bigint(20) unsigned NOT NULL,
  `language_id` bigint(20) unsigned NOT NULL COMMENT 'extension.extension_id',
  `title` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`term_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

/*!40000 ALTER TABLE `{DB_PREFIX}term_content` DISABLE KEYS */;
INSERT INTO `{DB_PREFIX}term_content` (`term_id`, `language_id`, `title`, `content`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
	(14, 35, 'Page en', 'test', '', '', ''),
	(14, 36, 'Page', '', '', '', ''),
	(15, 35, 'Tag one', 'Tag one', '', '', ''),
	(15, 36, 'Tag two', 'Tag two', '', '', ''),
	(16, 35, 'Blog', '', '', '', ''),
	(16, 36, 'Blog', '', '', '', ''),
	(17, 35, 'News', '', '', '', ''),
	(17, 36, 'News', '', '', '', ''),
	(18, 35, 'Events', '', '', '', ''),
	(18, 36, 'Events', '', '', '', ''),
	(20, 35, 'test tag', '', '', '', ''),
	(20, 36, 'test tag', '', '', '', ''),
	(21, 35, 'foobar', '', '', '', ''),
	(21, 36, 'foobar', '', '', '', ''),
	(22, 35, 'tips trick', '', '', '', ''),
	(22, 36, 'tips trick', '', '', '', ''),
	(23, 35, 'foo bar', '', '', '', ''),
	(23, 36, 'foo bar', '', '', '', ''),
	(24, 35, 'lorem ipsum', '', '', '', ''),
	(24, 36, 'lorem ipsum', '', '', '', ''),
	(25, 35, 'tag new 1', '', '', '', ''),
	(25, 36, 'tag new 1', '', '', '', ''),
	(26, 35, 'tag new 2', '', '', '', ''),
	(26, 36, 'tag new 2', '', '', '', ''),
	(27, 35, 'abcd', '', '', '', ''),
	(27, 36, 'abcd', '', '', '', '');
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
	(232, 18, 'custom_code', '', 0),
	(233, 16, 'robots', '', 0),
	(234, 16, 'post_per_page', '10', 0),
	(235, 16, 'post_lead', '2', 0),
	(236, 16, 'post_lead_excerpt', '101', 0),
	(237, 16, 'post_column', '2', 0),
	(238, 16, 'post_column_excerpt', '48', 0),
	(239, 16, 'post_order', '', 0),
	(240, 16, 'custom_code', '', 0);
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
	(463, 15, 'content_post', 1),
	(458, 16, 'content_post', 1),
	(457, 18, 'content_post', 1),
	(460, 21, 'content_post', 1),
	(461, 25, 'content_post', 1),
	(462, 26, 'content_post', 1),
	(459, 27, 'content_post', 1);
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
	(1, 1, 'admin@example.com', '$2y$10$sDZWAn9gysbophuJJGyB0OPxZfwwmPVJsm27kAtvKH7BRte.Aa57u', 'admin', 'John', 'Doe', 1, '2023-08-12 07:24:59', '2022-01-30 16:17:31', '2023-07-27 14:26:11'),
	(3, 2, 'james@example.com', '$2y$10$NeYYCLxL.tttyffQzKmliOazCa9vCnJx5EkSerZwvEXtCaCrtqRaC', 'james', 'James', 'Doe', 0, '2022-11-15 11:57:27', '2022-01-30 16:17:31', '2023-05-15 18:21:36'),
	(4, 2, 'jane@example.com', '$2y$10$NeYYCLxL.tttyffQzKmliOazCa9vCnJx5EkSerZwvEXtCaCrtqRaC', 'janedoe', 'Jane', 'Doe', 0, '2022-10-07 20:57:27', '2022-01-30 16:17:31', '2023-05-15 18:21:36');
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
	(1, 'Super Admin', 1, '{"access":["account\\/user","account\\/usergroup","content\\/category","extensions\\/module\\/slideshow","extensions\\/module\\/codex","extensions\\/plugin\\/architect"],"modify":["extensions\\/module\\/slideshow","extensions\\/module\\/codex","extensions\\/plugin\\/architect"]}', 1, '2022-10-29 14:37:53', '2023-05-17 16:41:01'),
	(2, 'Register', 0, '{"access":["content\\/category","content\\/post","content\\/setting","content\\/tag","extension\\/dashboard\\/online","extension\\/event","extension\\/installer","extension\\/language","extension\\/manage","extension\\/module","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/plugin","extension\\/theme","extension\\/theme\\/base","setting\\/setting","setting\\/site","tool\\/cache","tool\\/layout","tool\\/log"],"modify":["content\\/category","content\\/post","content\\/setting","content\\/tag","extension\\/dashboard\\/online","extension\\/event","extension\\/installer","extension\\/language","extension\\/manage","extension\\/module","extension\\/module\\/account","extension\\/module\\/banner","extension\\/module\\/carousel","extension\\/module\\/html","extension\\/module\\/information","extension\\/module\\/site","extension\\/module\\/slideshow","extension\\/plugin","extension\\/theme","extension\\/theme\\/base","setting\\/setting","setting\\/site","tool\\/cache","tool\\/layout","tool\\/log"]}', 0, '2022-10-21 14:37:53', '2023-05-16 16:03:04'),
	(3, 'test', 0, '{"access":[],"modify":[]}', 0, '2023-05-17 16:36:15', '2023-05-17 16:36:15');
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
	(9, 4, 'bio', '', 0),
	(19, 3, 'bio', 'Awesome', 0),
	(20, 1, 'bio', 'Test', 0);
/*!40000 ALTER TABLE `{DB_PREFIX}user_meta` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

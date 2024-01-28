DROP TABLE IF EXISTS `{DB_PREFIX}event`;
CREATE TABLE `{DB_PREFIX}event` (
  `event_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codename` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `emitter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `listener` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `priority` smallint NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}event` (`event_id`, `codename`, `description`, `emitter`, `listener`, `priority`, `status`) VALUES
    (1, 'codex', '', 'admin/page/dashboard::before', 'extension/module/method', 0, 0),
    (2, 'codex', '', 'admin/page/dashboard::after', 'extension/module/myMethod', 2, 0);

DROP TABLE IF EXISTS `{DB_PREFIX}extension`;
CREATE TABLE `{DB_PREFIX}extension` (
  `extension_id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'plugin_id, theme_id, language_id',
  `codename` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `version` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `author` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `install` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_id`),
  UNIQUE KEY `codename_type` (`codename`,`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}extension` (`extension_id`, `codename`, `type`, `name`, `version`, `description`, `author`, `url`, `setting`, `status`, `install`, `created`, `updated`) VALUES
    (32, 'base', 'theme', 'Theme Base', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. ', 'Shift CMS', 'https://example.com', '[]', 1, 1, NOW(), NOW()),
    (33, 'architect', 'plugin', 'Architect', '1.1.0', '', 'Shift CMS', 'https://github.com/qaharmdz/shift', '[]', 1, 1, NOW(), NOW()),
    (35, 'en', 'language', 'English', '1.0.0', '', 'Shift CMS', 'https://github.com/qaharmdz/shift', '{"locale":"en-US,en_US.UTF-8,en_US,en-gb,english","flag":"en.png"}', 1, 1, NOW(), NOW()),
    (36, 'id', 'language', 'Indonesia', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi quos a, quia rerum voluptates atque nulla nam accusantium nobis debitis. Voluptatibus natus et. Temporibus veniam, ea aspernatur iste enim, aperiam.\r\n', 'Shift CMS', 'https://github.com/qaharmdz/shift', '{"locale":"ID, en-ID,en_ID.UTF-8,indonesia","flag":"id.png"}', 1, 1, NOW(), NOW()),
    (37, 'codex', 'module', 'Codex - HTML, Twig and Script', '1.0.0', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quasi quos a, quia rerum voluptates atque nulla nam accusantium nobis debitis. ', 'Shift CMS', 'https://github.com/qaharmdz/shift', '[]', 1, 1, NOW(), NOW());

DROP TABLE IF EXISTS `{DB_PREFIX}extension_meta`;
CREATE TABLE `{DB_PREFIX}extension_meta` (
  `extension_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `extension_id` bigint unsigned NOT NULL DEFAULT '0',
  `extension_module_id` bigint unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`extension_meta_id`) USING BTREE,
  KEY `extension` (`extension_id`,`extension_module_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

DROP TABLE IF EXISTS `{DB_PREFIX}extension_module`;
CREATE TABLE `{DB_PREFIX}extension_module` (
  `extension_module_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `extension_id` bigint unsigned NOT NULL DEFAULT '0',
  `type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `setting` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `visibility` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'public' COMMENT 'public, usergroup',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `publish` datetime DEFAULT NULL,
  `unpublish` datetime DEFAULT NULL,
  PRIMARY KEY (`extension_module_id`) USING BTREE,
  KEY `extension_id` (`extension_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}extension_module` (`extension_module_id`, `extension_id`, `type`, `name`, `setting`, `visibility`, `status`, `created`, `updated`, `publish`, `unpublish`) VALUES
    (1, 37, 'code', 'Test', '{"editor":"{# {{ shift.lib.document.addStyle(shift.lib.config.get(&#039;env.url_site&#039;) ~ &#039;codex_id_1.css&#039;) }} #}\\r\\n\\r\\n{% set setting = {\\r\\n    &#039;name&#039;  : codex.getUser(&#039;fullname&#039;),\\r\\n    &#039;link&#039; : {\\r\\n        &#039;url&#039;  : shift.lib.router.url(&#039;common\\/home&#039;),\\r\\n        &#039;text&#039; : shift.lib.language.get(&#039;home&#039;),\\r\\n    },\\r\\n} \\r\\n%}\\r\\n\\r\\nHello {{ setting.name }},\\r\\n&lt;a href=&quot;{{ setting.link.url }}&quot;&gt;{{ setting.link.text }}&lt;\\/a&gt;","description":"desc","visibility":"public","visibility_usergroups":[]}', 'public', 1, NOW(), NOW(), NULL, NULL),
    (2, 37, 'code', 'Slideshow', '{"editor":"&lt;div class=&quot;uk-position-relative uk-visible-toggle uk-light&quot; \\r\\n     tabindex=&quot;-1&quot; \\r\\n     uk-slideshow=&#039;{&quot;autoplay&quot;:true, &quot;max-height&quot;:500, &quot;animation&quot;: &quot;push&quot;}&#039;\\r\\n&gt;\\r\\n  &lt;ul class=&quot;uk-slideshow-items&quot;&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/minimalist-dark-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;x: 100,-100&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;x: 200,-200&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;scale: 1.2,1.2,1&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/art-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;opacity: 0,0,0.2; backgroundColor: #000,#000&quot;&gt;&lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-medium uk-text-center&quot;&gt;\\r\\n        &lt;div uk-slideshow-parallax=&quot;scale: 1,1,0.8&quot;&gt;\\r\\n          &lt;h2 uk-slideshow-parallax=&quot;x: 200,0,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n          &lt;p uk-slideshow-parallax=&quot;x: 400,0,0;&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n        &lt;\\/div&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-top&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/wood-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;y: -50,0,0; opacity: 1,1,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;y: 50,0,0; opacity: 1,1,0&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n  &lt;\\/ul&gt;\\r\\n\\r\\n  &lt;a class=&quot;uk-position-center-left uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-previous uk-slideshow-item=&quot;previous&quot;&gt;&lt;\\/a&gt;\\r\\n  &lt;a class=&quot;uk-position-center-right uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-next uk-slideshow-item=&quot;next&quot;&gt;&lt;\\/a&gt;\\r\\n&lt;\\/div&gt;","description":"test","visibility":"usergroup","visibility_usergroups":["2","1"]}', 'public', 1, NOW(), NOW(), NULL, NULL),
    (3, 37, 'wysiwyg', 'Slideshow wysiwyg', '{"editor":"&lt;div class=&quot;uk-position-relative uk-visible-toggle uk-light&quot; \\r\\n     tabindex=&quot;-1&quot; \\r\\n     uk-slideshow=&#039;{&quot;autoplay&quot;:true, &quot;max-height&quot;:500, &quot;animation&quot;: &quot;push&quot;}&#039;\\r\\n&gt;\\r\\n  &lt;ul class=&quot;uk-slideshow-items&quot;&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/minimalist-dark-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;x: 100,-100&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;x: 200,-200&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;scale: 1.2,1.2,1&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/art-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-cover&quot; uk-slideshow-parallax=&quot;opacity: 0,0,0.2; backgroundColor: #000,#000&quot;&gt;&lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-medium uk-text-center&quot;&gt;\\r\\n        &lt;div uk-slideshow-parallax=&quot;scale: 1,1,0.8&quot;&gt;\\r\\n          &lt;h2 uk-slideshow-parallax=&quot;x: 200,0,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n          &lt;p uk-slideshow-parallax=&quot;x: 400,0,0;&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n        &lt;\\/div&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n    &lt;li&gt;\\r\\n      &lt;div class=&quot;uk-position-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-top&quot;&gt;\\r\\n        &lt;img src=&quot;media\\/image\\/demo\\/wood-table.jpg&quot; alt=&quot;&quot; uk-cover&gt;\\r\\n      &lt;\\/div&gt;\\r\\n      &lt;div class=&quot;uk-position-center uk-position-small uk-text-center&quot;&gt;\\r\\n        &lt;h2 uk-slideshow-parallax=&quot;y: -50,0,0; opacity: 1,1,0&quot;&gt;Heading&lt;\\/h2&gt;\\r\\n        &lt;p uk-slideshow-parallax=&quot;y: 50,0,0; opacity: 1,1,0&quot;&gt;Lorem ipsum dolor sit amet.&lt;\\/p&gt;\\r\\n      &lt;\\/div&gt;\\r\\n    &lt;\\/li&gt;\\r\\n  &lt;\\/ul&gt;\\r\\n\\r\\n  &lt;a class=&quot;uk-position-center-left uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-previous uk-slideshow-item=&quot;previous&quot;&gt;&lt;\\/a&gt;\\r\\n  &lt;a class=&quot;uk-position-center-right uk-position-small uk-hidden-hover&quot; href=&quot;#&quot; uk-slidenav-next uk-slideshow-item=&quot;next&quot;&gt;&lt;\\/a&gt;\\r\\n&lt;\\/div&gt;","description":"test","visibility":"usergroup","visibility_usergroups":["2","1"]}', 'public', 1, NOW(), NOW(), NULL, NULL);

DROP TABLE IF EXISTS `{DB_PREFIX}layout`;
CREATE TABLE `{DB_PREFIX}layout` (
  `layout_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `placements` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `custom_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}layout` (`layout_id`, `name`, `placements`, `custom_code`, `status`) VALUES
    (1, 'Default', '{"alpha":[],"topbar":[],"top":[],"sidebar_left":{"setting":{"node_child":"module"}},"content_top":[],"content_left":{"setting":{"node_child":"module"}},"content_right":{"setting":{"node_child":"module"}},"content_bottom":[],"sidebar_right":{"setting":{"node_child":"module"}},"bottom":[],"bottombar":[],"footer":[],"omega":[]}', '', 1),
    (2, 'Home', '{"alpha":[],"topbar":[],"top":{"setting":{"node_child":"row"},"rows":{"1":{"setting":{"container":"0"},"columns":{"col-hrpm7sa7svgd":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-6rs64urr0hsn":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"sidebar_left":{"setting":{"node_child":"module"}},"content_top":{"setting":{"node_child":"row"},"rows":{"row-nhnbo13qlmoq":{"setting":[],"columns":{"col-e1fsqh8gr88h":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-gqtlj81jjaf3":{"module_id":1,"codename":"codex","name":"Test"}}}}}}},"content_left":{"setting":{"node_child":"module"}},"content_right":{"setting":{"node_child":"module"}},"content_bottom":[],"sidebar_right":{"setting":{"node_child":"module"}},"bottom":[],"bottombar":[],"footer":[],"omega":[]}', '&lt;style&gt;\r\n  .element {\r\n    background: #d00;\r\n  }\r\n&lt;/style&gt;\r\n&lt;script&gt;console.log(&#039;cool&#039;)&lt;/script&gt;', 1),
    (6, 'Account', '{"alpha":[],"topbar":{"setting":{"node_child":"row"},"rows":{"row-2ug25fi9vjok":{"setting":[],"columns":{"col-r6k3l0pphfq2":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-fl3gdmvqdjkp":{"module_id":1,"codename":"codex","name":"Test"}}},"col-r3fd0p4f32vn":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-44ja9f30ctmc":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"top":{"setting":{"node_child":"row"},"rows":{"row-thch3s5anq6s":{"setting":{"child_width":"uk-child-width-1-3"},"columns":{"col-skiepg0eqn4k":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-p024kep8oq59":{"module_id":2,"codename":"codex","name":"Slideshow"}}},"col-t8fbrug9javj":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-pfn6ru99uon4":{"module_id":3,"codename":"codex","name":"Slideshow wysiwyg"}}}}}}},"sidebar_left":{"setting":{"node_child":"module"},"rows":{"mod-q9gp8c4338mu":{"module_id":2,"codename":"codex","name":"Slideshow"},"mod-ht161co7bv34":{"module_id":1,"codename":"codex","name":"Test"}}},"content_top":{"setting":{"node_child":"row"},"rows":{"row-i0odcepm6k4s":{"setting":{"child_width":"uk-child-width-1-2"},"columns":{"col-dn3baegtrgnb":{"setting":{"width":""},"modules":{"mod-pj00d8l3m13l":{"module_id":1,"codename":"codex","name":"Test"}}}}}}},"content_left":{"setting":{"node_child":"module"},"rows":{"rowmod-1gsag3bh140d":{"module_id":2,"codename":"codex","name":"Slideshow"}}},"content_right":{"setting":{"node_child":"module"},"rows":{"mod-72chgacinbqd":{"module_id":1,"codename":"codex","name":"Test"}}},"content_bottom":{"setting":{"node_child":"row"},"rows":{"row-gi759ko5qclm":{"setting":[],"columns":{"col-kltse6vj068k":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-c1t8sut9acmo":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"sidebar_right":{"setting":{"node_child":"module"},"rows":{"mod-v3uj99akgro7":{"module_id":2,"codename":"codex","name":"Slideshow"}}},"bottom":{"setting":{"node_child":"row"},"rows":{"row-ncp879cdbmns":{"setting":[],"columns":{"col-9bbgb3f6qie2":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-gf5id45uhnch":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"bottombar":{"setting":{"node_child":"row"},"rows":{"row-r0r7bkkupjm0":{"setting":[],"columns":{"col-p64nrnmdnq0r":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-s50q8vv423sb":{"module_id":2,"codename":"codex","name":"Slideshow"}}}}}}},"footer":{"setting":{"node_child":"row"},"rows":{"row-bsp3phek024b":{"setting":[],"columns":{"col-bh04lddpelei":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-l1mldh5bf4to":{"module_id":1,"codename":"codex","name":"Test"}}}}}}},"omega":{"setting":{"node_child":"row"},"rows":{"row-r22gi0mq12j7":{"setting":[],"columns":{"col-8ufcm97l7vdj":{"setting":{"width":"uk-width-1-2"},"modules":{"mod-o3i9rjhqvr3b":{"module_id":3,"codename":"codex","name":"Slideshow wysiwyg"}}}}}}}}', '', 1),
    (8, 'Contact', '{"alpha":[],"topbar":[],"top":[],"sidebar_left":{"setting":{"node_child":"module"}},"content_top":[],"content_left":{"setting":{"node_child":"module"}},"content_right":{"setting":{"node_child":"module"}},"content_bottom":[],"sidebar_right":{"setting":{"node_child":"module"}},"bottom":[],"bottombar":[],"footer":[],"omega":[]}', '', 1),
    (9, 'Sitemap', '', '', 1),
    (11, 'Information', '{"alpha":[],"topbar":[],"top":[],"sidebar_left":{"setting":{"node_child":"module"}},"content_top":[],"content_left":{"setting":{"node_child":"module"}},"content_right":{"setting":{"node_child":"module"}},"content_bottom":[],"sidebar_right":{"setting":{"node_child":"module"}},"bottom":[],"bottombar":[],"footer":[],"omega":[]}', '', 1),
    (12, 'Info About', '', '', 0);

DROP TABLE IF EXISTS `{DB_PREFIX}layout_module`;
CREATE TABLE `{DB_PREFIX}layout_module` (
  `layout_module_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint unsigned NOT NULL DEFAULT '0',
  `extension_module_id` bigint unsigned NOT NULL DEFAULT '0',
  `position` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci COMMENT='TODO: remove';

INSERT INTO `{DB_PREFIX}layout_module` (`layout_module_id`, `layout_id`, `extension_module_id`, `position`, `sort_order`) VALUES
    (66, 2, 1, 'content_top', 1),
    (67, 2, 2, 'top', 1);

DROP TABLE IF EXISTS `{DB_PREFIX}layout_route`;
CREATE TABLE `{DB_PREFIX}layout_route` (
  `layout_route_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `layout_id` bigint unsigned NOT NULL DEFAULT '0',
  `site_id` bigint unsigned NOT NULL DEFAULT '0',
  `route` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `url_params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `exclude` tinyint(1) NOT NULL DEFAULT '0',
  `priority` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`layout_route_id`)
) ENGINE=InnoDB AUTO_INCREMENT=545 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}layout_route` (`layout_route_id`, `layout_id`, `site_id`, `route`, `url_params`, `exclude`, `priority`) VALUES
    (32, 9, 0, 'information/sitemap', '', 0, 0),
    (59, 9, 1, 'information/sitemap', '', 0, 0),
    (486, 9, 3, 'information/sitemap', '', 0, 0),
    (496, 2, 0, 'page/home', '', 0, 0),
    (497, 2, 1, 'page/home', '', 0, 0),
    (524, 8, 0, 'information/contact', '', 0, 0),
    (525, 8, 1, 'information/contact', '', 0, 0),
    (526, 8, 3, 'information/contact', '', 0, 0),
    (527, 8, 0, 'information/contact/us', '', 0, 0),
    (528, 1, 0, '*', '', 0, -255),
    (529, 1, 1, '*', '', 0, -255),
    (530, 1, 3, '*', '', 0, -255),
    (531, 11, 0, 'information/information', '', 0, 0),
    (532, 11, 0, 'information/information', '', 0, 0),
    (533, 11, 1, 'information/information', 'information_id=1', 0, 0),
    (534, 11, 3, 'information/information', '', 0, 0),
    (535, 11, 3, 'information/information', '', 0, 0),
    (542, 6, 0, 'account/logout', '', 1, 11),
    (543, 6, 0, 'account/edit', 'user_id=1', 1, 1),
    (544, 6, 0, 'account/*', '', 0, 0);

DROP TABLE IF EXISTS `{DB_PREFIX}post`;
CREATE TABLE `{DB_PREFIX}post` (
  `post_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `term_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT 'default category',
  `visibility` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'public' COMMENT 'public, usergroup, password',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `status` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'draft' COMMENT 'publish, pending, draft, trash',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `publish` datetime DEFAULT NULL,
  `unpublish` datetime DEFAULT NULL,
  PRIMARY KEY (`post_id`) USING BTREE,
  KEY `taxonomy_status_publish` (`taxonomy`,`status`,`publish`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}post` (`post_id`, `parent_id`, `taxonomy`, `user_id`, `term_id`, `visibility`, `sort_order`, `status`, `created`, `updated`, `publish`, `unpublish`) VALUES
    (1, 0, 'content_post', 1, 17, 'usergroup', 0, 'publish', NOW(), NOW(), NULL, NULL),
    (2, 0, 'content_post', 1, 17, 'public', 0, 'publish', NOW(), NOW(), DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 30 DAY)),
    (3, 0, 'content_post', 1, 18, 'public', 0, 'publish', NOW(), NOW(), NULL, NULL),
    (4, 0, 'content_post', 1, 17, 'public', 0, 'publish', NOW(), NOW(), NULL, NULL),
    (5, 0, 'content_post', 1, 1, 'public', 0, 'publish', NOW(), NOW(), NULL, NULL),
    (6, 0, 'content_post', 1, 18, 'public', 0, 'publish', NOW(), NOW(), DATE_SUB(NOW(), INTERVAL 3 DAY), NULL),
    (7, 0, 'content_post', 1, 1, 'public', 0, 'publish', NOW(), NOW(), NULL, NULL),
    (8, 0, 'content_post', 1, 17, 'public', 0, 'draft', NOW(), NOW(), NULL, NULL),
    (9, 0, 'content_post', 1, 0, 'public', 0, 'draft', NOW(), NOW(), NULL, NULL),
    (10, 0, 'content_post', 1, 1, 'public', 0, 'publish', NOW(), NOW(), DATE_SUB(NOW(), INTERVAL 7 DAY), NULL);

DROP TABLE IF EXISTS `{DB_PREFIX}post_content`;
CREATE TABLE `{DB_PREFIX}post_content` (
  `post_id` bigint unsigned NOT NULL,
  `language_id` bigint unsigned NOT NULL COMMENT 'extension.extension_id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`post_id`,`language_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}post_content` (`post_id`, `language_id`, `title`, `excerpt`, `content`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
    (1, 35, 'Cinema Display VESA Mount Adapter kit', '&lt;p&gt;The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&#039;s no limit to what you can achieve.&lt;/p&gt;', '&lt;p&gt;The 30-inch&amp;nbsp;&lt;span style=&quot;background-color:hsl(0,75%,60%);color:hsl(0,0%,100%);&quot;&gt; Apple Cinema HD Display&amp;nbsp;&lt;/span&gt; delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&#039;s no limit to what you can achieve.&amp;nbsp;&lt;/p&gt;&lt;blockquote&gt;&lt;p&gt;The Cinema HD features an active-matrix liquid crystal display that produces flicker-free images that deliver twice the brightness, twice the sharpness and twice the contrast ratio of a typical CRT display.&amp;nbsp;&lt;/p&gt;&lt;/blockquote&gt;&lt;figure class=&quot;image image_resized&quot; style=&quot;width:55.32%;&quot;&gt;&lt;img src=&quot;https://localhost/mdzGit/shift/public/media/image/demo/banners/iphone68.jpg&quot; alt=&quot;iphone68.jpg&quot;&gt;&lt;/figure&gt;&lt;p&gt;Unlike other flat panels, it&#039;s designed with a pure digital interface to deliver distortion-free images that never need adjusting. With over 4 million digital pixels, the display is uniquely suited for scientific and technical applications such as visualizing molecular structures or analyzing geological data.&amp;nbsp;&amp;nbsp;&lt;/p&gt;&lt;figure class=&quot;image image_resized&quot; style=&quot;width:37.16%;&quot;&gt;&lt;img src=&quot;https://localhost/mdzGit/shift/public/media/image/demo/minimalist-dark-table.jpg&quot; alt=&quot;minimalist-dark-table.jpg&quot;&gt;&lt;/figure&gt;&lt;p&gt;Offering accurate, brilliant color performance, the Cinema HD delivers up to 16.7 million colors across a wide gamut allowing you to see subtle nuances between colors from soft pastels to rich jewel tones. A wide viewing angle ensures uniform color from edge to edge. Apple&#039;s ColorSync technology allows you to create custom profiles to maintain consistent color onscreen and in print. The result: You can confidently use this display in all your color-critical applications.&amp;nbsp;&lt;/p&gt;&lt;p&gt;Housed in a new aluminum design, the display has a very thin bezel that enhances visual accuracy. Each display features two FireWire 400 ports and two USB 2.0 ports, making attachment of desktop peripherals, such as iSight, iPod, digital and still cameras, hard drives, printers, and scanners, even more accessible and convenient. Taking advantage of the much thinner and lighter footprint of an LCD, the new displays support the VESA (Video Electronics Standards Association) mounting interface standard. Customers with the optional Cinema Display VESA Mount Adapter kit gain the flexibility to mount their display in locations most appropriate for their work environment.&amp;nbsp;&lt;/p&gt;&lt;p&gt;The Cinema HD features a single cable design with elegant breakout for the USB 2.0, FireWire 400 and a pure digital connection using the industry standard Digital Video Interface (DVI) interface. The DVI connection allows for a direct pure-digital connection.&lt;/p&gt;&lt;p&gt;Features:&lt;/p&gt;&lt;p&gt;Unrivaled display performance&lt;/p&gt;&lt;ul&gt;&lt;li&gt;30-inch (viewable) active-matrix liquid crystal display provides breathtaking image quality and vivid, richly saturated color.&lt;/li&gt;&lt;li&gt;Support for 2560-by-1600 pixel resolution for display of high definition still and video imagery.&lt;/li&gt;&lt;li&gt;Wide-format design for simultaneous display of two full pages of text and graphics.&lt;/li&gt;&lt;li&gt;Industry standard DVI connector for direct attachment to Mac- and Windows-based desktops and notebooks&lt;/li&gt;&lt;li&gt;Incredibly wide (170 degree) horizontal and vertical viewing angle for maximum visibility and color performance.&lt;/li&gt;&lt;li&gt;Lightning-fast pixel response for full-motion digital video playback.&lt;/li&gt;&lt;li&gt;Support for 16.7 million saturated colors, for use in all graphics-intensive applications.&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;Simple setup and operation&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Single cable with elegant breakout for connection to DVI, USB and FireWire ports&lt;/li&gt;&lt;li&gt;Built-in two-port USB 2.0 hub for easy connection of desktop peripheral devices.&lt;/li&gt;&lt;li&gt;Two FireWire 400 ports to support iSight and other desktop peripherals&lt;/li&gt;&lt;/ul&gt;', '', '', ''),
    (1, 36, 'Cinema Display VESA Mount Adapter kit ID', '', '', '', '', ''),
    (2, 35, 'Tempor duis velit ex magna magna consectetur', '&lt;p&gt;Tempor duis velit ex magna magna consectetur. Labore non cupidatat quis amet aute commodo quis. Esse deserunt laboris enim magna occaecat tempor deserunt id. Do commodo consequat pariatur aliqua nostrud laborum aute in proident. Do sunt exercitation fugiat sit exercitation velit eiusmod tempor laborum.&lt;/p&gt;', '&lt;p&gt;Tempor duis velit ex magna magna consectetur. Labore non cupidatat quis amet aute commodo quis. Esse deserunt laboris enim magna occaecat tempor deserunt id. Do commodo consequat pariatur aliqua nostrud laborum aute in proident. Do sunt exercitation fugiat sit exercitation velit eiusmod tempor laborum.&lt;/p&gt;&lt;p&gt;Eiusmod Lorem nulla ad voluptate dolor eu. Labore proident sint in laborum. Culpa excepteur occaecat culpa sunt eu esse. Nisi et minim cupidatat dolore fugiat minim dolor aliquip. Incididunt tempor nisi aute exercitation duis. Elit eiusmod consequat incididunt cillum id eiusmod aute mollit. Cillum ad adipisicing labore quis cupidatat. Aute reprehenderit tempor aliqua enim aliqua fugiat. Aute velit elit incididunt nostrud. Nulla cillum qui veniam quis duis fugiat minim in dolor. Sint aliqua reprehenderit eiusmod sunt. Do anim excepteur qui qui pariatur labore tempor do qui. Irure voluptate deserunt duis elit laboris Lorem nulla aute tempor. Esse ex veniam id quis anim dolore incididunt ad exercitation.&lt;/p&gt;&lt;p&gt;Laboris labore elit dolore irure aliquip ipsum do ex. Elit dolore sit occaecat deserunt. Laborum aute officia aliquip officia irure. Consectetur sit sint est sint in labore mollit cillum ad. Ex nulla magna fugiat aliqua elit ad. Dolor pariatur fugiat tempor labore Lorem. Aliqua enim laboris ullamco id ex. Cillum eu enim do id excepteur eu aliquip est officia. Reprehenderit est veniam adipisicing do et dolor et. Et laborum nulla anim excepteur quis ipsum. Non velit nulla sint excepteur. Aliqua id reprehenderit dolor ullamco sit.&lt;/p&gt;&lt;p&gt;Cupidatat sunt officia cupidatat cupidatat excepteur quis quis excepteur. Anim qui ullamco irure excepteur sint eiusmod mollit dolor. Enim et est dolor sit proident culpa pariatur. Fugiat velit elit aliquip aliquip deserunt. Officia est esse aliqua veniam. Dolore sint minim nisi ex consequat. Consequat sit esse adipisicing qui quis. Do in eiusmod excepteur cupidatat id. Sint aliquip dolore sit cillum cupidatat ullamco laborum anim.&lt;/p&gt;&lt;p&gt;Dolor laboris dolor nisi anim aliqua nostrud consequat. Nulla dolor voluptate minim qui reprehenderit ut commodo ea ut. Amet enim esse dolore aliquip dolor aute commodo aliqua. In elit veniam ex quis laborum magna. Amet mollit quis occaecat magna cillum. Sit occaecat exercitation et velit in est sint. Commodo commodo ea excepteur fugiat qui deserunt minim. Aliquip mollit quis est tempor commodo laborum. Quis nostrud mollit incididunt nulla ea tempor nostrud ea. Proident aute et mollit aute adipisicing laborum. Sunt eu excepteur aliqua aliqua. Excepteur in ullamco consequat ut dolore ad.&lt;/p&gt;', '', '', ''),
    (2, 36, 'Tempor duis velit ex magna magna consectetur', '', '', '', '', ''),
    (3, 35, 'Fugiat do elit dolore culpa ex adipisicing quis', '', '&lt;p&gt;Fugiat do elit dolore culpa ex adipisicing quis. Cillum deserunt ad dolor occaecat ipsum in sint fugiat. Incididunt velit reprehenderit veniam sunt voluptate quis qui consectetur fugiat. Aliqua quis reprehenderit officia proident incididunt velit commodo eu. Qui aliqua minim tempor nulla nisi Lorem. Non reprehenderit et amet tempor sunt et ex aute. Eu mollit officia adipisicing eiusmod consequat. Occaecat officia Lorem esse laborum ut. Eu consectetur cupidatat adipisicing magna labore mollit nostrud velit aliqua. Consequat culpa sunt culpa consequat qui consequat dolore voluptate. Fugiat reprehenderit proident dolor laboris consectetur cillum deserunt qui. Est laborum occaecat deserunt incididunt labore occaecat. Non ex aliqua nulla sit consectetur enim.&lt;/p&gt;&lt;p&gt;Aute exercitation voluptate elit excepteur deserunt pariatur qui incididunt. Reprehenderit id ea commodo enim excepteur officia. Voluptate ut sunt qui ex quis culpa magna est sint. Eu duis sint aliqua enim pariatur. Commodo voluptate nisi tempor cillum quis deserunt elit sint enim. Sit pariatur ea consectetur pariatur dolore est in enim. Velit elit officia Lorem culpa. Dolore velit pariatur consectetur proident sit. Voluptate adipisicing esse nulla incididunt. Nulla aliquip magna ipsum deserunt quis. Sunt Lorem est velit pariatur minim nostrud amet sint ex. Sit aute anim fugiat deserunt ad cillum. Ex dolore aliqua esse et quis veniam irure non. Dolore officia Lorem ipsum anim est est veniam amet cupidatat. Enim labore fugiat ex elit sint aute.&lt;/p&gt;&lt;p&gt;Excepteur esse labore laboris consectetur. Commodo eu sunt esse laborum mollit occaecat voluptate. Magna id ad mollit sint minim velit. Ex et officia sit nostrud irure consectetur cupidatat duis. Qui elit commodo proident enim laboris sit Lorem. Officia est amet in id nulla incididunt excepteur. Labore magna eu excepteur laboris sunt velit commodo consectetur. Mollit esse consectetur dolore fugiat laborum. Aute Lorem incididunt velit aliquip consectetur laboris est non id. Dolor cillum excepteur fugiat sit commodo adipisicing mollit. Sit sit quis aliquip officia velit commodo dolore eu ea. Ea magna veniam pariatur deserunt proident labore. Cupidatat laborum nostrud reprehenderit enim dolore excepteur fugiat. Minim aliquip consectetur aliquip laboris id anim aliquip.&lt;/p&gt;&lt;p&gt;Est sit anim voluptate nisi ut laborum exercitation adipisicing enim. In commodo anim excepteur et tempor consectetur nostrud eiusmod. Ex exercitation non voluptate consequat ullamco esse velit. Deserunt dolore amet sunt adipisicing nulla tempor. Consectetur pariatur voluptate velit occaecat consequat. Ex ex sunt officia culpa laboris minim magna Lorem. Anim ullamco dolor consequat ea mollit pariatur. Incididunt exercitation dolore eu ut elit incididunt. Amet labore esse anim dolore tempor. Exercitation officia voluptate qui et sint. Lorem et ullamco consequat ea fugiat irure ea dolore labore. Excepteur ex enim eu Lorem anim anim ipsum eu consequat. Enim nisi quis anim ullamco officia non consectetur.&lt;/p&gt;&lt;p&gt;Tempor excepteur occaecat reprehenderit velit irure ipsum nisi. Veniam cupidatat officia ea eiusmod veniam officia fugiat. Cillum id reprehenderit dolore minim irure mollit. Laboris aute laborum mollit sint ut excepteur exercitation eiusmod excepteur. Ex commodo eiusmod exercitation eiusmod amet. Ad aliquip nostrud et do consectetur magna. Ut duis officia exercitation adipisicing nisi cupidatat aute occaecat anim. Eiusmod ad irure ad pariatur consectetur aute tempor pariatur incididunt. Voluptate nisi aliqua reprehenderit incididunt proident. Eu est enim laborum ullamco voluptate mollit. Proident irure commodo commodo labore fugiat dolore. Occaecat in aliquip minim commodo nulla veniam velit.&lt;/p&gt;', '', '', ''),
    (3, 36, 'Fugiat do elit dolore culpa ex adipisicing quis', '', '', '', '', ''),
    (4, 35, 'Veniam eiusmod voluptate eu excepteur', '&lt;p&gt;Veniam eiusmod voluptate eu excepteur laboris aute id. Velit excepteur commodo labore eiusmod eu est aliquip fugiat nulla. Consequat incididunt sint proident excepteur veniam. Dolor culpa officia elit non. Excepteur dolor enim ad adipisicing consequat deserunt ut.&lt;/p&gt;', '&lt;p&gt;Veniam eiusmod voluptate eu excepteur laboris aute id. Velit excepteur commodo labore eiusmod eu est aliquip fugiat nulla. Consequat incididunt sint proident excepteur veniam. Dolor culpa officia elit non. Excepteur dolor enim ad adipisicing consequat deserunt ut.&lt;/p&gt;&lt;p&gt;Tempor ipsum ipsum Lorem veniam magna deserunt. Nulla amet Lorem elit consectetur Lorem commodo cillum dolore velit. Eiusmod culpa magna commodo laboris duis aliquip dolor et. Voluptate exercitation excepteur officia quis culpa deserunt ipsum sunt incididunt. Ullamco eu sit enim aute reprehenderit. Quis ipsum non ex Lorem sit cillum sunt duis do. Aliquip exercitation non sit exercitation excepteur do enim adipisicing pariatur. Quis adipisicing veniam pariatur sit consequat esse qui in. Culpa ea quis in ipsum pariatur. Duis sunt consectetur labore id minim fugiat occaecat in.&lt;/p&gt;&lt;p&gt;Velit sint proident commodo ad do aliqua ipsum non. Mollit velit tempor ipsum nulla amet. Velit et minim ipsum adipisicing qui. Sunt nisi eu non ex. Ex velit pariatur consectetur pariatur ad. Ea culpa cupidatat exercitation incididunt anim deserunt ea eu dolor. Aliqua sunt ea do minim nostrud deserunt magna veniam laboris.&lt;/p&gt;&lt;p&gt;Est laborum minim ullamco exercitation Lorem irure culpa non. Consequat sunt et velit qui tempor. Sit sint ad et laborum. Excepteur consequat do duis dolore minim aliqua. Ea enim aute dolor et veniam fugiat. Non sit non in adipisicing reprehenderit nulla do ut. Esse amet proident reprehenderit nulla Lorem. Irure in anim velit in in proident do id et. Ut nisi veniam culpa exercitation sint ipsum. Irure do laborum dolore elit voluptate veniam. Id non do laborum sit deserunt tempor excepteur. Ea esse nisi pariatur id mollit laboris duis velit. Pariatur consequat ipsum occaecat laboris et tempor elit aliqua consequat.&lt;/p&gt;&lt;p&gt;Aliquip aliquip dolor ea pariatur voluptate minim. Proident dolor quis amet non et. Qui consequat qui est nostrud. Laboris incididunt ad laborum ex aliqua ut incididunt. Aute nulla ullamco enim id pariatur commodo sunt. Qui ad ad sunt pariatur ullamco sint Lorem nostrud. Consectetur ex velit incididunt nostrud ex non. Laborum velit adipisicing non aute cupidatat magna incididunt aliquip. Cupidatat magna commodo sunt duis anim mollit. Voluptate do dolore duis ea qui non. Ullamco nulla veniam et sint deserunt aliqua occaecat. Proident sunt est irure excepteur do. Proident fugiat et aute culpa cupidatat.&lt;/p&gt;', '', '', ''),
    (4, 36, 'Veniam eiusmod voluptate eu excepteur', '', '', '', '', ''),
    (5, 35, 'Consequat eu occaecat aliquip voluptate officia', '&lt;p&gt;Consequat eu occaecat aliquip voluptate officia. Reprehenderit dolore veniam aliqua ex tempor pariatur officia. Laborum labore ipsum adipisicing nisi ullamco enim. Sint ea consequat ullamco ullamco exercitation laborum occaecat non elit. Lorem aliqua sit veniam eiusmod proident anim. Exercitation tempor sunt occaecat fugiat excepteur ea nostrud est et. Magna et cupidatat officia tempor pariatur deserunt. Amet pariatur aliquip eiusmod ullamco duis velit elit.&lt;/p&gt;', '&lt;p&gt;Consequat eu occaecat aliquip voluptate officia. Reprehenderit dolore veniam aliqua ex tempor pariatur officia. Laborum labore ipsum adipisicing nisi ullamco enim. Sint ea consequat ullamco ullamco exercitation laborum occaecat non elit. Lorem aliqua sit veniam eiusmod proident anim. Exercitation tempor sunt occaecat fugiat excepteur ea nostrud est et. Magna et cupidatat officia tempor pariatur deserunt. Amet pariatur aliquip eiusmod ullamco duis velit elit.&lt;/p&gt;&lt;p&gt;Aliqua reprehenderit ut mollit dolor ex ex commodo. Labore exercitation et eiusmod cillum elit tempor est. Veniam et laboris duis non proident commodo cupidatat ut eu. Nisi excepteur commodo do culpa mollit. Tempor consectetur culpa ex veniam dolor voluptate. Lorem ut occaecat esse eu ea.&lt;/p&gt;&lt;p&gt;Labore officia ex cupidatat voluptate laborum laboris duis labore ullamco. Sint enim sint pariatur esse cillum incididunt. Ullamco deserunt commodo et excepteur occaecat deserunt voluptate est. Dolore velit ad non Lorem. Culpa elit ut laboris exercitation aliquip reprehenderit non et. Enim aute sunt dolor exercitation reprehenderit cillum cillum reprehenderit ea. Anim dolor culpa cupidatat sit duis Lorem nulla reprehenderit.&lt;/p&gt;&lt;p&gt;Occaecat sit ea veniam veniam fugiat. Do commodo est aliqua nostrud aliquip minim est in. Ipsum Lorem voluptate tempor velit deserunt veniam sunt. Aliqua veniam anim ipsum proident nostrud fugiat qui. Sunt exercitation nisi magna occaecat sunt fugiat proident proident pariatur. Exercitation eiusmod nulla adipisicing ullamco nisi est. Anim ut aliqua sit officia pariatur culpa in minim tempor. Non ad exercitation cillum aute deserunt qui magna quis fugiat. Nisi proident nulla nulla commodo nulla nisi deserunt labore sit. Ullamco Lorem proident duis commodo eu aute eu quis ipsum.&lt;/p&gt;&lt;p&gt;Nisi consequat ad officia dolor in adipisicing. Id culpa eiusmod reprehenderit mollit adipisicing sint adipisicing. Cillum velit mollit ad non aliquip voluptate. Exercitation quis eu ullamco minim Lorem. Labore nulla occaecat excepteur minim culpa officia. Aliquip do reprehenderit sit excepteur veniam.&lt;/p&gt;&lt;p&gt;Fugiat aute dolore duis sint irure qui. In aliqua et mollit amet eiusmod. Amet pariatur quis ad velit aliquip quis aliqua enim aliqua. Id est nostrud in veniam ad. Laboris aliquip dolore voluptate aliquip cillum enim. Eu consequat culpa ex non ad esse voluptate quis nostrud. Ipsum laborum nostrud amet enim magna. Ipsum reprehenderit deserunt id adipisicing nulla esse.&lt;/p&gt;&lt;p&gt;Lorem aute adipisicing excepteur id ad exercitation minim eiusmod. Voluptate est ea elit consequat fugiat nisi. Lorem proident sint qui dolor eu pariatur et ad minim. Eu duis reprehenderit proident culpa cupidatat commodo. Adipisicing minim ullamco tempor sint elit elit minim sint laboris. Exercitation aliqua cupidatat nostrud esse adipisicing mollit quis occaecat. Exercitation cupidatat ad Lorem non esse fugiat commodo. Minim do mollit voluptate est.&lt;/p&gt;&lt;p&gt;Ullamco sunt elit exercitation sunt laborum non sit. Anim esse anim pariatur velit Lorem id velit aute. Veniam irure anim nulla commodo in Lorem reprehenderit aute reprehenderit. Adipisicing est sunt enim est eu. Qui cupidatat cillum eiusmod dolore.&lt;/p&gt;', '', '', ''),
    (5, 36, 'Consequat eu occaecat aliquip voluptate officia', '', '', '', '', ''),
    (6, 35, 'Labore mollit dolor cillum esse ullamco enim', '&lt;p&gt;Labore était mollit dolor cillum &lt;i&gt;&lt;strong&gt;esse ullamco&lt;/strong&gt;&lt;/i&gt; enim. Enim ea enim exercitation dolore consectetur amet. Irure et sit incididunt magna amet ullamco. Minim aliquip cupidatat aute tempor sunt deserunt.&lt;/p&gt;', '&lt;p&gt;Labore mollit dolor cillum esse ullamco enim. Enim ea enim exercitation dolore consectetur amet. Irure et sit incididunt magna amet ullamco. Minim aliquip cupidatat aute tempor sunt deserunt. Nisi sunt ad laborum qui et ut ea ullamco. Aliquip ullamco dolor fugiat Lorem do Lorem esse nisi id. Occaecat sint sunt sunt ex sunt officia. Reprehenderit minim nisi veniam anim consequat ut elit magna aliqua. Laborum tempor laboris dolor occaecat tempor. Nostrud voluptate mollit elit excepteur ea occaecat dolore.&lt;/p&gt;&lt;p&gt;Quis fugiat sint in aliquip excepteur minim nostrud. Minim cillum ad minim officia anim. Officia voluptate in sit duis culpa enim. Ea ullamco enim sit incididunt ipsum incididunt magna laboris. Non laboris minim tempor duis cupidatat sit fugiat ex consequat. Labore pariatur duis proident aute voluptate cupidatat anim aliquip. Veniam nulla mollit proident eu veniam esse labore et velit. Anim laboris eu pariatur pariatur consectetur adipisicing veniam in. Sit proident ad anim enim culpa anim Lorem consequat.&lt;/p&gt;&lt;p&gt;Consectetur enim voluptate commodo sunt magna minim. Est et pariatur quis reprehenderit. Incididunt aliquip nisi consectetur et do cupidatat consequat. Ea amet exercitation elit dolor ullamco. Enim culpa aute Lorem do. Voluptate cupidatat labore non laborum ullamco magna occaecat adipisicing. Cillum laborum excepteur deserunt irure sunt velit et.&lt;/p&gt;&lt;p&gt;Do occaecat minim labore non. Id eu consequat consequat Lorem anim eiusmod. Aliquip sit veniam id in. In excepteur eiusmod ipsum magna mollit eiusmod ex enim. Officia amet culpa aliqua mollit non do magna. Quis et fugiat aliqua enim aliqua eu ad sunt. Veniam ex elit ipsum enim.&lt;/p&gt;&lt;p&gt;Irure irure veniam aute ipsum ullamco sint. Ex officia esse veniam nisi magna excepteur in sunt. Anim aliqua cillum aute ad ea. Voluptate nulla quis veniam cillum labore culpa fugiat in Lorem. Ullamco deserunt voluptate consequat esse eiusmod magna cillum eiusmod. Esse et do sit reprehenderit. Est commodo laborum enim dolore.&lt;/p&gt;&lt;p&gt;Esse nostrud laboris reprehenderit minim enim in commodo tempor do. Aute dolor nostrud Lorem sunt commodo. Eu ullamco et irure voluptate Lorem enim sunt fugiat in. Culpa exercitation in exercitation occaecat minim magna culpa laboris. Proident amet amet exercitation do.&lt;/p&gt;&lt;p&gt;Occaecat cillum occaecat cupidatat sint ut magna officia. Voluptate nostrud fugiat consequat non irure in quis. Mollit fugiat aute sint qui aliqua. Amet nisi ipsum veniam adipisicing enim. Et sunt reprehenderit in ea incididunt ex aliqua tempor. Dolore eiusmod ad pariatur anim amet proident incididunt dolor veniam.&lt;/p&gt;&lt;p&gt;Est officia Lorem Lorem cillum reprehenderit esse. Lorem sunt eu in mollit ipsum. Ad officia tempor officia duis nisi adipisicing. Aliqua dolore reprehenderit non eu nulla aute eiusmod. Nulla Lorem non Lorem occaecat deserunt et non. Esse ad aliqua exercitation fugiat. Consequat ex eiusmod sint amet tempor. Nostrud nulla nulla culpa eiusmod.&lt;/p&gt;', '', '', ''),
    (6, 36, 'Labore mollit dolor cillum esse ullamco enim', '', '', '', '', ''),
    (7, 35, 'Veniam ad duis do nisi cillum aliquip anim', '', '&lt;p&gt;Veniam ad duis do nisi &lt;i&gt;&lt;strong&gt;cillum aliquip&lt;/strong&gt;&lt;/i&gt; anim. Non esse consequat est eiusmod consequat non culpa. Incididunt magna amet incididunt id nulla. Non ea ea officia incididunt nulla excepteur do eu eu. Sunt laboris laboris commodo anim. Pariatur esse proident cupidatat id et aliqua anim velit. Do nulla aliqua ullamco dolore incididunt.&lt;/p&gt;&lt;p&gt;Reprehenderit reprehenderit aliqua laboris proident labore aliqua aute. Quis do ex ut ut quis. Aute sint eu do sit. Labore ea pariatur in excepteur ut duis. Occaecat cillum in sunt qui quis sint magna. Ex est proident ut aute. Commodo eu dolor duis Lorem amet mollit. Et aliquip dolor mollit nulla mollit. Enim nisi dolor sint magna irure nostrud elit excepteur ex.&lt;/p&gt;&lt;p&gt;Et velit fugiat eu reprehenderit non. Irure excepteur minim enim aute. Excepteur eu qui minim minim pariatur cillum. Consequat ad ut minim minim enim ex qui. Cillum excepteur consectetur ut non Lorem sunt. Ea magna ipsum ut non ipsum nisi. Lorem magna enim anim est officia velit. Officia elit in culpa dolor officia.&lt;/p&gt;&lt;p&gt;Cupidatat commodo magna ut esse labore. Exercitation laboris nisi id amet in nisi sit. Ex laborum exercitation tempor amet irure. Et laborum dolor laborum veniam quis elit. Aute eu magna reprehenderit nostrud. Veniam labore officia ea exercitation non velit aliquip anim laboris. Voluptate minim sunt consectetur adipisicing incididunt esse anim ullamco.&lt;/p&gt;&lt;p&gt;Consequat consectetur voluptate culpa voluptate minim ullamco ullamco et nisi. Cillum fugiat in tempor minim et nostrud ut. Dolore exercitation velit dolor laboris. Elit sunt laboris ea non ad esse aliquip voluptate adipisicing. Sit laborum sunt non voluptate mollit.&lt;/p&gt;&lt;p&gt;Deserunt nisi occaecat laborum proident. Ad anim adipisicing ut velit pariatur. Nostrud veniam elit sunt eu do Lorem magna culpa id. Adipisicing veniam mollit proident est eiusmod culpa proident. Lorem non dolor labore culpa culpa. Reprehenderit pariatur sit qui aute exercitation eiusmod. Irure exercitation reprehenderit adipisicing deserunt. Ut et amet ipsum sunt. Aute aliqua aliquip sit ullamco officia cillum. Sit voluptate labore occaecat aliqua deserunt.&lt;/p&gt;&lt;p&gt;Reprehenderit dolore nostrud nostrud nisi fugiat. Elit anim dolore laboris in dolor cillum est. In adipisicing laboris esse culpa excepteur irure eiusmod in dolore. Sit consectetur labore enim deserunt dolore laboris. In laborum occaecat reprehenderit laboris cillum velit nostrud voluptate culpa. Esse proident quis id eiusmod incididunt laboris.&lt;/p&gt;&lt;p&gt;Adipisicing nisi excepteur officia nulla consectetur anim esse. Fugiat id labore proident commodo ullamco eiusmod mollit. Enim adipisicing cillum occaecat velit nisi. Sunt Lorem elit eu do aute. Ipsum minim duis proident aliquip reprehenderit. Laborum cupidatat Lorem sint consectetur nulla cupidatat consectetur incididunt.&lt;/p&gt;', '', '', ''),
    (7, 36, 'Veniam ad duis do nisi cillum aliquip anim', '', '', '', '', ''),
    (8, 35, 'Velit eu dolore amet pariatur sunt dolor', '&lt;p&gt;Velit eu dolore amet pariatur sunt dolor. Adipisicing voluptate voluptate sunt labore. Mollit aliqua reprehenderit deserunt pariatur do. Cillum nulla veniam do Lorem laborum magna irure. Aliqua consectetur officia dolore eiusmod sunt. Minim anim pariatur laborum commodo Lorem ex esse Lorem voluptate. Dolore ex labore id aute non anim commodo do incididunt. Eiusmod eu incididunt ex esse quis. Culpa voluptate ad id duis excepteur.&lt;/p&gt;', '&lt;p&gt;Velit eu dolore amet pariatur sunt dolor. Adipisicing voluptate voluptate sunt labore. Mollit aliqua reprehenderit deserunt pariatur do. Cillum nulla veniam do Lorem laborum magna irure. Aliqua consectetur officia dolore eiusmod sunt. Minim anim pariatur laborum commodo Lorem ex esse Lorem voluptate. Dolore ex labore id aute non anim commodo do incididunt. Eiusmod eu incididunt ex esse quis. Culpa voluptate ad id duis excepteur.&lt;/p&gt;&lt;p&gt;Eiusmod est dolore Lorem anim est ad. Ad excepteur adipisicing deserunt eu anim aliquip. Sunt id consectetur reprehenderit et sit aliquip reprehenderit. Nostrud qui anim minim ut esse laborum. Culpa in id reprehenderit duis excepteur dolore laboris duis.&lt;/p&gt;&lt;p&gt;Ut exercitation labore voluptate amet tempor. Laborum dolore sunt consectetur quis commodo irure sunt aliqua incididunt. Laboris ullamco commodo officia velit in reprehenderit nisi id quis. Anim consectetur consectetur officia dolor exercitation exercitation incididunt mollit quis. Amet officia minim aliquip ad pariatur reprehenderit ea enim esse. Non enim sit cupidatat minim aliquip. Consequat laboris eu pariatur quis tempor esse. Aliqua cillum esse cillum Lorem est ipsum. Irure ullamco cupidatat dolor est labore do consequat aliqua exercitation.&lt;/p&gt;&lt;p&gt;Do sunt exercitation eiusmod est ex eiusmod. Laboris aute quis id id commodo veniam elit. Officia aliqua incididunt fugiat nisi incididunt elit est. Non culpa veniam sint anim dolor minim velit officia dolore. Voluptate excepteur velit id proident nostrud quis reprehenderit. Amet deserunt irure eu excepteur irure ad sint sint dolore. Irure exercitation enim nostrud labore cillum eiusmod. Quis cupidatat ut fugiat non in esse. In quis quis velit id aute.&lt;/p&gt;&lt;p&gt;Mollit ullamco mollit nisi esse. Aliqua non pariatur ut voluptate ad ut Lorem duis. Non voluptate laborum occaecat dolor commodo in. Elit ad non do id aliquip dolor dolore. Eu duis ipsum ullamco proident incididunt duis dolore. Excepteur sint deserunt excepteur exercitation ut aute. Lorem irure voluptate dolor in fugiat anim veniam aliquip occaecat.&lt;/p&gt;&lt;p&gt;Mollit sint sint duis cillum tempor minim ex fugiat consequat. Amet nisi magna enim sunt incididunt ut voluptate esse. Magna consequat irure aute aliquip aliquip excepteur culpa. Aliqua in dolore ea excepteur pariatur Lorem. Proident anim dolore non ullamco tempor cupidatat ut velit. Et minim ut amet sunt consectetur ut quis.&lt;/p&gt;&lt;p&gt;Proident est tempor duis quis sint exercitation amet. Officia proident occaecat officia cillum reprehenderit exercitation pariatur laborum. Adipisicing aute consequat nostrud nostrud ipsum veniam esse mollit mollit. Sit minim labore quis labore adipisicing cupidatat. Et ut aliquip proident magna. Enim Lorem in Lorem in adipisicing Lorem exercitation ea.&lt;/p&gt;&lt;p&gt;Ipsum ipsum do quis cupidatat pariatur dolor do voluptate amet. Commodo laboris commodo velit labore ullamco consectetur. Dolor proident anim velit Lorem est ad laboris elit. Commodo eiusmod anim do aliqua mollit cillum enim commodo. Sint cupidatat sunt incididunt esse in non aliqua. Non incididunt quis cupidatat Lorem.&lt;/p&gt;', '', '', ''),
    (8, 36, 'Velit eu dolore amet pariatur sunt dolor', '', '', '', '', ''),
    (9, 35, 'Esse officia qui elit labore laborum officia anim', '&lt;p&gt;Esse officia qui elit labore laborum officia anim. Deserunt laborum dolore pariatur enim aliqua aliqua incididunt cupidatat irure. Consectetur magna ad minim quis enim dolore. Consectetur ad ut cupidatat ex qui esse enim duis. Ex laboris consequat mollit ipsum duis adipisicing. Tempor ea esse aliqua labore aliqua eiusmod nulla. Ut ullamco anim cillum enim esse. Eiusmod eiusmod dolor ipsum sunt sit do.&lt;/p&gt;', '&lt;p&gt;Esse officia qui elit labore laborum officia anim. Deserunt laborum dolore pariatur enim aliqua aliqua incididunt cupidatat irure. Consectetur magna ad minim quis enim dolore. Consectetur ad ut cupidatat ex qui esse enim duis. Ex laboris consequat mollit ipsum duis adipisicing. Tempor ea esse aliqua labore aliqua eiusmod nulla. Ut ullamco anim cillum enim esse. Eiusmod eiusmod dolor ipsum sunt sit do.&lt;/p&gt;&lt;p&gt;Nisi sit minim laborum cillum veniam eiusmod consequat minim. Proident enim dolore velit excepteur. Deserunt aliqua magna officia occaecat in. Cupidatat ea fugiat ex exercitation. Aute officia esse non esse duis excepteur proident minim.&lt;/p&gt;&lt;p&gt;In sunt Lorem consequat laborum ad nisi esse. Velit ad do mollit non elit ea dolore officia consectetur. Dolor consequat eiusmod magna ex pariatur. Et deserunt proident magna amet elit eiusmod ipsum. Excepteur commodo commodo nulla tempor in eiusmod laborum laborum. Deserunt mollit quis in qui labore ad reprehenderit. Amet duis ex exercitation labore duis. Mollit dolore consequat Lorem elit aliquip fugiat incididunt pariatur. Excepteur pariatur eu commodo id ipsum ipsum.&lt;/p&gt;&lt;p&gt;Aute velit eiusmod ipsum dolore minim. Ut consequat dolor deserunt sit reprehenderit commodo occaecat pariatur. Sit culpa ea ullamco pariatur sit. Incididunt adipisicing reprehenderit aliqua non minim pariatur et qui. Eu mollit tempor aute exercitation laboris in sint commodo. Lorem qui veniam et elit aute cupidatat velit labore. Labore in sunt adipisicing exercitation velit. Sit enim officia mollit et mollit mollit et. Consectetur velit excepteur cillum voluptate dolor adipisicing commodo elit aute. Proident elit et nisi proident.&lt;/p&gt;&lt;p&gt;Et amet duis culpa consequat proident dolore exercitation quis dolor. Sint incididunt aliquip cillum commodo excepteur. Deserunt non eiusmod pariatur sint tempor veniam in cupidatat. Fugiat magna cupidatat exercitation est dolor amet. Tempor ad nisi sunt officia magna consequat qui est.&lt;/p&gt;&lt;p&gt;Deserunt pariatur amet laboris commodo eu deserunt ex adipisicing nulla. Mollit nostrud ad consequat exercitation aute ad. Excepteur nulla consectetur Lorem ea enim anim exercitation magna. Quis consequat et pariatur pariatur. Aliquip duis anim in incididunt nisi eiusmod velit labore ex.&lt;/p&gt;&lt;p&gt;Officia magna adipisicing enim magna. Tempor veniam cillum eiusmod ad. Veniam magna laborum exercitation ullamco aliquip incididunt. Fugiat ipsum duis in sit aliqua eu. Dolore eu fugiat cillum ea labore. Sint amet mollit mollit labore et ad. Irure exercitation aliquip ullamco laborum pariatur anim qui nulla deserunt. Aliqua voluptate deserunt exercitation adipisicing adipisicing id aute sit. Aliqua laborum esse laborum cillum. Labore deserunt cupidatat aliquip Lorem ipsum velit cillum.&lt;/p&gt;&lt;p&gt;Voluptate non aute cillum nostrud et consequat ad ea anim. Labore ut cillum nostrud id culpa id ad reprehenderit. Laborum culpa occaecat adipisicing laborum sint nostrud proident consectetur. Fugiat cupidatat nulla consequat labore tempor. Aute mollit pariatur non nostrud duis labore velit aute duis. Laborum laboris dolore aliquip occaecat reprehenderit deserunt cupidatat reprehenderit. Non ex consectetur velit enim.&lt;/p&gt;', '', '', ''),
    (9, 36, 'Esse officia qui elit labore laborum officia anim', '', '', '', '', ''),
    (10, 35, 'About Us', '', '&lt;p&gt;About us - content EN&lt;/p&gt;', '', '', ''),
    (10, 36, 'About Us', '', '&lt;p&gt;About us - content ID&lt;/p&gt;', '', '', '');

DROP TABLE IF EXISTS `{DB_PREFIX}post_meta`;
CREATE TABLE `{DB_PREFIX}post_meta` (
  `post_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_meta_id`) USING BTREE,
  KEY `post_id` (`post_id`) USING BTREE,
  KEY `key` (`key`(191)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}post_meta` (`post_meta_id`, `post_id`, `key`, `value`, `encoded`) VALUES
    (610, 1, 'visibility_usergroups', '["1"]', 1),
    (611, 1, 'visibility_password', 'coolpass', 0),
    (612, 1, 'image', 'image/demo/wood-table.jpg', 0),
    (613, 1, 'robots', 'noindex, nofollow', 0),
    (614, 1, 'comment', '', 0),
    (615, 1, 'custom_code', '', 0),
    (700, 4, 'visibility_usergroups', '[]', 1),
    (701, 4, 'visibility_password', '', 0),
    (702, 4, 'image', 'image/no-image.png', 0),
    (703, 4, 'robots', '', 0),
    (704, 4, 'comment', '', 0),
    (705, 4, 'custom_code', '', 0),
    (814, 8, 'visibility_usergroups', '[]', 1),
    (815, 8, 'visibility_password', '', 0),
    (816, 8, 'image', 'image/no-image.png', 0),
    (817, 8, 'robots', '', 0),
    (818, 8, 'comment', '', 0),
    (819, 8, 'custom_code', '', 0),
    (820, 9, 'visibility_usergroups', '[]', 1),
    (821, 9, 'visibility_password', '', 0),
    (822, 9, 'image', 'image/no-image.png', 0),
    (823, 9, 'robots', '', 0),
    (824, 9, 'comment', '', 0),
    (825, 9, 'custom_code', '', 0),
    (952, 5, 'visibility_usergroups', '[]', 1),
    (953, 5, 'visibility_password', '', 0),
    (954, 5, 'image', 'image/no-image.png', 0),
    (955, 5, 'robots', '', 0),
    (956, 5, 'comment', '', 0),
    (957, 5, 'custom_code', '', 0),
    (1036, 6, 'visibility_usergroups', '[]', 1),
    (1037, 6, 'visibility_password', '', 0),
    (1038, 6, 'image', 'image/no-image.png', 0),
    (1039, 6, 'robots', '', 0),
    (1040, 6, 'comment', '', 0),
    (1041, 6, 'custom_code', '', 0),
    (1042, 7, 'visibility_usergroups', '[]', 1),
    (1043, 7, 'visibility_password', '', 0),
    (1044, 7, 'image', 'image/no-image.png', 0),
    (1045, 7, 'robots', '', 0),
    (1046, 7, 'comment', '', 0),
    (1047, 7, 'custom_code', '', 0),
    (1048, 3, 'visibility_usergroups', '[]', 1),
    (1049, 3, 'visibility_password', '', 0),
    (1050, 3, 'image', 'image/no-image.png', 0),
    (1051, 3, 'robots', '', 0),
    (1052, 3, 'comment', '', 0),
    (1053, 3, 'custom_code', '', 0),
    (1054, 2, 'visibility_usergroups', '[]', 1),
    (1055, 2, 'visibility_password', '', 0),
    (1056, 2, 'image', 'image/no-image.png', 0),
    (1057, 2, 'robots', '', 0),
    (1058, 2, 'comment', '', 0),
    (1059, 2, 'custom_code', '', 0),
    (1126, 10, 'visibility_usergroups', '[]', 1),
    (1127, 10, 'visibility_password', '', 0),
    (1128, 10, 'image', 'image/no-image.png', 0),
    (1129, 10, 'robots', '', 0),
    (1130, 10, 'comment', '', 0),
    (1131, 10, 'custom_code', '', 0);

DROP TABLE IF EXISTS `{DB_PREFIX}route_alias`;
CREATE TABLE `{DB_PREFIX}route_alias` (
  `route_alias_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint unsigned NOT NULL DEFAULT '0',
  `language_id` bigint unsigned NOT NULL DEFAULT '1' COMMENT 'extension.extension_id',
  `route` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `param` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT '',
  PRIMARY KEY (`route_alias_id`) USING BTREE,
  UNIQUE KEY `site_language_alias` (`site_id`,`language_id`,`alias`) USING BTREE,
  KEY `route_param_value` (`route`,`param`,`value`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1170 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}route_alias` (`route_alias_id`, `site_id`, `language_id`, `route`, `param`, `value`, `alias`) VALUES
    (6, 0, 35, 'page/contact', '', '', 'contact-us'),
    (7, 0, 35, 'page/home', '', '', '/'),
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
    (661, 0, 35, 'content/post', 'post_id', '1', 'cinema-display-vesa-mount-adapter'),
    (662, 0, 36, 'content/post', 'post_id', '1', 'cinema-display-vesa-mount-adapter-kit-id'),
    (663, 1, 35, 'content/post', 'post_id', '1', 'cinema-display-vesa-mount-adapter'),
    (664, 1, 36, 'content/post', 'post_id', '1', 'cinema-display-vesa-mount-adapter-kit-id'),
    (665, 3, 35, 'content/post', 'post_id', '1', 'cinema-display-vesa-mount-adapter'),
    (666, 3, 36, 'content/post', 'post_id', '1', 'cinema-display-vesa-mount-adapter-kit-id'),
    (724, 0, 35, 'content/post', 'post_id', '4', 'veniam-eiusmod-voluptate-eu-excepteur'),
    (725, 0, 36, 'content/post', 'post_id', '4', 'veniam-eiusmod-voluptate-eu-excepteur-4-3-36'),
    (726, 1, 35, 'content/post', 'post_id', '4', 'veniam-eiusmod-voluptate-eu-excepteur'),
    (727, 1, 36, 'content/post', 'post_id', '4', 'veniam-eiusmod-voluptate-eu-excepteur-4-3-36'),
    (728, 3, 35, 'content/post', 'post_id', '4', 'veniam-eiusmod-voluptate-eu-excepteur'),
    (729, 3, 36, 'content/post', 'post_id', '4', 'veniam-eiusmod-voluptate-eu-excepteur-4-3-36'),
    (808, 0, 35, 'content/post', 'post_id', '8', 'velit-eu-dolore-amet-pariatur-sunt-dolor'),
    (809, 0, 36, 'content/post', 'post_id', '8', 'velit-eu-dolore-amet-pariatur-sunt-dolor-36'),
    (810, 1, 35, 'content/post', 'post_id', '8', 'velit-eu-dolore-amet-pariatur-sunt-dolor'),
    (811, 1, 36, 'content/post', 'post_id', '8', 'velit-eu-dolore-amet-pariatur-sunt-dolor-36'),
    (812, 3, 35, 'content/post', 'post_id', '8', 'velit-eu-dolore-amet-pariatur-sunt-dolor'),
    (813, 3, 36, 'content/post', 'post_id', '8', 'velit-eu-dolore-amet-pariatur-sunt-dolor-36'),
    (814, 0, 35, 'content/post', 'post_id', '9', 'esse-officia-qui-elit-labore-laborum-officia-anim'),
    (815, 0, 36, 'content/post', 'post_id', '9', 'esse-officia-qui-elit-labore-laborum-officia-anim-36'),
    (816, 1, 35, 'content/post', 'post_id', '9', 'esse-officia-qui-elit-labore-laborum-officia-anim'),
    (817, 1, 36, 'content/post', 'post_id', '9', 'esse-officia-qui-elit-labore-laborum-officia-anim-36'),
    (818, 3, 35, 'content/post', 'post_id', '9', 'esse-officia-qui-elit-labore-laborum-officia-anim'),
    (819, 3, 36, 'content/post', 'post_id', '9', 'esse-officia-qui-elit-labore-laborum-officia-anim-36'),
    (820, 0, 35, 'page/sitemap', '', '', 'sitemap'),
    (947, 0, 35, 'content/post', 'post_id', '5', 'consequat-eu-occaecat-aliquip-voluptate-officia'),
    (948, 0, 36, 'content/post', 'post_id', '5', 'consequat-eu-occaecat-aliquip-voluptate-officia-5-3-36'),
    (949, 1, 35, 'content/post', 'post_id', '5', 'consequat-eu-occaecat-aliquip-voluptate-officia'),
    (950, 1, 36, 'content/post', 'post_id', '5', 'consequat-eu-occaecat-aliquip-voluptate-officia-5-3-36'),
    (951, 3, 35, 'content/post', 'post_id', '5', 'consequat-eu-occaecat-aliquip-voluptate-officia'),
    (952, 3, 36, 'content/post', 'post_id', '5', 'consequat-eu-occaecat-aliquip-voluptate-officia-5-3-36'),
    (971, 0, 35, 'content/category', 'category_id', '16', 'blog'),
    (972, 0, 36, 'content/category', 'category_id', '16', 'blog-36'),
    (973, 1, 35, 'content/category', 'category_id', '16', 'blog'),
    (974, 1, 36, 'content/category', 'category_id', '16', 'blog-36'),
    (975, 3, 35, 'content/category', 'category_id', '16', 'blog'),
    (976, 3, 36, 'content/category', 'category_id', '16', 'blog-36'),
    (995, 0, 35, 'content/post', '', '', NULL),
    (996, 0, 35, 'content/category', '', '', NULL),
    (1057, 0, 35, 'content/post', 'post_id', '6', 'labore-mollit-dolor-cillum-esse-ullamco'),
    (1058, 0, 36, 'content/post', 'post_id', '6', 'labore-mollit-dolor-cillum-esse-ullamco-enim-6-3-36'),
    (1059, 1, 35, 'content/post', 'post_id', '6', 'labore-mollit-dolor-cillum-esse-ullamco'),
    (1060, 1, 36, 'content/post', 'post_id', '6', 'labore-mollit-dolor-cillum-esse-ullamco-enim-6-3-36'),
    (1061, 3, 35, 'content/post', 'post_id', '6', 'labore-mollit-dolor-cillum-esse-ullamco'),
    (1062, 3, 36, 'content/post', 'post_id', '6', 'labore-mollit-dolor-cillum-esse-ullamco-enim-6-3-36'),
    (1063, 0, 35, 'content/post', 'post_id', '7', 'veniam-ad-duis-do-nisi-cillum-aliquip-anim'),
    (1064, 0, 36, 'content/post', 'post_id', '7', 'veniam-ad-duis-do-nisi-cillum-aliquip-anim-7-3-36'),
    (1065, 1, 35, 'content/post', 'post_id', '7', 'veniam-ad-duis-do-nisi-cillum-aliquip-anim'),
    (1066, 1, 36, 'content/post', 'post_id', '7', 'veniam-ad-duis-do-nisi-cillum-aliquip-anim-7-3-36'),
    (1067, 3, 35, 'content/post', 'post_id', '7', 'veniam-ad-duis-do-nisi-cillum-aliquip-anim'),
    (1068, 3, 36, 'content/post', 'post_id', '7', 'veniam-ad-duis-do-nisi-cillum-aliquip-anim-7-3-36'),
    (1075, 0, 35, 'content/post', 'post_id', '3', 'fugiat-do-elit-dolore-culpa-ex-adipisicing-quis'),
    (1076, 0, 36, 'content/post', 'post_id', '3', 'fugiat-do-elit-dolore-culpa-ex-adipisicing-quis-3-3-36'),
    (1077, 1, 35, 'content/post', 'post_id', '3', 'fugiat-do-elit-dolore-culpa-ex-adipisicing-quis'),
    (1078, 1, 36, 'content/post', 'post_id', '3', 'fugiat-do-elit-dolore-culpa-ex-adipisicing-quis-3-3-36'),
    (1079, 3, 35, 'content/post', 'post_id', '3', 'fugiat-do-elit-dolore-culpa-ex-adipisicing-quis'),
    (1080, 3, 36, 'content/post', 'post_id', '3', 'fugiat-do-elit-dolore-culpa-ex-adipisicing-quis-3-3-36'),
    (1093, 0, 35, 'content/category', 'category_id', '17', 'news'),
    (1094, 0, 36, 'content/category', 'category_id', '17', 'news-id'),
    (1095, 1, 35, 'content/category', 'category_id', '17', 'news'),
    (1096, 1, 36, 'content/category', 'category_id', '17', 'news-id'),
    (1097, 3, 35, 'content/category', 'category_id', '17', 'news'),
    (1098, 3, 36, 'content/category', 'category_id', '17', 'news-id'),
    (1105, 0, 35, 'content/category', 'category_id', '18', 'event'),
    (1106, 0, 36, 'content/category', 'category_id', '18', 'event-id'),
    (1107, 1, 35, 'content/category', 'category_id', '18', 'event'),
    (1108, 1, 36, 'content/category', 'category_id', '18', 'event-id'),
    (1109, 3, 35, 'content/category', 'category_id', '18', 'event'),
    (1110, 3, 36, 'content/category', 'category_id', '18', 'event-id'),
    (1111, 0, 35, 'content/post', 'post_id', '2', 'tempor-duis-velit-ex-magna-magna-consectetur'),
    (1112, 0, 36, 'content/post', 'post_id', '2', 'tempor-duis-velit-ex-magna-magna-consectetur-36'),
    (1113, 1, 35, 'content/post', 'post_id', '2', 'tempor-duis-velit-ex-magna-magna-consectetur'),
    (1114, 1, 36, 'content/post', 'post_id', '2', 'tempor-duis-velit-ex-magna-magna-consectetur-36'),
    (1115, 3, 35, 'content/post', 'post_id', '2', 'tempor-duis-velit-ex-magna-magna-consectetur'),
    (1116, 3, 36, 'content/post', 'post_id', '2', 'tempor-duis-velit-ex-magna-magna-consectetur-36'),
    (1117, 0, 35, 'content/category/home', '', '', 'content'),
    (1160, 0, 35, 'content/category', 'category_id', '1', 'page'),
    (1161, 0, 36, 'content/category', 'category_id', '1', 'page-id'),
    (1168, 0, 35, 'content/post', 'post_id', '10', 'about-us'),
    (1169, 0, 36, 'content/post', 'post_id', '10', 'about-us-36');

DROP TABLE IF EXISTS `{DB_PREFIX}setting`;
CREATE TABLE `{DB_PREFIX}setting` (
  `setting_id` bigint NOT NULL AUTO_INCREMENT,
  `site_id` bigint NOT NULL DEFAULT '0',
  `group` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`setting_id`),
  KEY `group` (`site_id`,`group`,`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4019 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}setting` (`setting_id`, `site_id`, `group`, `code`, `key`, `value`, `encoded`) VALUES
    (1296, 0, 'system', 'alias_distinct', 'information/information', 'information_id', 0),
    (1297, 0, 'system', 'alias_distinct', 'content/post', 'post_id', 0),
    (1298, 0, 'system', 'alias_multi', 'content/category', 'category_id', 0),
    (3818, 3, 'system', 'site', 'email', 'admin@example.com', 0),
    (3819, 3, 'system', 'site', 'meta_title', '{"35":"3rd site","36":"3rd site"}', 1),
    (3820, 3, 'system', 'site', 'meta_description', '{"35":"3rd site","36":""}', 1),
    (3821, 3, 'system', 'site', 'meta_keyword', '{"35":"","36":""}', 1),
    (3822, 3, 'system', 'site', 'logo', 'image/no-image.png', 0),
    (3823, 3, 'system', 'site', 'favicon', 'image/no-image.png', 0),
    (3824, 3, 'system', 'site', 'language', 'en', 0),
    (3825, 3, 'system', 'site', 'layout_id', '1', 0),
    (3826, 3, 'system', 'site', 'theme', 'base', 0),
    (3827, 3, 'system', 'site', 'maintenance', '0', 0),
    (3828, 0, 'system', 'site', 'email', 'admin@example.com', 0),
    (3829, 0, 'system', 'site', 'meta_title', '{"35":"1st Shift Site","36":"1st Shift Site"}', 1),
    (3830, 0, 'system', 'site', 'meta_description', '{"35":"","36":""}', 1),
    (3831, 0, 'system', 'site', 'meta_keyword', '{"35":"","36":""}', 1),
    (3832, 0, 'system', 'site', 'logo', 'image/logo.png', 0),
    (3833, 0, 'system', 'site', 'favicon', 'image/favicon.png', 0),
    (3834, 0, 'system', 'site', 'language', 'en', 0),
    (3835, 0, 'system', 'site', 'layout_id', '1', 0),
    (3836, 0, 'system', 'site', 'theme', 'base', 0),
    (3837, 0, 'system', 'site', 'maintenance', '0', 0),
    (3838, 0, 'theme', 'base', 'color', '#222', 0),
    (3839, 1, 'system', 'site', 'email', 'admin@example.com', 0),
    (3840, 1, 'system', 'site', 'meta_title', '{"35":"2nd Site","36":"2nd Site"}', 1),
    (3841, 1, 'system', 'site', 'meta_description', '{"35":"2nd Site","36":"2nd Site"}', 1),
    (3842, 1, 'system', 'site', 'meta_keyword', '{"35":"2nd Site","36":"2nd Site"}', 1),
    (3843, 1, 'system', 'site', 'logo', 'image/logo.png', 0),
    (3844, 1, 'system', 'site', 'favicon', 'image/favicon.png', 0),
    (3845, 1, 'system', 'site', 'language', 'en', 0),
    (3846, 1, 'system', 'site', 'layout_id', '1', 0),
    (3847, 1, 'system', 'site', 'theme', 'base', 0),
    (3848, 1, 'system', 'site', 'maintenance', '1', 0),
    (3881, 0, 'system', 'setting', 'compression', '6', 0),
    (3882, 0, 'system', 'setting', 'admin_language', 'en', 0),
    (3883, 0, 'system', 'setting', 'admin_limit', '36', 0),
    (3884, 0, 'system', 'setting', 'mail_engine', 'mail', 0),
    (3885, 0, 'system', 'setting', 'smtp_host', '', 0),
    (3886, 0, 'system', 'setting', 'smtp_username', '', 0),
    (3887, 0, 'system', 'setting', 'smtp_password', '', 0),
    (3888, 0, 'system', 'setting', 'smtp_port', '25', 0),
    (3889, 0, 'system', 'setting', 'smtp_timeout', '300', 0),
    (3890, 0, 'system', 'setting', 'error_display', '1', 0),
    (3891, 0, 'system', 'setting', 'development', '1', 0),
    (3892, 0, 'system', 'setting', 'mail_smtp_hostname', '', 0),
    (3893, 0, 'system', 'setting', 'mail_smtp_username', '', 0),
    (3894, 0, 'system', 'setting', 'mail_smtp_password', '', 0),
    (3895, 0, 'system', 'setting', 'mail_smtp_port', '', 0),
    (3896, 0, 'system', 'setting', 'mail_smtp_timeout', '', 0),
    (4007, 0, 'system', 'alias_multi', 'content/post', 'category_id', 0),
    (4008, 0, 'plugin', 'content', 'post_robots', 'index, nofollow', 0),
    (4009, 0, 'plugin', 'content', 'post_comment', 'register', 0),
    (4010, 0, 'plugin', 'content', 'post_custom_code', '', 0),
    (4011, 0, 'plugin', 'content', 'category_robots', 'index, follow', 0),
    (4012, 0, 'plugin', 'content', 'category_post_per_page', '10', 0),
    (4013, 0, 'plugin', 'content', 'category_post_lead', '1', 0),
    (4014, 0, 'plugin', 'content', 'category_post_lead_excerpt', '200', 0),
    (4015, 0, 'plugin', 'content', 'category_post_column', '2', 0),
    (4016, 0, 'plugin', 'content', 'category_post_column_excerpt', '48', 0),
    (4017, 0, 'plugin', 'content', 'category_post_order', 'p.publish~desc', 0),
    (4018, 0, 'plugin', 'content', 'category_custom_code', '', 0);

DROP TABLE IF EXISTS `{DB_PREFIX}site`;
CREATE TABLE `{DB_PREFIX}site` (
  `site_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `url_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`site_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}site` (`site_id`, `name`, `url_host`) VALUES (0, 'Default', 'https://localhost/');

DROP TABLE IF EXISTS `{DB_PREFIX}site_relation`;
CREATE TABLE `{DB_PREFIX}site_relation` (
  `site_relation_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `site_id` bigint unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `taxonomy_id` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`site_relation_id`) USING BTREE,
  UNIQUE KEY `site_taxonomy_id` (`site_id`,`taxonomy`,`taxonomy_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}site_relation` (`site_relation_id`, `site_id`, `taxonomy`, `taxonomy_id`) VALUES
    (170, 0, 'content_category', 1),
    (137, 0, 'content_category', 16),
    (157, 0, 'content_category', 17),
    (159, 0, 'content_category', 18),
    (160, 0, 'content_post', 2),
    (154, 0, 'content_post', 3),
    (89, 0, 'content_post', 4),
    (131, 0, 'content_post', 5),
    (151, 0, 'content_post', 6),
    (152, 0, 'content_post', 7),
    (108, 0, 'content_post', 8),
    (109, 0, 'content_post', 9),
    (174, 0, 'content_post', 10),
    (72, 1, 'content_post', 1);

DROP TABLE IF EXISTS `{DB_PREFIX}term`;
CREATE TABLE `{DB_PREFIX}term` (
  `term_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`term_id`) USING BTREE,
  KEY `taxonomy` (`taxonomy`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}term` (`term_id`, `parent_id`, `taxonomy`, `sort_order`, `status`, `created`, `updated`) VALUES
    (1, 0, 'content_category', 99, 1, NOW(), NOW()),
    (15, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (16, 0, 'content_category', 0, 1, NOW(), NOW()),
    (17, 16, 'content_category', 0, 1, NOW(), NOW()),
    (18, 16, 'content_category', 0, 1, NOW(), NOW()),
    (20, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (21, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (22, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (23, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (24, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (25, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (26, 0, 'content_tag', 0, 1, NOW(), NOW()),
    (27, 0, 'content_tag', 0, 1, NOW(), NOW());

DROP TABLE IF EXISTS `{DB_PREFIX}term_content`;
CREATE TABLE `{DB_PREFIX}term_content` (
  `term_id` bigint unsigned NOT NULL,
  `language_id` bigint unsigned NOT NULL COMMENT 'extension.extension_id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `meta_title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `meta_keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`term_id`,`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}term_content` (`term_id`, `language_id`, `title`, `content`, `meta_title`, `meta_description`, `meta_keyword`) VALUES
    (1, 35, 'Page', '&lt;p&gt;test&lt;/p&gt;', '', '', ''),
    (1, 36, 'Page', '', '', '', ''),
    (15, 35, 'Tag one', 'Tag one', '', '', ''),
    (15, 36, 'Tag two', 'Tag two', '', '', ''),
    (16, 35, 'Blog', '', '', '', ''),
    (16, 36, 'Blog', '', '', '', ''),
    (17, 35, 'News', '&lt;p&gt;Test&lt;/p&gt;', '', '', ''),
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

DROP TABLE IF EXISTS `{DB_PREFIX}term_meta`;
CREATE TABLE `{DB_PREFIX}term_meta` (
  `term_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_meta_id`) USING BTREE,
  KEY `term_id` (`term_id`) USING BTREE,
  KEY `key` (`key`(191)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=401 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}term_meta` (`term_meta_id`, `term_id`, `key`, `value`, `encoded`) VALUES
    (305, 16, 'robots', '', 0),
    (306, 16, 'post_per_page', '10', 0),
    (307, 16, 'post_lead', '2', 0),
    (308, 16, 'post_lead_excerpt', '101', 0),
    (309, 16, 'post_column', '2', 0),
    (310, 16, 'post_column_excerpt', '48', 0),
    (311, 16, 'post_order', '', 0),
    (312, 16, 'custom_code', '', 0),
    (361, 17, 'robots', '', 0),
    (362, 17, 'post_per_page', '10', 0),
    (363, 17, 'post_lead', '1', 0),
    (364, 17, 'post_lead_excerpt', '200', 0),
    (365, 17, 'post_column', '3', 0),
    (366, 17, 'post_column_excerpt', '100', 0),
    (367, 17, 'post_order', '', 0),
    (368, 17, 'custom_code', '', 0),
    (377, 18, 'robots', 'noindex, follow', 0),
    (378, 18, 'post_per_page', '10', 0),
    (379, 18, 'post_lead', '2', 0),
    (380, 18, 'post_lead_excerpt', '101', 0),
    (381, 18, 'post_column', '2', 0),
    (382, 18, 'post_column_excerpt', '48', 0),
    (383, 18, 'post_order', 'p.publish~asc', 0),
    (384, 18, 'custom_code', '', 0),
    (393, 1, 'robots', '', 0),
    (394, 1, 'post_per_page', '9', 0),
    (395, 1, 'post_lead', '1', 0),
    (396, 1, 'post_lead_excerpt', '101', 0),
    (397, 1, 'post_column', '2', 0),
    (398, 1, 'post_column_excerpt', '46', 0),
    (399, 1, 'post_order', '', 0),
    (400, 1, 'custom_code', '', 0);

DROP TABLE IF EXISTS `{DB_PREFIX}term_relation`;
CREATE TABLE `{DB_PREFIX}term_relation` (
  `term_relation_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `taxonomy_id` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_relation_id`) USING BTREE,
  UNIQUE KEY `term_taxonomy_id` (`term_id`,`taxonomy`,`taxonomy_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=575 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}term_relation` (`term_relation_id`, `term_id`, `taxonomy`, `taxonomy_id`) VALUES
    (512, 15, 'content_post', 1),
    (507, 16, 'content_post', 1),
    (574, 16, 'content_post', 2),
    (516, 16, 'content_post', 4),
    (573, 17, 'content_post', 3),
    (541, 17, 'content_post', 5),
    (571, 17, 'content_post', 6),
    (506, 18, 'content_post', 1),
    (572, 18, 'content_post', 6),
    (509, 21, 'content_post', 1),
    (510, 25, 'content_post', 1),
    (511, 26, 'content_post', 1),
    (508, 27, 'content_post', 1);

DROP TABLE IF EXISTS `{DB_PREFIX}user`;
CREATE TABLE `{DB_PREFIX}user` (
  `user_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` bigint unsigned NOT NULL DEFAULT '0',
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT 'nickname?',
  `firstname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `lastname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

DROP TABLE IF EXISTS `{DB_PREFIX}user_group`;
CREATE TABLE `{DB_PREFIX}user_group` (
  `user_group_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `backend` tinyint unsigned NOT NULL DEFAULT '0',
  `permission` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

INSERT INTO `{DB_PREFIX}user_group` (`user_group_id`, `name`, `backend`, `permission`, `status`, `created`, `updated`) VALUES
    (1, 'Super Admin', 1, '{"access":[],"modify":[]}', 1, NOW(), NOW()),
    (2, 'Register', 0, '{"access":[],"modify":[]}', 0, NOW(), NOW());

DROP TABLE IF EXISTS `{DB_PREFIX}user_meta`;
CREATE TABLE `{DB_PREFIX}user_meta` (
  `user_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `encoded` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_meta_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

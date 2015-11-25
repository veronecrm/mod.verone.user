CREATE TABLE IF NOT EXISTS `#__permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `entity` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `section` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `group` int(11) NOT NULL,
  `permission` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

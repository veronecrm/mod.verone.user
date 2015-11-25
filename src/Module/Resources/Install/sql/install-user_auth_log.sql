CREATE TABLE IF NOT EXISTS `#__user_auth_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `sessionId` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `userAgent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loginDate` int(11) NOT NULL,
  `logoutDate` int(11) NOT NULL,
  `logoutType` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `sessionId` (`sessionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `outgoing_mail_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'If not null, the blacklisting applies only to this category',
  `mail_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'If not null, the blacklisting applies only to this mail_type',
  `blacklist_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mail_address` (`mail_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This table contains the list of mail addresses that requeste' AUTO_INCREMENT=1 ;


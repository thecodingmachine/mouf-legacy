
CREATE TABLE IF NOT EXISTS `outgoing_mails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) COLLATE utf8_unicode_ci NULL,
  `mail_type` varchar(50) COLLATE utf8_unicode_ci NULL,
  `title` text COLLATE utf8_unicode_ci NULL,
  `text_body` text COLLATE utf8_unicode_ci NULL,
  `html_body` text COLLATE utf8_unicode_ci NULL,
  `sent_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `unique_key` varchar(50) COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`,`mail_type`),
  KEY `mail_type` (`mail_type`),
  KEY `sent_date` (`sent_date`),
  UNIQUE `unique_key` (`unique_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This tables will store any outgoing mail passing through the' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `outgoing_mail_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `outgoing_mail_id` int(11) NOT NULL,
  `mail_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mail_address_name` varchar(255) COLLATE utf8_unicode_ci NULL,
  `role` varchar(7) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Can be one of: "to", "cc", "bcc", "from", "replyto"',
  `requested_blacklist` TINYINT COLLATE utf8_unicode_ci NOT NULL DEFAULT 0 COMMENT '1 if this user requested to be blacklisted following this particular mail',
  PRIMARY KEY (`id`),
  KEY `outgoing_mail_id` (`outgoing_mail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This table stores the mail addresses related to outgoing mai' AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `outgoing_mail_addresses`
--
ALTER TABLE `outgoing_mail_addresses`
  ADD CONSTRAINT `outgoing_mail_addresses_ibfk_1` FOREIGN KEY (`outgoing_mail_id`) REFERENCES `outgoing_mails` (`id`);
  
  

CREATE TABLE IF NOT EXISTS `outgoing_mail_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail_address` int(11) NOT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'If not null, the blacklisting applies only to this category',
  `mail_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'If not null, the blacklisting applies only to this mail_type',
  `blacklist_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mail_address` (`mail_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This table contains the list of mail addresses that requeste' AUTO_INCREMENT=1 ;


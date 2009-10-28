-- --------------------------------------------------------

--
-- Structure de la table `alerts`
--

CREATE TABLE IF NOT EXISTS `alerts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  `url` varchar(2048) COLLATE utf8_unicode_ci NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NULL,
  `date` datetime NOT NULL,
  `level` int(11) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT 0,
  `validation_date` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Structure de la table `alert_recipients`
--

CREATE TABLE IF NOT EXISTS `alert_recipients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alert_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alert_id` (`alert_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Contenu de la table `alert_recipients`
--


--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `alert_recipients`
--
ALTER TABLE `alert_recipients`
  ADD CONSTRAINT `alert_recipients_ibfk_1` FOREIGN KEY (`alert_id`) REFERENCES `alerts` (`id`);

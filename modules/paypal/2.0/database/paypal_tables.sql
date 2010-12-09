
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


-- --------------------------------------------------------

--
-- Structure de la table `paypal_ipn_responses`
--

CREATE TABLE IF NOT EXISTS `paypal_ipn_responses` (
  `id` int(11) NOT NULL auto_increment,
  `full_request` text collate utf8_unicode_ci NOT NULL,
  `hackattempt` int(11) NOT NULL,
  `paypal_item_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_business` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_item_number` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_status` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_gross` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_currency` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_txn_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_receiver_email` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_receiver_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_quantity` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_num_cart_items` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_date` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_first_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_type` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_gross` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_fee` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_settle_amount` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_memo` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_email` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_txn_type` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_status` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_street` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_city` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_state` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_zip` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_country` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_status` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_tax` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_name1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_selection1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_name2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_selection2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_invoice` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_custom` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_notify_version` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_verify_sign` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_business_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_fee` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_exchange_rate` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_settle_currency` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_parent_txn_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_pending_reason` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_reason_code` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_residence_country` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_test_ipn` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_charset` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_subscr_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_subscr_date` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_subscr_effective` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_period1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_period2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_period3` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_amount1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_amount2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_amount3` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_amount1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_amount2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_amount3` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_recurring` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_reattempt` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_retry_at` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_recur_times` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_username` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_password` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_for_auction` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_auction_closing_date` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_auction_multi_item` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_auction_buyer_id` varchar(255) collate utf8_unicode_ci default NULL,
  `creation_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `creation_user_id` int(11) default NULL,
  `modification_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modification_user_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `hackattempttxnid` (`hackattempt`,`paypal_txn_id`),
  KEY `creation_user_id` (`creation_user_id`),
  KEY `modification_user_id` (`modification_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=44 ;

--
-- Contenu de la table `paypal_ipn_responses`
--

-- --------------------------------------------------------

--
-- Structure de la table `paypal_payments`
--

CREATE TABLE IF NOT EXISTS `paypal_payments` (
  `id` int(11) NOT NULL auto_increment,
  `status_id` int(11) NOT NULL,
  `paypal_business` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_receiver_email` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_receiver_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_item_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_item_number` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_invoice` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_custom` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_name1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_selection1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_name2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_option_selection2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_status` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_pending_reason` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_reason_code` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_date` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_txn_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_parent_txn_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_txn_type` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_gross` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_fee` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_currency` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_settle_amount` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_settle_currency` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_exchange_rate` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_gross` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_fee` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_first_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_business_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_name` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_street` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_city` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_state` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_zip` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_country` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_address_status` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_email` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payer_status` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_payment_type` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_notify_version` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_verify_sign` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_ubscr_date` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_subscr_effective` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_trial_period1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_trial_period2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_regular_period` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_trial_amount1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_trial_amount2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_regular_amount` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_trial_period_unit1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_trial_period_unit2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_regular_period_unit` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_amount1` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_amount2` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_mc_amount3` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_recurring` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_reattempt` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_retry_at` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_recur_times` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_username` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_password` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_subscr_id` varchar(255) collate utf8_unicode_ci default NULL,
  `paypal_tax` varchar(255) collate utf8_unicode_ci default NULL,
  `estimated_next_payment` timestamp NOT NULL default '0000-00-00 00:00:00',
  `creation_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `creation_user_id` int(11) default NULL,
  `modification_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modification_user_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `status_id` (`status_id`),
  KEY `creation_user_id` (`creation_user_id`),
  KEY `modification_user_id` (`modification_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Contenu de la table `paypal_payments`
--


-- --------------------------------------------------------

--
-- Structure de la table `paypal_payment_status`
--

CREATE TABLE IF NOT EXISTS `paypal_payment_status` (
  `id` int(11) NOT NULL,
  `label` varchar(100) collate utf8_unicode_ci default NULL,
  `description` varchar(500) collate utf8_unicode_ci default NULL,
  `creation_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `creation_user_id` int(11) default NULL,
  `modification_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `modification_user_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `label` (`label`),
  KEY `creation_user_id` (`creation_user_id`),
  KEY `modification_user_id` (`modification_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `paypal_payment_status`
--

INSERT INTO `paypal_payment_status` (`id`, `label`, `description`, `creation_date`, `creation_user_id`, `modification_date`, `modification_user_id`) VALUES
(1, 'awaiting', 'Awaiting payment', '2009-06-19 11:25:09', NULL, '0000-00-00 00:00:00', NULL),
(2, 'subscribed', 'Subscription active', '2009-06-19 11:25:09', NULL, '0000-00-00 00:00:00', NULL),
(3, 'failed', 'Payment failed', '2009-06-19 11:25:09', NULL, '0000-00-00 00:00:00', NULL),
(4, 'cancelled', 'Payment cancelled', '2009-06-19 11:25:09', NULL, '0000-00-00 00:00:00', NULL),
(5, 'eot', 'Payment end of term', '2009-06-19 11:25:09', NULL, '0000-00-00 00:00:00', NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `paypal_payments`
--
ALTER TABLE `paypal_payments`
  ADD CONSTRAINT `paypal_payments_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `paypal_payment_status` (`id`);

<?php
MoufManager::getMoufManager()->declareComponent('fineCommonTranslationService', 'FinePHPArrayTranslationService', true);
MoufManager::getMoufManager()->setParameter('fineCommonTranslationService', 'i18nMessagePath', 'plugins/utils/i18n/fine/2.0/ressources/');
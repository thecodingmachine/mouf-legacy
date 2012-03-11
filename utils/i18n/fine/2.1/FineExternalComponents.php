<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

MoufManager::getMoufManager()->declareComponent('fineCommonTranslationService', 'FinePHPArrayTranslationService', true);
MoufManager::getMoufManager()->setParameter('fineCommonTranslationService', 'i18nMessagePath', 'plugins/utils/i18n/fine/2.1/resources/');
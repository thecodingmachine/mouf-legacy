<?php
MoufManager::getMoufManager()->declareComponent('fineAdminLabelMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('fineAdminLabelMenuItem', 'menuText', '<b>I18N with Fine</b>');
MoufManager::getMoufManager()->setParameter('fineAdminLabelMenuItem', 'menuLink', '');
MoufManager::getMoufManager()->setParameter('fineAdminLabelMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('fineAdminLabelMenuItem', 'propagatedUrlParameters', false);

MoufManager::getMoufManager()->declareComponent('fineSupportedLanguagesMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('fineSupportedLanguagesMenuItem', 'menuText', 'Supported languages');
MoufManager::getMoufManager()->setParameter('fineSupportedLanguagesMenuItem', 'menuLink', 'mouf/editLabels/supportedLanguages');
MoufManager::getMoufManager()->setParameter('fineSupportedLanguagesMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('fineSupportedLanguagesMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

MoufManager::getMoufManager()->declareComponent('fineEnableDisableMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('fineEnableDisableMenuItem', 'menuText', 'Enable/Disable translation');
MoufManager::getMoufManager()->setParameter('fineEnableDisableMenuItem', 'menuLink', 'mouf/editLabels/');
MoufManager::getMoufManager()->setParameter('fineEnableDisableMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('fineEnableDisableMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

MoufManager::getMoufManager()->declareComponent('fineMissingLabelsMenuItem', 'SplashMenuItem', true);
MoufManager::getMoufManager()->setParameter('fineMissingLabelsMenuItem', 'menuText', 'Edit translations');
MoufManager::getMoufManager()->setParameter('fineMissingLabelsMenuItem', 'menuLink', 'mouf/editLabels/missinglabels');
MoufManager::getMoufManager()->setParameter('fineMissingLabelsMenuItem', 'menuCssClass', '');
MoufManager::getMoufManager()->setParameter('fineMissingLabelsMenuItem', 'propagatedUrlParameters', array (
  0 => 'selfedit',
));

$actionMenu = MoufManager::getMoufManager()->getInstance("actionMenu");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("fineAdminLabelMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("fineSupportedLanguagesMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("fineEnableDisableMenuItem");
$actionMenu->menuItems[] = MoufManager::getMoufManager()->getInstance("fineMissingLabelsMenuItem");


// Controller declaration
MoufManager::getMoufManager()->declareComponent('editLabels', 'EditLabelController', true);
MoufManager::getMoufManager()->bindComponents('editLabels', 'template', 'moufTemplate');
?>
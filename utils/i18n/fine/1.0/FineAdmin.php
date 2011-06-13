<?php
MoufUtils::registerMenuItem('fineAdminSubMenu', 'I18N with Fine', null, 'moufSubMenu', 40);
MoufUtils::registerMenuItem('fineEditTranslationsMenuItem', 'Edit translations', 'mouf/editLabels/missinglabels', 'fineAdminSubMenu', 10);
MoufUtils::registerMenuItem('fineEnableDisable2MenuItem', 'Enable/Disable translation', 'mouf/editLabels/', 'fineAdminSubMenu', 20);
MoufUtils::registerMenuItem('fineSupportedLanguages2MenuItem', 'Supported languages', 'mouf/editLabels/supportedLanguages', 'fineAdminSubMenu', 30);


// Controller declaration
MoufManager::getMoufManager()->declareComponent('editLabels', 'EditLabelController', true);
MoufManager::getMoufManager()->bindComponents('editLabels', 'template', 'moufTemplate');
?>
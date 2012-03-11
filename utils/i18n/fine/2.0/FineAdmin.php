<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

MoufUtils::registerMainMenu('htmlMainMenu', 'HTML', null, 'mainMenu', 40);
MoufUtils::registerMenuItem('htmlFineMainMenu', 'Fine', null, 'htmlMainMenu', 10);
MoufUtils::registerMenuItem('htmlFineSupportedLanguagesMenuItem', 'Supported languages', 'javascript:chooseInstancePopup("FinePHPArrayTranslationService", "'.ROOT_URL.'mouf/editLabels/supportedLanguages?name=", "'.ROOT_URL.'", "'.ROOT_URL.'")', 'htmlFineMainMenu', 40);
MoufUtils::registerMenuItem('htmlFineEditTranslationMenuItem', 'Edit translations', 'javascript:chooseInstancePopup("FinePHPArrayTranslationService", "'.ROOT_URL.'mouf/editLabels/missinglabels?name=", "'.ROOT_URL.'", "'.ROOT_URL.'")', 'htmlFineMainMenu', 40);
MoufUtils::registerMenuItem('htmlFineEnableDisable2MenuItem', 'Enable/Disable translation', 'mouf/editLabels/', 'htmlFineMainMenu', 20);
MoufUtils::registerMenuItem('htmlFineImportCSV2MenuItem', 'Import/Export', 'mouf/editLabels/excelimport', 'htmlFineMainMenu', 40);

/*
 * @ExtendedAction {"name":"Supported languages", "url":"mouf/editLabels/supportedLanguages", "default":false}
 * @ExtendedAction {"name":"Edit translations", "url":"mouf/editLabels/missinglabels", "default":false}
*/
// Controller declaration
MoufManager::getMoufManager()->declareComponent('editLabels', 'EditLabelController', true);
MoufManager::getMoufManager()->bindComponents('editLabels', 'template', 'moufTemplate');
?>
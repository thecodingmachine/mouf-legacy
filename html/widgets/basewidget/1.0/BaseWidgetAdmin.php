<?php

MoufUtils::registerMenuItem('baseWidgetAdminSubMenu', 'Widgets', null, 'moufSubMenu', 55);
MoufUtils::registerMenuItem('splashAdminApacheConfig2Item', 'Switch edit mode', 'mouf/baseWidget/editMode', 'baseWidgetAdminSubMenu', 10);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('baseWidget', 'BaseWidgetController', true);
MoufManager::getMoufManager()->bindComponents('baseWidget', 'template', 'moufTemplate');
?>
<?php
MoufManager::getMoufManager()->declareComponent('replaceNullWithHyphen', 'NullTransformationFormatter', true);

MoufManager::getMoufManager()->setParameter('replaceNullWithHyphen', 'value', '-');

MoufManager::getMoufManager()->declareComponent('readOnlyCheckBoxFormatter', 'ReadOnlyCheckBoxFormatter', true);

?>
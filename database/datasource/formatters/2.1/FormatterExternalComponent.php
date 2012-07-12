<?php
MoufManager::getMoufManager()->declareComponent('replaceNullWithHyphen', 'NullTransformationFormatter', true);
MoufManager::getMoufManager()->setParameter('replaceNullWithHyphen', 'value', '-');

MoufManager::getMoufManager()->declareComponent('boldFormatter', 'PrefixSuffixFormatter', true);
MoufManager::getMoufManager()->setParameter('boldFormatter', 'prefix', '<b>');
MoufManager::getMoufManager()->setParameter('boldFormatter', 'suffix', '</b>');

MoufManager::getMoufManager()->declareComponent('italicFormatter', 'PrefixSuffixFormatter', true);
MoufManager::getMoufManager()->setParameter('italicFormatter', 'prefix', '<i>');
MoufManager::getMoufManager()->setParameter('italicFormatter', 'suffix', '</i>');



MoufManager::getMoufManager()->declareComponent('readOnlyCheckBoxFormatter', 'ReadOnlyCheckboxFormatter', true);


MoufManager::getMoufManager()->declareComponent('timestampToFrdateFormatter', 'DateFormatter', true);
MoufManager::getMoufManager()->setParameter('timestampToFrdateFormatter', 'sourceFormat', 'timestamp');
MoufManager::getMoufManager()->setParameter('timestampToFrdateFormatter', 'destFormat', 'd/m/Y');
?>
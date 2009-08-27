<?php
require dirname(__FILE__) . '/../config/php/config.php';

if (file_exists(stubConfig::getLibPath() . DIRECTORY_SEPARATOR . 'stubbles.php') == true) {
    require stubConfig::getLibPath() . DIRECTORY_SEPARATOR . 'stubbles.php';
} else {
    require stubConfig::getSourcePath() . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'net' . DIRECTORY_SEPARATOR . 'stubbles' . DIRECTORY_SEPARATOR . 'stubClassLoader.php';
    require stubConfig::getLibPath() . DIRECTORY_SEPARATOR . 'starWriter.php';
}
?>
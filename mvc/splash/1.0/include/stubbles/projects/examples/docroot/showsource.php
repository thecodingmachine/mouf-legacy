<?php
if (!isset($_GET['example'])) {
    $example = 'index';
} else {
    $example = $_GET['example'];
}
if (!preg_match('/^[a-z\-0-9]+$/i', $example)) {
    die('Invalid parameter passed!');
}
$group = $_GET['group'];
if (!preg_match('/^[a-z\-0-9]+$/i', $group)) {
    die('Invalid parameter passed!');
}
$filepath = sprintf('%s/%s/%s.php', dirname(__FILE__), $group, $example);
highlight_file($filepath);
?>
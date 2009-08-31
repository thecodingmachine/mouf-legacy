<?php 
if (!extension_loaded("curl")) {
?>
<html>
	<head>
		<title>Welcome to Mouf</title>
	</head>
	<body>
		<h1>Missing dependencies</h1>
		<p>In order to run Mouf, you will first need to enable the "php_curl" extension on your server.</p>
		<p>Please enable this extension and refresh this page.</p>
	</body>
</html>
<?php 
	exit();
}
?>



<html>
	<head>
		<title>Welcome to Mouf</title>
	</head>
	<body>
		<h1>Welcome to the Mouf framework</h1>
		
		<p>Apparently, this is the first time you are running Mouf. You will need to install it.</p>
		<p>Please click the install button below. This will create and install a ".htaccess" file in the "Mouf" directory.
		This will also create 4 files in your root directory: Mouf.php, MoufComponents.php, MoufRequire.php and MoufUniversalParameters.php (if they don't already exist)</p>
		<p>Please make sure that the Mouf directory is writable by your web-server.</p>
		
		<form action="install.php" method="post">
			<input type="submit" value="Install" />
		</form>
	</body>
</html>
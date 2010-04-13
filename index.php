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
		This will also create 5 files in your root directory: Mouf.php, MoufComponents.php, MoufRequire.php, MoufUI.php and MoufUniversalParameters.php (if they don't already exist)</p>
		<p>Please make sure that the Mouf directory is writable by your web-server.</p>
		<p>Finally, please make sure that the Apache Rewrite module is enabled on your server. Since this install process will create a ".htaccess" file, 
		you must make sure it will be taken into account. If after clicking the "Install" button, nothing happens, it is likely that your Apache server
		has been configured to ignore the ".htaccess" files. In this case, please dive into your Apache configuration and look for a "<code>AllowOverride</code>" directive.
		You should set this directive to: "<code>AllowOverride All</code>".</p>
		
		<form action="install.php" method="post">
			<input type="submit" value="Install" />
		</form>
	</body>
</html>
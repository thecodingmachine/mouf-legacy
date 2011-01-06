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

if (!is_writable(dirname(__FILE__)) || !is_writable(dirname(__FILE__)."/..")) {
?>
<html>
	<head>
		<title>Welcome to Mouf</title>
	</head>
	<body>
		<h1>Web directory must be writable for the Apache user</h1>
		<p>In order to run Mouf, you will first need to change the permissions on the web directory so that the Apache user can write into it.
		Especially, you should check that those 2 directories can be written into:</p>
		<ul>
			<?php if(!is_writable(dirname(__FILE__)."/..")) {?>
				<li><?php echo realpath(dirname(__FILE__)."/..") ?></li>
			<?php }
			if(!is_writable(dirname(__FILE__))) {?>
				<li><?php echo realpath(dirname(__FILE__)) ?></li>
			<?php }?>
		</ul>
		<?php if (function_exists("posix_getpwuid")) {
			$processUser = posix_getpwuid(posix_geteuid());
			$processUserName = $processUser['name'];
		?>
			<p>You can try these commands:</p>
			<pre>
			<?php if(!is_writable(dirname(__FILE__)."/..")) {?>
chown <?php echo $processUserName.":".$processUserName." ".realpath(dirname(__FILE__)."/..") ?><br/>
			<?php }
			if(!is_writable(dirname(__FILE__))) {?>
chown <?php echo $processUserName.":".$processUserName." ".realpath(dirname(__FILE__));
			}?>
</pre>
		<?php 
		}
		?>
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
		<form action="install.php" method="post">
		
			<p>Apparently, this is the first time you are running Mouf. You will need to install it.</p>
			<?php if (file_exists(dirname(__FILE__)."/../MoufUsers.php")): ?>
				<p>The MoufUsers.php file has been detected. Logins/passwords from this file will be used to access Mouf.
				If you want to reset your login or password, delete the MoufUsers.php file and start again the installation procedure.</p>		
			<?php else: ?>
				<p>In order to connect to Mouf, you will need a login and a password.</p>
				<ul>
					<li>
						<label>Login: <input name="login" value="admin" type="text" /></label>
					</li>
					<li>
						<label>Password: <input name="password" type="password" /></label>
					</li>
				</ul>
			<?php endif ?>
			<p>Please click the install button below. This will create and install a ".htaccess" file in the "Mouf" directory.
			This will also create 7 files in your root directory: config.php, Mouf.php, MoufComponents.php, MoufRequire.php, MoufUI.php, MoufUniversalParameters.php and MoufUsers.php (if they don't already exist)</p>
			<p>Please make sure that the Mouf directory is writable by your web-server.</p>
			<p>Finally, please make sure that the Apache Rewrite module is enabled on your server. Since this install process will create a ".htaccess" file, 
			you must make sure it will be taken into account. If after clicking the "Install" button, nothing happens, it is likely that your Apache server
			has been configured to ignore the ".htaccess" files. In this case, please dive into your Apache configuration and look for a "<code>AllowOverride</code>" directive.
			You should set this directive to: "<code>AllowOverride All</code>".</p>
		
			<input type="submit" value="Install" />
		</form>
	</body>
</html>
<h1>Login</h1>
<p>In order to log into Mouf, you need to have an account.</p>
<p>Accounts (login and credentials) are stored in the "MoufUsers.php" file, in the root directory of your web application.
Currently, it seems that no accounts are defined for this application because Mouf cannot find the "MoufUsers.php" file.</p>

<p>In order to create that file, the easiest way is to trigger a new installation of Mouf. The install process will
create the MoufUsers.php file automatically, and will not delete anything. In order to do this, you just need to delete the 
<code>".htaccess"</code> that is present <b>in the <code>"mouf"</code> subdirectory</b> of your web application.
Then, <a href="<?php echo ROOT_URL."mouf/" ?>">restart the install process</a>.</p> 
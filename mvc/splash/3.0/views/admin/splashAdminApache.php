<h1>Splash Apache Configuration</h1>

<p>This page helps you create the <em>.htaccess</em> file that is used by Splash to manage the redirection of web pages.</p>

<form action="write" method="post">
<input type="hidden" name="selfedit" value="<?php echo plainstring_to_htmlprotected(isset($_REQUEST["selfedit"])?$_REQUEST["selfedit"]:"false") ?>" />
<button>Write file</button>
</form>
<?php 
/* @var $this SimpleLoginController */
$this->drawArray($this->contentBeforeLoginBox);

if ($this->badCredentials) {
?>
<div class="warning mediumcenterbox"><?php echo ($this->i18nBadCredentialsLabel)?iMsg($this->badCredentialsLabel):$this->badCredentialsLabel; ?></div>
<br/><br/>
<?php 
}
?>

<div class="smallcenterbox lightbox login_controller">
<form class='splash' action='login'  method='post'>
	<input type='hidden' name='redirect' value='<?php echo plainstring_to_htmlprotected($this->redirecturl) ?>' />
	<ol>
		<li>
			<label for='login'><?php echo ($this->i18nLoginLabel)?iMsg($this->loginLabel):$this->loginLabel; ?></label>
			<input type='text' id='login' class='required' name='login' value="<?php echo plainstring_to_htmlprotected($this->login) ?>" />
		</li>
		<li>
			<label for='password'><?php echo ($this->i18nPasswordLabel)?iMsg($this->passwordLabel):$this->passwordLabel; ?></label>
			<input type='password' id='password' name='password' />
		</li>
		<li class="login_controller_submit">
			<button type='submit' name='action' value='login'><?php echo ($this->i18nLoginSubmitLabel)?iMsg($this->loginSubmitLabel):$this->loginSubmitLabel; ?></button>
		</li>
	</ol>
</form>
<div style="clear:both"></div>
</div>

<?php 
$this->drawArray($this->contentAfterLoginBox);
?>
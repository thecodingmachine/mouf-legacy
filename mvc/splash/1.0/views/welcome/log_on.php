<div style="float: left;padding-right: 15px">
<div class="stats-key" style="float: left;width: 450px;height: 300px;">
<form name="logon" action="<?php echo ROOT_URL ?>Login/logon" id="form" method="post">
	<br/>
	<?php
	if (SessionUtils::getRedirectUrl()!=null){ ?>
		<input type="hidden" name="doRedirectAfterLogin" value="<?php echo SessionUtils::getRedirectUrl()?>"/>
	<?php
		SessionUtils::removeRedirectUrl();
	} ?>
	<div id="log-on-member"><?php eMsg('logon.allready.member'); ?></div>
	<div style="padding: 20px;border: 1px solid white; width: 350px;margin: auto">
		<table>
			<tr>
				<td id="table-header"><?php eMsg('logon.email');?> : </td>
				<td><input size="35" type="text" value="you@your_domain.com" onfocus="erase(this);" id="loEmail" name="loEmail"/></td>
			</tr>
			<tr>
				<td id="table-header"><?php eMsg('logon.password');?> : </td>
				<td><input size="35" type="password" id="loPass" name="loPass"/></td>
			</tr>
		</table>
		<br/>
		<div id="sign_up_button" onclick="validateLogon()"><input type="image"  value="     "/></div>
		<p class="small_error" id="emailError"><?php eMsg('email.invalid'); ?></p>
		<p class="small_error" id="passEmpty"><?php  eMsg('password.empty'); ?></p>
		<div style="display: none"></div>
	</div>
</form>
<br/>
<form id="formForgot" action="Welcome/sendForgotPassword" method="post">
	<div  onclick="document.getElementById('divEnterEmail').style.display = 'block';" style="cursor: pointer;padding-left: 40px">
		<a name="forgot_pwd" href="#forgot_pwd"><?php eMsg('logon.forgot.password'); ?></a>
	</div>
	<div id="divEnterEmail" style="display: none;" align="center">
		<br/>
		<div style="padding: 20px;border: 1px solid white; width: 350px;">
			<input name="forgotEmail" id="forgotEmail" type="text" value="<?php eMsg('welcome.forgot.ask.email')?>" onclick="if (this.value=='<?php eMsg('welcome.forgot.ask.email')?>') this.value=''"/>
			<input type="button" onclick="validateEmail()" value="OK"/>
			<p class="small_error" id="emailError2"><?php eMsg('email.invalid'); ?></p>
		</div>
	</div>
</form>
<br/>
</div>
</div>
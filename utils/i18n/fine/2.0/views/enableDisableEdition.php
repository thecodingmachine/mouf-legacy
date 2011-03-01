<h1>Message edition</h1>
<p>When navigating in your application, you can have links automatically added by Fine to edit your messages. By enabling 
the message edition feature, you will be able to edit messages directly into you web application.</p>
<p>Message edition is <b><?php echo ($this->isMessageEditionMode==true)?"enabled":"disabled" ?></b></p>
<p>Change message edition status:</p>
<form action="setMode" method="post">
	<input type="radio" name="mode" value="on" id="mode_on" <?php echo ($this->isMessageEditionMode==true)?"checked":"" ?>/><label for="mode_on" style="float: none">Enable</label><br/>
	<input type="radio" name="mode" value="off" id="mode_off" <?php echo ($this->isMessageEditionMode==true)?"":"checked" ?>/><label for="mode_off" style="float: none">Disable</label><br/>
	<br/>
	<p>Message auto check is <b><?php echo ($this->isMessageAutoMode==true)?"enabled":"disabled" ?></b></p>
	<p>If edit mode is activated, you could create all forgotten element automatically:</p>
	<input type="radio" name="auto" value="on" id="auto_on" <?php echo ($this->isMessageAutoMode==true)?"checked":"" ?>/><label for="auto_on" style="float: none">Enable</label><br/>
	<input type="radio" name="auto" value="off" id="auto_off" <?php echo ($this->isMessageAutoMode==true)?"":"checked" ?>/><label for="auto_off" style="float: none">Disable</label><br/>
	<input type="submit" value="Save" />
</form>

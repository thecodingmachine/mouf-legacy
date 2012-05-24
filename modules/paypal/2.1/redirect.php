<?php /* @var $this PaypalService */ ?>
<html>
	<head>
	</head>
	<body onload="alert('redirect!');document.forms[0].submit()">
	<?php echo $this->generateForm($request, $payment->id); ?>
	</body>
</html>
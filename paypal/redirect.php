<html>
	<head>
	</head>
	<body onload="document.forms[0].submit()">
	<?php echo $this->generateForm($request, $payment->id); ?>
	</body>
</html>
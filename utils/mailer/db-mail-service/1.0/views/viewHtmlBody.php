<?php 
/*@var $this DBMailServiceListController */
?>
<html>
	<head>
		<script type="text/javascript">
		function init() {
			var _docHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight;
			window.parent.setIframeHeight(_docHeight);
		}
		</script>
	</head>
	<body onload="init();">
		<?php echo $this->mail->getBodyHtml(); ?>
	</body>
</html>
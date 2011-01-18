<?php /* @var $this InstallController */ ?>
<h1>Installation in progress, please wait...</h1>

<div id="installProcess">
<?php 
include 'displaySteps.php';
?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery.ajaxSetup({
	  "error":function() {   
		jQuery('#installProcess').html("<div class='error'>An error occured in the install process.</div>");
	}});
	var performNextStep = function() {
		jQuery.getJSON("<?php echo ROOT_URL ?>mouf/install/nextstep", null, function(json){
			if (json.code == "finished") {
				//jQuery('#installProcess').html("<div class='good'>Installation finished. TODO: redirect</div>");
				jQuery('#installProcess').html(json.html);
			} else if (json.code == "continue") {
				jQuery('#installProcess').html(json.html);
				performNextStep();
			} else {
				jQuery('#installProcess').html("<div class='error'>Unknown JSON answer</div>");
			}
							
		});
	};
	performNextStep();
});
</script>

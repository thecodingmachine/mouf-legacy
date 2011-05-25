<?php /* @var $this InstallController */ ?>
<h1>Installation in progress, please wait...</h1>

<div id="errorZone">
</div>
<div id="installProcess">
<?php 
include 'displaySteps.php';
?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery.ajaxSetup({
	  "error":function(xmlHttpRequest) {   
		jQuery('#errorZone').append("<div class='error'>An error occured in the install process. Error message:<br/><pre>"+xmlHttpRequest.responseText+"</pre></div>");
	}});
	var performNextStep = function() {
		jQuery.getJSON("<?php echo ROOT_URL ?>mouf/install/nextstep", null, function(json){
			if (json.code == "finished") {
				//jQuery('#installProcess').html("<div class='good'>Installation finished. TODO: redirect</div>");
				jQuery('#installProcess').html(json.html);
			} else if (json.code == "continue") {
				jQuery('#installProcess').html(json.html);
				performNextStep();
			} else if (json.code == "redirect") {
				window.location.href = json.redirect;
				return;
			} else if (json.code == "error") {
				jQuery('#installProcess').html("<div class='error'>"+json.html+"</div>");
				performNextStep();
			} else {
				jQuery('#installProcess').html("<div class='error'>Unknown JSON answer</div>");
			}
							
		});
	};
	performNextStep();
});
</script>

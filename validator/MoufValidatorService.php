<?php

/**
 * Service specialized in validating the environment.
 * The validator service centralizes the validation steps provided by "Validation Providers" (implementing the MoufValidationProviderInterface).
 * 
 * @Component
 */
class MoufValidatorService implements HtmlElementInterface {
	
	/**
	 * The array of validators that will be run when validation is triggered.
	 * 
	 * @Property
	 * @var array<MoufValidationProviderInterface>
	 */
	public $validators;
	
	/**
	 * Whether we are in selfEdit mode or not.
	 * Note: this is a string! It must be "true" to be in selfedit mode.
	 * 
	 * @var string
	 */
	public $selfEdit;
	
	public function toHtml() {
?>	
		<div id="validators"></div>
		<script type="text/javascript">
				
		function addValidator(name, url) {
// FIXME:  todo: detect bad JSON and display an error message!

			if (typeof(window.moufNbValidators) == "undefined") {
				window.moufNbValidators = 0;
			} else {
				window.moufNbValidators++;
			}
			var validatorNb = window.moufNbValidators;
			jQuery('#validators').append("<div id='validator"+validatorNb+"' class='validator'><div class='loading'>Running "+name+"</div></div>");


			jQuery.ajaxSetup({
			  "error":function() {   
				jQuery('#validator'+validatorNb).html("<div class='error'>Unable to run '"+name+"'</div>");
			}});	
			jQuery.getJSON("<?php echo ROOT_URL ?>"+url, null, function(json){
				if (json.code == "ok") {
					jQuery('#validator'+validatorNb).html("<div class='good'>"+json.html+"</div>");
				} else if (json.code == "warn") {
					jQuery('#validator'+validatorNb).html("<div class='warning'>"+json.html+"</div>");
				} else {
					jQuery('#validator'+validatorNb).html("<div class='error'>"+json.html+"</div>");
				}
								
			});
		}
		jQuery(document).ready(function() {
<?php 


			foreach ($this->validators as $validator) {
				/* @var $validator MoufValidationProviderInterface */
				echo "addValidator('".addslashes($validator->getName())."', '".addslashes($validator->getUrl())."')\n";
			}
?>
		});
		</script>
<?php 
		
	}
	
	/**
	 * Registers dynamically a new validator. 
	 * 
	 * @param string $name
	 * @param string $url
	 * @param array<string> $propagatedUrlParameters
	 */
	public function registerBasicValidator($name, $url, $propagatedUrlParameters = null) {
		$this->validators[] = new MoufBasicValidationProvider($name, $url, $propagatedUrlParameters);
	}
	
	/**
	 * Registers dynamically a new validator. 
	 * 
	 * @param MoufValidationProviderInterface $validationProvider
	 */
	public function registerValidator(MoufValidationProviderInterface $validationProvider) {
		$this->validators[] = $validationProvider;
	}
}

?>
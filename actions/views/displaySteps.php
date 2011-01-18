<?php /* @var $this InstallController */ 

foreach ($this->actionsList as $actionDescriptor) {
	/* @var $actionDescriptor MoufActionDescriptor */
	try {
		echo "<div class='".$actionDescriptor->status."'>".$actionDescriptor->getName()."</div>";
	} catch (MoufInstanceNotFoundException $e) {
		// If we can't find the action provider, maybe it is not installed yet.
		// Let's just not display the name.
		echo "<div class='".$actionDescriptor->status."'>Install action provided by '".plainstring_to_htmlprotected($actionDescriptor->actionProviderName)."'...</div>";
	}
}

if ($this->done) {
	echo "<div class='good'>".$this->multiStepActionService->getConfirmationMessage()."</div>";
	echo "<p><a href='".plainstring_to_htmlprotected($this->multiStepActionService->getFinalUrlRedirect())."'>Continue</a></p>";
}
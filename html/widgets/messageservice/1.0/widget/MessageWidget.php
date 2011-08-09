<?php

/**
 * The MessageWidget is in charge of displaying HTML messages.
 * Those messages are registered using an object implementing the MessageProviderInterface object.
 * Usually, this is a SessionMessageService object, and the developper accesses that object using the simple "set_user_message" function.
 * 
 * @author David Negrier
 * @Component
 */
class MessageWidget implements HtmlElementInterface {
	
	/**
	 * The object that will return the messages to be displayed.
	 * 
	 * @Property
	 * @Compulsory
	 * @var MessageProviderInterface
	 */
	public $messageProvider;
	
	/**
	* Renders the messages in HTML.
	* The Html is echoed directly into the output.
	*/
	public function toHtml() {
		
		// An array of messages where the message is the KEY and the value is array("type"=>$type, "nbOccurences"=>$nbOcc).
		// Used to avoid displaying duplicate messages.
		$invertedMessages = array();
		
		$toDisplayMessages = array();
		
		$messages = $this->messageProvider->getMessages();
		foreach ($messages as $message) {
			/* @var $message UserMessageInterface */
			$html = $message->getMessage();
			$type = $message->getType();
			if (isset($invertedMessages[$html]) && $invertedMessages[$html]["type"] == $type) {
				$invertedMessages[$html]["nbOccurences"] += 1;
			} else {
				$invertedMessages[$html] = array("type"=>$type, "nbOccurences"=>1);
				$toDisplayMessages[] = $message;
			}
		}
		
		foreach ($toDisplayMessages as $message) {
			/* @var $message UserMessageInterface */
			$html = $message->getMessage();
			$type = $message->getType();
			
			echo "<div class='".$type."'>";
			echo $html;
			if ($invertedMessages[$html]["nbOccurences"] > 1) {
				// TODO: translate this.
				echo " (message repeated ".$invertedMessages[$html]["nbOccurences"]." times)";
			}
			echo "</div>";
		}
	}
}
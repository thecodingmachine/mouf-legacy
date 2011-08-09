<?php

/**
 * Sets a message to be displayed to a user.
 *
 * @param string $html The message to be displayed, as a HTML string.
 * @param string $type The type of the message. Can be one of UserMessageInterface::SUCCESS, UserMessageInterface::INFO, UserMessageInterface::WARNING, UserMessageInterface::ERROR.
 * @param string $category The category of the message to set. A category is a string. If "null", the global category is used (this means the message will be displayed at the top of the screen).
 */
function set_user_message($html, $type = UserMessageInterface::ERROR, $category = null) {
	$instance = MoufManager::getMoufManager()->getInstance("userMessageService");
	/* @var $instance SessionMessageService */
	$instance->setMessage($html, $type, $category);
}
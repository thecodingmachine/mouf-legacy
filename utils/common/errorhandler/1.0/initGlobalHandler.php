<?php

set_error_handler(array("GlobalErrorHandler", "handleGlobalError"));
set_exception_handler(array("GlobalErrorHandler", "handleGlobalException"));
register_shutdown_function('moufFatalErrorShutdownHandler');

function moufFatalErrorShutdownHandler()
{
	$last_error = error_get_last();
	if ($last_error != null && $last_error['type'] === E_ERROR) {
		// fatal error
		GlobalErrorHandler::globalErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}
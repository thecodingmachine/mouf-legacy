<?php
$msg['controller.annotation.var.missingclosebracket.title'] = "An error was detected in @Var annotation.";
$msg['controller.annotation.var.missingclosebracket.text']="<p class=\"small\" style=\"color: red;\">An error was detected in @Var annotation. Missing closing bracket in: '{0}'.</p>";

$msg['controller.annotation.var.unabletofindvalidator.title'] = "An error was detected in @Var annotation.";
$msg['controller.annotation.var.unabletofindvalidator.text']="<p class=\"small\" style=\"color: red;\">An error was detected in @Var annotation. Unable to find Validator '{0}'. Please check that this class exist and was included in your project.</p>";

$msg['controller.annotation.var.urlorigintakesanint.title'] = "An error was detected in @Var annotation.";
$msg['controller.annotation.var.urlorigintakesanint.text']="<p class=\"small\" style=\"color: red;\">An error was detected in @Var annotation. In the origin, a 'url' origin was specified. The parameter for 'url' must be an integer. Origin specified: {0}</p>";

$msg['controller.annotation.var.incorrectcommand.title'] = "An error was detected in @Var annotation.";
$msg['controller.annotation.var.incorrectcommand.text'] = "<p class=\"small\" style=\"color: red;\">An error was detected in @Var annotation. In the 'origin' parameter, you can specify either request / session or url. '{0}' was passed as a command.</p>";

$msg['controller.annotation.var.validationexception.title'] = "Validator error.";
$msg['controller.annotation.var.validationexception.text'] = "An error was detected in a validator.";

$msg['controller.annotation.var.validationexception.debug.title'] = "Validator error.";
$msg['controller.annotation.var.validationexception.debug.text'] = "An error was detected in the validator \"<i>{0}</i>\" for the argument \"<i>{1}</i>\" with value \"<i>{2}</i>\".";

$msg['error.500.title']="An error occured in the application.";
$msg['error.500.text']="We are sorry, an error occured in the application. Please <a href='".ROOT_URL."'>click here</a> to go back to the home page.";

$msg['404.back.on.tracks']="Go to <a href='".ROOT_URL."'>Home Page</a> to be back on tracks!";
$msg['404.wrong.class']="The URL seems to contain an error.";
$msg['404.wrong.file']="The URL seems to contain an error. ";
$msg['404.wrong.method']="The URL seems to contain an error. ";
$msg['404.wrong.url']="The URL seems to contain an error.";
?>
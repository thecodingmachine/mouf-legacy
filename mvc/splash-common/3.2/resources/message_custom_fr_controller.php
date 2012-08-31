<?php
$msg['controller.404.no.action']="Note pour les développeurs: le controlleur '{0}' a été trouvé, et la fonction '{1}' existe. Cependant, la fonction '{1}' ne possède pas d'annotation @Action. Elle ne peut donc pas être accédée par URL.";
$msg['controller.annotation.var.incorrectcommand.text']="<p class=\"small\" style=\"color: red;\">Une erreur a été détectée dans une annotation @Var. Dans le paramètre 'origin', seules les commandes request / session ou url sont acceptées. '{0}' a été passé en commande.</p>";
$msg['controller.annotation.var.incorrectcommand.title']="Une erreur a été détectée dans une annotation @Var.";
$msg['controller.annotation.var.missingclosebracket.text']="<p class=\"small\" style=\"color: red;\">Une erreur a été détectée dans une annotation @Var. Un crochet fermant (]) est manquant: '{0}'.</p>";
$msg['controller.annotation.var.missingclosebracket.title']="Une erreur a été détectée dans une annotation @Var.";
$msg['controller.annotation.var.unabletofindvalidator.text']="<p class=\"small\" style=\"color: red;\">Une erreur a été détectée dans une annotation @Var. Impossible de trouver le Validator '{0}'. Vérifier que cette classe existe et qu'elle est bien inclue dans le projet.</p>";
$msg['controller.annotation.var.unabletofindvalidator.title']="Une erreur a été détectée dans une annotation @Var.";
$msg['controller.annotation.var.urlorigintakesanint.text']="<p class=\"small\" style=\"color: red;\">Une erreur a été détectée dans une annotation @Var. Dans le paramètre 'origin', 'url' a été spécifié. Le paramètre pour 'url' Doit être un entier. 'origin' specifiée: {0}</p>";
$msg['controller.annotation.var.urlorigintakesanint.title']="Une erreur a été détectée dans une annotation @Var.";
$msg['controller.annotation.var.validation.error']="Les paramètres passés dans l'URL sont incorrects: Le paramètre {1} devrait passer le validateur {0}, mais ce n'est pas le cas. Valeur passée: '{2}'.";
$msg['controller.annotation.var.validation.error.title']="Les paramètres passés dans l'URL sont incorrects";
$msg['controller.annotation.var.validationexception.debug.text']="Une erreur de validation a été détectée dans le validateur \"<i>{0}</i>\" pour l'argument \"<i>{1}</i>\" avec la valeur \"<i>{2}</i>\".";
$msg['controller.annotation.var.validationexception.debug.title']="Une erreur de validation a été détectée.";
$msg['controller.annotation.var.validationexception.text']="Une erreur a été détectée lors de la validation d'un champ.";
$msg['controller.annotation.var.validationexception.title']="Une erreur a été détectée.";
$msg['controller.incorrect.parameter.text']="Les paramètres passés dans l'URL sont incorrects: L'URL correspond à l'action '{0}->{1}'. Cette action nécessite que le paramètre '{2}' soit spécifié.";
$msg['controller.incorrect.parameter.title']="Les paramètres passés dans l'URL sont incorrects.";
?>

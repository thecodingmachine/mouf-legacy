<?php
MoufAdmin::getValidatorService()->registerBasicValidator('Fine i18n validator', 'plugins/utils/i18n/fine/2.1/direct/check_default_instance.php');
MoufAdmin::getValidatorService()->registerBasicValidator('Fine i18n validator', 'plugins/utils/i18n/fine/2.1/direct/check_default_message_file.php');
MoufAdmin::getValidatorService()->registerBasicValidator('Fine i18n validator', 'plugins/utils/i18n/fine/2.1/direct/check_missing_default_messages.php');
?>
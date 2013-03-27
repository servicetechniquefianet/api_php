<?php
define('FIANET_USE_CDATA', true); //set it to true if you want to protect the values in the XML stream from special chars that could break the stream and cause errors. We strongly advise you to let it set to true.
define('ROOT_DIR', str_replace('\\', '/', realpath(dirname(__FILE__).'/../..')));

//these lines are mandatory
require_once ROOT_DIR . '/lib/includes/functions.inc.php';
require_once ROOT_DIR . '/lib/kernel/includes.inc.php';
require_once ROOT_DIR . '/lib/common/includes.inc.php';

//these lines are not mandatory. Uncomment only the ones you need
require_once ROOT_DIR . '/lib/kwixo/includes.inc.php';
require_once ROOT_DIR . '/lib/sac/includes.inc.php';
require_once ROOT_DIR . '/lib/sceau/includes.inc.php';
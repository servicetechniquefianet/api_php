<?php

// relever le point de départ
$timestart = microtime(true);

/* * ********************************************
 * Checks Kwixo availability
 * ********************************************* */
require_once '../../includes/includes.inc.php';
$kwixo = new Kwixo();

echo "<h2>Kwixo on standard platforms</h2>";
//checks payment in once on standard platform
echo ($kwixo->cashAvailable() ?
        'Kwixo in once is available.' :
        'Kwixo is not available');
echo '<br />';
//checks payment in installements on standard platform
echo ($kwixo->creditAvailable() ?
        'Kwixo in installements is available.' :
        'Kwixo in installements is not available.');
echo '<hr />';
echo "<h2>Kwixo on mobile platforms</h2>";
//checks payment in once on mobile platform
echo ($kwixo->cashAvailable(true) ?
        'Kwixo in once is available on mobile.' :
        'Kwixo is not available on mobile.');
echo '<br />';
//checks payment in installements on mobile platform
echo ($kwixo->creditAvailable(true) ?
        'Kwixo in installements is available on mobile.' :
        'Kwixo in installements is not available on mobile.');


echo '<hr />';
echo "<h2>Kwixo on this platform</h2>";
/** global check * */
//detects platform type
$mobile_detect = new MobileDetect();
$mobile = $mobile_detect->isMobile();

echo "<i>This platform is ".(!$mobile ? "not " : "")."mobile.</i><br />";
//render availability
echo $kwixo->cashAvailable($mobile) ? 'Kwixo comptant disponible' . ($mobile ? ' sur mobile' : '') . '.' : 'Kwixo non dispo' . ($mobile ? ' sur mobile' : '') . '.';
echo $kwixo->creditAvailable($mobile) ? 'Kwixo crédit disponible' . ($mobile ? ' sur mobile' : '') . '.' : 'Kwixo crédit non dispo' . ($mobile ? ' sur mobile' : '') . '.';


echo "<hr />";

//Fin du code PHP
$timeend = microtime(true);
$time = $timeend - $timestart;

//Afficher le temps d'éxecution
$page_load_time = number_format($time, 3);
echo "Debut du script: " . date("H:i:s", $timestart);
echo "<br>Fin du script: " . date("H:i:s", $timeend);
echo "<br>Script execute en " . $page_load_time . " sec";
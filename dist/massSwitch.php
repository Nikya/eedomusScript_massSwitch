<?php
/** ****************************************************************************
* Nikya eedomus Script Mass Switch
********************************************************************************
* Plugin version : 1.0
* Author : Nikya
* Origine : https://github.com/Nikya/eedomusScript_massSwitch
*******************************************************************************/

/**DEV EMULATOR*/ // require_once ("eedomusScriptsEmulator.php");

/** ***************************************************************************
* Init
*/

/** Separator  */
define('SEP_PID', ',');
define('SEP_VAL', ':');

/** The full Action Tab */
$actionMap = array();


/** ***************************************************************************
* Read
*/

$pToOff = trim(getArg('toOff', false, ''));
$pToOn = trim(getArg('toOn', false, ''));
$pToVal = trim(getArg('toVal', false, ''));
$pForce = trim(getArg('force', false, ''));
$force = strtolower($pForce) ==  'true';


/** ***************************************************************************
* Build
*/

// With OFF devices
if(strlen($pToOff) == 0)
	$aToOff = array();
else
	$aToOff = explode(SEP_PID, $pToOff);
foreach ($aToOff as $pid) {
	$actionMap[$pid] = 0;
}


// With ON devices
if(strlen($pToOn) == 0)
	$aToOn = array();
else
	$aToOn = explode(SEP_PID, $pToOn);
foreach ($aToOn as $pid) {
	$actionMap[$pid] = 100;
}

// With VALUED devices
if(strlen($pToVal) == 0)
	$aToVal = array();
else
	$aToVal = explode(SEP_PID, $pToVal);
foreach ($aToVal as $pidV) {
	$pidAndValue = explode(SEP_VAL, $pidV);
	if(count($pidAndValue) == 2) {
		$actionMap[$pidAndValue[0]] = $pidAndValue[1];
	}
}


/** ***************************************************************************
* Execute
*/
$continue = false;
$exeCpt = 0;
$exeDetails = '';

foreach ($actionMap as $pid => $val) {
	if ($force)
		$continue = true;
	else {
		$cValueTs = getValue($pid);
		$cValue = $cValueTs['value'];
		if ($cValue != $val)
			$continue = true;
		else
			$continue = false;
	}

	if ($continue) {
		setValue($pid, $val);
		$exeCpt++;
		$exeDetails .= "{\"pid\" : $pid, \"val\" : $val},";
	}
}
$exeDetails = substr($exeDetails, 0, -1);


/** ***************************************************************************
* Result repport
*/
$msgFr = $exeCpt . " actions executées";
$msgEn = $exeCpt . " executed actions";
$forceStr = $force ? 'true' : 'false';

$response = ' { ';
$response.= ' "params" : {';
$response.= '   "ToOffCount" : '. count($aToOff) .', ';
$response.= '   "ToOnCount" : '. count($aToOn) .', ';
$response.= '   "ToValCount" : '. count($aToVal) .', ';
$response.= '   "force" : '. "\"$forceStr\"" ;
$response.= ' }, ';
$response.= ' "results" : {';
$response.= '   "exe_msg_fr" : '. "\"$msgFr\""  .', ';
$response.= '   "exe_msg_en" : '. "\"$msgEn\""  .', ';
$response.= '   "exeCount" : '. $exeCpt  .', ';
$response.= '   "executions" : [';
$response.= 		$exeDetails ;
$response.= '   ]';
$response.= ' } ';
$response.= ' } ';


/** ****************************************************************************
* Fin du script, affichage du résultat au format XML
*/
//echo $response; exit;
sdk_header('text/xml');
echo jsonToXML($response);

<?php
    
/**
 * Used to setup and fix common variables and include
 * the Joel procedural and class library.
 *
 * You should not have to change this file and allows
 * for some configuration in wp-config.php.
 *
 * @package Joel
 */



if (!defined('JOEL_MEMORY_LIMIT') )
	define('JOEL_MEMORY_LIMIT', '32M');

if ( function_exists('memory_get_usage') && ( (int) @ini_get('memory_limit') < abs(intval(JOEL_MEMORY_LIMIT)) ) )
	@ini_set('memory_limit', JOEL_MEMORY_LIMIT);


/** try to find globals overwrite */
if (isset($_REQUEST['GLOBALS']) )
	die('GLOBALS overwrite attempt detected');


define('BASEDIR', dirname(dirname(__FILE__)));
require BASEDIR."/config.inc.php";
require BASEDIR.'/app/library/adodb_lite/adodb.inc.php';
require BASEDIR.'/app/library/flobo.utils.php';

function autoload($name) {
  if(file_exists($file = CONTROLLER.'/'.ucfirst($name).'.php')) {
		include_once $file;
	
	} elseif(file_exists($file = LIBRARY.'/class.'.ucfirst($name).'.php')) {
		include_once $file;
	
	} elseif(file_exists(MODELS.'/'.ucfirst($name).'.php')) {
		include_once MODELS.'/'.ucfirst($name).'.php';
	
	} elseif(isset($_SESSION['joel']) && file_exists($file = BASEDIR.'/'.$_SESSION['joel']->client.'/controller/'.ucfirst($name).'.php')) {
		include_once $file;
	
	} elseif(preg_match('/pear/',$name)) { 
		return false;
	
	} elseif(preg_match("/^Plugin_/",$name)) { 
		if(file_exists($file = BASEDIR.'/plugins/'.substr($name,7,-10).'/plugin.php')) {
			include_once $file;
		}
	
	} elseif(preg_match("/ADO/",$name)) {
		$driver = substr($name,0,strpos($name,'_'));
		include_once dirname(__FILE__).'/library/adodb_lite/adodbSQL_drivers/'.$driver.'/'.$driver.'_driver.inc';
	}
}

spl_autoload_register('autoload');

if(!isset($_SESSION)) session_start();
if(isset($_GET['reset'])) session_destroy();

// Get database up and runnin'
if(!isset($_SESSION['db'])) 
	$_SESSION['db'] =& ADONewConnection($db_config['databasetype']);

$_SESSION['db']->PConnect($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['databasename']) 
	or die("database connection failed");

$_SESSION['db']->execute("SET NAMES `utf8` COLLATE `utf8_general_ci`");

if(!isset($_SESSION['joel'])) { 
	$_SESSION['joel'] = new JoelController();
}

$_SESSION['joel']->loadPlugins();

require_once(BASEDIR.'/'.$_SESSION['joel']->client.'/bootstrap.php');


?>
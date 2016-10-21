<?php

require_once("mysqlite3.php");
require_once("application.php");


ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');
//set_error_handler(array('ServerErrorLogHandler', 'errorHandler'));

$globalRefreshInterval = 3;


######## START SESSION ##########

$sessionId = session_id();

if(empty($sessionId))
{
  session_start();
  $sessionId = session_id();
}

// if($_GET['clientID'])
// {
//     $_SESSION['client'] = $_POST['clientID'];
// }
if(array_key_exists('name', $_POST) && !empty($_POST['name']))
{
	$_SESSION['name'] = $_POST['name'];
}
if(array_key_exists('groupid', $_POST) && !empty($_POST['groupid']))
{
	$_SESSION['groupid'] = $_POST['groupid'];
}
if( !isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > $globalRefreshInterval )
{
  $_SESSION['last_access'] = time();
}

######## DATABASE STUFF ################
$objDb = new MySQLite3();
$objDb->installDb();

if(!$objDb){
  echo $objDb->lastErrorMsg();
} else {
  //echo "Opened database successfully\n";
}

######## GET APPLICATION STUFF #########

$objApp = new Application($objDb);

//error_log(print_r($_GET, true));


if(array_key_exists('mode', $_POST) && !empty($_POST['mode']))
{
  //error_log(print_r($_POST, true));
  switch ($_POST['mode'])
  {
    case 'setmsg':
      $msg = null;
      if(array_key_exists('msg', $_POST))
      {
        $msg = $_POST['msg'];
      }
      $objApp->pushMessage($msg, $_SESSION['name'], $_SESSION['groupid']);
      break;
    default:
      # code...
      break;
  }
}

if(array_key_exists('mode', $_GET) && !empty($_GET['mode']))
{

  switch ($_GET['mode'])
  {
    case 'update':
      $msgCollection = $objApp->getUpdate($_SESSION['last_access'], $globalRefreshInterval);
      echo json_encode($msgCollection);
      break;

    case 'showall':
      $msgCollection = $objApp->getAll();
      //echo json_encode($msgCollection);
      echo "<pre style='background: #cbffbc;'>";
      print_r($msgCollection);
      echo "</pre>";
      // http://itpc25/notifications/server/index.php?mode=showall
      break;
    default:
      # code...
      break;
  }
}

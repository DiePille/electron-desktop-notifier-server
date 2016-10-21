<?php

class Application
{

  private $objDB = null;

  public function __construct( \SQLite3 $dbhandle)
  {
    $this->objDB = $dbhandle;
  }

  public function getUpdate($lastAccess, $globalRefreshInterval)
  {
    $msgCollection = false;

    $test = $lastAccess - $globalRefreshInterval;

    $ret = $this->objDB->query('SELECT * FROM MASSAGE WHERE INSERTTIME > '.($lastAccess - $globalRefreshInterval).'');
    //print_r('SELECT * FROM MASSAGE WHERE INSERTTIME < '.($lastAccess - $globalRefreshInterval).'');

    while($row = $ret->fetchArray(SQLITE3_ASSOC) )
    {
      $msgCollection[] = array('body'=> html_entity_decode($row['MASSAGE']), 'title' => 'Nachricht von '.$row['USERNAME'].': ');
    }

   return $msgCollection;

  }

  public function getAll()
  {
    $msgCollection = false;

    $sql = "SELECT * FROM MASSAGE ORDER BY INSERTTIME DESC";
    $ret = $this->objDB->query($sql);

    while($row = $ret->fetchArray(SQLITE3_ASSOC) )
    {
      $msgCollection[] = $row;
    }

   return $msgCollection;

  }
  public function pushMessage( $msg, $from, $groupid)
  {
    $msg = htmlentities($msg);
    $sql = "INSERT INTO MASSAGE (MASSAGE, USERNAME, GROUPID, INSERTTIME) VALUES ( '".$msg."', '".$from."', ".$groupid.", '".time()."' );";
	
    $ret = $this->objDB->exec($sql);

    if(!$ret)
    {
      echo $this->objDB->lastErrorMsg();
    }
    else
    {
      echo true;
    }

  }


}

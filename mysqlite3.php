<?php

class MySQLite3 extends SQLite3
{

  public function __construct()
  {
    $this->open('msgdb.db');
  }


  public function installDb()
  {
    $sql ="CREATE TABLE IF NOT EXISTS MASSAGE (ID INTEGER PRIMARY KEY,
      MASSAGE TEXT NOT NULL,
      USERNAME  TEXT    NOT NULL,
      GROUPID   INT     NOT NULL,
      'INSERTTIME' TIMESTAMP     NOT NULL)";
    
    $ret = $this->exec($sql);
    if(!$ret){
      echo $this->lastErrorMsg();
    } else {
      //echo $this->changes(), " Record updated successfully\n";
    }

  }

}

<?php
  class Database{
    function MySqlConnection(){
      $host   = "localhost";
      $uname  = "root";
      $pass   = "";
      $db     = "";




      $baglanti = @mysqli_connect($host,$uname,$pass,$db);
      mysqli_set_charset($baglanti, "utf8");
      return $baglanti;
    }
  }

?>

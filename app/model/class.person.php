<?php
  class Person{
    protected $userId, $name, $surname, $email,$userName, $pass, $newPass, $tel;
    public function setUserId($userId){
      $this->userId = (int)$userId;
    }
    public function setName($name){
      $this->name = trim(strip_tags($name));
    }
    public function setSurname($surname){
      $this->surname = trim(strip_tags($surname));
    }
    public function setEmail($email){
      $this->email = trim(strip_tags($email));
    }
    public function setUserName($userName){
      $this->userName = trim(strip_tags($userName));
    }
    public function setPass($pass){
      $this->pass = trim(strip_tags($pass));
    }
    public function setNewPass($newPass){
      $this->newPass = trim(strip_tags($newPass));
    }
    public function setTel($tel){
      $this->tel = (int)$tel;
      $this->tel = (string)$tel;
      $this->tel = trim(strip_tags($tel));
    }

    #true veya false değeri döndürür.(Boş ise true değilse false)
    protected function IsNullOrEmptyString($str){
      if(is_array($str)){
        foreach ($str as $deger) {
          if(!isset($deger) || trim($deger) === '' || trim($deger) == '' || trim($deger) == null){
            return true;
          }
        }
        return false;
      }
      else return (!isset($str) || trim($str) === '');
    }
    protected function tcNoControl($tcNo=null){
      if(is_null($tcNo) || trim($tcNo) == "" || trim($tcNo) == null) return false;
      else {
        $tcNo = (string)$tcNo;
        if(strlen($tcNo) != 11) return false;
        else if(intval(($tcNo[strlen($tcNo)-1])) % 2 != 0) return false;
        else {
          $sum=0;
          for($i=0;$i<10;$i++){
            if(!intval($tcNo[$i])) return false;
            else $sum += (int)$tcNo[$i];
          }
          $sum = (string)$sum;
          if($sum[strlen($sum)-1] == $tcNo[strlen($tcNo)-1])return true;
          else return false;
        }
      }
    }
  }





?>

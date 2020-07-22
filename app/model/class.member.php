<?php
  class Member extends Person{

    #true veya hata mesajını döndürür.(Giriş basarılı ise true, değil ise hata mesajı)
    public function logIn(){
        $db = new Database();
        $connection = $db->MySqlConnection();
        if($connection){
          $email = mysqli_real_escape_string($connection, $this->email);
          $pass = mysqli_real_escape_string($connection, $this->pass);
          $sql = "SELECT * FROM users
          WHERE BINARY email='$email'
          AND pass=md5('$pass')";
          $query = mysqli_query($connection,$sql);
          if($query->num_rows == 1){
            $read = mysqli_fetch_array($query);
            $status = (int)$read["status"];
            if($status == 1){
              $confirm = (int)$read["confirm"];
              $settingOb = new Settings();
              $settings = $settingOb->getSettings();
              if(isset($settings["email_dogrulama"]) && (int)$settings["email_dogrulama"] == 1 && $confirm == 0){
                return "Giriş Yapabilmeniz İçin Lütfen Hesabınızı Mailinizden Onaylayınız.";
              }
              else{
                $_SESSION["krs_login"] = "true";
                $_SESSION["krs_name"] = $read["name"];
                $_SESSION["krs_surname"] = $read["surname"];
                $_SESSION["krs_email"] = $email;
                $_SESSION["krs_tel"] = $read["tel"];
                $_SESSION["krs_user_id"] = $read["user_id"];
                return true;
              }
            }
            else return "Hesabınız Askıya Alındığı İçin Giriş Yapamazsınız.";
          }
          else return "Kullanıcı Bulunamadı";
        }
        else {
          return "Bağlantı Hatası";
        }
    }
    #Gerekli sessionların değerlerini null yapar
    public function signOut(){
      $_SESSION["krs_login"] = null;
      $_SESSION["krs_name"] = null;
      $_SESSION["krs_surname"] = null;
      $_SESSION["email"] = null;
      $_SESSION["krs_user_id"] = null;
    }
    public function updateUserInformation(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return "Bağlantı Hatası";
      else {
        $userId=(int)$this->userId;
        $pass = mysqli_real_escape_string($connection,$this->pass);
        $name = mysqli_real_escape_string($connection,$this->name);
        $surname = mysqli_real_escape_string($connection,$this->surname);
        $tel = mysqli_real_escape_string($connection,$this->tel);
        $newPass = mysqli_real_escape_string($connection,$this->newPass);
        if($this->IsNullOrEmptyString($name) && $this->IsNullOrEmptyString($surname) && $this->IsNullOrEmptyString($tel) && $this->IsNullOrEmptyString($newPass)){
          return "En Az Bir Alanı Doldurunuz.";
        }
        else{
          $sql = "SELECT * FROM users WHERE user_id=$userId AND pass=md5('$pass')";
          $query = mysqli_query($connection,$sql);
          if($query->num_rows != 1) return "Şifrenizi Hatalı Girdiniz";
          else {
            $count=0;
            $updateSql = "UPDATE users SET ";
            if(!$this->IsNullOrEmptyString($name)){
              if($count++ > 0) $updateSql.=" , ";
              $updateSql .= " name='$name' ";
            }
            if(!$this->IsNullOrEmptyString($surname)){
              if($count++ > 0) $updateSql.=" , ";
              $updateSql .= " surname='$surname' ";
            }
            if(!$this->IsNullOrEmptyString($tel)){
              if($count++ > 0) $updateSql.=" , ";
              $updateSql .= " tel='$tel' ";
            }
            if(!$this->IsNullOrEmptyString($newPass)){
              if(strlen($newPass) < 8) return "Şifreniz En Az 8 Haneli Olmalıdır.";
              else if(strstr($newPass," ")) return "Şifrenizde boşluk olmamalıdır.";
              else if($count++ > 0) $updateSql.=" , ";
              $updateSql .= " pass=md5('$newPass') ";
            }

            $updateSql .= " WHERE user_id=$userId ";
            $updateQuery = mysqli_query($connection,$updateSql);
            $_SESSION["krs_name"] = $name;
            $_SESSION["krs_surname"] = $surname;
            $_SESSION["krs_tel"] = $tel;

            return ($updateQuery) ? true : "Bilgileriniz Güncellenemedi";
          }
        }
      }
    }
    public function banUser(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return "Bağlantı Hatası";
      else {
        $userId = (int)$this->userId;
        $getSql="SELECT * FROM users WHERE user_id=$userId";
        $getQuery=mysqli_query($connection,$getSql);
        if ($getQuery->num_rows <= 0) return "Kullanıcı Bulunamadı";
        else {
          $read=mysqli_fetch_array($getQuery);
          $status=(int)$read['status'];
          if ($status==0) return "Kullancı Zaten Engellenmiş";
          else {
            $sql = "UPDATE users
            SET status=0
            WHERE user_id=$userId";
            $query = mysqli_query($connection,$sql);
            return ($query) ? $query : "Kullanıcı Engellemede Hata Oluştu";
          }
        }
      }
    }
    public function unBanUser(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return "Bağlantı Hatası";
      else {
        $userId = (int)$this->userId;
        $sql = "UPDATE users
        SET status=1
        WHERE user_id=$userId";
        $query = mysqli_query($connection,$sql);
        return ($query) ? true : "Üyenin Engeli Kaldırılamadı";
      }
    }
    public function confirmUser($md5UserId=null){
      if(is_null(trim($md5UserId)) || trim($md5UserId) == "") return "Kullanıcı Bulunamadı1";
      else {
        $db = new Database();
        $connection = $db->MySqlConnection();
        if(!$connection) return "Bağlantı Başarısız";
        else{
          $md5UserId = mysqli_real_escape_string($connection,$md5UserId);
          if(strlen($md5UserId) != 32) return "Kullanıcı Bulunamadı2";
          else{
            $getSql = "SELECT * FROM users
            WHERE md5(user_id)='$md5UserId'";
            $getQuery = mysqli_query($connection,$getSql);
            if($getQuery->num_rows != 1) return "Kullanıcı Bulunamadı3";
            else{
              $updateSql = "UPDATE users
              SET confirm=1
              WHERE md5(user_id)='$md5UserId'";
              $updateQuery = mysqli_query($connection,$updateSql);
              return ($updateQuery) ? true : "Hesap Onaylanamadı";
            }
          }
        }
      }
    }

    public function getUsers(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return false;
      else {
        $sql = "SELECT * FROM users";
        $query = mysqli_query($connection,$sql);
        if($query->num_rows <= 0) return false;
        else {
          //$userInformation;
          $i=0;
          while($read = mysqli_fetch_array($query)){
            $userInformation[$i++] = $read;
          }
          return $userInformation;
        }
      }
    }
    public function getSearchUsers($get=null){
      if(!is_array($get) || count($get) <= 0) return false;
      else{
        $db = new Database();
        $connection = $db->MySqlConnection();
        if(!$connection) return false;
        else {
          $sql = "SELECT * FROM users WHERE ";
          $i=0;
          foreach ($get as $key => $value) {
            if($i++>0) $getSql.=" AND ";
            $sql .= " $key LIKE '%$value%' ";
          }
          $query = mysqli_query($connection,$sql);
          if($query->num_rows <= 0) return false;
          else {
            //$userInformation;
            $i=0;
            while($read = mysqli_fetch_array($query)){
              $userInformation[$i++]= $read;
            }
            return $userInformation;
          }
        }
      }
    }

    public function getUserInformation($md5Email=null){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return false;
      else {
        $sql = "";
        $userId = (int)$this->userId;
        $email = mysqli_real_escape_string($connection,$this->email);
        if((int)$userId > 0) $sql = "SELECT * FROM users WHERE user_id=$userId";
        else if(trim($email) != "" && !is_null($email)) $sql = "SELECT * FROM users WHERE email='$email'";
        else if(!is_null($md5Email)) $sql = "SELECT * FROM users WHERE md5(email)='$md5Email'";
        else return false;
        $query = mysqli_query($connection,$sql);
        if($query->num_rows != 1) return false;
        else {
          //$userInformation;
          return mysqli_fetch_array($query,MYSQLI_ASSOC);
        }
      }
    }



  }

?>

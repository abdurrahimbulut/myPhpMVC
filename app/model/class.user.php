<?php
  class User extends Person{
    #true veya hata mesajını string tipinde döndürür.
    public function signUp(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return "Bağlantı Hatası";
      else {
        $email = mysqli_real_escape_string($connection,$this->email);
        $name = mysqli_real_escape_string($connection,$this->name);
        $surname = mysqli_real_escape_string($connection,$this->surname);
        $tel = mysqli_real_escape_string($connection,$this->tel);
        $pass = mysqli_real_escape_string($connection,$this->pass);

        if($this->IsNullOrEmptyString(array($email,$name,$surname,$tel,$pass))) return "Lütfen Boş Değer Girmeyiniz";
        else if(strlen($tel) < 10) return "Lütfen Geçerli Bir Telefon Numarası Giriniz";
        else if(strlen($pass) < 8) return "Şifre en az 8 haneli olmalıdır";
        else if(strstr($pass," ")) return "Şifrenizde boşluk olmamalıdır.";
        else {
          $getSql = "SELECT * FROM users WHERE email ='$email'";
          $getQuery = mysqli_query($connection,$getSql);
          if($getQuery->num_rows > 0) return "Bu Emaile Ait Kullanıcı Mevcut";
          else {
            $date = date('d.m.Y');
            $sql = "INSERT INTO users VALUES('','$name','$surname','$email','$tel',md5('$pass'),'$date',0,1)";
            $query = mysqli_query($connection,$sql);
            return $query;
          }
        }
      }
    }
  }

?>

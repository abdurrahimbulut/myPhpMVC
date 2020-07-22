<?php
  $filePath = $_SERVER["SCRIPT_FILENAME"];
  $filePath = str_ireplace("/index.php","",$filePath);


  $subFolder = $_SERVER["SCRIPT_NAME"];
  $subFolder = str_ireplace("/index.php","",$subFolder);
  define('URL',"http://".$_SERVER["SERVER_NAME"].$subFolder);
  define('PATH',$filePath);


  function loadClasses(){
    require_once("./app/model/class.database.php");
    require_once("./app/model/class.person.php");
    require_once("./app/model/class.user.php");
    require_once("./app/model/class.member.php");
    require_once("./app/model/class.admin.php");
    require_once("./app/model/class.category.php");
    require_once("./app/model/class.product.php");
    require_once("./app/model/class.cart.php");
    require_once("./app/model/class.productInformation.php");
    require_once("./app/model/class.image.php");
    require_once("./app/model/class.filter.php");
    require_once("./app/model/class.filtervalues.php");
    require_once("./app/model/class.xml.php");
    require_once("./app/model/class.pages.php");

    require_once("./app/model/class.phpmailer.php");
    require_once("./app/model/class.mail.php");

    require_once("./app/model/class.productcomments.php");
    require_once("./app/model/class.address.php");

    require_once("./app/model/class.settings.php");
    require_once("./app/model/class.bank.php");
    require_once("./app/model/class.order.php");
    require_once("./app/model/class.exchange.php");
    require_once("./app/model/class.message.php");

  }

  function publicUrl($value=""){
    $url = URL."/app/user-app/view/public/$value";
    return $url;
  }
  function publicAdminUrl($value=""){
    $url = URL."/app/admin-app/view/public/$value";
    return $url;
  }
  function staticUrl($value=""){
    $url = "./app/user-app/view/public/static/$value";
    return $url;
  }
  function staticAdminUrl($value=""){
    $url = "./app/admin-app/view/public/static/$value";
    return $url;
  }
  function publicPath($value=""){
      $path = "./app/user-app/view/public/$value";
      return $path;
  }

  function view($page=false){
    $pageMessage = "";
    $parentPage ="";

    if (isset($_GET["url"])) {
      $url = explode('/',$_GET["url"]);
      $parentPage = trim($url[0]);
    }
    if ($parentPage=="profil" && !loginState()) {
      header("location:".url());
      exit;
    }
    else{
      include("app/user-app/controller/controller.php");
      include("app/user-app/controller/".$page."_controller.php");
      include( staticUrl("header.php"));
      if($parentPage == "profil") include(staticUrl("profil_side_bar.php"));
      include("app/user-app/view/".$page.".php");
      include( staticUrl("footer.php"));
    }
  }

  function adminView($page=false){
    $pageMessage = "";

    if($page != "giris" && !adminLoginState()){
      header("location:".url("yonetim/giris"));
    }
    else {

      include("app/admin-app/controller/".$page."_controller.php");

      include("app/admin-app/controller/controller.php");

      if($page != "giris") include( staticAdminUrl("header.php"));
      include("app/admin-app/view/".$page.".php");
      if($page != "giris") include( staticAdminUrl("footer.php"));


    }
  }

  function url($page=""){
    return URL."/".$page;
  }
  function adminUrl($page=""){
    return URL."/yonetim/".$page;
  }

  function loginState(){
    return (isset($_SESSION["krs_login"]) && $_SESSION["krs_login"] == "true");
  }
  function adminLoginState(){
    return (isset($_SESSION["krs_admin_login"]) && $_SESSION["krs_admin_login"] == "true");
  }

  function seoUrl($s) {
   $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',');
   $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','');
   $s = str_replace($tr,$eng,$s);
   $s = strtolower($s);
   $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
   $s = preg_replace('/\s+/', '-', $s);
   $s = preg_replace('|-+|', '-', $s);
   $s = preg_replace('/#/', '', $s);
   $s = str_replace('.', '', $s);
   $s = trim($s, '-');
   return $s;
  }


  function kisalt($kelime, $str = 10){
    if (strlen($kelime) > $str){
      if (function_exists("mb_substr")) $kelime = mb_substr($kelime, 0, $str, "UTF-8").'...';
      else $kelime = substr($kelime, 0, $str).'...';
    }
    return $kelime;
  }


?>

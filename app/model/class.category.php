<?php

  class Category{
    protected $categoryId, $parentId, $categoryName;
    public function setCategoryId($categoryId){
      $this->categoryId = (int)$categoryId;
    }
    public function setParentId($parentId){
      $this->parentId = (int)$parentId;
    }
    public function setCategoryName($categoryName){
      $this->categoryName = trim(strip_tags($categoryName));
    }
    public function __construct(){
      $this->categoryId = 0;
      $this->parentId = 0;
      $this->categoryName = null;
    }


    #Bağlantı yoksa false varsa dizi döndürür.
    public function getCategories(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return array("false");
      else {
        $parentId = (int)$this->parentId;
        $mainSql = "SELECT * FROM categories
        WHERE parent_id=$parentId";
        
        $mainQuery = mysqli_query($connection,$mainSql);
        $categories = array();

        while($mainRead = mysqli_fetch_array($mainQuery,MYSQLI_ASSOC)){
          $categoryId = (int)$mainRead["category_id"];
          $categories[$categoryId] = array("category_id" => $categoryId, "name" => $mainRead["name"]);
          $subSql = "SELECT * FROM categories
          WHERE parent_id=$categoryId";
          $subQuery = mysqli_query($connection,$subSql);
          if($subQuery->num_rows > 0){
            while($subRead = mysqli_fetch_array($subQuery,MYSQLI_ASSOC)){
              $this->parentId = $categoryId;
              $categories[$categoryId]["sub"] = $this->getCategories();
            }
          }
        }
        return $categories;
      }
    }
    #Tüm kategorileri döndürür. Eğer hata varsa false döndürür.
    public function getAllCategories(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection)return false;
      else {
        $sql = "SELECT * FROM categories ORDER BY category_id DESC";
        $query = mysqli_query($connection,$sql);
        $categories = array();
        $i=0;
        while($read = mysqli_fetch_array($query)){
          $categories[$i++] = array(
            "name"        =>  $read["name"],
            "categoryId"  =>  $read["category_id"],
            "parentId"    =>  $read["parent_id"]
          );
        }
        return $categories;
      }
    }
    #Parametre ile alınan id'nin bilgilerini döndürür.
    public function getSelectedCategory(){
      $db=new Database();
      $connection= $db->MySqlConnection();
      if($connection==false) return false;
      else{
        $sql="SELECT * FROM categories WHERE category_id=$this->categoryId";
        $query=mysqli_query($connection,$sql);
        if($query->num_rows != 1) return false;
        else{
          $read=mysqli_fetch_array($query);
          $categories = array(
            "name"=>$read["name"],
            "category_id"=>$read["category_id"]
          );
          return $categories;
        }
      }

    }
    #Alt kategorileri döndürür.
    public function getSubCategories(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return false;
      else {
        $sql = "SELECT * FROM categories WHERE parent_id=$this->categoryId";
        $query = mysqli_query($connection,$sql);
        if($query->num_rows <= 0) return false;
        else {
          $subCategories = array();
          $i=0;
          while($read = mysqli_fetch_array($query)){
            $subCategories[$i++] = array(
              "name"           =>  $read["name"],
              "category_id"    =>  $read["category_id"]
            );
          }

          return $subCategories;
        }
      }
    }
    #Kategori özelliklerini döndürür.

    public function getCategoriesAndSubCategories(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return array();
      else{
        $i=0; $parentId = 0;
        $categories = array();
        if(is_int($this->parentId) && $this->parentId > 0){
          $parentId = (int)$this->parentId;
        }

        $getSql = "SELECT * FROM categories
        WHERE parent_id=$parentId";
        $getQuery = mysqli_query($connection,$getSql);

        while($read = mysqli_fetch_array($getQuery)){
          $categories[$i] = array(
            "name"        =>  $read["name"],
            "categoryId"  =>  $read["category_id"],
            "parentId"    =>  $read["parent_id"],
          );
          $this->parentId = (int)$read["category_id"];
          $subCategories = $this->getCategoriesAndSubCategories();

          $categories[$i]["subCategories"] = $subCategories;
          $i++;
        }
        return $categories;
      }
    }


    public function insertCategory(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return "Bağlantı Hatası";
      else {
        $name = mysqli_real_escape_string($connection,$this->categoryName);
        $parentId = (int)$this->parentId;
        $getSql = "SELECT * FROM categories WHERE name='$name'";
        $getQuery = mysqli_query($connection,$getSql);
        if($getQuery->num_rows > 0) return "Bu İsimde Kategori Zaten Mevcut.";
        else {
          $insertSql = "INSERT INTO categories VALUES('','$name',$parentId)";
          $insertQuery = mysqli_query($connection,$insertSql);
          return ($insertQuery) ? true : "Kategori Eklenemedi";
        }
      }
    }
    #id is gönderilen kategoryi siler
    public function deleteCategory(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection) return false;
      else {
        $categoryId=(int)$this->categoryId;
        $deleteSql = "DELETE FROM categories WHERE category_id=$categoryId";
        $delete = mysqli_query($connection,$deleteSql);
        if(!$delete) return false;
        else{
          $filter = new Filter();
          $filter->setCategoryId($categoryId);
          $deleteFilters = $filter->deleteCategoryFilter();
          if(!$deleteFilters) return false;
          else{
            $getSql = "SELECT * FROM categories WHERE parent_id=$categoryId";
            $getQuery = mysqli_query($connection,$getSql);
            if($getQuery->num_rows > 0){
              while($read = mysqli_fetch_array($getQuery)){
                $this->categoryId = (int)$read["category_id"];
                $this->deleteCategory();
              }
            }
            else return $delete;
          }
        }
      }
    }
    public function updateCategory(){
      $db = new Database();
      $connection = $db->MySqlConnection();
      if(!$connection)return false;
      else {
        $categoryName = mysqli_real_escape_string($connection,$this->categoryName);
        $categoryId = (int)$this->categoryId;
        if(trim($categoryName) == "" || trim($categoryName) == null) return false;
        else {
          $sql = "UPDATE categories SET name='$categoryName' WHERE category_id=$categoryId";
          $query = mysqli_query($connection,$sql);
          return $query;
        }
      }
    }

  }

?>

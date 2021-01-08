<?php
include('../Database/Database.php');
class Product
{
    public $id;
    public $name;
    public $image;
    public $price;
    public $category_id;
    public $user_id;
    private $database;


    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
    }

    public function getProducts()
    {
        $query = $this->database->prepare("select * from products");
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function getProductsByCategory($category_id)
    {
        $query = $this->database->prepare("select * from products where category_id=?");
        $query->execute([$category_id]);
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function getProduct($id)
    {
        $query = $this->database->prepare("select * from products where id=?");
        $query->execute([$id]);
        $data = $query->fetch(PDO::FETCH_OBJ);
        return $data;
    }

    // public function getPoliticsProducts()
    // {
    //     $category = new Category();
    //     $category = $category->getCategoryByTitle("politics");
    //     return $this->getNewsByCategory($category->id);
    // }


    public function addProduct()
    {
        try {
            $query = $this->database->prepare("insert into products values(?,?,?,?,?,?)");
            $query->execute([$this->id, $this->name,$this->image,$this->price,$this->category_id,$this->user_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateProduct($id)
    {
        $product = $this->getProduct($id);
        ($this->name != null) ? $product->title =  $this->title : "";
        ($this->image != null) ? $product->image =  $this->image : "";
        ($this->price != null) ? $product->price =  $this->price : "";
        ($this->category_id != null) ? $product->category_id =  $this->category_id : "";
        ($this->user_id != null) ? $product->user_id =  $this->user_id : "";
        try {
            $query = $this->database->prepare("UPDATE `products` SET `name`=?,`image`=?,
            `price`=?,`category_id`=?,`user_id`=? WHERE id = ?");
            $query->execute([$this->name,$this->image,$this->price,$this->category_id,$this->user_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteProduct($id)
    {
        try {
            $query = $this->database->prepare("delete from products where id=?");
            $query->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

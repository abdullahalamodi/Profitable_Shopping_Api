<?php 
include('../Database/Database.php');  //it's incloded in User file ^_^
class Category{

    public $id;
    public $name;
    private $database;

    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
    }

    public function getCategories()
    {
        $query = $this->database->prepare("select * from categories");
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function getCategoryById($id)
    {
        $query = $this->database->prepare("select * from categories where id=?");
        $query->execute([$id]);
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }

    public function getCategoryByTitle($title)
    {
        $query = $this->database->prepare("select * from categories where name like ?");
        $query->execute([$title]);
        $data = $query->fetch(PDO::FETCH_OBJ);
        return $data;
    }

    public function addCategory($data)
    {
        try {
            $query = $this->database->prepare("insert into categories values(?,?)");
            $query->execute([$this->id, $this->name]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateCategory($id)
    {
        $category = $this->getCategoryById($id);
        ($this->name != null) ? $category->name =  $this->name : null;
        try {
            $query = $this->database->prepare("UPDATE `categories` SET `name`=? WHERE id = ?");
            $query->execute([$this->name,$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteCategory($id)
    {
         try {
            $query = $this->database->prepare("DELETE FROM categories WHERE id=?");
            $query->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>
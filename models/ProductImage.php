<?php
// include('../Database/Database.php');  //it's incloded in User file ^_^
include('../services/uploade_image.php');
class ProductImage
{

    public $id;
    public $path;
    public $product_id;
    private $database;
    private $response;

    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
        $this->response = new Response();
    }

    public function getImages($product_id)
    {
        $query = $this->database->prepare("SELECT * from product_images where product_id=?");
        try {
            if ($query->execute([$product_id])) {
                $this->response->case = true;
                $this->response->data = $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                //on failure
                $this->response->case = false;
                $this->response->data = "fieled to get images";
            }
        } catch (PDOException $e) {
            $this->response->case = false;
            $this->response->data = "request fieled cuse : $e";
        }
        return $this->response;
    }

    public function getImageById($id)
    {
        $query = $this->database->prepare("SELECT * from product_images where id=?");
        try {
            if ($query->execute([$id])) {
                $this->response->case = true;
                $this->response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $this->response->case = false;
                $this->response->data = "fieled to get image";
            }
        } catch (PDOException $e) {
            $this->response->case = false;
            $this->response->data = "request fieled cuse : $e";
        }
        return $this->response;
    }



    public function addImage()
    {
        $this->response = UploadeImage::save($this->path);
        if ($this->response->case) {
            $this->path = $this->response->data;
            try {
                $query = $this->database->prepare("INSERT INTO `product_images`(`path`, `product_id`) VALUES (?,?)");
                if ($query->execute([
                    $this->path,
                    $this->product_id
                ])) {
                    $this->response->case = true;
                    $this->response->data = "image add succesfuly";
                } else {
                    $this->response->case = false;
                    $this->response->data = "filed to save image path in database";
                }
            } catch (Exception $e) {
                $this->response->case = false;
                $this->response->data = "filed to save image path in database : $e";
            }
        }
        return $this->response;
    }

    //stoped heeer
    public function updateImage($id)
    {
        $image = $this->getImageById($id);
        ($this->path != null) ? $image->path =  $this->path : null;
        ($this->product_id != null) ? $image->product_id =  $this->product_id : null;
        try {
            $query = $this->database->prepare("UPDATE `product_images` SET
             `path`=?,
             `product_id`=?,
              WHERE id = ?");
            $query->execute([
                $image->path,
                $image->product_id,
                $id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    //delete images for product
    public function deleteImages($product_id)
    {
        try {
            $query = $this->database->prepare("DELETE FROM product_images WHERE product_id=?");
            $query->execute([$product_id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteImage($id)
    {
        try {
            $query = $this->database->prepare("DELETE FROM product_images WHERE id=?");
            $query->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

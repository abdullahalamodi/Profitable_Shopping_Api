<?php
include('ProductImage.php');
include('../Database/Database.php');
class Product
{
    public $id;
    public $name;
    public $quantity;
    public $description;
    public $doller_price;
    public $rial_price;
    public $images;
    public $category_id;
    public $user_id;
    private $database;
    private $productImages;


    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
        $this->productImages = new ProductImage();
    }

    public function getProducts()
    {
        $response = new Response();
        try {
            $query = $this->database->prepare("select * from products");
            //on success
            if ($query->execute()) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $product) {
                    //get images for each product and put them inside product data
                    $product->images = $this->productImages->getImages($product->id);
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get products";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getProductsByCategory($category_id)
    {
        $response = new Response();

        $query = $this->database->prepare("select * from products where category_id=?");
        try {
            if ($query->execute([$category_id])) {
                $response->case = true;
                $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($response->data as $product) {
                    //get images for each product and put them inside product data
                    $product->images = $this->productImages->getImages($product->id);
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get products";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function getProduct($id)
    {
        $response = new Response();

        $query = $this->database->prepare("select * from products where id=?");
        try {
            if ($query->execute([$id])) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
                //get images and put them inside product data
                $response->data->images =
                    $this->productImages->getImages($response->data->id);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get product";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    private function getLastProductId()
    {
        $response = new Response();

        $query = $this->database->prepare("SELECT MAX(id) from products");
        try {
            if ($query->execute()) {
                $response->case = true;
                $response->data = $query->fetch(PDO::FETCH_OBJ);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to get id";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function addProduct()
    {
        $response = new Response();

        try {
            $query = $this->database->prepare("INSERT into  
            `products`(`name`, `quantity`, `description`, `rial_price`, `doller_price`, `category_id`, `user_id`)
             VALUES (?,?,?,?,?,?,?)");
            if ($query == false) {
                $response->case = false;
                $response->data = "wrong statment";
                return $response;
            } elseif ($query->execute([
                $this->name,
                $this->quantity,
                $this->description,
                $this->rial_price,
                $this->doller_price,
                $this->category_id,
                $this->user_id
            ])) {
                $idResponse = $this->getLastProductId();
                //add product images
                if ($idResponse->case) {
                    for ($i = 0; $i > $this->images->count(); $i++) {
                        $productImage = new ProductImage();
                        $productImage->path = $this->images[$i]->path;
                        $productImage->product_id = $idResponse->data;
                        $productImage->addImage();
                    }
                    $response->case = true;
                    $response->data = "success add product";
                }
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to add product";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function updateProduct($id)
    {
        $response = new Response();
        $product = $this->getProduct($id);
        ($this->name != null) ? $product->name =  $this->name : "";
        ($this->quantity != null) ? $product->quantity =  $this->quantity : "";
        ($this->description != null) ? $product->description =  $this->description : "";
        ($this->rial_price != null) ? $product->rial_price =  $this->rial_price : "";
        ($this->doller_price != null) ? $product->doller_price =  $this->doller_price : "";
        ($this->category_id != null) ? $product->category_id =  $this->category_id : "";
        ($this->user_id != null) ? $product->user_id =  $this->user_id : "";
        try {
            $query = $this->database->prepare("UPDATE `products` SET 
            `name`=?,
            `quantity`=?,
            `description`=?,
            `rial_price`=?,
            `doller_price`=?,
            `category_id`=?,
            `user_id`=?
             WHERE id = ?");
            if ($query->execute([
                $product->name,
                $product->quantity,
                $product->description,
                $product->rial_price,
                $product->doller_price,
                $product->category_id,
                $product->user_id,
                $id
            ])) {
                //add product images
                foreach ($this->images as $image) {
                    $productImages = new ProductImage();
                    $productImages->path = $image->path;
                    $productImages->product_id = $image->product_id;
                    $productImages->addImage();
                }
                $response->case = true;
                $response->data = "success update product";
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to update product";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }

    public function deleteProduct($id)
    {
        $response = new Response();

        $query = $this->database->prepare("DELETE from products where id=?");
        try {
            if ($query->execute([$id])) {
                $response->case = true;
                //delete images product
                $this->productImages->deleteImages($id);
            } else {
                //on failure
                $response->case = false;
                $response->data = "fieled to delete product";
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "request fieled cuse : $e";
        }
        return $response;
    }
}

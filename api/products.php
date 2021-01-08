<?php
include('../models/Product.php');
$product = new Product();
$method = $_SERVER['REQUEST_METHOD'];
//post
if (isset($_POST) && !empty($_POST)) {
    //update
    if ($_GET['id'] >= 0) {
        $product->name = $_POST['name'];
        $product->image = $_POST['image'];
        $product->price = $_POST['price'];
        $product->category_id = $_POST['category_id'];
        $product->user_id = $_POST['user_id'];

        if ($product->updateProduct($_GET['id'])) {
            echo "product updated successfuly ^_9";
        } else {
            echo "filed to update product !!";
        }
        // add
    } else {
        $product->id = $_POST['id'];
        $product->name = $_POST['name'];
        $product->image = $_POST['image'];
        $product->price = $_POST['price'];
        $product->category_id = $_POST['category_id'];
        $product->user_id = $_POST['user_id'];
        if ($product->addProduct()) {
            echo "product added successfuly ^_9";
        } else {
            echo "filed to add product !!";
        }
    }
} elseif ($method == "DELETE") {
    if ($product->deleteProduct($_GET['id'])) {
        echo "product deleted successfuly ^_9";
    } else {
        echo "filed to delete product !!";
    }
} else {
    if (isset($_GET['id'])) {
        $data = $product->getProduct($_GET['id']);
    } else {
        $data = $product->getProducts();
    }
    echo json_encode($data);
}

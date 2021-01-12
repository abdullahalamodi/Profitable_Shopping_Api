<?php
include('../models/Product.php');
include('../models/Response.php');

$product = new Product();
$method = $_SERVER['REQUEST_METHOD'];
$response = new Response();
//post
if (isset($_POST) && !empty($_POST)) {
    //update
    if (isset($_GET['id'])) {
        $product->name = $_POST['name'];
        $product->description = $_POST['description'];
        $product->quantity = $_POST['quantity'];
        $product->doller_price = $_POST['doller_price'];
        $product->rial_price = $_POST['rial_price'];
        $product->category_id = $_POST['category_id'];
        $product->user_id = $_POST['user_id'];
        $product->images = $_POST['images'];

        $response = ($product->updateProduct($_GET['id']));
        // add
    } else {
        $product->name = $_POST['name'];
        $product->description = $_POST['description'];
        $product->quantity = $_POST['quantity'];
        $product->doller_price = $_POST['doller_price'];
        $product->rial_price = $_POST['rial_price'];
        $product->category_id = $_POST['category_id'];
        $product->user_id = $_POST['user_id'];
        $product->images = $_POST['images'];

        $response = $product->addProduct();
    }
} elseif ($method == "DELETE") {
    $response = ($product->deleteProduct($_GET['id']));
} else {
    if (isset($_GET['id'])) {
        $response = $product->getProduct($_GET['id']);
    } else {
        $response = $product->getProducts();
    }
}
echo json_encode($response->data);

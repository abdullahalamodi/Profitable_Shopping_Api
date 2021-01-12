<?php
include('../models/Category.php');
include('../models/Response.php');
include('../models/ProductImage.php');

$category = new Category();
$response = new Response();
$method = $_SERVER['REQUEST_METHOD'];

if (isset($_POST) && !empty($_POST)) {
    //update
    if (isset($_GET['id'])) {
        $category->name = $_POST['name'];
        $response = $category->updateCategory($_GET['id']);
    } else {
        //add
        $category->name = $_POST['name'];
        $response = $category->addCategory();
    }
} elseif ($method == "DELETE") {
    $response = $category->deleteCategory($_GET['id']);
} else {
    if (isset($_GET['id'])) {
        $response = $category->getCategoryById($_GET['id']);
    } else {
        $response = $category->getCategories();
    }
}
echo json_encode($response->data);

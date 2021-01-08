<?php
include('../models/Category.php');
$category = new Category();
$method = $_SERVER['REQUEST_METHOD'];
//post
if (isset($_POST) && !empty($_POST)) {
    //update
    if ($_GET['id'] >= 0) {
        $category->name = $_POST['name'];

        if ($category->updateCategory($_GET['id'])) {
            echo "category updated successfuly ^_9";
        } else {
            echo "filed to update category !!";
        }
        // add
    } else {
        $category->id = $_POST['id'];
        $category->name = $_POST['name'];

        if ($category->addCategory()) {
            echo "category added successfuly ^_9";
        } else {
            echo "filed to add category !!";
        }
    }
} elseif ($method == "DELETE") {
    if ($category->deleteCategory($_GET['id'])) {
        echo "category deleted successfuly ^_9";
    } else {
        echo "filed to delete category !!";
    }
} else {
    if (isset($_GET['id'])) {
        $data = $category->getCategoryById($_GET['id']);
    } else {
        $data = $category->getCategories();
    }
    echo json_encode($data);
}

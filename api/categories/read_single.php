<?php
include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

// Instantiate A Category Object
$category = new Category($db);

// Get Categiry ID
$category->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get Category
$category->read_SingleCategory();

// Read Category
if($category->category === false) {
    echo json_encode(array('message' => 'category_id Not Found'));
} else {
    print_r(json_encode($category->category));
}
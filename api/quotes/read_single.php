<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

// Instantiate A Quote Object
$quote = new Quote($db);

// Get Quote ID
$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

// Get Raw Quote Data
$quote->read_SingleQuote();

// Read Quote
if($quote->quote === false) {
    echo json_encode(array('message' => 'No Quotes Found'));
} else {
    print_r(json_encode($quote->quote));
}
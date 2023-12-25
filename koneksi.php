<?php
require_once 'vendor/autoload.php';


// Koneksi ke MongoDB
$mongo = new MongoDB\Client("mongodb://localhost:27017");

// Pilih database yang digunakan
$database = $mongo->selectDatabase("BlogDB");

// Menggunakan collection "posts"
$postsCollection = $database->selectCollection("posts");

// Menggunakan collection "images"
$imagesCollection = $database->selectCollection("images");
?>
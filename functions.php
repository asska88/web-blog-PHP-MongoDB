<?php
require_once 'koneksi.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

// Fungsi untuk menginisialisasi koneksi ke database
function getDatabaseConnection()
{
    $client = new Client("mongodb://localhost:27017");
    $database = $client->selectDatabase("BlogDB");
    return $database;
}

// Mendapatkan daftar semua postingan
function getAllPosts()
{
    $database = getDatabaseConnection();
    $postsCollection = $database->selectCollection("posts");

    $posts = $postsCollection->find();
    $result = [];

    foreach ($posts as $post) {
        $post->image = getImageUrl($post->_id);
        $result[] = $post;
    }

    return $result;
}

// Mendapatkan URL gambar berdasarkan ID postingan
function getImageUrl($postId)
{
    $database = getDatabaseConnection();
    $imagesCollection = $database->selectCollection("images");

    $query = ['postId' => $postId];
    $image = $imagesCollection->findOne($query);

    if ($image && isset($image->imageUrl)) {
        return $image->imageUrl;
    }

    return null;
}

// Mendapatkan satu postingan berdasarkan ID
function getPostById($id)
{
    $database = getDatabaseConnection();
    $postsCollection = $database->selectCollection("posts");

    $query = ['_id' => new ObjectId($id)];
    $post = $postsCollection->findOne($query);

    return $post;
}

// Menambahkan postingan baru
function addPost($title, $content, $image)
{
    $database = getDatabaseConnection();
    $postsCollection = $database->selectCollection("posts");
    $imagesCollection = $database->selectCollection("images");

    $postId = new ObjectId();

    $post = [
        '_id' => $postId,
        'title' => $title,
        'content' => $content
    ];

    $imageData = [
        'postId' => $postId,
        'imageUrl' => $image
    ];

    $postsCollection->insertOne($post);
    $imagesCollection->insertOne($imageData);
}

// Mengedit postingan berdasarkan ID
function editPost($id, $title, $content)
{
    $database = getDatabaseConnection();
    $postsCollection = $database->selectCollection("posts");

    $filter = ['_id' => new ObjectId($id)];
    $update = ['$set' => ['title' => $title, 'content' => $content]];
    $postsCollection->updateOne($filter, $update);
}

// Menghapus postingan berdasarkan ID
function deletePost($id)
{
    $database = getDatabaseConnection();
    $postsCollection = $database->selectCollection("posts");

    $filter = ['_id' => new ObjectId($id)];
    $postsCollection->deleteOne($filter);
}
?>
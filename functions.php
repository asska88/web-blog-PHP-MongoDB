<?php
require_once 'koneksi.php';

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

function makeTextBoldFromAsterisks($text) {
    $pattern = '/\*(.*?)\*/'; // Pola pencarian tanda * di antara teks
    $replacement = '<strong>$1</strong>'; // Pengganti untuk menerapkan format tebal
    $text = preg_replace($pattern, $replacement, $text);
    return $text;
}

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
function addComment($postId,$name, $email, $message)
{
    $postId = $postId;
    $name = $name;
    $email = $email;
    $message = $message;

    $database = getDatabaseConnection();
    $commentsCollection = $database->selectCollection("comment");

    $commentId = new ObjectId();

    $comment = [
        '_id' => $commentId,
        'postId' => $postId,
        'name' => $name,
        'email' => $email,
        'message' => $message
    ];

    $commentsCollection->insertOne($comment);

}
function getComments() 
{
    $database = getDatabaseConnection();
    $commentCollection = $database->selectCollection("comment");

    $comments = $commentCollection->find();
    $result = [];
    foreach ($comments as $comment) {
            $result[] = $comment;
    }

    return $result;
}
function getCommentById($commentId) {
    $database = getDatabaseConnection();
    $commentCollection = $database->selectCollection("comment");

    $query = ['_id' => new MongoDB\BSON\ObjectID($commentId)];
    $comment = $commentCollection->findOne($query);
    return $comment;
}
function addReplyToComment($commentId, $replyName, $replyEmail, $replyMessage)
{
    $commentId = $commentId;
    $replyName = $replyName;
    $replyEmail = $replyEmail;
    $replyMessage = $replyMessage;
    $database = getDatabaseConnection();
    $repliesCollection = $database->selectCollection("replies");

    $reply = [
        'comment_id' => $commentId,
        'reply_name' => $replyName,
        'reply_email' => $replyEmail,
        'reply_message' => $replyMessage
    ];

    $repliesCollection->insertOne($reply);
}

function getRepliesByCommentId($commentId) {
    $database = getDatabaseConnection();
    $repliesCollection = $database->selectCollection("replies");

    $query = ['_id' => new ObjectId($commentId)];
    $replies = $repliesCollection->findOne($query);

    return $replies;
}

function getRepliesComment()
{
    $database = getDatabaseConnection();
    $repliesCollection = $database->selectCollection("replies");

    $replies = $repliesCollection->find();
    $result = [];
    foreach ($replies as $reply) {
        if($reply)
        $result[] = $reply;
    }

    return $result;
}
?>
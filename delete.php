<?php
require_once 'functions.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    deletePost($id);
    header('Location: index.php');
}
?>
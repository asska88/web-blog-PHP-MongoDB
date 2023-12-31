<?php
require_once 'vendor/autoload.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $image = $_FILES['images']['tmp_name'];
    $imagePath = 'uploads/' . $_FILES['images']['name'];
    move_uploaded_file($image, $imagePath);
    $isList = false;
    if (strpos($content, '-') !== false || strpos($content, '*') !== false) {
        $isList = true;
    }

    if ($isList) {
        // Pisahkan setiap baris menjadi elemen daftar
        $lines = explode("\n", $content);
        $listContent = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $listContent .= '<li>' . $line . '</li>';
            }
        }
        $content = '<ul>' . $listContent . '</ul>';
    }
    // Periksa apakah konten postingan berupa daftar angka
    $isNumberedList = false;
    $lines = explode("\n", $content);
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/^\d+\./', $line)) {
            $isNumberedList = true;
            break;
        }
    }

    if ($isNumberedList) {
        // Buat daftar angka
        $listContent = '';
        $counter = 1;
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $listContent .= '<li>' . $counter . '. ' . $line . '</li>';
                $counter++;
            }
        }
        $content = '<ol>' . $listContent . '</ol>';
    }
    addPost($title, $content, $imagePath);
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <h1>Add New Post</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content"></textarea><br><br>
        <label for="images">Image:</label><br>
        <input type="file" id="images" name="images"><br><br>
        <button type="submit">Add Post</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
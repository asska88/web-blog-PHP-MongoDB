<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $image = $_FILES['images']['tmp_name'];
    $imagePath = 'uploads/' . $_FILES['images']['name'];
    move_uploaded_file($image, $imagePath);
    addPost($title, $content, $imagePath);
    $_SESSION['message'] = "Postingan berhasil ditambahkan";
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<script>
    <?php if (isset($_SESSION['message'])) : ?>
        alert("<?php echo $_SESSION['message']; ?>");
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
</script>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Azka Blog</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.html">Azka Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.html">Contact</a></li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('assets/img/home-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="site-heading">
                        <h1>Welcome to My Blog</h1>
                        <span class="subheading">A Blog About Nothing</span>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Postingan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="control label">title</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="images" class="control label">image</label>
                            <input type="file" class="form-control" id="images" name="images">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="control label">content</label>
                            <textarea name="content" id="" cols="30" rows="30" id="content" class="form-control"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">post</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<!-- Main Content-->
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Post preview-->
            <div class="post-preview">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="text-primary">Artikel Terbaru</h2>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Postingan
                    </button>
                </div>
                <?php
                $posts = getAllPosts();
                $posts = array_reverse($posts); // Mengurutkan array secara terbalik
                $counter = 0;
                foreach ($posts as $post) {
                    echo '
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">' . $post->title . '</h5>
                            <p class="card-text">' . substr($post->content, 0, 150) . '...</p>
                            <a href="edit.php?id=' . $post->_id . '" class="btn btn-outline-dark btn-sm">Edit</a>
                            <a href="delete.php?id=' . $post->_id . '" class="btn btn-outline-danger btn-sm">Delete</a>
                            <a href="view.php?id=' . $post->_id . '" class="btn btn-outline-link btn-sm text-primary pb-2" style="text-decoration: underline;">Baca Selengkapnya</a>
                        </div>
                        <img src="' . $post->image . '" class="card-img-bottom" alt="Gambar Artikel">
                    </div>';
                    $counter++;
                    if ($counter >= 1) {
                        echo '<div class="row"></div>';
                    }
                }
                
                if ($counter == 1) {
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <!-- Side widgets-->
        <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                        </div>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="#!">a</a></li>
                                    <li><a href="#!">b</a></li>
                                    <li><a href="#!">c</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="#!">d</a></li>
                                    <li><a href="#!">e</a></li>
                                    <li><a href="#!">f</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Side widget-->
                <!-- Recent Posts widget -->
                <div class="card mb-4">
                    <div class="card-header">Recent Posts</div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><a href="#!">Post 1</a></li>
                            <li><a href="#!">Post 2</a></li>
                            <li><a href="#!">Post 3</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-->
        <footer class="border-top">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <ul class="list-inline text-center">
                            <li class="list-inline-item">
                                <a href="https://www.instagram.com/asska_chameleon/?igsh=ZGNjOWZkYTE3MQ%3D%3D&utm_source=qr">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-instagram fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://www.facebook.com/asska2?mibextid=LQQJ4d">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://github.com/asska88">
                                    <span class="fa-stack fa-lg">
                                        <i class="fas fa-circle fa-stack-2x"></i>
                                        <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="small text-center text-muted fst-italic">Copyright &copy; Azka Website 2023</div>
                    </div>
                </div>
            </div>
    </div>
</div>
                    <!-- Pagination-->
                    <nav aria-label="Pagination">
                        <hr class="my-0" />
                        <ul class="pagination justify-content-center my-4">
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Newer</a></li>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="#!">1</a></li>
                            <li class="page-item"><a class="page-link" href="#!">2</a></li>
                            <li class="page-item"><a class="page-link" href="#!">3</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#!">...</a></li>
                            <li class="page-item"><a class="page-link" href="#!">15</a></li>
                            <li class="page-item"><a class="page-link" href="#!">Older</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
</body>

</html>
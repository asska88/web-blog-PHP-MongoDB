<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">Azka Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- navbar end -->
    <!-- Page Header-->
    <header class="masthead" style="background-image: url('assets/img/post-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1>Man must explore, and this is exploration at its greatest</h1>
                        <h2 class="subheading">Problems look mighty small from 150 miles up</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- page header end -->
    <?php
    require_once 'vendor/autoload.php';
    require_once 'functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            addComment($posId, $name, $email, $message);
            echo '<div class="alert alert-success">Komentar berhasil ditambahkan!</div>';
            header('Location: view.php');
            exit;
        } elseif (isset($_POST['reply_name'], $_POST['reply_email'], $_POST['reply_message'], $_POST['comment_id'])) {
            $replyName = $_POST['reply_name'];
            $replyEmail = $_POST['reply_email'];
            $replyMessage = $_POST['reply_message'];
            $commentId = $_POST['comment_id'];

            addReplyToComment($commentId, $replyName, $replyEmail, $replyMessage);
            echo '<div class="alert alert-success">balasan Komentar berhasil ditambahkan!</div>';
            header('Location: view.php');
        }
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $post = getPostById($id);

        echo '<div class="col-md-6 col-lg-12">
        <!-- Post Content-->
        <article class="mb-4">
            <div class="container fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-10 col-xl-7">
                        <h2 class="section-heading">' . $post->title . '</h2>';

        // Cek apakah konten artikel berupa daftar
        $content = $post['content'];
        $isList = false;
        if (preg_match('/<ul>|<ol>/', $content)) {
            $isList = true;
            $content = str_replace('<ul>', '<ol>', $content); // Mengganti <ul> dengan <ol> agar daftar tampil sebagai daftar angka
            $content = str_replace('</ul>', '</ol>', $content); // Mengganti </ul> dengan </ol> agar daftar tampil sebagai daftar angka
        }

        if ($isList) {
            echo $content;
        } else {
            $imageUrl = getImageUrl($post['_id']);
            if ($imageUrl) {
                echo '<div class="text-center"><img class="img-fluid" src="' . $imageUrl . '" alt="Post Image"></div>';
            }
            echo '<p>' . $content . '</p>';
        }

        echo '<a href="index.php" class="btn btn-primary rounded btn-sm float-end">Kembali ke Daftar Postingan</a>
                    </div>
                </div>
            </div>
        </article>
    </div>';
    }
    ?>
    <!-- Tampilkan Komentar -->
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="section-heading">Komentar</h2>
                <?php
                require_once 'vendor/autoload.php';
                require_once 'functions.php';
                $comments = getComments();

                // $commentId = $_GET['comment_id'];

                // $repliesByCommentId = getRepliesByCommentId($commentId);
                
                $replies = getRepliesComment();
                
                if ($comments || $replies) {
                    foreach ($comments as $comment) {
                        echo '<div class="card mb-4">';
                        echo '<div class="card-header"><strong>Nama : </strong>' . $comment['name'] . '</div>';
                        echo '<div class="card-body">';
                        echo '<p class="card-text">' . $comment['message'] . '</p>';
                        if (!empty($replies)) {
                            echo '<div class="replies">';
                            echo '<h5 class="card-title">Balasan:</h5>';

                            foreach ($replies as $reply) {
                                // echo '<div class="card">';
                                // echo '<div class="card-body">';
                                // echo '<p class="card-text">Comment ID: ' . $reply['comment_id'] . '</p>';
                                // echo '</div>';
                                // echo '</div>';
                                if ($reply['comment_id'] == $comment['_id']) {
                                    echo '<div class="card mb-2">';
                                    echo '<div class="card-header"><strong>Nama : </strong>' . $reply['reply_name'] . '</div>';
                                    echo '<div class="card-body">';
                                    echo '<p class="card-text">' . $reply['reply_message'] . '</p>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }

                            echo '</div>';
                        }
                        // tombol "Balas"
                        echo '<button class="btn btn-link btn-sm reply-btn float-end">Balas</button>';

                        // form balas
                        echo '<div class="card-body reply-form" style="display: none;">';
                        echo '<h5 class="card-title">Kirim Balasan</h5>';
                        echo '<form action="" method="POST">';
                        echo '<input type="hidden" name="comment_id" value="' . $comment['_id'] . '">';

                        echo '<div class="form-group">';
                        echo '<label for="reply-name">Nama:</label>';
                        echo '<input type="text" class="form-control" id="reply-name" name="reply_name" required>';
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label for="reply-email">Email:</label>';
                        echo '<input type="email" class="form-control" id="reply-email" name="reply_email" required>';
                        echo '</div>';

                        echo '<div class="form-group">';
                        echo '<label for="reply-message">Balasan:</label>';
                        echo '<textarea class="form-control" id="reply-message" name="reply_message" rows="3" required></textarea>';
                        echo '</div>';

                        echo '<button type="submit" class="btn btn-primary">Kirim Balasan</button>';
                        echo '</form>';
                        echo '</div>';

                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Belum ada komentar.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Ambil semua tombol "Balas"
        const replyButtons = document.querySelectorAll('.reply-btn');

        // Loop melalui setiap tombol "Balas" dan tambahkan event listener
        replyButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();

                // Toggle tampilan formulir balasan terkait
                const replyForm = button.parentNode.querySelector('.reply-form');
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            });
        });
    </script>
    <!-- Form Komentar -->
    <div class="container px-4 px-lg-5 ">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                <h2 class="section-heading ">Tinggalkan Komentar</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label ">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Komentar</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-2">Kirim Komentar</button>
                    <a href="index.php" class="btn btn-primary rounded btn-sm float-end">Kembali ke Daftar Postingan</a>
                </form>
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
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>
<?php
$sourcePath = "./sources";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isAuthenticated.php";

include "$sourcePath/utilities/date.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Pembayaran SPP</title>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="<?php echo $sourcePath; ?>/public/landing-page/assets/css/style.css" rel="stylesheet">
</head>

<body>
  <header id="header" class="header fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <a href="/<?php echo $originalPath; ?>" class="logo d-flex align-items-center">
        <span>Pembayaran SPP</span>
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#utama">Utama</a></li>
          <li><a class="nav-link scrollto" href="#tentang">Tentang</a></li>
          <li><a class="nav-link scrollto" href="#login-siswa">Siswa</a></li>
          <li><a class="nav-link scrollto" href="#login-petugas">Petugas</a></li>

          <li><a class="getstarted scrollto" href="#login-siswa">Login</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>

  <section id="utama" class="hero d-flex align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center">
          <h1 data-aos="fade-up">Pembayaran SPP</h1>
          <h2 style="text-align: justify;" data-aos="fade-up" data-aos-delay="400">Aplikasi berbasis website untuk melakukan pendataan terhadap pembayaran SPP</h2>
          <div data-aos="fade-up" data-aos-delay="600">
            <div class="text-center text-lg-start">
              <a href="#login-siswa" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                <span>Login</span>
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
          <img src="<?php echo $sourcePath; ?>/public/landing-page/assets/img/utama.png" class="img-fluid" alt="">
        </div>
      </div>
    </div>

  </section>

  <main id="main">
    <section class="mt-0 pt-0 mb-6 pb-6" id="tentang">
      <div class="container counts my-0 py-0" data-aos="fade-up">
        <div class="row gy-4 my-3 py-3">
          <div class="col-sm">
            <div class="count-box">
              <i class="bi bi-people"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?php echo mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS 'total' FROM siswa;"))["total"]; ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>Siswa</p>
              </div>
            </div>
          </div>

          <div class="col-sm">
            <div class="count-box">
              <i class="bi bi-journal" style="color: #15be56;"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?php echo mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS 'total' FROM spp;"))["total"]; ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>SPP</p>
              </div>
            </div>
          </div>

          <div class="col-sm">
            <div class="count-box">
              <i class="bi bi-person" style="color: #ee6c20;"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="<?php echo mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS 'total' FROM petugas;"))["total"]; ?>" data-purecounter-duration="1" class="purecounter"></span>
                <p>Petugas</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container about" data-aos="fade-up">
        <div class="row gx-0">
          <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
            <div class="content">
              <h2>Pembayaran SPP</h2>

              <p style="text-align: justify;">
                Aplikasi berbasis website ini berguna dalam mengelola dan mencatat setiap transaksi pembayaran SPP yang dilakukan oleh siswa. Memudahkan para staff sekolah dan memastikan bahwa setiap transaksi pembayaran SPP tercatat dengan baik. Siswa juga dapat menggunakan aplikasi ini untuk memantau pembayaran SPP mereka dan memastikan bahwa transaksi telah tercatat. Aplikasi ini membuat proses pendataan pembayaran SPP menjadi lebih efisien, praktis, dan terorganisir.
              </p>

              <div class="text-center text-lg-start">
                <a href="#login-siswa" class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                  <span>Berikutnya</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
            <img src="<?php echo $sourcePath; ?>/public/landing-page/assets/img/tentang.png" class="img-fluid" alt="">
          </div>
        </div>
      </div>
    </section>

    <section id="login-siswa" class="features my-6 py-6">
      <div class="container my-0 py-0" data-aos="fade-up">
        <div class="row feature-icons my-0 py-0" data-aos="fade-up">
          <h3 style="display: flex; justify-content: center; align-items: center;">
            Fitur-Fitur Siswa

            <a href="/<?php echo $originalPath ?>/sources/models/authentication/login/siswa.php" class="btn btn-primary" style="margin-left: 1rem;">
              Login
            </a>
          </h3>

          <div class="row">
            <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
              <img src="<?php echo $sourcePath; ?>/public/landing-page/assets/img/siswa.png" class="img-fluid p-4" alt="">
            </div>

            <div class="col-xl-8 d-flex content">
              <div class="row align-self-center gy-4">
                <div class="col-sm icon-box" data-aos="fade-up">
                  <i class="ri-stack-line"></i>
                  <div>
                    <h4>Memantau Transaksi Pembayaran</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <br>
    <br>
    <br>
    <br>
    <br>

    <section id="login-petugas" class="features my-6 py-6">
      <div class="container my-0 py-0" data-aos="fade-up">
        <div class="row feature-icons my-0 py-0" data-aos="fade-up">
          <h3 style="display: flex; justify-content: center; align-items: center;">
            Fitur-Fitur Petugas

            <a href="/<?php echo $originalPath ?>/sources/models/authentication/login/petugas.php" class="btn btn-primary" style="margin-left: 1rem;">
              Login
            </a>
          </h3>

          <div class="row">
            <div class="col-xl-4 text-center" data-aos="fade-right" data-aos-delay="100">
              <img src="<?php echo $sourcePath; ?>/public/landing-page/assets/img/petugas.png" class="img-fluid p-4" alt="">
            </div>

            <div class="col-xl-8 d-flex content">
              <div class="row align-self-center gy-4">
                <div class="col-sm icon-box" data-aos="fade-up">
                  <i class="ri-stack-line"></i>
                  <div>
                    <h4>Memantau Transaksi Pembayaran</h4>
                  </div>
                </div>

                <div class="col-sm icon-box" data-aos="fade-up">
                  <i class="ri-mail-line"></i>
                  <div>
                    <h4>Memasukkan Entri Pembayaran</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer id="footer" class="footer">
    <div class="container">
      <div class="copyright">
        <b>Copyright © <?php echo currentYear(); ?> Irswanda</b>
      </div>
    </div>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/aos/aos.js"></script>
  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/vendor/php-email-form/validate.js"></script>

  <script src="<?php echo $sourcePath; ?>/public/landing-page/assets/js/main.js"></script>
</body>

</html>
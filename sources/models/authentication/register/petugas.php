<?php
$sourcePath = "../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isAuthenticated.php";
include "$sourcePath/middlewares/activity.php";

include "$sourcePath/utilities/date.php";
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Register Petugas";
  include "$sourcePath/components/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition login-page" id="body-theme">
  <div class="login-box" id="login-container">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="/<?php echo $originalPath; ?>" class="h1">
          <b>Pembayaran SPP</b>

          <img class="mt-2" src="<?php echo $sourcePath; ?>/public/dist/img/rpl-logo.png" height="200px" class="img-fluid">
        </a>
      </div>

      <div class="card-body">
        <form method="POST" onsubmit="return confirmModal('form', this, 'login-container');">
          <?php
          $inputArray = [
            [
              "id" => 1,
              "display" => "Nama",
              "name" => "nama",
              "type" => "text",
              "icon" => "address-card",
              "value" => isset($_POST["nama"]) ? $_POST["nama"] : null
            ],
            [
              "id" => 2,
              "display" => "Username",
              "name" => "username",
              "type" => "text",
              "icon" => "user",
              "value" => isset($_POST["username"]) ? $_POST["username"] : null
            ],
            [
              "id" => 3,
              "display" => "Telepon",
              "name" => "telepon",
              "type" => "number",
              "icon" => "phone",
              "value" => isset($_POST["telepon"]) ? $_POST["telepon"] : null
            ],
            [
              "id" => 4,
              "display" => "Password",
              "name" => "password",
              "type" => "password",
              "icon" => "lock",
              "value" =>  isset($_POST["password"]) ? $_POST["password"] : null
            ],
            [
              "id" => 5,
              "display" => "Konfirmasi Password",
              "name" => "konfirmasi_password",
              "type" => "password",
              "icon" => "unlock",
              "value" => isset($_POST["konfirmasi_password"]) ? $_POST["konfirmasi_password"] : null
            ]
          ];

          include "$sourcePath/components/input/basic.php";
          ?>

          <div class="row">
            <div class="col-sm">
              <button type="submit" class="btn btn-primary btn-block">Register Petugas</button>
              <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '<?php echo $sourcePath; ?>/..', 'login-container');">Kembali</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="title is-6 m-1 p-0"><b>Copyright © <?php echo currentYear(); ?> Irswanda</b></div>

  <?php
  include "$sourcePath/components/script.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = md5($_POST["password"]);
    $konfirmasiPassword = md5($_POST["konfirmasi_password"]);

    if ($password == $konfirmasiPassword) {
      $nama = $_POST["nama"];
      $username = $_POST["username"];
      $telepon = $_POST["telepon"];

      try {
        $result = mysqli_query($connection, "INSERT INTO petugas (nama, username, password, telepon, level, status) VALUES ('$nama', '$username', '$password', '$telepon', 'petugas', 'tidak aktif');");

        if ($result) {
          echo "<script>successModal(null, null, 'login-container');</script>";
        } else {
          echo "<script>errorModal(null, null, 'login-container');</script>";
        };
      } catch (exception $e) {
        $message = null;
        $errorMessage = mysqli_error($connection);

        if (str_contains($errorMessage, "Duplicate entry")) {
          if (str_contains($errorMessage, "'username'")) {
            $message = "Username sudah digunakan";
          };
        };

        echo "<script>errorModal('$message', null, 'login-container');</script>";
      };
    } else {
      echo "<script>errorModal('Konfirmasi password salah', null, 'login-container');</script>";
    };
  };
  ?>
</body>

</html>
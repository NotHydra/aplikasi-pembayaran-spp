<?php
$sourcePath = "../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isAuthenticated.php";

include "$sourcePath/utilities/date.php";
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Login Petugas";
  include "$sourcePath/components/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition login-page" id="body-theme">
  <div class="login-box" id="login-container">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="/<?php echo $originalPath; ?>" class="h1"><b>Pembayaran SPP</b></a>
      </div>

      <div class="card-body">
        <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return confirmModal('form', this, 'login-container');">
          <?php
          $inputArray = [
            [
              "id" => 1,
              "display" => "Username",
              "name" => "username",
              "type" => "text",
              "icon" => "user",
              "value" => isset($_POST["username"]) ? $_POST["username"] : null
            ],
            [
              "id" => 2,
              "display" => "Password",
              "name" => "password",
              "type" => "password",
              "icon" => "lock",
              "value" =>  isset($_POST["password"]) ? $_POST["password"] : null
            ]
          ];

          include "$sourcePath/components/input/basic.php";
          ?>

          <div class="row">
            <div class="col-sm">
              <button type="submit" class="btn btn-primary btn-block">Login Petugas</button>
              <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '<?php echo $sourcePath; ?>/..', 'login-container');">Kembali</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="title is-6 m-1 p-0"><b>Copyright © <?php echo currentYear(); ?> Rizky Irswanda Ramadhana</b></div>

  <?php
  include "$sourcePath/components/script.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $result = mysqli_query($connection, "SELECT id, status FROM petugas WHERE username='$username' AND password='$password' AND dihapus='0';");

    if (mysqli_num_rows($result) > 0) {
      $data = mysqli_fetch_assoc($result);

      if ($data["status"] == "aktif") {
        $_SESSION["id"] = $data["id"];
        $_SESSION["type"] = "petugas";

        echo "<script>successModal(null, '/$originalPath/sources/models/utama', 'login-container');</script>";
      } else {
        echo "<script>errorModal('Akun belum aktif', null, 'login-container');</script>";
      }
    } else {
      echo "<script>errorModal('Username atau password salah', null, 'login-container');</script>";
    }
  };
  ?>
</body>

</html>
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
  $headTitle = "Login Siswa";
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
              "display" => "NISN",
              "name" => "nisn",
              "type" => "number",
              "icon" => "address-card",
              "value" => isset($_POST["nisn"]) ? $_POST["nisn"] : null
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
              <button type="submit" class="btn btn-primary btn-block">Login Siswa</button>
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
    $nisn = $_POST["nisn"];
    $password = md5($_POST["password"]);

    $result = mysqli_query($connection, "SELECT id FROM siswa WHERE nisn='$nisn' AND password='$password' AND dihapus='0';");

    if (mysqli_num_rows($result) > 0) {
      $data = mysqli_fetch_assoc($result);

      $_SESSION["id"] = $data["id"];
      $_SESSION["type"] = "siswa";

      echo "<script>successModal(null, '/$originalPath/sources/models/utama', 'login-container');</script>";
    } else {
      echo "<script>errorModal('NISN atau password salah', null, 'login-container');</script>";
    }
  };
  ?>
</body>

</html>
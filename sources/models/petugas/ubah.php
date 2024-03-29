<?php
$sourcePath = "../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";
include "$sourcePath/middlewares/activity.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";

activity("Mengunjungi Halaman Ubah Petugas");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT level FROM petugas WHERE id='$id';");
$data = mysqli_fetch_assoc($result);
if (mysqli_num_rows($result) <= 0 or !roleCheckMinimum($sessionLevel, roleConvert($data["level"]) + 1)) {
  echo "<script>window.location='.';</script>";
};
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Petugas";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [2, null];
    include "$sourcePath/components/nav.php";
    ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm">
              <div class="card">
                <?php
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "Ubah",
                    "link" => null
                  ]
                ];

                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT nama, username, telepon, level, status FROM petugas WHERE id='$id';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Nama",
                            "name" => "nama",
                            "type" => "text",
                            "value" => isset($_POST["nama"]) ? $_POST["nama"] : $data["nama"],
                            "placeholder" => "Masukkan nama disini",
                            "enable" => true
                          ],
                          [
                            "id" => 2,
                            "display" => "Username",
                            "name" => "username",
                            "type" => "text",
                            "value" => isset($_POST["username"]) ? $_POST["username"] : $data["username"],
                            "placeholder" => "Masukkan username disini",
                            "enable" => true

                          ],
                          [
                            "id" => 3,
                            "display" => "Telepon",
                            "name" => "telepon",
                            "type" => "number",
                            "value" => isset($_POST["telepon"]) ? $_POST["telepon"] : $data["telepon"],
                            "placeholder" => "Masukkan telepon disini",
                            "enable" => true
                          ],
                          [
                            "id" => 4,
                            "display" => "Level",
                            "name" => "level",
                            "type" => "select",
                            "value" => [$sessionLevel == "superadmin" ? [
                              ["admin", "Admin"],
                              ["petugas", "Petugas"],
                            ] : [["petugas", "Petugas"]], isset($_POST["level"]) ? $_POST["level"] : $data["level"]],
                            "placeholder" => "Masukkan level disini",
                            "enable" => true
                          ],
                          [
                            "id" => 5,
                            "display" => "Status",
                            "name" => "status",
                            "type" => "select",
                            "value" => [[
                              ["tidak aktif", "Tidak Aktif"],
                              ["aktif", "Aktif"],
                            ], isset($_POST["status"]) ? $_POST["status"] : $data["status"]],
                            "placeholder" => "Masukkan status disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-warning btn-block" type="submit"><i class="fa fa-edit"></i> Ubah</button>
                        <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '.');"><i class="fa fa-undo"></i> Kembali</a>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    include "$sourcePath/components/footer.php";
    ?>
  </div>

  <?php
  include "$sourcePath/components/script.php";
  include "$sourcePath/components/select/script.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $username = $_POST["username"];
    $telepon = $_POST["telepon"];
    $level = $_POST["level"];
    $status = $_POST["status"];

    try {
      $result = mysqli_query($connection, "UPDATE petugas SET nama='$nama', username='$username', telepon='$telepon', level='$level', status='$status' WHERE id='$id';");

      if ($result) {
        activity("Mengubah Petugas");
        echo "<script>successModal(null, null);</script>";
      } else {
        echo "<script>errorModal(null, null);</script>";
      };
    } catch (exception $e) {
      $message = null;
      $errorMessage = mysqli_error($connection);

      if (str_contains($errorMessage, "Duplicate entry")) {
        if (str_contains($errorMessage, "'username'")) {
          $message = "Username sudah digunakan";
        };
      };

      echo "<script>errorModal('$message', null);</script>";
    };
  };
  ?>
</body>

</html>
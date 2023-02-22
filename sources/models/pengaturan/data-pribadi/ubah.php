<?php
$sourcePath = "../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";
include "$sourcePath/middlewares/activity.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";

activity("Mengunjungi Halaman Ubah Data Pribadi");
roleGuardMinimum($sessionLevel, "siswa", "/$originalPath");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Data Pribadi";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [8, 1];
    include "$sourcePath/components/nav.php";
    ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm">
              <div class="card">
                <?php
                $pageItemObject = $pageArray[$navActive[0]]["child"][$navActive[1]];
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
                        if ($sessionType == "petugas") {
                          $inputArray = [
                            [
                              "id" => 1,
                              "display" => "Nama",
                              "name" => "nama",
                              "type" => "text",
                              "value" => isset($_POST["nama"]) ? $_POST["nama"] : $sessionNama,
                              "placeholder" => "Masukkan nama disini",
                              "enable" => true
                            ],
                            [
                              "id" => 2,
                              "display" => "Username",
                              "name" => "username",
                              "type" => "text",
                              "value" =>  isset($_POST["username"]) ? $_POST["username"] : $sessionUsername,
                              "placeholder" => "Masukkan username disini",
                              "enable" => true
                            ],
                            [
                              "id" => 3,
                              "display" => "Telepon",
                              "name" => "telepon",
                              "type" => "number",
                              "value" => isset($_POST["telepon"]) ? $_POST["telepon"] : $sessionTelepon,
                              "placeholder" => "Masukkan telepon disini",
                              "enable" => true
                            ]
                          ];
                        } else if ($sessionType == "siswa") {
                          $inputArray = [
                            [
                              "id" => 1,
                              "display" => "NISN",
                              "name" => "nisn",
                              "type" => "number",
                              "value" => isset($_POST["nisn"]) ? $_POST["nisn"] : $sessionNISN,
                              "placeholder" => "Masukkan NISN disini",
                              "enable" => true
                            ],
                            [
                              "id" => 2,
                              "display" => "NIS",
                              "name" => "nis",
                              "type" => "number",
                              "value" => isset($_POST["nis"]) ? $_POST["nis"] : $sessionNIS,
                              "placeholder" => "Masukkan NIS disini",
                              "enable" => true
                            ],
                            [
                              "id" => 3,
                              "display" => "Nama",
                              "name" => "nama",
                              "type" => "text",
                              "value" => isset($_POST["nama"]) ? $_POST["nama"] : $sessionNama,
                              "placeholder" => "Masukkan nama disini",
                              "enable" => true
                            ],
                            [
                              "id" => 4,
                              "display" => "Rombel",
                              "name" => "id_rombel",
                              "type" => "select",
                              "value" => [
                                array_map(function ($itemObject) {
                                  return [$itemObject[0], $itemObject[1] . " - " . $itemObject[2] . " - " . $itemObject[3] . " - " . $itemObject[4]];
                                }, mysqli_fetch_all(mysqli_query($connection, "SELECT rombel.id, rombel.rombel, kompetensi_keahlian.singkatan, jurusan.singkatan, tingkat.tingkat FROM rombel INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id ORDER BY rombel.dibuat DESC;"))), isset($_POST["id_rombel"]) ? $_POST["id_rombel"] : $data["id_rombel"]
                              ],
                              "placeholder" => "Masukkan rombel disini",
                              "enable" => true
                            ],
                            [
                              "id" => 5,
                              "display" => "Alamat",
                              "name" => "alamat",
                              "type" => "text",
                              "value" => isset($_POST["alamat"]) ? $_POST["alamat"] : $sessionAlamat,
                              "placeholder" => "Masukkan alamat disini",
                              "enable" => true
                            ],
                            [
                              "id" => 6,
                              "display" => "Telepon",
                              "name" => "telepon",
                              "type" => "number",
                              "value" => isset($_POST["telepon"]) ? $_POST["telepon"] : $sessionTelepon,
                              "placeholder" => "Masukkan telepon disini",
                              "enable" => true
                            ]
                          ];
                        };

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
    if ($sessionType == "petugas") {
      $nama = $_POST["nama"];
      $username = $_POST["username"];
      $telepon = $_POST["telepon"];

      try {
        $result = mysqli_query($connection, "UPDATE petugas SET nama='$nama', username='$username', telepon='$telepon' WHERE id='$sessionId';");

        if ($result) {
          activity("Mengubah Data Pribadi");
          echo "<script>successModal(null, './ubah.php');</script>";
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
    } else if ($sessionType == "siswa") {
      $nisn = $_POST["nisn"];
      $nis = $_POST["nis"];
      $nama = $_POST["nama"];
      $idRombel = $_POST["id_rombel"];
      $alamat = $_POST["alamat"];
      $telepon = $_POST["telepon"];

      try {
        $result = mysqli_query($connection, "UPDATE siswa SET nisn='$nisn', nis='$nis', nama='$nama', id_rombel='$idRombel', alamat='$alamat', telepon='$telepon' WHERE id='$sessionId';");

        if ($result) {
          echo "<script>successModal(null, './ubah.php');</script>";
        } else {
          echo "<script>errorModal(null, null);</script>";
        };
      } catch (exception $e) {
        $message = null;
        $errorMessage = mysqli_error($connection);

        if (str_contains($errorMessage, "Duplicate entry")) {
          if (str_contains($errorMessage, "'nisn'")) {
            $message = "NISN sudah digunakan";
          } else if (str_contains($errorMessage, "'nis'")) {
            $message = "NIS sudah digunakan";
          };
        };

        echo "<script>errorModal('$message', null);</script>";
      };
    };
  };
  ?>
</body>

</html>
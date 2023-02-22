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

activity("Mengunjungi Halaman Buat Siswa");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Siswa";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [3, null];
    include "$sourcePath/components/nav.php";
    ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm">
              <div class="card">
                <?php
                $pageItemObject = $pageArray[$navActive[0]];
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "Buat",
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
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "NISN",
                            "name" => "nisn",
                            "type" => "number",
                            "value" => isset($_POST["nisn"]) ? $_POST["nisn"] : null,
                            "placeholder" => "Masukkan NISN disini",
                            "enable" => true
                          ],
                          [
                            "id" => 2,
                            "display" => "NIS",
                            "name" => "nis",
                            "type" => "number",
                            "value" => isset($_POST["nis"]) ? $_POST["nis"] : null,
                            "placeholder" => "Masukkan NIS disini",
                            "enable" => true
                          ],
                          [
                            "id" => 3,
                            "display" => "Nama",
                            "name" => "nama",
                            "type" => "text",
                            "value" => isset($_POST["nama"]) ? $_POST["nama"] : null,
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
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT rombel.id, rombel.rombel, kompetensi_keahlian.singkatan, jurusan.singkatan, tingkat.tingkat FROM rombel INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id ORDER BY rombel.dibuat DESC;"))), isset($_POST["id_rombel"]) ? $_POST["id_rombel"] : null
                            ],
                            "placeholder" => "Masukkan rombel disini",
                            "enable" => true
                          ],
                          [
                            "id" => 5,
                            "display" => "Alamat",
                            "name" => "alamat",
                            "type" => "text",
                            "value" => isset($_POST["alamat"]) ? $_POST["alamat"] : null,
                            "placeholder" => "Masukkan alamat disini",
                            "enable" => true
                          ],
                          [
                            "id" => 6,
                            "display" => "Telepon",
                            "name" => "telepon",
                            "type" => "number",
                            "value" => isset($_POST["telepon"]) ? $_POST["telepon"] : null,
                            "placeholder" => "Masukkan telepon disini",
                            "enable" => true
                          ],
                          [
                            "id" => 7,
                            "display" => "Password",
                            "name" => "password",
                            "type" => "password",
                            "value" => isset($_POST["password"]) ? $_POST["password"] : null,
                            "placeholder" => "Masukkan password disini",
                            "enable" => true
                          ],
                          [
                            "id" => 8,
                            "display" => "Konfirmasi Password",
                            "name" => "konfirmasi_password",
                            "type" => "password",
                            "value" => isset($_POST["konfirmasi_password"]) ? $_POST["konfirmasi_password"] : null,
                            "placeholder" => "Masukkan konfirmasi password disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-plus"></i> Buat</button>
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
    $password = md5($_POST["password"]);
    $konfirmasiPassword = md5($_POST["konfirmasi_password"]);

    if ($password == $konfirmasiPassword) {
      $nisn = $_POST["nisn"];
      $nis = $_POST["nis"];
      $nama = $_POST["nama"];
      $idRombel = $_POST["id_rombel"];
      $alamat = $_POST["alamat"];
      $telepon = $_POST["telepon"];

      try {
        $result = mysqli_query($connection, "INSERT INTO siswa (nisn, nis, nama, id_rombel, alamat, telepon, password) VALUES ('$nisn', '$nis', '$nama', '$idRombel', '$alamat', '$telepon', '$password');");

        if ($result) {
          activity("Membuat Siswa");
          echo "<script>successModal(null, null);</script>";
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
    } else {
      echo "<script>errorModal('Konfirmasi password salah', null);</script>";
    };
  };
  ?>
</body>

</html>
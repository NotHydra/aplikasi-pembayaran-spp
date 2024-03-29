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

activity("Mengunjungi Halaman Ubah Rombel");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM rombel WHERE id='$id';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='.';</script>";
};
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Rombel";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [4, 1];
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
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT id_kompetensi_keahlian, id_jurusan, id_tingkat, rombel FROM rombel WHERE id='$id';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Rombel",
                            "name" => "rombel",
                            "type" => "text",
                            "value" => isset($_POST["rombel"]) ? $_POST["rombel"] : $data["rombel"],
                            "placeholder" => "Masukkan rombel disini",
                            "enable" => true
                          ],
                          [
                            "id" => 2,
                            "display" => "Kompetensi Keahlian",
                            "name" => "id_kompetensi_keahlian",
                            "type" => "select",
                            "value" => [array_map(function ($itemObject) {
                              return [$itemObject[0], $itemObject[1] . " - " . $itemObject[2]];
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, kompetensi_keahlian, singkatan FROM kompetensi_keahlian ORDER BY kompetensi_keahlian ASC;"))), isset($_POST["id_kompetensi_keahlian"]) ? $_POST["id_kompetensi_keahlian"] : $data["id_kompetensi_keahlian"]],
                            "placeholder" => "Masukkan kompetensi keahlian disini",
                            "enable" => true
                          ],
                          [
                            "id" => 3,
                            "display" => "Jurusan",
                            "name" => "id_jurusan",
                            "type" => "select",
                            "value" => [array_map(function ($itemObject) {
                              return [$itemObject[0], $itemObject[1] . " - " . $itemObject[2]];
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, jurusan, singkatan FROM jurusan ORDER BY jurusan ASC;"))), isset($_POST["id_jurusan"]) ? $_POST["id_jurusan"] : $data["id_jurusan"]],
                            "placeholder" => "Masukkan jurusan disini",
                            "enable" => true
                          ],
                          [
                            "id" => 4,
                            "display" => "Tingkat",
                            "name" => "id_tingkat",
                            "type" => "select",
                            "value" => [array_map(function ($itemObject) {
                              return [$itemObject[0], $itemObject[1]];
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, tingkat FROM tingkat ORDER BY tingkat ASC;"))), isset($_POST["id_tingkat"]) ? $_POST["id_tingkat"] : $data["id_tingkat"]],
                            "placeholder" => "Masukkan tingkat disini",
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
    $rombel = $_POST["rombel"];
    $idKompetensiKeahlian = $_POST["id_kompetensi_keahlian"];
    $idJurusan = $_POST["id_jurusan"];
    $idTingkat = $_POST["id_tingkat"];

    try {
      $result = mysqli_query($connection, "UPDATE rombel SET rombel='$rombel', id_kompetensi_keahlian='$idKompetensiKeahlian', id_jurusan='$idJurusan', id_tingkat='$idTingkat' WHERE id='$id';");

      if ($result) {
        activity("Mengubah Rombel");
        echo "<script>successModal(null, null);</script>";
      } else {
        echo "<script>errorModal(null, null);</script>";
      };
    } catch (exception $e) {
      echo "<script>errorModal(null, null);</script>";
    };
  };
  ?>
</body>

</html>
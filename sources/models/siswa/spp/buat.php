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
include "$sourcePath/utilities/currency.php";

activity("Mengunjungi Halaman Buat SPP Siswa");
roleGuardMinimum($sessionLevel, "petugas", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM siswa WHERE id='$id';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='./..';</script>";
};
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
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "SPP",
                    "link" => "sources/models/siswa/spp?id=$id"
                  ],
                  [
                    "id" => 2,
                    "title" => "Buat",
                    "link" => null
                  ]
                ];

                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <?php
                      $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT siswa.id, siswa.nisn, siswa.nis, siswa.nama, rombel.rombel, kompetensi_keahlian.singkatan AS `kompetensi_keahlian`, jurusan.singkatan AS `jurusan`, tingkat.tingkat, siswa.alamat, siswa.telepon
                        FROM siswa 
                        INNER JOIN rombel ON siswa.id_rombel=rombel.id 
                        INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id
                        INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id
                        INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id
                        WHERE siswa.id='$id';
                      "));

                      $inputArray = [
                        [
                          "id" => 1,
                          "display" => null,
                          "name" => null,
                          "type" => "display",
                          "value" => $data["nisn"] . " - " . $data["nis"] . " - " . $data["nama"] . " - " . $data["rombel"] . " - " . $data["kompetensi_keahlian"] . " - " . $data["jurusan"] . " - " . $data["tingkat"] . " - " . $data["alamat"] . " - " . $data["telepon"],
                          "placeholder" => null,
                          "enable" => true
                        ]
                      ];

                      include "$sourcePath/components/input/detail.php";
                      ?>

                      <form method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $sppArray = array_map(function ($sppObject) {
                          return $sppObject[0];
                        }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT id_spp FROM spp_detail WHERE id_siswa='$id';")));

                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "SPP",
                            "name" => "id_spp",
                            "type" => "select",
                            "value" => [
                              array_map(function ($itemObject) {
                                global $sppArray;

                                return [$itemObject[0], $itemObject[1] . " - " . numberToCurrency($itemObject[2]) .  (in_array($itemObject[0], $sppArray) ? " (Sudah Digunakan)" : "")];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, tahun, nominal FROM spp ORDER BY dibuat DESC;"))), isset($_POST["id_spp"]) ? $_POST["id_spp"] : null
                            ],
                            "placeholder" => "Masukkan SPP disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-plus"></i> Buat</button>
                        <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '.?id=<?php echo $id; ?>');"><i class="fa fa-undo"></i> Kembali</a>
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
    $idSiswa = $id;
    $idSPP = $_POST["id_spp"];

    try {
      $result = mysqli_query($connection, "INSERT INTO spp_detail (id_siswa, id_spp) VALUES ('$idSiswa', '$idSPP');");

      if ($result) {
        activity("Membuat SPP Siswa");
        echo "<script>successModal(null, '?id=$id');</script>";
      } else {
        echo "<script>errorModal(null, null);</script>";
      };
    } catch (exception $e) {
      $message = null;
      $errorMessage = mysqli_error($connection);

      if (str_contains($errorMessage, "Duplicate entry")) {
        $message = "SPP sudah digunakan";
      };

      echo "<script>errorModal('$message', null);</script>";
    };
  };
  ?>
</body>

</html>
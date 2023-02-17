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

activity("Mengunjungi halaman hapus spp siswa");
roleGuardMinimum($sessionLevel, "petugas", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM siswa WHERE id='$id';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='./..';</script>";
};

$idSPPDetail = $_GET["idSPPDetail"];
$result = mysqli_query($connection, "SELECT id FROM spp_detail WHERE id_siswa='$id' AND id='$idSPPDetail';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='.?id=$id';</script>";
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
                $pageItemObject = $pageArray[$navActive[0]];
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "SPP",
                    "link" => "sources/models/siswa/spp?id=$id"
                  ],
                  [
                    "id" => 2,
                    "title" => "Hapus",
                    "link" => null
                  ]
                ];
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <?php
                      $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT siswa.nisn, siswa.nis, siswa.nama, rombel.rombel, siswa.alamat, siswa.telepon FROM siswa INNER JOIN rombel ON siswa.id_rombel=rombel.id WHERE siswa.id='$id';"));
                      $inputArray = [
                        [
                          "id" => 1,
                          "display" => null,
                          "name" => null,
                          "type" => "display",
                          "value" => $data["nisn"] . " - " . $data["nis"] . " - " . $data["nama"] . " - " . $data["rombel"] . " - " . $data["alamat"] . " - " . $data["telepon"],
                          "placeholder" => null,
                          "enable" => true
                        ]
                      ];

                      include "$sourcePath/components/input/detail.php";
                      ?>

                      <form method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT spp.tahun, spp.nominal FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id' AND spp_detail.id='$idSPPDetail';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "SPP",
                            "name" => "id_spp",
                            "type" => "text",
                            "value" => $data["tahun"] . " - " . numberToCurrency($data["nominal"]),
                            "placeholder" => "Masukkan SPP disini",
                            "enable" => false
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-danger btn-block" type="submit"><i class="fa fa-trash"></i> Hapus</button>
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
    try {
      $result = mysqli_query($connection, "DELETE FROM spp_detail WHERE id_siswa='$id' AND id='$idSPPDetail';");

      if ($result) {
        activity("Menghapus spp siswa");
        echo "<script>successModal(null, '.?id=$id');</script>";
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
<?php
$sourcePath = "../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";

roleGuardMinimum($sessionLevel, "petugas", "/$originalPath");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM siswa WHERE id='$id' AND dihapus='0';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='./..';</script>";
};

$idSPP = $_GET["idSPP"];
$result = mysqli_query($connection, "SELECT id FROM spp WHERE id='$idSPP' AND dihapus='0';");
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

<body class="hold-transition layout-navbar-fixed layout-fixed light-mode" id="body-theme">
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
                $extraTitle = "Buat Pembayaran";
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return confirmModal('form', this);" enctype="multipart/form-data">
                        <?php
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Bukti Pembayaran",
                            "name" => "bukti_pembayaran",
                            "type" => "image",
                            "value" => null,
                            "placeholder" => "Masukkan bukti pembayaran disini",
                            "enable" => true
                          ],
                          [
                            "id" => 2,
                            "display" => "Tanggal Pembayaran",
                            "name" => "tanggal_pembayaran",
                            "type" => "date",
                            "value" => isset($_POST["tanggal_pembayaran"]) ? $_POST["tanggal_pembayaran"] : null,
                            "placeholder" => "Masukkan tanggal pembayaran disini",
                            "enable" => true
                          ],
                          [
                            "id" => 3,
                            "display" => "Bulan Pembayaran",
                            "name" => "bulan_pembayaran",
                            "type" => "select",
                            "value" => [
                              [
                                [1, "Januari"],
                                [2, "Februari"],
                                [3, "Maret"],
                                [4, "April"],
                                [5, "Mei"],
                                [6, "Juni"],
                                [7, "Juli"],
                                [8, "Agustus"],
                                [9, "September"],
                                [10, "Oktober"],
                                [11, "November"],
                                [12, "Desember"]
                              ], isset($_POST["bulan_pembayaran"]) ? $_POST["bulan_pembayaran"] : null
                            ],
                            "placeholder" => "Masukkan bulan pembayaran disini",
                            "enable" => true
                          ],
                          [
                            "id" => 4,
                            "display" => "Jumlah Pembayaran",
                            "name" => "jumlah_pembayaran",
                            "type" => "number",
                            "value" => isset($_POST["jumlah_pembayaran"]) ? $_POST["jumlah_pembayaran"] : null,
                            "placeholder" => "Masukkan jumlah pembayaran disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-plus"></i> Buat</button>
                        <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '.?id=<?php echo $id; ?>&idSPP=<?php echo $idSPP; ?>');"><i class="fa fa-undo"></i> Kembali</a>
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
    $buktiPembayaran = date("Ymdhis") . "-" . $_FILES["bukti_pembayaran"]["name"];
    $tanggalPembayaran = $_POST["tanggal_pembayaran"];
    $bulanPembayaran = $_POST["bulan_pembayaran"];
    $jumlahPembayaran = $_POST["jumlah_pembayaran"];

    try {
      move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $sourcePath . '/public/dist/img/storage/' . $buktiPembayaran);
      $result = mysqli_query($connection, "INSERT INTO pembayaran (id_petugas, id_siswa, id_spp, bukti_pembayaran, tanggal_pembayaran, bulan_pembayaran, jumlah_pembayaran) VALUES ('$sessionId', '$id', '$idSPP', '$buktiPembayaran', '$tanggalPembayaran','$bulanPembayaran', '$jumlahPembayaran');");

      if ($result) {
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
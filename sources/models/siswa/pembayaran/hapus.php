<?php
$sourcePath = "../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";
include "$sourcePath/utilities/currency.php";

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

$idPembayaran = $_GET["idPembayaran"];
$result = mysqli_query($connection, "SELECT id FROM pembayaran WHERE id='$idPembayaran' AND id_siswa='$id' AND id_spp='$idSPP' AND dihapus='0';");
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
                $pageItemObject = $pageArray[$navActive[0]];
                $extraTitle = "Hapus";
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT bukti_pembayaran, tanggal_pembayaran, bulan_pembayaran, jumlah_pembayaran FROM pembayaran WHERE id='$idPembayaran' AND id_siswa='$id' AND id_spp='$idSPP' AND dihapus='0';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Bukti Pembayaran",
                            "name" => "bukti_pembayaran",
                            "type" => "image",
                            "value" => $data["bukti_pembayaran"],
                            "placeholder" => "Masukkan bukti pembayaran disini",
                            "enable" => false
                          ],
                          [
                            "id" => 2,
                            "display" => "Tanggal Pembayaran",
                            "name" => "tanggal_pembayaran",
                            "type" => "date",
                            "value" => $data["tanggal_pembayaran"],
                            "placeholder" => "Masukkan tanggal pembayaran disini",
                            "enable" => false
                          ],
                          [
                            "id" => 3,
                            "display" => "Bulan Pembayaran",
                            "name" => "bulan_pembayaran",
                            "type" => "text",
                            "value" => numberToMonth($data["bulan_pembayaran"]),
                            "placeholder" => "Masukkan bulan pembayaran disini",
                            "enable" => false
                          ],
                          [
                            "id" => 4,
                            "display" => "Jumlah Pembayaran",
                            "name" => "jumlah_pembayaran",
                            "type" => "text",
                            "value" => numberToCurrency($data["jumlah_pembayaran"]),
                            "placeholder" => "Masukkan jumlah pembayaran disini",
                            "enable" => false
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-danger btn-block" type="submit"><i class="fa fa-trash"></i> Hapus</button>
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
    try {
      $result = mysqli_query($connection, "UPDATE pembayaran SET dihapus='1' WHERE id='$idPembayaran' AND dihapus='0';");

      if ($result) {
        echo "<script>successModal(null, '.?id=$id&idSPP=$idSPP');</script>";
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
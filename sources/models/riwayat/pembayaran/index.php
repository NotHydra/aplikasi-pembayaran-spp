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

roleGuardSingle($sessionLevel, "siswa", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM spp_detail WHERE id_siswa='$sessionId' AND id='$id';");
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
  include "$sourcePath/components/data-table/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [6, null];
    include "$sourcePath/components/nav.php";
    ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <?php
          $cardArray = [
            [
              "id" => 1,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Total Pembayaran",
                  "icon" => "envelope",
                  "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM pembayaran WHERE id_spp_detail='$id';"))["total"]
                ],
                [
                  "id" => 2,
                  "title" => "Total Jumlah Pembayaran",
                  "icon" => "wallet",
                  "value" => numberToCurrency(mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(jumlah_pembayaran) AS `total` FROM pembayaran WHERE id_spp_detail ='$id';"))["total"])
                ]
              ]
            ]
          ];

          include "$sourcePath/components/card.php";
          ?>

          <div class="row">
            <div class="col-sm">
              <div class="card">
                <?php
                $pageItemObject = $pageArray[$navActive[0]];
                $extraTitle = "Pembayaran";
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <form class="row mb-2" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="col-sm">
                      <?php
                      $inputArray = [
                        [
                          "id" => 1,
                          "display" => null,
                          "name" => "tahun",
                          "type" => "select",
                          "value" => [
                            array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                              return [$yearObject[0], $yearObject[0]];
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(dibuat) FROM pembayaran WHERE id_spp_detail='$id' ORDER BY dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : null
                          ],
                          "placeholder" => "Pilih tahun disini",
                          "enable" => true
                        ],
                      ];

                      include "$sourcePath/components/input/detail.php";
                      ?>
                    </div>

                    <div class="col-sm">
                      <?php
                      $inputArray = [
                        [
                          "id" => 1,
                          "display" => null,
                          "name" => "bulan",
                          "type" => "select",
                          "value" => [
                            [
                              [0, "Semua"],
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
                            ], isset($_POST["bulan"]) ? $_POST["bulan"] : null
                          ],
                          "placeholder" => "Pilih bulan disini",
                          "enable" => true
                        ],
                      ];

                      include "$sourcePath/components/input/detail.php";
                      ?>
                    </div>

                    <div class="col-sm">
                      <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-search"></i> Cari</button>
                    </div>
                  </form>

                  <div class="row">
                    <div class="col-sm">
                      <table id="main-table" class="table table-bordered table-striped table-sm">
                        <thead>
                          <tr>
                            <th class="text-center align-middle export">No.</th>
                            <th class="text-center align-middle export">Petugas</th>
                            <th class="text-center align-middle export">Bukti Pembayaran</th>
                            <th class="text-center align-middle export">Tanggal Pembayaran</th>
                            <th class="text-center align-middle export">Bulan Pembayaran</th>
                            <th class="text-center align-middle export">Jumlah Pembayaran</th>
                            <th class="text-center align-middle export">Dibuat</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $currentDate = date("Y-m-d H:i:s");

                          $extraFilter = "";
                          if (isset($_POST["tahun"])) {
                            $tahunFilter = $_POST["tahun"];
                            if ($tahunFilter != 0) {
                              $extraFilter = $extraFilter . " AND YEAR(pembayaran.dibuat)='$tahunFilter'";
                            };
                          };

                          if (isset($_POST["bulan"])) {
                            $bulanFilter = $_POST["bulan"];
                            if ($bulanFilter != 0) {
                              $extraFilter = $extraFilter . " AND MONTH(pembayaran.dibuat)='$bulanFilter'";
                            };
                          };

                          $result = mysqli_query($connection, "SELECT pembayaran.id, petugas.nama AS `petugas_nama`, pembayaran.bukti_pembayaran, pembayaran.tanggal_pembayaran, pembayaran.bulan_pembayaran, pembayaran.jumlah_pembayaran, pembayaran.dibuat FROM pembayaran INNER JOIN petugas ON pembayaran.id_petugas=petugas.id WHERE pembayaran.id_spp_detail='$id' $extraFilter ORDER BY pembayaran.dibuat DESC;");
                          foreach ($result as $i => $data) {
                            $idPembayaran = $data["id"];
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                              <td class="text-center align-middle"><?php echo $data["petugas_nama"]; ?></td>
                              <td class="text-center align-middle">
                                <img class="m-auto d-block" src="<?php echo $sourcePath; ?>/public/dist/img/storage/<?php echo $data["bukti_pembayaran"]; ?>" width="250px">
                              </td>
                              <td class="text-center align-middle"><?php echo $data["tanggal_pembayaran"]; ?></td>
                              <td class="text-center align-middle"><?php echo numberToMonth($data["bulan_pembayaran"]); ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["jumlah_pembayaran"]); ?></td>
                              <td class="text-center align-middle"><?php echo $data["dibuat"]; ?></td>
                            </tr>
                          <?php
                          };
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <a class="btn btn-danger btn-block mt-1" role="button" onclick="confirmModal('location', './..');"><i class="fa fa-undo"></i> Kembali</a>
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
  include "$sourcePath/components/data-table/script.php";
  include "$sourcePath/components/select/script.php";
  ?>
</body>

</html>
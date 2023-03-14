<?php
$sourcePath = "./../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";
include "$sourcePath/middlewares/activity.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";
include "$sourcePath/utilities/currency.php";

activity("Mengunjungi Halaman Keuangan");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Keuangan";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/data-table/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [7, 2];
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
                    "title" => "Utama",
                    "link" => null
                  ]
                ];

                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <form class="mb-4" method="POST">
                    <div class="row my-0">
                      <div class="col-sm">
                        <?php
                        $tahunArray = [];
                        foreach (["siswa"] as $tahunTableObject) {
                          $tahunArray = array_merge($tahunArray, array_map(function ($yearObject) {
                            return $yearObject[0];
                          }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(dibuat) FROM $tahunTableObject;"))));
                        };

                        rsort($tahunArray);

                        $uniqueTahunArray = [];
                        foreach (array_unique($tahunArray) as $tahunObject) {
                          array_push($uniqueTahunArray, $tahunObject);
                        };

                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Tahun",
                            "name" => "tahun",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($uniqueTahunObject) {
                                return [$uniqueTahunObject, $uniqueTahunObject];
                              }, $uniqueTahunArray)), isset($_POST["tahun"]) ? $_POST["tahun"] : 0
                            ],
                            "placeholder" => "Pilih tahun disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>
                      </div>

                      <div class="col-sm">
                        <?php
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Bulan",
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
                              ], isset($_POST["bulan"]) ? $_POST["bulan"] : 0
                            ],
                            "placeholder" => "Pilih bulan disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>
                      </div>
                    </div>

                    <div class="row my-0">
                      <div class="col-sm">
                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-search"></i> Cari</button>
                      </div>
                    </div>
                  </form>

                  <div class="row">
                    <div class="col-sm">
                      <table id="main-table" class="table table-bordered table-striped table-sm">
                        <thead>
                          <tr>
                            <th class="text-center align-middle export">Tahun</th>
                            <th class="text-center align-middle export">Total Nominal</th>
                            <th class="text-center align-middle export">Total Sudah Dibayar</th>
                            <th class="text-center align-middle export">Total Belum Dibayar</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                          $rowArray = !isset($_POST["tahun"]) || (isset($_POST["tahun"]) && $_POST["tahun"] == 0) ? array_merge(["Total"], $uniqueTahunArray) : array($_POST["tahun"]);
                          foreach ($rowArray as $rowObject) {
                            $extraFilter = "";
                            if ($rowObject != "Total") {
                              $extraFilter = $extraFilter . " AND YEAR(dibuat)='$rowObject'";
                            };

                            if (isset($_POST["bulan"])) {
                              $bulanFilter = $_POST["bulan"];
                              if ($bulanFilter != 0) {
                                $extraFilter = $extraFilter . " AND MONTH(dibuat)='$bulanFilter'";
                              };
                            };

                            $resultSiswa = mysqli_query($connection, "SELECT id FROM siswa WHERE '1'='1' $extraFilter;");
                            $totalNominal = 0;
                            $totalSudahDibayar = 0;
                            foreach ($resultSiswa as $dataSiswa) {
                              $id = $dataSiswa["id"];
                              $totalNominal += mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id';"))["total"];
                              $totalSudahDibayar += mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$id';"))["total"];
                            };

                            $tableArray = [
                              numberToCurrency($totalNominal),
                              numberToCurrency($totalSudahDibayar),
                              numberToCurrency($totalNominal - $totalSudahDibayar)
                            ];
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $rowObject; ?></td>

                              <?php
                              foreach ($tableArray as $tableObject) {
                              ?>
                                <td class="text-center align-middle"><?php echo $tableObject; ?></td>
                              <?php
                              };
                              ?>
                            </tr>
                          <?php
                          };
                          ?>
                        </tbody>
                      </table>
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
  include "$sourcePath/components/data-table/script.php";
  include "$sourcePath/components/select/script.php";
  ?>
</body>

</html>
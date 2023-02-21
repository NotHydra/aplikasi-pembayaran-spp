<?php
$sourcePath = "../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";
include "$sourcePath/utilities/currency.php";

roleGuardSingle($sessionLevel, "siswa", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Riwayat";
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
          $totalNominal = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$sessionId';"))["total"];
          $totalSudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$sessionId';"))["total"];
          $cardArray = [
            [
              "id" => 1,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Total SPP",
                  "icon" => "book",
                  "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM spp_detail WHERE id_siswa='$sessionId';"))["total"]
                ],
                [
                  "id" => 2,
                  "title" => "Total Nominal",
                  "icon" => "wallet",
                  "value" => numberToCurrency($totalNominal)
                ]
              ]
            ],
            [
              "id" => 2,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Total Sudah Dibayar",
                  "icon" => "check",
                  "value" => numberToCurrency($totalSudahDibayar)
                ],
                [
                  "id" => 2,
                  "title" => "Total Belum Dibayar",
                  "icon" => "times",
                  "value" => numberToCurrency($totalNominal - $totalSudahDibayar)
                ]
              ]
            ],
            [
              "id" => 3,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Status",
                  "icon" => "clipboard",
                  "value" => $totalNominal == $totalSudahDibayar ? "Sudah Lunas" : "Belum Lunas"
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
                  <form class="row mb-2" method="POST">
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
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT spp.tahun FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id ORDER BY spp_detail.dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : null
                          ],
                          "placeholder" => "Pilih tahun disini",
                          "enable" => true
                        ]
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
                            <th class="text-center align-middle export">Tahun</th>
                            <th class="text-center align-middle export">Nominal</th>
                            <th class="text-center align-middle export">Sudah Dibayar</th>
                            <th class="text-center align-middle export">Belum Dibayar</th>
                            <th class="text-center align-middle export">Status</th>
                            <th class="text-center align-middle export">Dibuat</th>
                            <th class="text-center align-middle">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $currentDate = date("Y-m-d H:i:s");

                          $extraFilter = "";
                          if (isset($_POST["tahun"])) {
                            $tahunFilter = $_POST["tahun"];
                            if ($tahunFilter != 0) {
                              $extraFilter = $extraFilter . " AND tahun='$tahunFilter'";
                            };
                          };

                          $result = mysqli_query($connection, "SELECT spp_detail.id, spp.tahun, spp.nominal, SUM(pembayaran.jumlah_pembayaran) AS `sudah_dibayar`, spp_detail.dibuat FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id LEFT JOIN pembayaran ON spp_detail.id=pembayaran.id_spp_detail WHERE spp_detail.id_siswa='$sessionId' $extraFilter GROUP BY spp_detail.id ORDER BY spp_detail.dibuat DESC;");
                          foreach ($result as $i => $data) {
                            $idSPPDetail = $data["id"];
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                              <td class="text-center align-middle"><?php echo $data["tahun"]; ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["nominal"]); ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["sudah_dibayar"]); ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["nominal"] - $data["sudah_dibayar"]); ?></td>

                              <?php
                              if ($data["nominal"] == $data["sudah_dibayar"]) {
                              ?>
                                <td class="text-center align-middle">Sudah Lunas</td>
                              <?php
                              } else {
                              ?>
                                <td class="text-center align-middle">Belum Lunas</td>
                              <?php
                              };
                              ?>

                              <td class="text-center align-middle"><?php echo $data["dibuat"]; ?></td>

                              <td class="text-center align-middle">
                                <div class="btn-group">
                                  <a class="btn btn-app bg-primary m-0" href="./pembayaran?id=<?php echo $idSPPDetail; ?>">
                                    <i class="fas fa-envelope"></i> Pembayaran
                                  </a>
                                </div>
                              </td>
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
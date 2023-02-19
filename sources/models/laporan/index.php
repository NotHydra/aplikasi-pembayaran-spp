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
include "$sourcePath/utilities/currency.php";

activity("Mengunjungi halaman laporan");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Laporan";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/data-table/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [7, null];
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
                    "title" => "Utama",
                    "link" => null
                  ]
                ];
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
                          "type" => "text",
                          "value" => isset($_POST["tahun"]) ? $_POST["tahun"] : "Semua",
                          "placeholder" => "Masukkan tahun disini",
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
                      <?php
                      $extraFilter = "";
                      if (isset($_POST["tahun"])) {
                        $tahunFilter = $_POST["tahun"];
                        if ($tahunFilter != "Semua") {
                          $extraFilter = $extraFilter . " AND YEAR(dibuat)='$tahunFilter'";
                        };
                      };

                      if (isset($_POST["bulan"])) {
                        $bulanFilter = $_POST["bulan"];
                        if ($bulanFilter != 0) {
                          $extraFilter = $extraFilter . " AND MONTH(dibuat)='$bulanFilter'";
                        };
                      };

                      $resultSiswa = mysqli_query($connection, "SELECT siswa.id FROM siswa WHERE '1'='1' $extraFilter;");
                      $totalSudahLunas = 0;
                      foreach ($resultSiswa as $dataSiswa) {
                        $id = $dataSiswa["id"];
                        $totalNominal = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id';"))["total"];
                        $totalSudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$id';"))["total"];

                        if ($totalNominal == $totalSudahDibayar) {
                          $totalSudahLunas += 1;
                        }
                      }

                      $tableArray = [
                        [
                          "id" => 1,
                          "title" => "Total Pengguna",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM petugas WHERE '1'='1' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 2,
                          "title" => "Total Petugas",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM petugas WHERE level='petugas' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 3,
                          "title" => "Total Admin",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM petugas WHERE level='admin' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 4,
                          "title" => "Total Superadmin",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM petugas WHERE level='superadmin' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 5,
                          "title" => "Total Siswa",
                          "value" => mysqli_num_rows($resultSiswa)
                        ],
                        [
                          "id" => 6,
                          "title" => "Total Sudah Lunas",
                          "value" => $totalSudahLunas
                        ],
                        [
                          "id" => 7,
                          "title" => "Total Belum Lunas",
                          "value" => mysqli_num_rows($resultSiswa) - $totalSudahLunas
                        ],
                        [
                          "id" => 8,
                          "title" => "Total Rombel",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM rombel WHERE '1'='1' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 9,
                          "title" => "Total Kompetensi Keahlian",
                          "icon" => "microchip",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM kompetensi_keahlian WHERE '1'='1' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 10,
                          "title" => "Total Jurusan",
                          "icon" => "wrench",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM jurusan WHERE '1'='1' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 11,
                          "title" => "Total Tingkat",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM tingkat WHERE '1'='1' $extraFilter;"))["total"]
                        ],
                        [
                          "id" => 12,
                          "title" => "Total SPP",
                          "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM spp WHERE '1'='1' $extraFilter;"))["total"]
                        ]
                      ];
                      ?>
                      <table id="main-table" class="table table-bordered table-striped table-sm">
                        <thead>
                          <tr>
                            <?php
                            foreach ($tableArray as $tableObject) {
                            ?>
                              <th class="text-center align-middle export"><?php echo $tableObject["title"]; ?></th>
                            <?php
                            }
                            ?>
                          </tr>
                        </thead>

                        <tbody>
                          <tr>
                            <?php
                            foreach ($tableArray as $tableObject) {
                            ?>
                              <td class="text-center align-middle"><?php echo $tableObject["value"]; ?></td>
                            <?php
                            }
                            ?>
                          </tr>
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
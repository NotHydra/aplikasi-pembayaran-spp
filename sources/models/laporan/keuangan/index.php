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
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Rombel",
                            "name" => "rombel",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[1]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, rombel FROM rombel ORDER BY rombel ASC;")))), isset($_POST["rombel"]) ? $_POST["rombel"] : 0
                            ],
                            "placeholder" => "Pilih rombel disini",
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
                            "display" => "Kompetensi Keahlian",
                            "name" => "kompetensi_keahlian",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[1]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, singkatan FROM kompetensi_keahlian ORDER BY singkatan ASC;")))), isset($_POST["kompetensi_keahlian"]) ? $_POST["kompetensi_keahlian"] : 0
                            ],
                            "placeholder" => "Pilih kompetensi keahlian disini",
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
                            "display" => "Jurusan",
                            "name" => "jurusan",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[1]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, singkatan FROM jurusan ORDER BY singkatan ASC;")))), isset($_POST["jurusan"]) ? $_POST["jurusan"] : 0
                            ],
                            "placeholder" => "Pilih jurusan disini",
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
                            "display" => "Tingkat",
                            "name" => "tingkat",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[1]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT id, tingkat FROM tingkat ORDER BY tingkat ASC;")))), isset($_POST["tingkat"]) ? $_POST["tingkat"] : 0
                            ],
                            "placeholder" => "Pilih tingkat disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>
                      </div>
                    </div>

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

                            if (isset($_POST["rombel"])) {
                              $rombelFilter = $_POST["rombel"];
                              if ($rombelFilter != 0) {
                                $extraFilter = $extraFilter . " AND siswa.id_rombel='$rombelFilter'";
                              };
                            };

                            if (isset($_POST["kompetensi_keahlian"])) {
                              $kompetensiKeahlianFilter = $_POST["kompetensi_keahlian"];
                              if ($kompetensiKeahlianFilter != 0) {
                                $extraFilter = $extraFilter . " AND kompetensi_keahlian.id='$kompetensiKeahlianFilter'";
                              };
                            };

                            if (isset($_POST["jurusan"])) {
                              $jurusanFilter = $_POST["jurusan"];
                              if ($jurusanFilter != 0) {
                                $extraFilter = $extraFilter . " AND jurusan.id='$jurusanFilter'";
                              };
                            };

                            if (isset($_POST["tingkat"])) {
                              $tingkatFilter = $_POST["tingkat"];
                              if ($tingkatFilter != 0) {
                                $extraFilter = $extraFilter . " AND tingkat.id='$tingkatFilter'";
                              };
                            };

                            if ($rowObject != "Total") {
                              $extraFilter = $extraFilter . " AND YEAR(siswa.dibuat)='$rowObject'";
                            };

                            if (isset($_POST["bulan"])) {
                              $bulanFilter = $_POST["bulan"];
                              if ($bulanFilter != 0) {
                                $extraFilter = $extraFilter . " AND MONTH(siswa.dibuat)='$bulanFilter'";
                              };
                            };

                            $resultSiswa = mysqli_query($connection, "SELECT siswa.id 
                              FROM siswa 
                              INNER JOIN rombel ON siswa.id_rombel=rombel.id 
                              INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id
                              INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id
                              INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id
                              WHERE '1'='1' $extraFilter;
                            ");

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
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

activity("Mengunjungi Halaman SPP Siswa");
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
  include "$sourcePath/components/data-table/head.php";
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
          <?php
          $totalNominal = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id';"))["total"];
          $totalSudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$id';"))["total"];
          $cardArray = [
            [
              "id" => 1,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Total SPP",
                  "icon" => "book",
                  "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM spp_detail WHERE id_siswa='$id';"))["total"]
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
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "SPP",
                    "link" => "sources/models/siswa/spp?id=$id"
                  ],
                  [
                    "id" => 2,
                    "title" => "Utama",
                    "link" => null
                  ]
                ];

                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
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

                  <form class="mb-4" method="POST">
                    <div class="row my-0">
                      <div class="col-sm">
                        <?php
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Tahun SPP",
                            "name" => "tahun_spp",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[0]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT spp.tahun FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id' ORDER BY spp.tahun DESC;")))), isset($_POST["tahun_spp"]) ? $_POST["tahun_spp"] : 0
                            ],
                            "placeholder" => "Pilih tahun SPP disini",
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
                            "display" => "Status",
                            "name" => "status",
                            "type" => "select",
                            "value" => [
                              [
                                [0, "Semua"],
                                [1, "Belum Lunas"],
                                [2, "Sudah Lunas"]
                              ], isset($_POST["status"]) ? $_POST["status"] : 0
                            ],
                            "placeholder" => "Pilih status disini",
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
                            "display" => "Tahun Pembuatan",
                            "name" => "tahun",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[0]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(spp_detail.dibuat) FROM spp_detail WHERE spp_detail.id_siswa='$id' ORDER BY spp_detail.dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : 0
                            ],
                            "placeholder" => "Pilih tahun pembuatan disini",
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
                            "display" => "Bulan Pembuatan",
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
                            "placeholder" => "Pilih bulan pembuatan disini",
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
                          $statusFilter = isset($_POST["status"]) ? $_POST["status"] : 0;

                          if (isset($_POST["tahun_spp"])) {
                            $tahunSPPFilter = $_POST["tahun_spp"];
                            if ($tahunSPPFilter != 0) {
                              $extraFilter = $extraFilter . " AND spp.tahun='$tahunSPPFilter'";
                            };
                          };

                          if (isset($_POST["tahun"])) {
                            $tahunFilter = $_POST["tahun"];
                            if ($tahunFilter != 0) {
                              $extraFilter = $extraFilter . " AND YEAR(spp_detail.dibuat)='$tahunFilter'";
                            };
                          };

                          if (isset($_POST["bulan"])) {
                            $bulanFilter = $_POST["bulan"];
                            if ($bulanFilter != 0) {
                              $extraFilter = $extraFilter . " AND MONTH(spp_detail.dibuat)='$bulanFilter'";
                            };
                          };

                          $result = mysqli_query($connection, "SELECT spp_detail.id, spp.tahun, spp.nominal, SUM(pembayaran.jumlah_pembayaran) AS `sudah_dibayar`, spp_detail.dibuat FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id LEFT JOIN pembayaran ON spp_detail.id=pembayaran.id_spp_detail WHERE spp_detail.id_siswa='$id' $extraFilter GROUP BY spp_detail.id ORDER BY spp.tahun DESC;");
                          foreach ($result as $i => $data) {
                            $idSPPDetail = $data["id"];

                            if ($statusFilter == 0 || ($statusFilter == 1 && $data["nominal"] != $data["sudah_dibayar"]) || ($statusFilter == 2 && $data["nominal"] == $data["sudah_dibayar"])) {
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
                                    <a class="btn btn-app bg-primary m-0" href="./pembayaran?id=<?php echo $id; ?>&idSPPDetail=<?php echo $idSPPDetail; ?>">
                                      <i class="fas fa-envelope"></i> Pembayaran
                                    </a>

                                    <a class="btn btn-app bg-danger m-0" href="./hapus.php?id=<?php echo $id; ?>&idSPPDetail=<?php echo $idSPPDetail; ?>">
                                      <i class="fas fa-trash"></i> Hapus
                                    </a>
                                  </div>
                                </td>
                              </tr>
                          <?php
                            };
                          };
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <a class="btn btn-primary btn-block mt-1" role="button" href="./buat.php?id=<?php echo $id; ?>"><i class="fa fa-plus"></i> Buat</a>
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
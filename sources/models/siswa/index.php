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

activity("Mengunjungi Halaman Siswa");
roleGuardMinimum($sessionLevel, "petugas", "/$originalPath/sources/models/utama");
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
          $result = mysqli_query($connection, "SELECT siswa.id FROM siswa;");
          $totalSudahLunas = 0;
          foreach ($result as $data) {
            $id = $data["id"];
            $totalNominal = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id';"))["total"];
            $totalSudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$id';"))["total"];

            if ($totalNominal == $totalSudahDibayar) {
              $totalSudahLunas += 1;
            };
          };

          $cardArray = [
            [
              "id" => 1,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Total Siswa",
                  "icon" => "users",
                  "value" => mysqli_num_rows($result)
                ],
                [
                  "id" => 2,
                  "title" => "Total Sudah Lunas",
                  "icon" => "check",
                  "value" => $totalSudahLunas
                ],
                [
                  "id" => 3,
                  "title" => "Total Belum Lunas",
                  "icon" => "times",
                  "value" => mysqli_num_rows($result) - $totalSudahLunas
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
                          "display" => null,
                          "name" => "tahun",
                          "type" => "select",
                          "value" => [
                            array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                              return [$yearObject[0], $yearObject[0]];
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(dibuat) FROM siswa ORDER BY dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : 0
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
                            ], isset($_POST["bulan"]) ? $_POST["bulan"] : 0
                          ],
                          "placeholder" => "Pilih bulan disini",
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
                            <th class="text-center align-middle export">NISN</th>
                            <th class="text-center align-middle export">NIS</th>
                            <th class="text-center align-middle export">Nama</th>
                            <th class="text-center align-middle export">Rombel</th>
                            <th class="text-center align-middle export">Kompetensi Keahlian</th>
                            <th class="text-center align-middle export">Jurusan</th>
                            <th class="text-center align-middle export">Tingkat</th>
                            <th class="text-center align-middle export">Alamat</th>
                            <th class="text-center align-middle export">Telepon</th>
                            <th class="text-center align-middle export">Nominal</th>
                            <th class="text-center align-middle export">Sudah Dibayar</th>
                            <th class="text-center align-middle export">Belum Dibayar</th>
                            <th class="text-center align-middle export">Status</th>
                            <th class="text-center align-middle export">Dibuat</th>
                            <th class="text-center align-middle export">Diubah</th>
                            <th class="text-center align-middle">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $currentDate = date("Y-m-d H:i:s");

                          $extraFilter = "";
                          $statusFilter = isset($_POST["status"]) ? $_POST["status"] : 0;

                          if (isset($_POST["tahun"])) {
                            $tahunFilter = $_POST["tahun"];
                            if ($tahunFilter != 0) {
                              $extraFilter = $extraFilter . " AND YEAR(siswa.dibuat)='$tahunFilter'";
                            };
                          };

                          if (isset($_POST["bulan"])) {
                            $bulanFilter = $_POST["bulan"];
                            if ($bulanFilter != 0) {
                              $extraFilter = $extraFilter . " AND MONTH(siswa.dibuat)='$bulanFilter'";
                            };
                          };

                          $result = mysqli_query($connection, "SELECT siswa.id, siswa.nisn, siswa.nis, siswa.nama, rombel.rombel, kompetensi_keahlian.singkatan AS `kompetensi_keahlian`, jurusan.singkatan AS `jurusan`, tingkat.tingkat, siswa.alamat, siswa.telepon, siswa.dibuat, siswa.diubah 
                            FROM siswa
                            INNER JOIN rombel ON siswa.id_rombel=rombel.id 
                            INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id
                            INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id
                            INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id
                            WHERE '1'='1' $extraFilter 
                            ORDER BY siswa.dibuat DESC;
                          ");

                          foreach ($result as $i => $data) {
                            $id = $data["id"];
                            $totalNominal = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(spp.nominal) AS `total` FROM spp_detail INNER JOIN spp ON spp_detail.id_spp=spp.id WHERE spp_detail.id_siswa='$id';"))["total"];
                            $totalSudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(pembayaran.jumlah_pembayaran) AS `total` FROM pembayaran INNER JOIN spp_detail ON pembayaran.id_spp_detail=spp_detail.id WHERE spp_detail.id_siswa='$id';"))["total"];

                            if ($statusFilter == 0 || ($statusFilter == 1 && $totalNominal != $totalSudahDibayar) || ($statusFilter == 2 && $totalNominal == $totalSudahDibayar)) {
                          ?>
                              <tr>
                                <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                                <td class="text-center align-middle"><?php echo $data["nisn"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["nis"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["nama"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["rombel"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["kompetensi_keahlian"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["jurusan"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["tingkat"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["alamat"]; ?></td>
                                <td class="text-center align-middle"><?php echo $data["telepon"]; ?></td>
                                <td class="text-center align-middle"><?php echo numberToCurrency($totalNominal); ?></td>
                                <td class="text-center align-middle"><?php echo numberToCurrency($totalSudahDibayar); ?></td>
                                <td class="text-center align-middle"><?php echo numberToCurrency($totalNominal - $totalSudahDibayar); ?></td>

                                <?php
                                if ($totalNominal == $totalSudahDibayar) {
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
                                <td class="text-center align-middle"><?php echo dateInterval($data["diubah"], $currentDate); ?></td>

                                <td class="text-center align-middle">
                                  <div class="btn-group">
                                    <a class="btn btn-app bg-primary m-0" href="./spp?id=<?php echo $data['id']; ?>">
                                      <i class="fas fa-clipboard"></i> SPP
                                    </a>

                                    <?php
                                    if (roleCheckMinimum($sessionLevel, "admin")) {
                                    ?>
                                      <a class="btn btn-app bg-warning m-0" href="./ubah.php?id=<?php echo $data['id']; ?>">
                                        <i class="fas fa-edit"></i> Ubah
                                      </a>

                                      <a class="btn btn-app bg-danger m-0" href="./ubah-password.php?id=<?php echo $data['id']; ?>">
                                        <i class="fas fa-lock"></i> Ubah Password
                                      </a>

                                      <a class="btn btn-app bg-danger m-0" href="./hapus.php?id=<?php echo $data['id']; ?>">
                                        <i class="fas fa-trash"></i> Hapus
                                      </a>
                                    <?php
                                    };
                                    ?>
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

                  <?php
                  if (roleCheckMinimum($sessionLevel, "admin")) {
                  ?>
                    <a class="btn btn-primary btn-block mt-1" href="./buat.php"><i class="fa fa-plus"></i> Buat</a>
                  <?php
                  };
                  ?>
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
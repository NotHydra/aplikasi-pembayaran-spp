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

activity("Mengunjungi Halaman Rombel");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Rombel";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/data-table/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [4, 1];
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
                  "title" => "Total Rombel",
                  "icon" => "archway",
                  "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM rombel;"))["total"]
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
                  <form class="mb-4" method="POST">
                    <div class="row my-0">
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
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Tahun Pembuatan",
                            "name" => "tahun",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[0]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(dibuat) FROM rombel ORDER BY dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : 0
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
                            <th class="text-center align-middle export">Kompetensi Keahlian</th>
                            <th class="text-center align-middle export">Jurusan</th>
                            <th class="text-center align-middle export">Tingkat</th>
                            <th class="text-center align-middle export">Rombel</th>
                            <th class="text-center align-middle export">Dibuat</th>
                            <th class="text-center align-middle export">Diubah</th>
                            <th class="text-center align-middle">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $currentDate = date("Y-m-d H:i:s");

                          $extraFilter = "";
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

                          if (isset($_POST["tahun"])) {
                            $tahunFilter = $_POST["tahun"];
                            if ($tahunFilter != 0) {
                              $extraFilter = $extraFilter . " AND YEAR(rombel.dibuat)='$tahunFilter'";
                            };
                          };

                          if (isset($_POST["bulan"])) {
                            $bulanFilter = $_POST["bulan"];
                            if ($bulanFilter != 0) {
                              $extraFilter = $extraFilter . " AND MONTH(rombel.dibuat)='$bulanFilter'";
                            };
                          };

                          $result = mysqli_query($connection, "SELECT rombel.id, rombel.rombel, rombel.dibuat, rombel.diubah, kompetensi_keahlian.kompetensi_keahlian, kompetensi_keahlian.singkatan AS `kompetensi_keahlian_singkatan`, jurusan.jurusan, jurusan.singkatan AS `jurusan_singkatan`, tingkat.tingkat FROM rombel INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id WHERE '1'='1' $extraFilter ORDER BY rombel.dibuat DESC;");
                          foreach ($result as $i => $data) {
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                              <td class="text-center align-middle"><?php echo $data["kompetensi_keahlian"] . " - " . $data["kompetensi_keahlian_singkatan"]; ?></td>
                              <td class="text-center align-middle"><?php echo $data["jurusan"] . " - " . $data["jurusan_singkatan"]; ?></td>
                              <td class="text-center align-middle"><?php echo $data["tingkat"]; ?></td>
                              <td class="text-center align-middle"><?php echo $data["rombel"]; ?></td>
                              <td class="text-center align-middle"><?php echo $data["dibuat"]; ?></td>
                              <td class="text-center align-middle"><?php echo dateInterval($data["diubah"], $currentDate); ?></td>

                              <td class="text-center align-middle">
                                <div class="btn-group">
                                  <a class="btn btn-app bg-warning m-0" href="./ubah.php?id=<?php echo $data['id']; ?>">
                                    <i class="fas fa-edit"></i> Ubah
                                  </a>

                                  <a class="btn btn-app bg-danger m-0" href="./hapus.php?id=<?php echo $data['id']; ?>">
                                    <i class="fas fa-trash"></i> Hapus
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

                  <a class="btn btn-primary btn-block mt-1" href="./buat.php"><i class="fa fa-plus"></i> Buat</a>
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
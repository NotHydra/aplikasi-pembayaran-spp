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

activity("Mengunjungi Halaman SPP");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "SPP";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/data-table/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [5, null];
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
                  "title" => "Total SPP",
                  "icon" => "clipboard",
                  "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM spp;"))["total"]
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
                            "display" => "Tahun Pembuatan",
                            "name" => "tahun",
                            "type" => "select",
                            "value" => [
                              array_merge([[0, "Semua"]], array_map(function ($yearObject) {
                                return [$yearObject[0], $yearObject[0]];
                              }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(dibuat) FROM spp ORDER BY dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : 0
                            ],
                            "placeholder" => "Pilih tahun pembuatan disini",
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
                          ],
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
                            <th class="text-center align-middle export">Dibuat</th>
                            <th class="text-center align-middle export">Diubah</th>
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
                              $extraFilter = $extraFilter . " AND YEAR(dibuat)='$tahunFilter'";
                            };
                          };

                          if (isset($_POST["bulan"])) {
                            $bulanFilter = $_POST["bulan"];
                            if ($bulanFilter != 0) {
                              $extraFilter = $extraFilter . " AND MONTH(dibuat)='$bulanFilter'";
                            };
                          };

                          $result = mysqli_query($connection, "SELECT id, tahun, nominal, dibuat, diubah FROM spp WHERE '1'='1' $extraFilter ORDER BY tahun DESC;");
                          foreach ($result as $i => $data) {
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                              <td class="text-center align-middle"><?php echo $data["tahun"]; ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["nominal"]); ?></td>
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
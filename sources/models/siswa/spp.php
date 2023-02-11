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

roleGuardMinimum($sessionLevel, "petugas", "/$originalPath");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM siswa WHERE id='$id' AND dihapus='0';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='.';</script>";
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
          $cardArray = [
            [
              "id" => 1,
              "child" => [
                [
                  "id" => 1,
                  "title" => "Total SPP",
                  "icon" => "book",
                  "value" => mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(id) AS `total` FROM spp WHERE dihapus='0';"))["total"]
                ],
                [
                  "id" => 2,
                  "title" => "Total Nominal",
                  "icon" => "wallet",
                  "value" => numberToCurrency(mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(nominal) AS `total` FROM spp WHERE dihapus='0';"))["total"])
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
                  "value" => numberToCurrency(mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(jumlah_pembayaran) AS `total` FROM pembayaran WHERE id_siswa='$id' AND dihapus='0';"))["total"])
                ],
                [
                  "id" => 2,
                  "title" => "Total Belum Dibayar",
                  "icon" => "times",
                  "value" => numberToCurrency(mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(nominal) AS `total` FROM spp WHERE dihapus='0';"))["total"] - mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(jumlah_pembayaran) AS `total` FROM pembayaran WHERE id_siswa='$id' AND dihapus='0';"))["total"])
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
                $extraTitle = "SPP";
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
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT tahun FROM spp WHERE dihapus='0' ORDER BY dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : null
                          ],
                          "placeholder" => "Pilih tahun disini",
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
                            <th class="text-center align-middle export">Tahun</th>
                            <th class="text-center align-middle export">Nominal</th>
                            <th class="text-center align-middle export">Sudah Dibayar</th>
                            <th class="text-center align-middle export">Belum Dibayar</th>
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

                          $result = mysqli_query($connection, "SELECT id, tahun, nominal FROM spp WHERE dihapus='0' $extraFilter ORDER BY dibuat DESC;");
                          foreach ($result as $i => $data) {
                            $idSPP = $data["id"];

                            $sudahDibayar = mysqli_fetch_assoc(mysqli_query($connection, "SELECT SUM(jumlah_pembayaran) AS `total` FROM pembayaran WHERE id_siswa='$id' AND id_spp='$idSPP' AND dihapus='0';"))["total"];
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                              <td class="text-center align-middle"><?php echo $data["tahun"]; ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["nominal"]); ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($sudahDibayar); ?></td>
                              <td class="text-center align-middle"><?php echo numberToCurrency($data["nominal"] - $sudahDibayar); ?></td>

                              <td class="text-center align-middle">
                                <div class="btn-group">
                                  <a class="btn btn-app bg-primary m-0" href="./pembayaran?id=<?php echo $id; ?>&idSPP=<?php echo $idSPP; ?>">
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

                  <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '.');"><i class="fa fa-undo"></i> Kembali</a>
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
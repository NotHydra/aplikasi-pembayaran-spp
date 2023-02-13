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

activity("Mengunjungi halaman pulih petugas");
roleGuardMinimum($sessionLevel, "superadmin", "/$originalPath/sources/models/utama");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Petugas";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/data-table/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [2, null];
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
                $extraTitle = "Pulih";
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
                            }, mysqli_fetch_all(mysqli_query($connection, "SELECT DISTINCT YEAR(dibuat) FROM petugas WHERE dihapus='1' ORDER BY dibuat DESC;")))), isset($_POST["tahun"]) ? $_POST["tahun"] : null
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
                            <th class="text-center align-middle export">Nama</th>
                            <th class="text-center align-middle export">Username</th>
                            <th class="text-center align-middle export">Telepon</th>
                            <th class="text-center align-middle export">Level</th>
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

                          $result = mysqli_query($connection, "SELECT id, nama, username, telepon, level, status, dibuat, diubah FROM petugas WHERE dihapus='1' $extraFilter ORDER BY dibuat DESC;");
                          foreach ($result as $i => $data) {
                            $id = $data['id'];
                          ?>
                            <tr>
                              <td class="text-center align-middle"><?php echo $i + 1; ?>.</td>
                              <td class="align-middle"><?php echo $data["nama"]; ?></td>
                              <td class="align-middle"><?php echo $data["username"]; ?></td>
                              <td class="align-middle"><?php echo $data["telepon"]; ?></td>
                              <td class="text-center align-middle"><?php echo ucwords($data["level"]); ?></td>
                              <td class="text-center align-middle"><?php echo ucwords($data["status"]); ?></td>
                              <td class="text-center align-middle"><?php echo $data["dibuat"]; ?></td>
                              <td class="text-center align-middle"><?php echo dateInterval($data["diubah"], $currentDate); ?></td>

                              <td class="text-center align-middle">
                                <?php
                                if (roleCheckMinimum($sessionLevel, roleConvert($data["level"]) + 1)) {
                                ?>
                                  <div class="btn-group">
                                    <a class="btn btn-app bg-success m-0" onclick="confirmModal('location', './pulih.php?id=<?php echo $id; ?>');">
                                      <i class="fas fa-history"></i> Pulih
                                    </a>
                                  </div>
                                <?php
                                } else {
                                ?>
                                  <div class="btn-group">
                                    <a class="btn btn-app bg-gray disabled m-0">
                                      <i class="fas fa-history"></i> Pulih
                                    </a>
                                  </div>
                                <?php
                                };
                                ?>
                              </td>
                            </tr>
                          <?php
                          };
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <a class="btn btn-danger btn-block mt-1" role="button" onclick="confirmModal('location', '.');"><i class="fa fa-undo"></i> Kembali</a>
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

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
      $id = $_GET["id"];
      $result = mysqli_query($connection, "SELECT level FROM petugas WHERE id='$id' AND dihapus='1';");
      $data = mysqli_fetch_assoc($result);

      if (mysqli_num_rows($result) <= 0 or !roleCheckMinimum($sessionLevel, roleConvert($data["level"]) + 1)) {
        echo "<script>window.location='./pulih.php';</script>";
      } else {
        try {
          $result = mysqli_query($connection, "UPDATE petugas SET dihapus='0' WHERE id='$id' AND dihapus='1';");

          if ($result) {
            activity("Memulihkan petugas");
            echo "<script>successModal(null, './pulih.php');</script>";
          } else {
            echo "<script>errorModal(null, null);</script>";
          };
        } catch (exception $e) {
          echo "<script>errorModal(null, null);</script>";
        }
      };
    }
  };
  ?>
</body>

</html>
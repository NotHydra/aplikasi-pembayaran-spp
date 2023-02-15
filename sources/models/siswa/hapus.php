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

activity("Mengunjungi halaman hapus siswa");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM siswa WHERE id='$id';");
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
          <div class="row">
            <div class="col-sm">
              <div class="card">
                <?php
                $pageItemObject = $pageArray[$navActive[0]];
                $extraTitle = "Hapus";
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT siswa.nisn, siswa.nis, siswa.nama, rombel.rombel, siswa.alamat, siswa.telepon FROM siswa INNER JOIN rombel ON siswa.id_rombel=rombel.id WHERE siswa.id='$id';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "NISN",
                            "name" => "nisn",
                            "type" => "number",
                            "value" => $data["nisn"],
                            "placeholder" => "Masukkan NISN disini",
                            "enable" => false
                          ],
                          [
                            "id" => 2,
                            "display" => "NIS",
                            "name" => "nis",
                            "type" => "number",
                            "value" => $data["nis"],
                            "placeholder" => "Masukkan NIS disini",
                            "enable" => false
                          ],
                          [
                            "id" => 3,
                            "display" => "Nama",
                            "name" => "nama",
                            "type" => "text",
                            "value" => $data["nama"],
                            "placeholder" => "Masukkan nama disini",
                            "enable" => false
                          ],
                          [
                            "id" => 4,
                            "display" => "Rombel",
                            "name" => "id_rombel",
                            "type" => "text",
                            "value" => $data["rombel"],
                            "placeholder" => "Masukkan rombel disini",
                            "enable" => false
                          ],
                          [
                            "id" => 5,
                            "display" => "Alamat",
                            "name" => "alamat",
                            "type" => "text",
                            "value" => $data["alamat"],
                            "placeholder" => "Masukkan alamat disini",
                            "enable" => false
                          ],
                          [
                            "id" => 6,
                            "display" => "Telepon",
                            "name" => "telepon",
                            "type" => "number",
                            "value" => $data["telepon"],
                            "placeholder" => "Masukkan telepon disini",
                            "enable" => false
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-danger btn-block" type="submit"><i class="fa fa-trash"></i> Hapus</button>
                        <a class="btn btn-danger btn-block" role="button" onclick="confirmModal('location', '.');"><i class="fa fa-undo"></i> Kembali</a>
                      </form>
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
  include "$sourcePath/components/select/script.php";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
      $result = mysqli_query($connection, "DELETE FROM siswa WHERE id='$id';");

      if ($result) {
        activity("Menghapus siswa");
        echo "<script>successModal(null, '.');</script>";
      } else {
        echo "<script>errorModal(null, null);</script>";
      };
    } catch (exception $e) {
      echo "<script>errorModal(null, null);</script>";
    };
  };
  ?>
</body>

</html>
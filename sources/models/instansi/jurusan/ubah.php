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

activity("Mengunjungi Halaman Ubah Jurusan");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM jurusan WHERE id='$id';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='.';</script>";
};
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Jurusan";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [4, 3];
    include "$sourcePath/components/nav.php";
    ?>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm">
              <div class="card">
                <?php
                $pageItemObject = $pageArray[$navActive[0]]["child"][$navActive[1]];
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "Ubah",
                    "link" => null
                  ]
                ];

                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT jurusan, singkatan FROM jurusan WHERE id='$id';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Jurusan",
                            "name" => "jurusan",
                            "type" => "text",
                            "value" => isset($_POST["jurusan"]) ? $_POST["jurusan"] : $data["jurusan"],
                            "placeholder" => "Masukkan jurusan disini",
                            "enable" => true
                          ],
                          [
                            "id" => 2,
                            "display" => "Singkatan",
                            "name" => "singkatan",
                            "type" => "text",
                            "value" => isset($_POST["singkatan"]) ? $_POST["singkatan"] : $data["singkatan"],
                            "placeholder" => "Masukkan singkatan disini",
                            "enable" => true
                          ]
                        ];

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <button class="btn btn-warning btn-block" type="submit"><i class="fa fa-edit"></i> Ubah</button>
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
    $jurusan = $_POST["jurusan"];
    $singkatan = $_POST["singkatan"];

    try {
      $result = mysqli_query($connection, "UPDATE jurusan SET jurusan='$jurusan', singkatan='$singkatan' WHERE id='$id';");

      if ($result) {
        activity("Mengubah Jurusan");
        echo "<script>successModal(null, null);</script>";
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
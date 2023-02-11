<?php
$sourcePath = "../../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/isNotAuthenticated.php";

include "$sourcePath/utilities/session/data.php";
include "$sourcePath/utilities/role.php";
include "$sourcePath/utilities/date.php";

roleGuardMinimum($sessionLevel, "admin", "/$originalPath");

$id = $_GET["id"];
$result = mysqli_query($connection, "SELECT id FROM kompetensi_keahlian WHERE id='$id' AND dihapus='0';");
if (mysqli_num_rows($result) <= 0) {
  echo "<script>window.location='.';</script>";
};
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Kompetensi Keahlian";
  include "$sourcePath/components/head.php";
  include "$sourcePath/components/select/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [4, 2];
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
                $extraTitle = "Ubah";
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return confirmModal('form', this);">
                        <?php
                        $data = mysqli_fetch_assoc(mysqli_query($connection, "SELECT kompetensi_keahlian, singkatan FROM kompetensi_keahlian WHERE id='$id' AND dihapus='0';"));
                        $inputArray = [
                          [
                            "id" => 1,
                            "display" => "Kompetensi Keahlian",
                            "name" => "kompetensi_keahlian",
                            "type" => "text",
                            "value" => isset($_POST["kompetensi_keahlian"]) ? $_POST["kompetensi_keahlian"] : $data["kompetensi_keahlian"],
                            "placeholder" => "Masukkan kompetensi keahlian disini",
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
    $kompetensiKeahlian = $_POST["kompetensi_keahlian"];
    $singkatan = $_POST["singkatan"];

    try {
      $result = mysqli_query($connection, "UPDATE kompetensi_keahlian SET kompetensi_keahlian='$kompetensiKeahlian', singkatan='$singkatan' WHERE id='$id' AND dihapus='0';");

      if ($result) {
        echo "<script>successModal(null, null);</script>";
      } else {
        echo "<script>errorModal(null, null);</script>";
      };
    } catch (exception $e) {
      $message = null;

      echo "<script>errorModal('$message', null);</script>";
    };
  };
  ?>
</body>

</html>
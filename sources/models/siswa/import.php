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

activity("Mengunjungi Halaman Import Siswa");
roleGuardMinimum($sessionLevel, "admin", "/$originalPath/sources/models/utama");
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
                $extraTitle = [
                  [
                    "id" => 1,
                    "title" => "Import",
                    "link" => null
                  ]
                ];

                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form method="POST" enctype="multipart/form-data" onsubmit="return confirmModal('form', this);">
                        <div class="form-group">
                          <label for="file">File Excel</label>

                          <div class="input-group">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="file" style="width: 100%;" name="file" oninvalid="this.setCustomValidity('Silakan Pilih File Terlebih Dahulu');" onchange="this.setCustomValidity(''); loadImage(this.id, event);" required>
                              <label class="custom-file-label" for="file">Masukkan file excel disini</label>
                            </div>
                          </div>
                        </div>

                        <button class="btn btn-success btn-block" type="submit"><i class="fa fa-download"></i> Import</button>
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
  ?>

  <script src="<?php echo $sourcePath; ?>/public/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <script>
    bsCustomFileInput.init();
  </script>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
      include "$sourcePath/utilities/spreadsheet/SpreadsheetReader.php";

      move_uploaded_file($_FILES['file']['tmp_name'], basename($_FILES['file']['name']));
      $reader = new SpreadsheetReader($_FILES["file"]["name"], false);
      unlink($_FILES["file"]["name"]);

      $rombelIdObject = [];
      foreach (mysqli_fetch_all(mysqli_query($connection, "SELECT rombel.id, rombel.rombel FROM rombel ORDER BY rombel.rombel ASC;")) as $itemObject) {
        $rombelIdObject[$itemObject[1]] = (int) $itemObject[0];
      };

      $formatIsValid = true;
      $sqlValue = array();
      foreach ($reader as $i => $row) {
        $nisn = $row[1];
        $nis = $row[2];
        $nama = $row[3];
        $rombel = $row[4];
        $alamat = $row[5];
        $telepon = $row[6];
        $password = $row[7];

        if ($i == 0) {
          if (!($nisn == "NISN" && $nis == "NIS" && $nama == "Nama" && $rombel == "Rombel" && $alamat == "Alamat" && $telepon == "Telepon" && $password == "Password")) {
            $formatIsValid = false;
            echo "<script>errorModal('Format Tidak Sesuai', './import.php');</script>";
            break;
          };
        } else {
          if (array_key_exists($row[4], $rombelIdObject)) {
            $password = md5($password);
            $rombel = $rombelIdObject[$row[4]];

            array_push($sqlValue, "('$nisn', '$nis', '$nama', $rombel, '$alamat', '$telepon', '$password')");
          } else {
            $formatIsValid = false;
            echo "<script>errorModal('Rombel Tidak Sesuai', './import.php');</script>";
            break;
          };
        };
      };

      if ($formatIsValid) {
        $result = mysqli_query($connection, "INSERT INTO siswa (nisn, nis, nama, id_rombel, alamat, telepon, password) VALUES " . join(", ", $sqlValue) . ";");

        if ($result) {
          activity("Mengimport Siswa");
          echo "<script>successModal(null, './import.php');</script>";
        } else {
          echo "<script>errorModal(null, './import.php');</script>";
        };
      };
    } catch (exception $e) {
      $message = null;
      $errorMessage = mysqli_error($connection);

      if (str_contains($errorMessage, "Duplicate entry")) {
        if (str_contains($errorMessage, "'nisn'")) {
          $message = "NISN sudah digunakan";
        } else if (str_contains($errorMessage, "'nis'")) {
          $message = "NIS sudah digunakan";
        };
      };

      echo "<script>errorModal('$message', './import.php');</script>";
    };
  };
  ?>
</body>

</html>
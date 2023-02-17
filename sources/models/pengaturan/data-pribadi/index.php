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

activity("Mengunjungi halaman data pribadi");
roleGuardMinimum($sessionLevel, "siswa", "/$originalPath/sources/models/authentication/logout.php");
?>

<!DOCTYPE html>
<html lang="in">

<head>
  <?php
  $headTitle = "Data Pribadi";
  include "$sourcePath/components/head.php";
  include "$sourcePath/utilities/modal.php";
  ?>
</head>

<body class="hold-transition layout-navbar-fixed layout-fixed" id="body-theme">
  <div class="wrapper">
    <?php
    $navActive = [7, 1];
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
                    "title" => "Utama",
                    "link" => null
                  ]
                ];
                include "$sourcePath/components/content/head.php";
                ?>

                <div class="card-body">
                  <div class="row">
                    <div class="col-sm">
                      <form>
                        <?php
                        if ($sessionType == "petugas") {
                          $inputArray = [
                            [
                              "id" => 1,
                              "display" => "Nama",
                              "name" => "nama",
                              "type" => "text",
                              "value" => $sessionNama,
                              "placeholder" => "Masukkan nama disini",
                              "enable" => false
                            ],
                            [
                              "id" => 2,
                              "display" => "Username",
                              "name" => "username",
                              "type" => "text",
                              "value" => $sessionUsername,
                              "placeholder" => "Masukkan username disini",
                              "enable" => false
                            ],
                            [
                              "id" => 3,
                              "display" => "Telepon",
                              "name" => "telepon",
                              "type" => "number",
                              "value" => $sessionTelepon,
                              "placeholder" => "Masukkan telepon disini",
                              "enable" => false
                            ],
                            [
                              "id" => 4,
                              "display" => "Level",
                              "name" => "level",
                              "type" => "text",
                              "value" => ucwords($sessionLevel),
                              "placeholder" => "Masukkan level disini",
                              "enable" => false
                            ],
                            [
                              "id" => 5,
                              "display" => "Status",
                              "name" => "status",
                              "type" => "text",
                              "value" => ucwords($sessionStatus),
                              "placeholder" => "Masukkan status disini",
                              "enable" => false
                            ],
                          ];
                        } else if ($sessionType == "siswa") {
                          $idRombelDisplay = mysqli_fetch_assoc(mysqli_query($connection, "SELECT rombel.rombel, kompetensi_keahlian.singkatan FROM rombel INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id WHERE rombel.id='$sessionIdRombel' ORDER BY rombel.dibuat DESC;"));
                          $inputArray = [
                            [
                              "id" => 1,
                              "display" => "NISN",
                              "name" => "nisn",
                              "type" => "number",
                              "value" => $sessionNISN,
                              "placeholder" => "Masukkan NISN disini",
                              "enable" => false
                            ],
                            [
                              "id" => 2,
                              "display" => "NIS",
                              "name" => "nis",
                              "type" => "number",
                              "value" => $sessionNIS,
                              "placeholder" => "Masukkan NIS disini",
                              "enable" => false
                            ],
                            [
                              "id" => 3,
                              "display" => "Nama",
                              "name" => "nama",
                              "type" => "text",
                              "value" => $sessionNama,
                              "placeholder" => "Masukkan nama disini",
                              "enable" => false
                            ],
                            [
                              "id" => 4,
                              "display" => "Rombel",
                              "name" => "id_rombel",
                              "type" => "text",
                              "value" => $idRombelDisplay["rombel"] . " - " . $idRombelDisplay["singkatan"],
                              "placeholder" => "Masukkan rombel disini",
                              "enable" => false
                            ],
                            [
                              "id" => 5,
                              "display" => "Alamat",
                              "name" => "alamat",
                              "type" => "text",
                              "value" => $sessionAlamat,
                              "placeholder" => "Masukkan alamat disini",
                              "enable" => false
                            ],
                            [
                              "id" => 6,
                              "display" => "Telepon",
                              "name" => "telepon",
                              "type" => "number",
                              "value" => $sessionTelepon,
                              "placeholder" => "Masukkan telepon disini",
                              "enable" => false
                            ]
                          ];
                        };

                        include "$sourcePath/components/input/detail.php";
                        ?>

                        <a class="btn btn-warning btn-block" href="./ubah.php"><i class="fa fa-edit"></i> Ubah</a>
                        <a class="btn btn-danger btn-block" href="./ubah-password.php"><i class="fa fa-lock"></i> Ubah Password</a>

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
</body>

</html>
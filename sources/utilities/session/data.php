<?php
$sessionId = $_SESSION["id"];
$sessionType = $_SESSION["type"];

if ($sessionType == "petugas") {
    $result = mysqli_query($connection, "SELECT nama, username, telepon, level FROM petugas WHERE id='$sessionId' AND status='aktif';");

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        $sessionNama = $data["nama"];
        $sessionUsername = $data["username"];
        $sessionTelepon = $data["telepon"];

        $sessionLevel = $data["level"];
        $sessionStatus = "aktif";
    } else {
        echo "<script>window.location='/$originalPath/sources/models/authentication/logout.php'</script>";
    };
} else if ($sessionType == "siswa") {
    $result = mysqli_query($connection, "SELECT nisn, nis, nama, id_rombel, alamat, telepon FROM siswa WHERE id='$sessionId';");

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        $sessionNISN = $data["nisn"];
        $sessionNIS = $data["nis"];
        $sessionNama = $data["nama"];
        $sessionIdRombel = $data["id_rombel"];
        $sessionAlamat = $data["alamat"];
        $sessionTelepon = $data["telepon"];

        $sessionLevel = "siswa";
        $sessionStatus = "aktif";
    } else {
        echo "<script>window.location='/$originalPath/sources/models/authentication/logout.php'</script>";
    };
};

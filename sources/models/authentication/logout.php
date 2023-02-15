<?php
$sourcePath = "../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/connection.php";
include "$sourcePath/utilities/session/start.php";

include "$sourcePath/middlewares/activity.php";

include "$sourcePath/utilities/session/data.php";

activity("Berhasil logout");

session_destroy();
echo "<script>window.location='/$originalPath'</script>";

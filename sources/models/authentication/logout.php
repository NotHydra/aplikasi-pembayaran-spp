<?php
$sourcePath = "../..";
include "$sourcePath/utilities/environment.php";
include "$sourcePath/utilities/session/start.php";

session_destroy();
echo "<script>window.location='/$originalPath'</script>";

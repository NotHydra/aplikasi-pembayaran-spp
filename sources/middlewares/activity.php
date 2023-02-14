<?php
function activity($aktivitas)
{
    global $connection, $sessionId, $sessionType;
    if ($sessionType == "petugas") {
        mysqli_query($connection, "INSERT INTO aktivitas (id_petugas, aktivitas) VALUES ('$sessionId', '$aktivitas');");
    };
};

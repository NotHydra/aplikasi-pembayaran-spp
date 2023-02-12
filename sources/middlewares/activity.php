<?php
function activity($aktivitas)
{
    global $connection, $sessionId;
    mysqli_query($connection, "INSERT INTO aktivitas (id_petugas, aktivitas) VALUES ('$sessionId', '$aktivitas');");
}

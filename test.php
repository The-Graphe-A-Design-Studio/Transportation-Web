<?php
    $Date = "2020-02-28 16:19:16";
    echo date('Y-m-d H:i:s', strtotime($Date. ' + 1 days'));
    echo "<br><br>";
    echo date('Y-m-d H:i:s', strtotime($Date. ' + 7 days'));
?>
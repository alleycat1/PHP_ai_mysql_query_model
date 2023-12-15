<?php
    require "config.php";
    $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $result = mysqli_query($db, "SELECT * FROM ssiegel_order_status");

    echo "<table border='1'>";
    echo "<tr>";
    while ($fieldinfo = mysqli_fetch_field($result)) {
        echo "<th>" . $fieldinfo->name . "</th>";
    }
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    mysqli_close($db);
?>
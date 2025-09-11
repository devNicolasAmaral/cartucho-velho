<?php
if (class_exists('mysqli')) {
    echo "MySQLi class exists and is working!\n";
    echo "MySQLi version: " . mysqli_get_client_version() . "\n";
} else {
    echo "MySQLi class NOT found\n";
}
?>

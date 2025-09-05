<?php
    include_once 'config.php';

    session_start();
    session_destroy();
    header('Location:' . BASE_URL . 'login.php');
    exit;
?>
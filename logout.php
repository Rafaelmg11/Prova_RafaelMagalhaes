<?php
    session_start();
    session_destroy();
    header("Location:login.php");
    require_once 'conexao.php';
    exit();
?>
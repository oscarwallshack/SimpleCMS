<?php
session_start();

if (!isset($_SESSION['udaneLogowanie'])) {
    header('Location:logowanie.php');
    exit();
}

require('head.php');
require('aheader.php');
?>



    
    <main class="main_content">

    <h1>Witam w panelu admina.</h1>
    <hr>
    </main>
    <?php require('afooter.php'); ?>
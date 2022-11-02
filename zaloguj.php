<?php

session_start();

if ((isset($_POST['login'])) || (isset($_POST['haslo']))) {


    $login = $_POST['login'];
    $_SESSION['login'] = $login; //dodanie loginy do zmiennej sesyjnej aby dodać przywitanie w zarzadzanie.php
    $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT); // zapisanie przesłanej zmiennej w hashu bcrypt

    //sprawdzenie poprawności loginu

    if (!ctype_alnum($login)) {
        $_SESSION['e_login'] = "Login może składać się tylko z dużych i małych liter i cyfr.";
        header('Location: login.php');
        exit();
    }

    try {
        //sprawdzenie czy login znajduje sie w bazie
        require_once 'db/database.php';

        $admin = $db->prepare("SELECT * FROM 31300146_walczak.admini WHERE login = ?");
        $admin->execute(array($login));

        if (!$admin) throw new Exception($db->error);

        //sprawdzenie czy w bazie znaleziono admina o nazwie $login

        $ilu_adminow = $admin->rowCount();

        if ($ilu_adminow > 0) {

            $wiersz = $admin->fetch(PDO::FETCH_ASSOC); //pobranie wiersza danych admina o nazwie $login
            
            //sprawdzenie poprawnosci hasla 

            if (password_verify($_POST['haslo'], $wiersz['haslo'])) {
                $_SESSION['udaneLogowanie'] = true;
                header('Location: admin/admin.php');
                exit();
            } else {
                $_SESSION['e_log'] = '<span style="color:red"> Nieprawidłowy login lub hasło! </span>';
                header('Location: login.php');
                exit();
            }
        } else {
            $_SESSION['e_log'] = '<span style="color:red"> Nieprawidłowy login lub hasło! </span>';
            header('Location: login.php');
            exit();
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
        echo '<br />Informacja developerska: ' . $e;
    }
} else {
    header('Location: login.php');
    exit();
}

<?php
session_start();

require_once '/db/database.php';

// ####### DODAWANIE NOWEJ STRONY #######

if (isset($_POST['submit_new_page'])) {
    if ((isset($_POST['nazwa'])) && $_POST['nazwa'] != '' && (isset($_POST['meta_title'])) && $_POST['meta_title'] != '' && (isset($_POST['meta_description'])) && $_POST['meta_description'] != '' && (isset($_POST['meta_robots']))) {
        $meta_title = trim($_POST['meta_title']);
        $meta_description = trim($_POST['meta_description']);
        $meta_robots = $_POST['meta_robots'];
        $nazwa = trim($_POST['nazwa']);
        $numer = $_POST['numer'];   //kolejność w menu
        $naglowek = "Nagłówek h1!";
        $tresc = "To jest Twoja nowa strona!";

        //dodawanie "-" w miejscach spacji
        $url = str_replace(' ', '-', $nazwa) . '.php'; 

        // $template_file = require('templateFile.php');

        // $newPage = fopen($nazwa.".php", "w", './') or die("Unable to open file!");
        // fwrite($newPage, $template_file);
        // fclose($myfile);

        $file = '/templateFile.php';
        $newfile = '/' . $url;

        if (copy($file, $newfile)) {

            try {


                // sprawdzenie czy podana nazwa strony została już wykorzystana 

                $rezultat = $db->prepare("SELECT id FROM 31300146_walczak.strony WHERE nazwa = ? ");
                $rezultat->execute(array($nazwa));

                if (!$rezultat) throw new Exception($db->error);

                $rezultat = $rezultat->rowCount();
                if ($rezultat > 0) {
                    $_SESSION['e_all_pages'] = "Strona o takiej nazwie już istnieje.";
                    header('Location: pages.php');
                    exit();
                }

                //dodanie danych do bazy

                //przygotowywanie zapytania
                $query = $db->prepare("INSERT INTO 31300146_walczak.strony VALUES (NULL, :nazwa, :numer, :meta_url, :meta_title, :meta_description, :meta_robots, :naglowek_h1, :tresc)");
                $query->bindValue(':nazwa', $nazwa, PDO::PARAM_STR);
                $query->bindValue(':numer', $numer, PDO::PARAM_STR);
                $query->bindValue(':meta_url', $url, PDO::PARAM_STR);
                $query->bindValue(':meta_title', $meta_title, PDO::PARAM_STR);
                $query->bindValue(':meta_description', $meta_description, PDO::PARAM_STR);
                $query->bindValue(':meta_robots', $meta_robots, PDO::PARAM_STR);
                $query->bindValue(':naglowek_h1', $naglowek, PDO::PARAM_STR);
                $query->bindValue(':tresc', $tresc, PDO::PARAM_STR);


                //egzekucja zapytania
                $query->execute();
            } catch (Exception $e) {
                $_SESSION['e_all_pages'] = 'Błąd serwera. Informacja developerska: <br>' . $e;
                header('Location: pages.php');
                exit();
            }


            $_SESSION['e_all_pages'] = 'Udało się stworzyć stronę!';
            header('Location: pages.php');
            exit();
        } else {
            $_SESSION['e_all_pages'] = 'Coś poszło nie tak, spróbuj ponownie!';
        }
    } else {
        $_SESSION['e_all_pages'] = 'Wszystkie pola są wymagane';
        header('Location: pages.php');
        exit();
    }
}

// ####### USUWANIE STRONY #######
if (isset($_GET['delete'])) {

    try {
        $id_to_del = $_GET['delete']; //pobranie id strony do usunięcia
        
        //pobranie informacji jaki plik jest do usunięcia
        $file_to_delete = $db->prepare("SELECT meta_url FROM 31300146_walczak.strony WHERE id= ?");
        $file_to_delete->execute(array($id_to_del));
        $file_to_delete = $file_to_delete->fetch();

        // usuwanie pliku z serwera
        if (!unlink('/' . $file_to_delete['meta_url'])) {
            throw new Exception($db->error);
        }

        $akcja = $db->prepare("DELETE FROM 31300146_walczak.strony WHERE id= ?");
        $akcja->execute(array($id_to_del));

        $akcja = $akcja->rowCount();
        if ($akcja = 1) {
            $_SESSION['e_all_pages'] = 'Operacja udana';
            header('Location: pages.php');
            exit();
        } else {
            throw new Exception($db->error);
        }
    } catch (Exception $e) {
        $_SESSION['e_all_pages'] = 'Operacja usunięcia strony nie udana :(' . $file_to_delete['meta_url'];
        header('Location: pages.php');
        exit();
    }
}
// ####### EDYCJA  STRONY #######
if (isset($_POST['edit_page'])) {
    if (isset($_POST['hidden_input'])) {
        $id_to_update = $_POST['hidden_input'];
        $nazwa_to_update = $_POST['nazwa'];
        $numer_to_update = $_POST['numer'];
        $meta_title_to_update = $_POST['meta_title'];
        $meta_description_to_update = $_POST['meta_description'];
        $meta_robots_to_update = $_POST['meta_robots'];

        try {

            $update = $db->prepare("UPDATE 31300146_walczak.strony SET nazwa=?, numer=?, meta_title=?, meta_description=?, meta_robots=? WHERE id= ?");
            $update->execute(array($nazwa_to_update, $numer_to_update, $meta_title_to_update, $meta_description_to_update, $meta_robots_to_update, $id_to_update));


            $update = $update->rowCount();

            // rename("$nazfile.txt", "/home/user/login/docs/my_file.txt");


            if ($update = 1) {
                $_SESSION['e_all_pages'] = 'Operacja udana';
                header('Location: pages.php');
                exit();
            } else {
                throw new Exception($db->error);
            }
        } catch (Exception $e) {
            $_SESSION['e_all_pages'] = 'Operacja edycji nie udana :(( <br>' . $e;
            header('Location: pages.php');
            exit();
        }
    }
}

// ####### EDYCJA  TREŚCI #######

if (isset($_POST['edit_content'])) {
    if (isset($_POST['hidden_input'])) {
        $id_to_update = $_POST['hidden_input'];
        $naglowek_h1_to_update = $_POST['naglowek_h1'];
        $tresc_to_update = $_POST['tresc'];

        try {

            $update = $db->prepare("UPDATE 31300146_walczak.strony SET naglowek_h1=?, tresc=? WHERE id= ?");
            $update->execute(array($naglowek_h1_to_update, $tresc_to_update, $id_to_update));


            $update = $update->rowCount();
            if ($update = 1) {
                $_SESSION['e_blocks'] = 'Operacja udana';
                header('Location: blocks.php');
                exit();
            } else {
                throw new Exception($db->error);
            }
        } catch (Exception $e) {
            $_SESSION['e_blocks'] = 'Operacja edycji nie udana :(( <br>' . $e;
            header('Location: blocks.php');
            exit();
        }
    }
}

header('Location: admin.php');
exit();

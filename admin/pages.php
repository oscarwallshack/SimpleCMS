<?php
session_start();

if (!isset($_SESSION['udaneLogowanie'])) {
    header('Location:logowanie.php');
    exit();
}
try {
    require_once 'db/database.php';

    // $sql = 'SELECT * FROM 31300146_walczak.strony WHERE meta_url = "$url"';
    // $meta_tags = $db->query($sql);
    $update = false;

    $pages = $db->prepare("SELECT * FROM 31300146_walczak.strony");
    $pages->execute();


    if (!$pages) throw new Exception($db->error);
    //$page = $pages->fetchAll(); //pobranie wiersza danych strony

} catch (Exception $e) {
    $_SESSION['e_all_pages'] = 'Błąd serwera. Informacja developerska: <br>' . $e;
}

if (isset($_GET['edit'])) {
    try {
        $id_to_edit = $_GET['edit'];

        $akcja = $db->prepare("SELECT * FROM 31300146_walczak.strony WHERE id= ?");
        $akcja->execute(array($id_to_edit));

        $working_parkig = $akcja->rowCount();
        if ($working_parkig = 1) {
            foreach ($akcja as $data) {
                $nazwa = $data['nazwa'];
                $numer = $data['numer'];
                $meta_url = $data['meta_url'];
                $meta_title = $data['meta_title'];
                $meta_description = $data['meta_description'];
                $meta_robots = $data['meta_robots'];

                $update = true;
            }
        }
    } catch (Exception $e) {
        $_SESSION['e_all_pages'] = 'Operacja nie udana';
        header('Location: pages.php');
        exit();
    }
}

require('head.php');
require('aheader.php');
?>




<main class="main_content">

    <h1>Strony</h1>
    <hr>
    <!-- DODAWANIE NOWYCH STRON -->

    <span class="dyskretny_margin">
        <button class="btn_new_page">Dodaj nową stronę</button>
        <br>

    </span>
    <div class="dyskretny_margin div_display form_hide<?php if ($_GET['edit'] > 0) {
                                                            echo 'blokades';
                                                        } ?>">
        <form class="form_new_page" action='functions.php' method='post'>
            <table>
                <caption>
                    <h2>Podaj dane</h2>
                </caption>
                <tbody>
                    <tr>
                        <td><input class="text_input" type="text" name='nazwa' placeholder="Nazwa" value="<?php echo $nazwa ?>" /></td>
                    </tr>
                    <tr>
                        <td><input class="text_input" type="number" name='numer' placeholder="Pozycja w menu" value="<?php echo $numer ?>" /></td>
                    </tr>
                    <tr>
                        <td><input class="text_input" type="text" name='meta_title' placeholder="Meta title" value="<?php echo $meta_title ?>" /></td>
                    </tr>
                    <tr>
                        <td><input class="text_input" type="text" name='meta_description' placeholder="Meta description" value="<?php echo $meta_description ?>" /></td>
                    </tr>
                    <tr>
                        <td><label><input type="radio" name='meta_robots' value="index" <?php if ($meta_robots == 'index') {
                                                                                            echo 'checked';
                                                                                        } ?> />Index</label></td>
                    <tr>
                    </tr>
                    <td><label><input type="radio" name='meta_robots' value="noindex" <?php if ($meta_robots == 'noindex') {
                                                                                            echo 'checked';
                                                                                        } ?> />Noindex</label></td>
                    </tr>

                </tbody>
            </table>

            </br>
            </br>
            <?php if ($update == true) : ?>
                <input type="hidden" name="hidden_input" value="<?php echo $_GET['edit']; ?>" />
                <input class="btn btn_submit_new_page" type="submit" value="Aktualizuj" name="edit_page" />
            <?php else : ?>
                <input class="btn btn_submit_new_page" type="submit" value="Akceptuj" name="submit_new_page" />
            <?php endif; ?>
            <button class="btn btn_delete"><a href="https://walczak.4suns.pl/admin/pages.php" name="submit_new_page">Anuluj</a></button>
            <br>
            </br>


        </form>
    </div>

    <!-- WYŚWIETLANIE ISTNIEJĄCYCH STRON -->

    <div class="dyskretny_margin all_pages">

        <?php if (isset($_SESSION['e_all_pages'])) {
            echo $_SESSION['e_all_pages'];
            unset($_SESSION['e_all_pages']);
        }
        ?>
        <table class="all_pages_table">
            <thead>
                <tr class="naglowki">
                    <th>Id</th>
                    <th>Nazwa</th>
                    <th>Kolejność</th>
                    <th>Meta-url</th>
                    <th>Meta-title</th>
                    <th>Meta-description</th>
                    <th>Meta-robots</th>
                    <th colspan='2'>Akcje</th>

                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($pages as $page) { //pobranie danych i zapis do zmiennej $data jako tablica asocjacyjna
                ?>
                    <tr class="border">
                        <td>
                            <?= $page['id'] ?>
                        </td>
                        <td>
                            <?= $page['nazwa'] ?>
                        </td>
                        <td>
                            <?= $page['numer'] ?>
                        </td>
                        <td>
                            <?= $page['meta_url'] ?>
                        </td>
                        <td>
                            <?= $page['meta_title'] ?>
                        </td>
                        <td>
                            <?= $page['meta_description'] ?>
                        </td>
                        <td>
                            <?= $page['meta_robots'] ?>
                        </td>
                        <td class="td_button"> 
                            <button class="btn_edit"><a href="pages.php?edit=<?php echo $page['id']; ?>">Edytuj</a></button>
                        </td>
                        <td class="td_button">
                            <button class="<?php if($page['id'] == 1){echo 'disabled ';}?> btn_delete"><a href="functions.php?delete=<?php echo $page['id']; ?>" class="<?php if($page['id'] == 1){echo 'disabled';}?>">Usuń</a></button>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

    </div>







</main>

<?php require('afooter.php'); ?>
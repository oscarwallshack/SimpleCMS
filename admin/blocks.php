<?php
session_start();

if (!isset($_SESSION['udaneLogowanie'])) {
    header('Location:logowanie.php');
    exit();
}
try {
    require_once 'db/database.php';

    $pages = $db->prepare("SELECT * FROM 31300146_walczak.strony");
    $pages->execute();


    if (!$pages) throw new Exception($db->error);
    //$page = $pages->fetchAll(); //pobranie wiersza danych strony

} catch (Exception $e) {
    $_SESSION['e_blocks'] = 'Błąd serwera. Informacja developerska: <br>' . $e;
}

if (isset($_GET['edit'])) {
    try {
        $id_to_edit = $_GET['edit'];

        $akcja = $db->prepare("SELECT * FROM 31300146_walczak.strony WHERE id= ?");
        $akcja->execute(array($id_to_edit));

        $working_parkig = $akcja->rowCount();
        if ($working_parkig = 1) { //pobieranie danych do wpisania w formularzu edycji
            foreach ($akcja as $data) {
                $nazwa = $data['nazwa'];
                $naglowek_h1 = $data['naglowek_h1'];
                $tresc = $data['tresc'];

                $update = true;
            }
        }
    } catch (Exception $e) {
        $_SESSION['e_blocks'] = 'Operacja nie udana';
        header('Location: blocks.php');
        exit();
    }
}

require('head.php');
require('aheader.php');
?>




<main class="main_content">

    <h1>Edytuj treści</h1>
    <hr>


    <!-- WYŚWIETLANIE JAKIE TREŚCI SĄ NA STRONIE -->

    <div class="dyskretny_margin all_pages">

        <?php if (isset($_SESSION['e_blocks'])) {
            echo $_SESSION['e_blocks'];
            unset($_SESSION['e_blocks']);
        }
        ?>
        <table class="all_pages_table">
            <thead>
                <tr class="naglowki">
                    <th>Id</th>
                    <th>Nazwa</th>
                    <th>Nagłówek h1</th>
                    <th>Akcje</th>

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
                            <?= $page['naglowek_h1'] ?>
                        </td>
                                                <td class="td_button">
                            <button class="btn_edit"><a href="blocks.php?edit=<?php echo $page['id']; ?>">Edytuj</a></button>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>

    </div>

    <!-- EDYCJA TREŚCI FORMULARZ -->

    <div class="dyskretny_margin div_display form_hides<?php if ($_GET['edit'] > 0) {
                                                                echo 'blokades';
                                                            } ?>">
        <form class="form" action='functions.php' method='post'>
            <table>
                <caption>
                    <h2>Podaj dane</h2>
                </caption>
                <tbody>
                    <tr>
                        <td><input class="text_input" type="text" name='naglowek_h1' placeholder="Nagłówek H1" value="<?php echo $naglowek_h1 ?>" /></td>
                    </tr>
                    <tr>
                        <td class=""><textarea class="textarea tinymce" type="text" name='tresc' placeholder="Treść"><?php echo $tresc ?> </textarea></td>
                    </tr>

                </tbody>
            </table>

            </br>
            </br>
            <input type="hidden" name="hidden_input" value="<?php echo $_GET['edit']; ?>" />
            <input class="btn btn_submit_new_page" type="submit" value="Aktualizuj" name="edit_content" />
            <button class="btn btn_delete"><a href="https://walczak.4suns.pl/admin/blocks.php" name="submit_new_page">Anuluj</a></button>
            <br>
            </br>


        </form>
    </div>

</main>

<?php require('afooter.php'); ?>
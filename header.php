<?php

try {

    $pages = $db->prepare("SELECT * FROM 31300146_walczak.strony");
    $pages->execute();

    if (!$pages) throw new Exception($db->error);
    $page = $pages->fetch(); //pobranie wiersza danych strony
} catch (Exception $e) {
    echo 'Błąd serwera. Informacja developerska: <br>' . $e;
}
    // $url = substr($url, 0, strpos($url, '.php'));

?>

<body class="grid_container">
    <header class="header">
        <nav class="top_nav" id="id_top_nav">
       <div> <a href="index" id="logo">CMS-Project</a></div>

            <ul>

                <!-- <li> <a href="index.php" id="logo">BRAND</a></li> -->
                <li style="order: <?php echo $page['numer'];?>;"><a href="index" class="active">HOME</a></li>

                <?php
                foreach ($pages as $page) { //pobranie danych i zapis do zmiennej $data jako tablica asocjacyjna
                ?>
                    <li style="order: <?php echo $page['numer'];?>;"><a href="https://walczak.4suns.pl/<?php echo substr($page['meta_url'], 0, strpos($page['meta_url'], '.php')); ?>"><?php echo $page['nazwa']; ?></a></li>
                <?php } ?>

                <li style="order: -99;"> <a href="login.php" id="zaloguj_butt">Zaloguj</a></li>

                <a href="javascript:void(0);" class="icon" onclick="myFunction();">
                    <i class="fa fa-bars"></i></a>
            </ul>
        </nav>
    </header>
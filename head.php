<?php
session_start();
try {

    $url = ($_SERVER['REQUEST_URI']);
    $url = substr($url, 1);

    if(substr($url, -4) != '.php'){
        $url = $url.'.php';
    }

    // $url = substr($url, 0, strpos($url, '.php'));
    require_once 'db/database.php';

    $meta_tags = $db->prepare("SELECT * FROM 31300146_walczak.strony WHERE meta_url = ? ");
    $meta_tags->execute([$url]);

    if (!$meta_tags) throw new Exception($db->error);
    $meta_tag = $meta_tags->fetch(); //pobranie wiersza danych strony
} catch (Exception $e) {
    echo 'Błąd serwera. Informacja developerska: <br>' . $e;
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,700&display=swap&subset=latin-ext" rel="stylesheet">
    <script type="text/javascript" src="/JS/script.js" defer></script>
    <script type="text/javascript" src="/JS/jquery.min.js" defer></script>
    <script type="text/javascript" src="/plugins/tinymce/tinymce.min.js" defer></script>
    <script type="text/javascript" src="/plugins/tinymce/init-tinymce.js" defer></script>


    <link rel="stylesheet" href="CSS/style.css" type="text/css">
    <meta name="robots" content="<?php echo $meta_tag['meta_robots']; ?>" />
    <title><?php echo $meta_tag['meta_title']; ?></title>
    <meta name="description" content="<?php echo $meta_tag['meta_description']; ?>">
</head>

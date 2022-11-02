<?php

session_start();

if ((isset($_SESSION['udaneLogowanie'])) && ($_SESSION['udaneLogowanie'] == true)) {
    header('Location: admin/admin.php');
    exit();
}

require('head.php');
?>

<body class="login_page">
    <div class="container_logowanie">
        <form action='zaloguj.php' method='post'>
            <table>
                <caption style="color:white">
                    <h1>Panel logowania</h1>
                </caption>
                <tbody>
                    <tr>
                        <td><input class="form_inputs" type="text" name='login' placeholder="Login" /></td>
                    </tr>
                    <tr>
                        <td><input class="form_inputs" type="password" name='haslo' placeholder="Password" /></td>
                    </tr>
                </tbody>
            </table>

            </br>
            </br>
            <button><input class="btn" type="submit" value="Zaloguj" name="submit" /></button>
            <br>
            </br>

            <?php

            if (isset($_SESSION['e_log'])) {
                echo $_SESSION['e_log'];
                unset($_SESSION['e_log']);
            }

            ?>
        </form>
    </div>
</body>
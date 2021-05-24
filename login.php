<?php

require_once 'dbconfig.php';

session_start();

if (isset($_SESSION["username"])) {
    header("Location: home.php");
    exit;
}

if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]) or die("Errore: " . mysqli_connect_error());
    $user = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $searchField = filter_var($user, FILTER_VALIDATE_EMAIL) ? "email" : "username";
    $query = "SELECT id,username,password FROM users WHERE " . $searchField . " ='" . $user . "'";

    /*if(strpos($user,"@") !== false){
        $query = "SELECT * FROM users WHERE email = '" . $user . "' AND password = '". $password."'";
    }else{
        $query = "SELECT * FROM users WHERE username = '" . $user . "' AND password = '". $password."'";    
    }*/

    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        $entry = mysqli_fetch_assoc($res);

        if (password_verify($_POST["password"], $entry["password"])) {
            $_SESSION["id"] = $entry["id"];
            $_SESSION["username"] = $entry["username"];
            
            mysqli_free_result($res);
            mysqli_close($conn);

            if(isset($_GET["returnTo"])){
                header("Location: ".$_GET["returnTo"]);
                exit;
            }
            
            header("Location: home.php");
            exit;
        } else {
            $errore = "Credenziali non valide!";
        }
    } else {
        $errore = "Utente non esistente!";
    }
} else if (isset($_POST["username"]) || isset($_POST["password"])) {
    $errore = "Compilare tutti i campi!";
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Lifestyle - Login</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="styles/login.css" />
    <script src="scripts/script_menu.js" defer="true"></script>
</head>

<body>
    <header>
        <nav>
            <div class="bar">
                <div class="logo">
                    Fitness Lifestyle
                    <img src="images/logo.png" />
                </div>

                <div class="link">
                    <a href="home.php">Home</a>
                    <a href="account.php">Account</a>
                </div>

                <div class="menu_tendina">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>

            <div class="impostazioni">
                <div id="menuAperto" class="hidden">
                    <a href="home.php">Home</a>
                    <div></div>
                    <a href="account.php">Account</a>
                </div>
            </div>
        </nav>
    </header>

    <section>
        <h1>Accedi al tuo account</h1>

        <?php
        if (isset($errore)) {
            echo "<p class='errore'>" . $errore . "</p>";
        }
        ?>

        <main>
            <form name="autenticazione" method="post">
                <p>
                    <label>Inserisci Email/Nome utente <input type="text" name="username"></label>
                </p>
                <p>
                    <label>Inserisci Password <input type="password" name="password"></label>
                </p>
                <p>
                    <label>&nbsp;<input type="submit" value="Accedi"></label>
                </p>
            </form>
        </main>

        <div>
            <h3>Non sei iscritto?</h3>
            <a href="registrazione.php">Clicca qui</a>
        </div>
    </section>

    <footer>
        <div>
            <img src="images/logo.png" />
            <h3>Fitness lifestyle</h3>
        </div>
        <p>
            <strong>Giovanni Caschetto - Matricola O46002058</strong>
            <em>email: giovannicasch@gmail.com</em>
        </p>
    </footer>
</body>

</html>
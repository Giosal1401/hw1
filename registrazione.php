<?php

require_once "dbconfig.php";

session_start();

if (isset($_SESSION["username"])) {
    header("Location: home.php");
    exit;
}

if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"])) {

    $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]) or die("Errore: " . mysqli_connect_error());
    $user = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = mysqli_real_escape_string($conn, strtolower($_POST["email"]));

    $errori = array();
    //Controllo Username
    if(strlen($user) < 5){
        $errori[] = "Usernamenon valido,troppo corto!";
    }else{
        $query = "SELECT username FROM users WHERE username='".$user."'";

        $res = mysqli_query($conn,$query);
        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                if ($row["username"] == $_POST["username"]) {
                    $errori[] = "Username già esistente!";
                }
            }
        }
    }
    
    //Controllo Email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errori[] = "Email non valida";
    }else{
        $query = "SELECT email FROM users WHERE email='".$email."'";

        $res = mysqli_query($conn,$query);
        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                if ($row["email"] === $_POST["email"]) {
                    $errori[] = "Email già associata ad un account!";
                }
            }
        }
    }

    //Controllo Password
    if(strlen($password) < 6 || strcmp(strtolower($password),$password) == 0 || preg_match('/[0-9]/',$password) == 0){
        $errori[] = "Password non valida";
    }

    //Registrazione
    if(count($errori) == 0) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        //$password = password_hash($password, PASSWORD_DEFAULT);
  
        $query = "INSERT INTO users (username,email,password) VALUES ('" . $user . "','" . $email . "','" . $password . "')";      
        if (mysqli_query($conn,$query)){
            $_SESSION["username"] = $_POST["username"];
            $_SESSION["id"] = mysqli_insert_id($conn);
            echo $_SESSION["id"];
            mysqli_close($conn);
            
            if(isset($_GET["returnTo"])){
                header("Location: ".$_GET["returnTo"]);
                exit;
            }else{
                header("Location: home.php");
                exit;
            }
        }else{
            $errori[] = "Registazione fallita, errore di connessione al Database";
        }
    }
    
    mysqli_close($conn);
}else if (isset($_POST["username"]) || isset($_POST["password"]) || isset($_POST["email"])) {
    $errori = array("Compila tutti i campi");
}

?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Lifestyle - Registrazione</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="styles/registrazione.css" />
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
        <h1>Registrati</h1>
        <ul>
            <li>Il nome utente deve contenere almeno 5 caratteri</li>
            <li>La password deve essere lunga almeno 6 caratteri di cui almeno 1 carattere maiuscolo e 1 numero</li>
        </ul>

        <?php
        if (isset($errori) && count($errori) != 0) {
            echo "<p class='errore'>";
            for($i = 0; $i< count($errori); $i++){
                echo $errori[$i]."<br>";
            }
            echo "</p>";
        }
        ?>

        <main>
            <form name="registrazione" method="post">
                <p>
                    <label>Inserisci Nome utente <input type="text" name="username"></label>
                </p>
                <p>
                    <label>Inserisci Email<input type="text" name="email"></label>
                </p>
                <p>
                    <label>Inserisci Password <input type="password" name="password"></label>
                </p>
                <p>
                    <label>&nbsp;<input type="submit" value="Registrati"></label>
                </p>
            </form>
        </main>

        <div>
            <h3>Sei già iscritto?</h3>
            <a href="login.php">Accedi qui</a>
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
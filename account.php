<?php
require_once "dbconfig.php";
session_start();

if (isset($_SESSION["id"])) {
    $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]) or die("Errore: " . mysqli_connect_error());

    $query = "SELECT username,email FROM users WHERE id ='" . $_SESSION["id"] . "'";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        $user = $row["username"];
        $email = $row["email"];
    } else $errore = true;

    mysqli_free_result($res);
    mysqli_close($conn);
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Lifestyle - Account</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="styles/account.css" />
    <script src="scripts/script_menu.js" defer="true"></script>
    <script src="scripts/account.js" defer="true"></script>
</head>

<body>
    <div id="container">
        <header>
            <nav>
                <div class="bar">
                    <div class="logo">
                        Fitness Lifestyle
                        <img src="images/logo.png" />
                    </div>

                    <div class="link">
                        <a href="home.php">Home</a>
                        <a href="carrello.php">Carrello</a>
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
                        <a href="carrello.php">Carrello</a>
                    </div>
                </div>
            </nav>
        </header>

        <section>
            <div <?php if (!isset($_SESSION["id"])) {echo "class=hidden";} ?>>
                <h1>I dettagli del tuo account</h1>
                <table>
                    <tr>
                        <th>Username: </th>
                        <td><?php if (isset($_SESSION["id"]) && !isset($errore)){echo $user;} else echo "null"; ?></td>
                    </tr>
                    <tr>
                        <th>Email: </th>
                        <td><?php if (isset($_SESSION["id"]) && !isset($errore)){echo $email;} else echo "null"; ?></td>
                    </tr>
                    <tr>
                        <th>Password: </th>
                        <td>*******</td>
                    </tr>
                </table><br>
                <p>Vuoi cambiare email? <button id="email_button">Clicca qui</button></p>
                <p>Vuoi cambiare password? <button id="password_button">Clicca qui</button></p>
                <a href="logout.php">Logout</a>
            </div>

            <div <?php if (isset($_SESSION["id"])) { echo "class=hidden";} ?>>
                <h1>Devi prima accedere al tuo account</h1>
                <p>Non sei iscritto?<a href="registrazione.php?returnTo=account.php">Registrati qui</a></p>
                <p>Sei già iscritto?<a href="login.php?returnTo=account.php">Accedi</a></p>
            </div>

            <div <?php if(!isset($_SESSION["id"])) { echo "class=hidden";} ?>>
                <h1>I tuoi ordini</h1>
                <div class="grid" id="ordini"></div>
            </div>
        </section>

        <section id="modal-view" class="hidden">
            <div>
                <ul class="hidden">
                    <li>La password deve essere lunga almeno 6 caratteri di cui almeno 1 carattere maiuscolo e 1 numero</li>
                </ul>
                <form name="email" class="hidden">
                    <p>
                        <label>Email attuale: <input type="text" name="old_email" value=<?php if (isset($_SESSION["id"]) && !isset($errore)) {echo $email;} ?>></label>
                    </p>
                    <p>
                        <label>Inserisci la nuova email: <input type="text" name="new_email"></label>
                    </p>
                    <p>
                        <label>&nbsp;<input type='submit' value='Cambia Email'></label>
                    </p>
                </form>
                <form name="password" class="hidden">
                    <p>
                        <label>Inserisci la vecchia password: <input type="password" name="old_password"></label>
                    </p>
                    <p>
                        <label>Inserisci la nuova password: <input type="password" name="new_password"></label>
                    </p>
                    <p>
                        <label>&nbsp;<input type='submit' value='Cambia Password'></label>
                    </p>
                </form>
                <button>Torna indietro</button>
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
    </div>

</body>

</html>
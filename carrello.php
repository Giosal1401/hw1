<?php
require_once "dbconfig.php";
session_start();

if (isset($_SESSION["id"])) {
    $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]) or die("Errore: " . mysqli_connect_error());

    $query = "SELECT nprodottipreferiti FROM users WHERE id ='" . $_SESSION["id"] . "'";
    $res = mysqli_query($conn, $query);

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);

        $npreferiti = $row["nprodottipreferiti"];
    }

    mysqli_free_result($res);
    mysqli_close($conn);
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Lifestyle - Carrello</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="stylesheet" href="styles/carrello.css" />
    <script src="scripts/script_menu.js" defer="true"></script>
    <script src="scripts/carrello.js" defer="true"></script>
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
        <div>
        <div <?php if (!isset($_SESSION["id"])) {echo "class=hidden";} ?>>
                <h1>I tuoi prodotti aggiunti al carrello</h1>
                <div class="grid" id="carrello"></div>
            </div>

            <div <?php if (!isset($_SESSION["id"]) || $npreferiti == 0) {echo "class=hidden";} ?>>
                <h2>Non dimenticare di acquistare i tuoi prodotti preferiti</h2>
                <div class="grid" id="preferiti"></div>
            </div>
        </div>
            

            <div <?php if (isset($_SESSION["id"])) {echo "class=hidden";} ?>>
                <h1>Devi prima accedere al tuo account</h1>
                <p>Non sei iscritto?<a href="registrazione.phpreturnTo=carrello.php">Registrati qui</a></p>
                <p>Sei già iscritto?<a href="login.php?returnTo=carrello.php">Accedi</a></p>
            </div>

        </section>

        <section id="modal-view" class="hidden">
            <div>
                <form>
                    <input name="id_prodotto" type="hidden" />
                    <p>
                        <label>Quantità: <input type="text" name="quantità" value="1"></label>
                    </p>
                    <p>
                        <label>&nbsp;<input type='submit' value='Acquista'></label>
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
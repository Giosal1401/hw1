<?php
    
require_once "dbconfig.php";
session_start();

if(isset($_SESSION["id"])){
    $conn = mysqli_connect($dbconfig["hostname"],$dbconfig["username"],$dbconfig["password"],$dbconfig["database"]);
    $user = $_SESSION["id"];

    $query = "SELECT users.nprodotticarrello FROM users WHERE id ='".$user."'";
    $res = mysqli_query($conn,$query);

    if(mysqli_num_rows($res) > 0){
        $nprodotti = mysqli_fetch_assoc($res)["nprodotticarrello"];
    }

    mysqli_free_result($res);
    mysqli_close($conn);
}

?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Lifetyle</title>
    <link rel="icon" type="image/png" href="images/icon.png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Oswald:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/home.css" />
    <script src="scripts/home.js" defer="true"></script>
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
                    <a href="account.php">Account</a>
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
                <div>
                    <div id="menu">
                        <a>Menu</a>
                    </div>

                    <div id="menuAperto" class="hidden">
                        <a href="pagina1/pagina1.html">Music</a>
                        <div></div>
                        <a href="pagina2/pagina2.html">Sport</a>
                        <div></div>
                        <a href="account.php">Account</a>
                        <div></div>
                        <a href="carrello.php">Carrello</a>
                    </div>
                </div>

                <div id="userDetails">
                    <div class="showDetails hidden" id="accountDetails">
                        <div <?php if(!isset($_SESSION["username"])) echo "class=hidden"; ?>>
                            <p>
                                <?php echo "Bentornato ".$_SESSION["username"]."!"?>
                            </p>
                            <!-- <span>
                                <a href="account.php">Account</a>
                                <a href="logout.php">Logout</a>
                            </span> -->
                        </div>

                        <div <?php if(isset($_SESSION["username"])) echo "class=hidden"; ?>>
                            <p>
                                Benvenuto!
                            </p>
                            <!-- <span>
                                <a href="account.php">Account</a>
                                <a href="login.php?returnTo=home.php">Accedi</a>
                            </span> -->
                        </div>
                    </div>
                    <div class="showDetails hidden" id="cartDetails">
                        <div <?php if(!isset($_SESSION["id"])) echo "class=hidden"; ?>>
                            <p>
                                <?php if(isset($nprodotti))echo "Totale prodotti: ".$nprodotti; else echo "Errore connessione al database!";?>
                            </p>
                            <!-- <a href="carrello.php">Vai al carrello</a> -->
                        </div>

                        <div <?php if(isset($_SESSION["id"])) echo "class=hidden"; ?>>
                            <p>
                                Prima devi registrarti!
                            </p>
                            <!-- <span>
                                <a href="registrazione.php">Registrati</a>
                                <a href="login.php?returnTo=home.php">Accedi</a>
                            </span>    -->
                        </div>
                    </div>  
                </div>
            </div>
        </nav>

        <h1>
            <strong>Fuel Your Ambition</strong>
            <a>Scopri le novità</a>
        </h1>
    </header>

    <section>
        <h1>i nostri prodotti</h1>

        <div class="details">
            <div>
                <h1>
                    nutrizione
                </h1>
                <img src="images/nutrizione.jpg" />
                <p>
                    Scopri gli elementi adatti alle tue esigenze.
                </p>
            </div>

            <div>
                <h1>
                    abbigliamento
                </h1>
                <img src="images/maglietta.jpg" />
                <p>
                    Qualunque sia il tuo stile di allenamento, abbiamo quello di cui hai bisogno.
                </p>
            </div>

            <div>
                <h1>
                    attrezzatura
                </h1>
                <img src="images/attrezzatura.jpg" />
                <p>
                    Scopri la nostra collezione di pesi, attrezzi e accessori per definire la tua muscolatura.
                </p>
            </div>
        </div>

        <section class="paragrafo">
            <div class="preferiti">
                <h1>I tuoi preferiti</h1>
            </div>
            <div class="grid" id="risultatiPreferiti"></div>
        </section>

        <section class="paragrafo">
            <div class="elementi">
                <h1>Tutti gli elementi</h1>
                <form>
                    Cerca<input type="text">
                </form>
            </div>
            <div class="grid" id="grid_elements"></div>
        </section>

    </section>

    <section id="modal-view" class="hidden">
        <div>
            <form class="hidden">
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
            <h3>
                Fitness lifestyle
            </h3>
        </div>
        <p>
            <strong>Giovanni Caschetto - Matricola O46002058</strong>
            <em>email: giovannicasch@gmail.com</em>
        </p>
    </footer>
</body>

</html>
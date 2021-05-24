<?php

    require_once "dbconfig.php";

    session_start();

    if(isset($_SESSION["id"])){
        $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]) or die("Errore: " . mysqli_connect_error());
        $user = $_SESSION["id"];
        
        $errori = array();

        if(isset($_POST["old_email"]) && isset($_POST["new_email"])){
            if(empty($_POST["old_email"]) || empty($_POST["new_email"])){
                mysqli_close($conn);
                echo json_encode(array("Compilare tutti i campi!"));
                exit;   
            }    
            $old_email = mysqli_real_escape_string($conn, strtolower($_POST["old_email"]));
            $new_email = mysqli_real_escape_string($conn, strtolower($_POST["new_email"]));

            if (!filter_var($old_email, FILTER_VALIDATE_EMAIL)) {
                $errori[] = "Email non valida";
            }
            if(!filter_var($new_email, FILTER_VALIDATE_EMAIL)){
                $errori[] = "Nuova email non valida";
            }else{
                $query = "SELECT email FROM users WHERE email='".$new_email."'";
        
                $res = mysqli_query($conn,$query);
                if(mysqli_num_rows($res) > 0){
                    while($row = mysqli_fetch_assoc($res)){
                        if ($row["email"] === $_POST["new_email"]) {
                            $errori[] = "Email già associata ad un account!";
                        }
                    }
                }
                mysqli_free_result($res);
            }

            $query = "SELECT email FROM users WHERE id='".$user."'";
            $res = mysqli_query($conn,$query);
            
            if(mysqli_num_rows($res) > 0){
                $entry = mysqli_fetch_assoc($res);
                if($_POST["old_email"] != $entry["email"]){
                    $errori[] = "Errore, non cambiare il campo email attuale!";
                }
            }
            mysqli_free_result($res);

            if(count($errori) == 0){
                $query = "UPDATE users set email='".$new_email."' WHERE id='".$user."' and email='".$old_email."'";
                if(mysqli_query($conn,$query)){
                    $response = array("Email aggiornata!");
                }else{
                    $response = array("Errore connessione con il database!");
                }
            }

            mysqli_close($conn);
            if(isset($response)) echo json_encode($response); else echo json_encode($errori);
            exit;

        }else if(isset($_POST["old_password"]) && isset($_POST["new_password"])){
            if(empty($_POST["old_password"]) || empty($_POST["new_password"])){
                mysqli_close($conn);
                echo json_encode(array("Compilare tutti i campi!"));
                exit;   
            }
            $old_password = mysqli_real_escape_string($conn,$_POST["old_password"]);
            $new_password = mysqli_real_escape_string($conn,$_POST["new_password"]);
            
            if(strlen($new_password) < 6 || strcmp(strtolower($new_password),$new_password) == 0 || preg_match('/[0-9]/',$new_password) == 0){
                $errori[] = "Nuova password non valida";
            }

            $query = "SELECT password FROM users WHERE id='".$user."'";
            $res = mysqli_query($conn,$query);
            if(mysqli_num_rows($res) > 0){
                $entry = mysqli_fetch_assoc($res);
                if(password_verify($_POST["old_password"], $entry["password"]) == false){
                    $errori[] = "Errore, password non corretta!";
                }
            }
            mysqli_free_result($res);

            if(count($errori) == 0){
                $password = password_hash($new_password, PASSWORD_BCRYPT);
                $query = "UPDATE users set password='".$password."' WHERE id='".$user."'";
                if(mysqli_query($conn,$query)){
                    $response = array("Password aggiornata!");
                }else{
                    $response = array("Errore connessione con il database!");
                }
            }
            
            mysqli_close($conn);
            if(isset($response)) echo json_encode($response); else echo json_encode($errori);
            exit;
        }
    }else{
        $errori = array("Operazione non autorizzata");
        echo json_encode($errori);
        exit;
    }   
    

?>

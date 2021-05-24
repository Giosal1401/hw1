<?php
    require_once "dbconfig.php";
    session_start();
    
    if(isset($_SESSION["id"])){
        if(isset($_POST["id_prodotto"])){
            $conn = mysqli_connect($dbconfig["hostname"],$dbconfig["username"],$dbconfig["password"],$dbconfig["database"]);
            
            $user = $_SESSION["id"];
            $prodotto = mysqli_real_escape_string($conn,$_POST["id_prodotto"]);


            if(isset($_POST["operazione"])){
                $operazione = mysqli_real_escape_string($conn,$_POST["operazione"]);
                if(strcmp("cancella",strtolower($operazione)) == 0){
                    $query = "DELETE FROM carrello where user='".$user."' and prodotto='".$prodotto."'";
                    mysqli_query($conn,$query);
                    mysqli_close($conn);
                    exit;
                }

                $quantità = mysqli_real_escape_string($conn,$_POST["quantità"]);
               
                if($quantità == 1 && strcmp("rimuovi",strtolower($operazione)) == 0){
                    $query = "DELETE FROM carrello where user='".$user."' and prodotto='".$prodotto."'";
                }else{
                    if(strcmp("aggiungi",strtolower($operazione)) == 0){
                        $quantità++;
                    }else{
                        $quantità--;
                    }
                    $query = "UPDATE carrello  SET quantità = '".$quantità."' WHERE user='".$user."' and prodotto='".$prodotto."'";
                }
                
                mysqli_query($conn,$query);
                mysqli_close($conn);
                exit;
            }else{
                $quantità = mysqli_real_escape_string($conn,$_POST["quantità"]);
                if(strcmp($quantità,"") == 0){
                    $errore = "Inserire la quantità";
                    mysqli_close($conn);
                    echo json_encode($errore);
                    exit;
                }else if(intval($quantità) <= 0){
                    $errore = "Inserire una quantità positiva";
                    mysqli_close($conn);
                    echo json_encode($errore);
                    exit;
                }
                
                //controllo che il prodotto non sia già prensente nel carrello
                $query = "SELECT * FROM carrello WHERE user='".$user."' and prodotto='".$prodotto."'";
                $res = mysqli_query($conn,$query);
                if(mysqli_num_rows($res) > 0){
                    $quantità = $quantità + mysqli_fetch_assoc($res)["quantità"];
    
                    $query = "UPDATE carrello  SET quantità = '".$quantità."' WHERE user='".$user."' and prodotto='".$prodotto."'";
                    
                    if(mysqli_query($conn,$query)){
                        $success = "Elemento inserito nel carrello!";
                    }else{
                        $errore = "Errore connessione con il database";
                    }
                   
                    mysqli_free_result($res);
                    mysqli_close($conn);
                }else{
                    $query = "INSERT INTO carrello (user,prodotto,quantità) VALUES ('".$user."','".$prodotto."','".$quantità."')";
                
                    if(mysqli_query($conn,$query)){
                        $success = "Elemento inserito nel carrello!";
                    }else{
                        $errore = "Errore connessione con il database";
                    }
                    
                    mysqli_close($conn);
                }
            }       
        }else{
            $errore = "Errore, manca l'id del prodotto che si vuole acquistare";
        }
        
    }else{
        $errore = "Devi effettuare il login prima di acquistare";
    }

    if(isset($errore)){echo json_encode($errore);}else echo json_encode($success);
?>
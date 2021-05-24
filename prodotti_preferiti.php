<?php
    require_once "dbconfig.php";
    session_start();
    

    $preferiti = array();
    if(isset($_SESSION["id"])){
        $conn = mysqli_connect($dbconfig["hostname"],$dbconfig["username"],$dbconfig["password"],$dbconfig["database"]); 
        $user = $_SESSION["id"];
    
        if(isset($_POST["id_prodotto"]) && isset($_POST["operazione"])){
            $prodotto = $_POST["id_prodotto"];

            if(strcmp($_POST["operazione"],"aggiungi") == 0){
                //Controllo che l'elemento non si già inserito nel database
                $query = "SELECT * FROM preferiti WHERE user='".$user."' and prodotto='".$prodotto."'";
                $res = mysqli_query($conn,$query);
    
                if(mysqli_num_rows($res) > 0){
                    mysqli_close($conn);
                    exit;
                }else{
                    $query = "INSERT INTO preferiti (user,prodotto) VALUES ('".$user."','".$prodotto."')";
                    mysqli_query($conn,$query);
                    mysqli_close($conn);
                    exit;
                }
            }else if(strcmp($_POST["operazione"],"elimina") == 0){
                $query = "DELETE FROM preferiti WHERE user='".$user."' and prodotto='".$prodotto."'";
                mysqli_query($conn,$query);
                mysqli_close($conn);
                exit;
            }
        }else{   
            $query = "SELECT prodotti.* FROM preferiti INNER JOIN prodotti ON prodotti.id = preferiti.prodotto WHERE preferiti.user='".$user."'";
            $res = mysqli_query($conn,$query);
        
            if(mysqli_num_rows($res) > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $preferiti[] = $row;
                }
            }

            mysqli_free_result($res);
            mysqli_close($conn);
        }   
    }
    echo json_encode($preferiti);          
?>
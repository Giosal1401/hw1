<?php
    require_once "dbconfig.php";
    session_start();
    
    $carrello = array();
    if(isset($_SESSION["id"])){
        $conn = mysqli_connect($dbconfig["hostname"],$dbconfig["username"],$dbconfig["password"],$dbconfig["database"]);
        $user = $_SESSION["id"];
                
        $query = "SELECT prodotti.*,carrello.quantità FROM carrello INNER JOIN prodotti ON prodotti.id = carrello.prodotto WHERE carrello.user='".$user."'";
        $res = mysqli_query($conn,$query);
        
        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $carrello[] = $row;
            }
        }
    }
    echo json_encode($carrello);
            
?>
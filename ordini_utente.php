<?php
    require_once "dbconfig.php";
    session_start();
    
    $ordini = array();

    if(isset($_SESSION["id"])){
        $conn = mysqli_connect($dbconfig["hostname"],$dbconfig["username"],$dbconfig["password"],$dbconfig["database"]);
        $user = $_SESSION["id"];
                
        $query = "SELECT info_ordini.*,prodotti.nome,prodotti.url_immagine FROM ordini INNER JOIN info_ordini ON info_ordini.ordine = ordini.id INNER JOIN prodotti ON prodotti.id = info_ordini.prodotto WHERE ordini.user='".$user."'ORDER BY ordini.id";
        $res = mysqli_query($conn,$query);
        
        $info_ordini = array();
        if(mysqli_num_rows($res) > 0){
            /*while($row = mysqli_fetch_assoc($res)){
                $ordini[] = $row;
            }*/
            $info_ordini[] = mysqli_fetch_assoc($res);
            $numero_ordine = $info_ordini[0]["ordine"];
            
            while($row = mysqli_fetch_assoc($res)){
                if($row["ordine"] == $numero_ordine){
                    $info_ordini[] = $row;
                }else{
                    $ordini[] = $info_ordini;
                    $info_ordini = array();
                    $info_ordini[] = $row;
                    $numero_ordine = $row["ordine"];
                }
            }
            $ordini[] = $info_ordini;
        }
    }
    echo json_encode($ordini);
            
?>
<?php 

require_once "dbconfig.php";

session_start();

if(isset($_SESSION["id"])){
    $conn = mysqli_connect($dbconfig["hostname"],$dbconfig["username"],$dbconfig["password"],$dbconfig["database"]);
    $user = $_SESSION["id"];

    /*$curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://localhost/hw1/prodotti_carrello.php");

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    echo $result;*/

    $query = "SELECT prodotti.id,carrello.quantità FROM carrello INNER JOIN prodotti ON prodotti.id = carrello.prodotto WHERE carrello.user='".$user."'";
    $res = mysqli_query($conn,$query);
        
    if(mysqli_num_rows($res) > 0){
        $query = "INSERT INTO ordini (user) VALUES ('".$user."')";
        mysqli_query($conn,$query);
        $id_ordine = mysqli_insert_id($conn);
        
        $query = "INSERT INTO info_ordini (ordine,prodotto,quantità) VALUES ";
        while($row = mysqli_fetch_assoc($res)){
            $query = $query."('".$id_ordine."','".$row["id"]."','".$row["quantità"]."'),";
        }
        $query = substr($query,0,-1);
    
        if(mysqli_query($conn,$query)){
            $query = "DELETE FROM carrello where user='".$user."'";
            if(mysqli_query($conn,$query)){
                $response = "Acquisto andato a buon fine!";
            }
        }else{
            $response = "Errore,connessione con il database";
            $query = "DELETE FROM ordini where id='".$id_ordine."'";
            mysqli_query($conn,$query);
        }
    }else{
        $response = "Carrello vuoto";
    }

    mysqli_free_result($res);
    mysqli_close($conn);
}else{
    $response = "Operazione non permessa,bisogna autenticarsi!";
}

echo json_encode($response);
?>
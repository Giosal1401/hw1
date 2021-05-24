<?php
    require_once "dbconfig.php";

    $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]);
    $query = "SELECT * FROM prodotti ORDER BY id";

    $res = mysqli_query($conn,$query);
        
    $prodotti = array();

    while($row = mysqli_fetch_assoc($res)){
        $prodotti[]=$row;
    }

    mysqli_free_result($res);
    mysqli_close($conn);

    echo json_encode($prodotti);

?>
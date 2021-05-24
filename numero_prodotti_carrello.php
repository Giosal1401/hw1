<?php
    require_once "dbconfig.php";

    session_start();

    if(isset($_SESSION["id"])){ 
        $conn = mysqli_connect($dbconfig["hostname"], $dbconfig["username"], $dbconfig["password"], $dbconfig["database"]);
        $user = $_SESSION["id"];
        
        $query = "SELECT nprodotticarrello FROM users WHERE id='".$user."'";
        $res = mysqli_query($conn,$query);
        if(mysqli_num_rows($res) > 0){
            $nprodotti = mysqli_fetch_assoc($res)["nprodotticarrello"];
        }
        mysqli_free_result($res);
        mysqli_close($conn);
        echo json_encode($nprodotti);
    }

    exit;
    

?>
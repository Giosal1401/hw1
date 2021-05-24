<?php

    if(isset($_GET["sport"])){
        $dati = array("coordinates" => "-73.5826985,45.5119864");
        $dati = http_build_query($dati);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://sports.api.decathlon.com/sports/search/" .$_GET["sport"]."?".$dati);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        echo $result;
    }else if(isset($_GET["groups"])){
        if($_GET["groups"] === "all"){
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://sports.api.decathlon.com/groups");

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            echo $result;
        }else{
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://sports.api.decathlon.com/groups/".$_GET["groups"]);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            echo $result;
        }
        
    }else if(isset($_GET["sports_group"])){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://sports.api.decathlon.com/sports/".$_GET["sports_group"]);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        echo $result;
    }
    
?>
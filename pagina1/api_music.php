<?php
    $consumerKey = "dPUoquDAKtiyQQQjQrVb";
    $consumerSecret = "oAWGNKwZunquWyAynXuPhKBwuYnbGhkb";

    if(isset($_GET["q"])){
        $dati = array("q" => $_GET["q"], "format" => "single");
        $dati = http_build_query($dati);
        $curl = curl_init();
    
        curl_setopt($curl , CURLOPT_URL, "https://api.discogs.com/database/search?".$dati);  
    
        $headers = array("Authorization: Discogs key=".$consumerKey.", secret=".$consumerSecret);
        curl_setopt($curl , CURLOPT_HTTPHEADER, $headers);
    
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36");
    
       curl_setopt($curl , CURLOPT_RETURNTRANSFER, 1);
       $result = curl_exec($curl);
       curl_close($curl);

       echo $result;
    }else{
        echo "null";
    }

?>
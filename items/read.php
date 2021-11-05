<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    

    //Palautuneet vastakset laitetaan tähän
    function msg($success,$status,$message,$extra = []){
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ],$extra);
    }

    //Incluudataan tietokanta ja Items
    include_once '../config/database.php';
    include_once '../class/items.php';

    //Tehdään tietokanta yhteys
    $database = new Database();
    $db = $database->getConnection();

    //Tämän arrayn sisälle tulee function msg, tämä echotetaan sivun lopussa.
    $returnData = [];

    //Tarkistetaan oliko Requesti GET
    if($_SERVER["REQUEST_METHOD"] != "GET") {
        $returnData = msg(0,404,'Sivua ei löytynyt.');
        echo json_encode($returnData);

    } else { //Requesti oli GET

        //uusi Objecti luokkaan Items
        $items = new Items($db);
        $stmt = $items->getItems();
        $itemCount = $stmt->rowCount();

    //Jos row oli enemmän kuin 0, niin tulostetaan tidot arrayhin
        if($itemCount > 0){
            
            $items = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $i = array(
                    "id" => $id,
                    "title" => $title,
                    "description" => $description,
                    "price" => $price,
                    "image" => $image,
                    "gtin" => $gtin
                );

                array_push($items, $i);
            }
            echo json_encode($items);

        } else{ //Ei löytynyt
            $returnData = msg(1,404,'Tietokanta on tyhjä');
            echo json_encode($returnData);
        }
    }
?>
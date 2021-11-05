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
        $item = new Items($db);
        $item->id = isset($_GET['id']) ? $_GET['id'] : die(); //Vaaditaan id, muuten lopetetaan
        $item->getSingleItem();

        //Jos id ei ole tyhjä, niin tulostetaan data arrayhin
        if($item->id != null){
            // create array
            $emp_arr = array(
                "id" =>  $item->id,
                "title" => $item->title,
                "description" => $item->description,
                "price" => $item->price,
                "image" => $item->image,
                "gtin" => $item->gtin
            );
            
            http_response_code(200);
            echo json_encode($emp_arr); //Palautetaan array data
        } else {
            
            /* http_response_code(404);
            echo json_encode("Item not found."); */
            $returnData = msg(1,404,'Ei löytynyt tuotetta.');
            echo json_encode($returnData);
        }
    }
?>
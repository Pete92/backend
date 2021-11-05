<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
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

    //Hyväksytään inputit $data muuttujaan
    $data = json_decode(file_get_contents("php://input"));

    //Tämän arrayn sisälle tulee function msg, tämä echotetaan sivun lopussa.
    $returnData = [];

    //Tarkistetaan oliko Requesti POST
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $returnData = msg(0,404,'Sivua ei löytynyt.');

    //jos oli, niin katsotaan onko inputeissa tavaraa
    } elseif(!isset($data->title) 
            || !isset($data->description)
            || !isset($data->price)
            || !isset($data->image)
            || !isset($data->gtin)
            || empty(trim($data->title))
            || empty(trim($data->description))
            || empty(trim($data->price))
            || empty(trim($data->image))
            || empty(trim($data->gtin)))
        {
            $fields = ['fields' => ['title', 'description', 'price', 'image', 'gtin' ]];
            $returnData = msg(0,422,'Tarkista täytitkö kakki kentät.',$fields);
            
    } else{
        
        //uusi Objecti luokkaan Items
        $item = new Items($db);
        $item->title = $data->title;
        $item->description = $data->description;
        $item->price = $data->price;
        $item->image = $data->image;
        $item->gtin = $data->gtin;
            
        if($item->createItem()){
            $returnData = msg(1,201,'Tuote lisätty!');

        } else{
            $returnData = msg(0,422,'Jokin meni pieleen');
        }
    }
    
echo json_encode($returnData);
?>
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

//Incluudataan tietokanta ja Users
include_once "../config/database.php";
include_once "../class/user.php";

//Tehdään tietokanta yhteys
$database = new Database ();
$db = $database->getConnection();

//Hyväksytään inputit $data muuttujaan
$data = json_decode(file_get_contents("php://input"));

//Tämän arrayn sisälle tulee function msg, tämä echotetaan sivun lopussa.
$returnData = [];

//Tarkistetaan oliko Requesti POST
if($_SERVER["REQUEST_METHOD"] != "POST"){
    $returnData = msg(0, 404, 'Sivua ei löytynyt');

    //jos oli, niin katsotaan onko inputeissa tavaraa
} else if(!isset($data->name)
        || !isset($data->email)  
        || !isset($data->password)
        || empty(trim($data->name))
        || empty(trim($data->email))
        || empty(trim($data->password)))
    {

    $fields = ['fields' => ['name', 'email', 'password']];
    $returnData = msg(0,422,'Tarkista täytitkö kakki kentät.',$fields);

    } else { //Jos oli inputeissa tavaraa, otetaan tyhjät välit pois
       

        $name = trim($data->name);
        $email = trim($data->email);
        $password = trim($data->password);

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $returnData = msg(0,422,'Tarkista Sähköpostin muoto.');  

        } else if(strlen($password) < 8){
            $returnData = msg(0,422,'Salasanasi täytyy olla vähintään 8 merkkiä pitkä.');

        } else if(strlen($name) < 3){
            $returnData = msg(0,422,'Nimesi täytyy olla vähintään 3 merkkiä pitkä.');

        } else {

            //uusi Objecti luokkaan User
            $user = new User($db);
            $user->name = $data->name;
            $user->email = $data->email;
            $user->password = $data->password;

            if($user->userExists()){
                if($user->setUser()){
                    $returnData = msg(1,201,'Rekisteröityminen onnistui!');
                } else {
                    $returnData = msg(0,422,'Jokin meni pieleen.');
                }
            } else {
                $returnData = msg(0,422,'Käyttäjätunnus jo käytössä, valitse toinen.');
            }
        } 
    }

echo json_encode($returnData);
?>
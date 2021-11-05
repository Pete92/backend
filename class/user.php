<?php


class User {

    //tietokanta yhteys ja taulu users
    private $conn;
    private $db_table = "users";
    

    //Määritellään muuttujat
    public $name;
    public $email;
    public $password;



    // construct functioni. Kun tehdään uusi new User($db), käy läpi tietokanta yhteyden. 
    public function __construct($db){
        $this->conn = $db;
    }

    //Rekisteröitymisen toiminta
    public function setUser()
    {

        //Onko sama email jo tietokannassa
        $check_email = "SELECT email FROM ". $this->db_table ." WHERE email = :email";
        //valmistellaan query komentoa     
        $check_email_stmt = $this->conn->prepare($check_email);
        $check_email_stmt->bindValue(':email', $this->email,PDO::PARAM_STR);
        //suoritetaan query komento
        $check_email_stmt->execute();

        //Jos Löyty, palautetaan false
        if($check_email_stmt->rowCount()){
            return false;
        }

        //Uuden käyttäjän lisääminen
        $sqlQuery = "INSERT INTO
                    ". $this->db_table ."
        SET
            name = :name,
            email = :email,
            password = :password";

            $stmt = $this->conn->prepare($sqlQuery);

            //sanitaze
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->password=htmlspecialchars(strip_tags($this->password));

            //Määritellään SQL koodiin muuttujat
            $stmt->bindValue(":name", $this->name);
            $stmt->bindValue(":email", $this->email);
            $stmt->bindValue(":password", password_hash($this->password, PASSWORD_DEFAULT));

            //Jos query komento onnistui
            if($stmt->execute()){
                return true;
            }

            return false;
    }



    //Kirjautumisen toiminta
    public function loginUser()
    {
        //Valitaan käyttäjän sähköposti
        $fetch_user_by_email = "SELECT * FROM ". $this->db_table ." WHERE email = :email";

        $query_stmt = $this->conn->prepare($fetch_user_by_email);
        $query_stmt->bindValue(":email", $this->email,PDO::PARAM_STR);
        $query_stmt->execute();

        //Jos löyty
        if($query_stmt->rowCount()){
            $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
            $check_password = password_verify($this->password, $row['password']);
            //Jos salasana oli oikein
            if($check_password){
                return true;
            }
        }
        return false;
    }
}
?>
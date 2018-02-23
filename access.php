<?php

// Declarando uma classe para acessar  esse arquivo php
class access {
    
    // Variaveis Globais de conexão
    var $host = null;
    var $user = null;
    var $pass = null;
    var $name = null;
    var $conn = null;
    var $result = null;
    
    // Construection Class
    function __construct($dbhost, $dbuser, $dbpass, $dbname) {
        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->pass = $dbpass;
        $this->name = $dbname;   
    }
    
    // Connection Function
    public function connect() {
        //Estabelendo conexao
        $this->conn = new mysqli($this->host,$this->user,$this->pass,$this->name);
        if (mysqli_connect_errno()) {
            echo "Could not connect to Database";
        }
        // Suportando todas as linguas
        $this->conn->set_charset("utf8");
    }
    
    // Funcao de desconectar
    public function disconnect() {
        if ($this->conn != null) {
            $this->conn->close();
        }
    }
    
    
    // Funcao de desconectar
    public function checkCredentials($checkin_valido) {
        
        echo $checkin_valido;
        echo "<div style='color:#fff;font-size:20px; text-align:center'>Nice</div>";
        
    }
    
    
    
    //Inserindo User Details
    public function PWAregisterUserFacebook($username, $password, $salt, $email, $avatar, $first_name, $last_name, $age, $gender, $locale, $link){
        
        //SQL Comando
        $sql = "INSERT INTO users SET username=?, password=?, salt=?, email=?, ava=?, first_name=?, last_name=?, age=?, gender=?, locale=?, link=?";
        
        // Abrindo conexao e prepando SQL comando e guardando o resultado dentro de STATEMENT variavel
        $statement = $this->conn->prepare($sql);
   
        //Se falhar
        if (!$statement){
            throw new Exception($statement->error);
        }
        
        //Bind 5 parametros do tipo STRING to be placed in $sql command
        $statement->bind_param("sssssssssss", $username, $password, $salt, $email, $avatar, $first_name, $last_name, $age, $gender, $locale, $link); 
        
        $returnValue = $statement->execute();
        return $returnValue;
        
    }
    
    //Inserindo User Details
    public function registerUser($username, $password, $salt, $email, $first_name, $last_name, $avatar){
        
        //SQL comando
        $sql = "INSERT INTO users SET username=?, password=?, salt=?, email=?, first_name=?, last_name=?, ava=?";
        // Abrindo conexao e prepando SQL comando e guardando o resultado dentro de STATEMENT variavel
        $statement = $this->conn->prepare($sql);
        
        //Se falhar
        if (!$statement){
            throw new Exception($statement->error);
        }
        
        //Bind 5 parametros do tipo STRING to be placed in $sql command
        $statement->bind_param("sssssss", $username, $password, $salt, $email, $first_name, $last_name, $avatar); 
        
        $returnValue = $statement->execute();
        return $returnValue;
        
    }
    
    //Inserindo User Details
    public function updateUserRegisterFacebook($username, $email, $avatar, $first_name, $last_name, $age, $gender, $locale, $link, $id){
        
        // sql statement
        $sql = "UPDATE users SET username='$username', email='$email', ava='$avatar', first_name='$first_name', last_name='$last_name', age='$age',gender='$gender',locale='$locale',link='$link'    WHERE email='$email'";
        
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

                $_SESSION['id'] = $row['id'];
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name'] = $row['last_name'];
		$_SESSION['email'] = $row['email'];
                $_SESSION['avatar'] = $row['ava'];
                
                $_SESSION['age'] = $row['age'];
                $_SESSION['gender'] = $row['gender'];
                $_SESSION['locale'] = $row['locale'];
                $_SESSION['link'] = $row['link'];
                
                header("Location: ");
                
            }
        }
        
    }
    
    
    // Select all posts + user information made by user with relevant $id
    public function selectPosts($id) {

        // declare array to store selected information
        $returnArray = array();

        // sql JOIN
        $sql = "SELECT checkins.id,
		checkins.uuid,
        checkins.text,
        checkins.date,
        users.id,
        users.username,
        users.first_name,
        users.email,
        users.ava
        FROM fiveam.checkins JOIN fiveam.users ON
        checkins.id = $id AND users.id = $id ORDER by date DESC";
        
        echo $sql;
        
        $result = $this->conn->query($sql);
        while($row = $result->fetch_object()){array_push($returnArray, $row);}
        return $returnArray;
    }
    
    
    // Select all posts + user information made by user with relevant $id
    public function selectPlace($id) {

        // declare array to store selected information
        $returnArray = array();

        // sql JOIN
        $sql = "SELECT 
                locais.id, 
                locais.nome, 
                locais.descricao, 
                locais.imagem,
                locais.telefone, 
                locais.tipo, 
                locais.horario,
                BAIRROS.bairro,
                CIDADES.cidade 
                FROM mostrai.locais 
                JOIN mostrai.CIDADES 
                ON locais.id_cidade = CIDADES.id_cidade 
                JOIN mostrai.BAIRROS 
                ON locais.id_bairro = BAIRROS.id_bairro 
                WHERE id='".$id."'";
        $result = $this->conn->query($sql);
        while($row = $result->fetch_object()){array_push($returnArray, $row);}
        return $returnArray;

    }
    
   
    public function verLike($user, $uuid)
    {
        $returnValue = array();
        $sql = 'SELECT * FROM likes WHERE 1 and user = "' . $user . '" and `uuid` = "' . $uuid . '"';
        $result = $this->conn->query($sql);
	$returnValue = array();
        while($row = $result->fetch_object()){array_push($returnValue, $row);}
        return $returnValue;
    } 
    

    
    
}



?>
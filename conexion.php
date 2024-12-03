<?php
class Conecta {

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "semestral";
    private $cnn;

    public function conectarBD() {
        $this->cnn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->cnn->connect_error) {
            die("Connection failed: " . $this->cnn->connect_error);
        }
        return $this->cnn; // Retorna la conexión
    }

    public function cerrar() {
        $this->cnn->close();
    }
}
?>

    

<?php

class Autodiagnostico
{
    // Atributo privado para la conexión a la base de datos
    private $pdo;

    // Constructor que recibe la conexión a la base de datos como parámetro
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Puedes agregar más atributos y métodos aquí según sea necesario

    public function getModulos(){
    
        // Ahora puedes usar $pdo para consultas a la base de datos
        $stmt = $this->pdo->query("SELECT * FROM modulos");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $modulos = array(); // Array donde se guardarán los resultados

        if (count($results) > 0) {
            // Almacena los resultados en el array
            foreach($results as $row) {
                $modulos[] = $row['Name_m'];
            }
        }

        // Convierte el array de PHP en JSON para que pueda ser utilizado en JavaScript
        $modulosJson = json_encode($modulos);
        //die(var_dump($modulosJson));
        return $modulosJson;
    }

    public function getModulos_byId($id){
        // Ahora puedes usar $pdo para consultas a la base de datos
        $stmt = $this->pdo->query("SELECT * FROM modulos where id = ".$id);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $modulos = array(); // Array donde se guardarán los resultados

        if (count($results) > 0) {
            // Almacena los resultados en el array
            foreach($results as $row) {
                $modulos[] = $row['Name_m'];
            }
            
        }

        // Convierte el array de PHP en JSON para que pueda ser utilizado en JavaScript
        $modulosJson = json_encode($modulos);

        return $modulo;
    }

    public function get_lastModulo(){
        // Ahora puedes usar $pdo para consultas a la base de datos
        $stmt = $this->pdo->query("SELECT * FROM modulos where id < ".$id." order by id desc limit 1");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $modulos = array(); // Array donde se guardarán los resultados

        if (count($results) > 0) {
            // Almacena los resultados en el array
            foreach($results as $row) {
                $modulos[] = $row['Name_m'];
            }
            
        }

        // Convierte el array de PHP en JSON para que pueda ser utilizado en JavaScript
        $modulosJson = json_encode($modulos);

        return $modulo;

        return $lastmodulo;
    }

    public function next_modulo(){
        return  $nextmodulo;
    }

    public function getQuestions(){
        return $questions;
    }

    public function updateAnswerQuestion(){
        return $result;
    }
}

?>

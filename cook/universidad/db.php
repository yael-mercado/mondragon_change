<?php
function getConnection() {
    $host = 'localhost'; // o la dirección de tu servidor
    $db = 'moodle_mondragon_prototipo';
    $user = 'root';
    $pass = '';

    try {
        // Intentar establecer una conexión a la base de datos
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Si llegamos aquí, la conexión fue exitosa
        return $pdo;

    } catch (PDOException $e) {
        // Si ocurre un error, mostrar el mensaje de error
        die("Error de conexión: " . $e->getMessage());
    }
}
?>

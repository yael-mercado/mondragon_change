<?php
function getMoodlePdoConnection() {
    // Include Moodle's config.php to get database credentials
    require_once(dirname(__FILE__) . '/../config.php');
    global $DB, $CFG, $USER, $OUTPUT;
    // Prepare the DSN (Data Source Name) for PDO
    $dsn = 'mysql:host=' . $CFG->dbhost . ';dbname=' . $CFG->dbname . ';charset=utf8mb4';

    // Credenciales de la base de datos
    $username = $CFG->dbuser;
    $password = $CFG->dbpass;

    try {
        // Create a new PDO instance
        $pdo = new PDO($dsn, $CFG->dbuser, $CFG->dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        // Handle connection error
        echo 'Connection failed: ' . $e->getMessage();
        return null;
    }
}
?>

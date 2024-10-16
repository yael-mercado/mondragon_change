<?php
include 'db.php'; // Asegúrate de que la ruta sea correcta

// Ahora puedes usar $pdo para consultas a la base de datos
$stmt = $pdo->query("SELECT * FROM modulos");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar resultados
foreach ($results as $row) {
    var_dump($row);
}
exit;
?>

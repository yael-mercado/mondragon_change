<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('../course/lib.php');
require_once($CFG->libdir.'/completionlib.php');
include 'dbo.php'; // Asegúrate de que la ruta sea correcta
require_once 'mapa_class.php';
require_once 'autodiagnostico.php';

global $DB, $CFG, $USER, $OUTPUT;
// Obtener la conexión a la base de datos
if (isloggedin() && !isguestuser()) {
    // Usuario está autenticado
    // Puedes agregar aquí el código que debe ejecutarse si el usuario está autenticado
} else {
    // Usuario no está autenticado, redirige a la página de inicio de sesión
    $loginurl = new moodle_url('/login/index.php');
    redirect($loginurl);
}

if ($_GET){
  $cat_id = $_GET['cat_id'];
}else{
  redirect('/');
}

$pdo = getMoodlePdoConnection();

// Instanciar la clase Autodiagnostico
if ($pdo){
  $autodiagnostico = new Autodiagnostico($pdo);
  $mapa = new mapa($pdo);
}else{
  redirect('/');
}

$PAGE->set_url('/myplugin/mypage.php'); // Establecer la URL de la página
$PAGE->set_title("Modulos"); // Establecer el título de la página
$PAGE->set_heading(""); // Establecer el encabezado

// Mostrar el encabezado estándar de Moodle
echo $OUTPUT->header();
echo $mapa->get_links_modulos();

//Mostrar mapa de contenido de moodle_url
echo $mapa->view_render_modulos($cat_id);

// Mostrar el pie de página estándar de Moodle
echo $OUTPUT->footer();
?>

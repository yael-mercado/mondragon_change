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

$pdo = getMoodlePdoConnection();

// Instanciar la clase Autodiagnostico
if ($pdo){
  $autodiagnostico = new Autodiagnostico($pdo);
  //$mapa = new mapa($pdo);
}else{
  redirect('/');
}

if (isset($_GET['mod'])) {
    $mod = required_param('mod', PARAM_INT);
} else {
    $mod = (int) $autodiagnostico->getlastModulo(); // Valor predeterminado si 'mod' no está en la URL.
    //die(var_dump($mod));
    if ($mod == 9){
      //redirect('/cook/reports.php');
    }
    //die(var_dump($mod));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // La solicitud es de tipo POST
    // Puedes acceder a los datos POST con $_POST
    $data = ($_POST);
    //die(var_dump($data['mod_id']));
    $registro_and_update = $autodiagnostico->register_answers($data);
    $mod = (int) $autodiagnostico->getlastModulo();

} else {
    // La solicitud no es de tipo POST
}
//$PAGE->set_url('/myplugin/mypage.php'); // Establecer la URL de la página
$PAGE->set_title("Autodiagnostico"); // Establecer el título de la página
$PAGE->set_heading(""); // Establecer el encabezado

// Mostrar el encabezado estándar de Moodle
echo $OUTPUT->header();
echo $autodiagnostico->get_links();

//Mostrar autotest de contenido de moodle_url
echo $autodiagnostico->render_view_questions_by_sectión($mod);

// Mostrar el pie de página estándar de Moodle
echo $OUTPUT->footer();
 ?>

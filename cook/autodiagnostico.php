<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('../course/lib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once 'mapa_class.php';
$id          = optional_param('id', 0, PARAM_INT);
$name        = optional_param('name', '', PARAM_TEXT);
$edit        = optional_param('edit', -1, PARAM_BOOL);
$hide        = optional_param('hide', 0, PARAM_INT);
$show        = optional_param('show', 0, PARAM_INT);
$idnumber    = optional_param('idnumber', '', PARAM_RAW);
$sectionid   = optional_param('sectionid', 0, PARAM_INT);
$section     = optional_param('section', 0, PARAM_INT);
$move        = optional_param('move', 0, PARAM_INT);
$marker      = optional_param('marker',-1 , PARAM_INT);
$switchrole  = optional_param('switchrole',-1, PARAM_INT); // Deprecated, use course/switchrole.php instead.
$return      = optional_param('return', 0, PARAM_LOCALURL);
global $DB, $CFG, $USER, $OUTPUT;
class Autodiagnostico
{
    // Atributo privado para la conexión a la base de datos
    private $pdo;

    // Constructor que recibe la conexión a la base de datos como parámetro
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_links(){
      $html = '<meta charset="UTF-8" />
      <link rel="icon" type="image/svg+xml" href="/universidad/vite.svg" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Universidad Mondragón</title>


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
      <link rel="stylesheet" crossorigin href="./universidad/assets/autodiagnostico-Di8n6sjF.css">
      <link rel="stylesheet" crossorigin href="./universidad/assets/resultados-DLbqTBXe.css">
      <link rel="stylesheet" crossorigin href="./universidad/assets/global-CPC3-saQr.css">';
      return $html;
    }

    // Puedes agregar más atributos y métodos aquí según sea necesario
    public function getModulos(){

        // Ahora puedes usar $pdo para consultas a la base de datos
        $stmt = $this->pdo->query("SELECT * FROM mdl_modulos");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $modulos = array(); // Array donde se guardarán los resultados

        if (count($results) > 0) {
            // Almacena los resultados en el array
            foreach($results as $row) {
                //die(var_dump($row));
                $modulos[] = ['mod_id' => $row['Id'], 'name' => $row['Name_m']];
            }
        }

        // Convierte el array de PHP en JSON para que pueda ser utilizado en JavaScript
        $modulosJson = $modulos;
        //die(var_dump($modulosJson));
        return $modulosJson;
    }

    public function get_categories_in_auto($category) {
      // Ahora puedes usar $pdo para consultas a la base de datos
      $stmt = $this->pdo->query("SELECT id, relative_activity_moodle FROM mdl_modulos WHERE relative_activity_moodle = {$category}");
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $modulos = array(); // Array donde se guardarán los resultados

      if (count($results) > 0) {
          // Almacena los resultados en el array
          foreach($results as $row) {
              //die(var_dump($row));
              $modulos[] = ['mod_id' => $row['Id'], 'category' => $row['relative_activity_moodle']];
          }
      }

      // Convierte el array de PHP en JSON para que pueda ser utilizado en JavaScript
      $modulosJson = $modulos;
      
      return $modulosJson;
    }

    public function getQuestionsmodulos($id_modulo){
      global $DB;
      // Ahora puedes usar $pdo para consultas a la base de datos
      $query = "SELECT mdl_modulos.id as m_id ,mdl_modulos.Name_m as name_m, mdl_modulos.min_points, mdl_modulos.max_points, mdl_modulos.relative_activity_moodle,
      mdl_questions_autodiagnostico.id as question_id, mdl_questions_autodiagnostico.question_text, mdl_questions_autodiagnostico.type_answer,
      mdl_Answer_autodiagnostico.Id as answer_id, mdl_Answer_autodiagnostico.Answer_text as answer_text, mdl_Answer_autodiagnostico.points FROM mdl_Answer_autodiagnostico
      INNER JOIN mdl_questions_autodiagnostico ON mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id
      INNER JOIN mdl_modulos ON mdl_modulos.id = mdl_questions_autodiagnostico.module_assing
      WHERE mdl_modulos.id = {$id_modulo}
      ORDER BY mdl_Answer_autodiagnostico.Id, mdl_questions_autodiagnostico.Id,  mdl_modulos.id;";

      $stmt = $this->pdo->query($query);
      $results = ((object) $stmt->fetchAll(PDO::FETCH_ASSOC));
      //die(var_dump($results));
      $final_mod = "";
      $final_ques = "";
      $final_ans = "";
      $modulos = [];
      $questios = [];
      $answers = [];

      // Imprimir los resultados
      foreach ($results as $value) {

          $res = ((object) $value);
          //var_dump($res->m_id);

          if ($res->m_id != $final_mod){
            $modulos[] = ['id_mod' => $res->m_id, 'name_mod' => $res->name_m, 'min_points' => $res->min_points, 'max_points' => $res->max_points];
          }
          $final_mod = $res->m_id;

          if ($res->question_id != $final_ques){
            $questios[] = ['mod_id' => $res->m_id, 'question_id' => $res->question_id, 'question_text' => $res->question_text, 'type_answer' => $res->type_answer];
          }
          $final_ques = $res->question_id;

          if ($res->answer_id != $final_ans){
            $answers[] = ['question_id' => $res->question_id, 'answer_id' => $res->answer_id, 'answer_text' => $res->answer_text, 'points' => $res->points];
          }
          $final_ans = $res->answer_id;

      }

      $autodiagnostico = ['modulos' => $modulos, 'questions' => $questios, 'answers' => $answers];

      return $autodiagnostico;
    }

    public function getModulos_byId($id){
        // Ahora puedes usar $pdo para consultas a la base de datos
        $stmt = $this->pdo->query("SELECT * FROM mdl_modulos where id = ".$id);
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

    public function isModCompleted($id_modulo){
      global $DB, $USER;

      $sql = "SELECT mdl_answer_users_questions.*
      FROM mdl_modulos
      inner join mdl_questions_autodiagnostico on mdl_modulos.id = mdl_questions_autodiagnostico.module_assing
      inner join mdl_Answer_autodiagnostico on mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id
      inner join mdl_answer_users_questions on (mdl_questions_autodiagnostico.Id = mdl_answer_users_questions.Question_id and mdl_Answer_autodiagnostico.Id = mdl_answer_users_questions.Answer_id)
      WHERE mdl_modulos.Id = {$id_modulo} and mdl_answer_users_questions.User_id_moodle = {$USER->id}";

      $stmt = $this->pdo->query($sql);
      $res_user_ans = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //die(var_dump($res_user_ans));
      $result = (count($res_user_ans) ? 1 : 0);

      return $result;
    }

    public function get_result_autodiagnostic(){
      global $DB, $USER;
      $sql_query = "SELECT mdl_modulos.Id as m_id, mdl_modulos.Name_m as m_name, mdl_modulos.relative_activity_moodle, count(mdl_Answer_autodiagnostico.Question_id) as questions, sum(mdl_Answer_autodiagnostico.points) as points_per_mod, mdl_modulos.min_points, mdl_modulos.max_points, GROUP_CONCAT(mdl_answer_users_questions.Answer_id) as answers
      FROM mdl_modulos
      inner join mdl_questions_autodiagnostico on mdl_modulos.id = mdl_questions_autodiagnostico.module_assing
      inner join mdl_Answer_autodiagnostico on mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id
      inner join mdl_answer_users_questions on (mdl_questions_autodiagnostico.Id = mdl_answer_users_questions.Question_id and mdl_Answer_autodiagnostico.Id = mdl_answer_users_questions.Answer_id)
      WHERE mdl_answer_users_questions.User_id_moodle = {$USER->id}
      group by mdl_modulos.Id, mdl_modulos.Name_m;";

      $result = $DB->get_records_sql($sql_query);

      return $result;
    }

    public function get_result_autodiagnostic_by_course($course_id){
      global $DB, $USER;
      $sql_query = "SELECT mdl_modulos.Id as m_id, mdl_modulos.Name_m as m_name, mdl_modulos.relative_activity_moodle, count(mdl_Answer_autodiagnostico.Question_id) as questions, sum(mdl_Answer_autodiagnostico.points) as points_per_mod, mdl_modulos.min_points, mdl_modulos.max_points, GROUP_CONCAT(mdl_answer_users_questions.Answer_id) as answers
      FROM mdl_modulos
      inner join mdl_questions_autodiagnostico on mdl_modulos.id = mdl_questions_autodiagnostico.module_assing
      inner join mdl_Answer_autodiagnostico on mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id
      inner join mdl_answer_users_questions on (mdl_questions_autodiagnostico.Id = mdl_answer_users_questions.Question_id and mdl_Answer_autodiagnostico.Id = mdl_answer_users_questions.Answer_id)
      WHERE mdl_answer_users_questions.User_id_moodle = {$USER->id}
      and mdl_modulos.relative_activity_moodle = {$course_id}
      group by mdl_modulos.Id, mdl_modulos.Name_m;";

      $result = $DB->get_records_sql($sql_query);

      return $result;
    }

    public function getlastModulo(){
      global $USER, $DB;

      $sql = "SELECT mods.Id as actual_mod,
      (select mod_next.Id as next_mod from mdl_modulos as mod_next where mod_next.Id > mods.Id limit 1) as next_modulo,
      (select mod_back.Id as back_mod from mdl_modulos as mod_back where mod_back.Id < mods.Id order by mod_back.Id desc limit 1) as back_modulo
      FROM mdl_modulos as mods
      inner join mdl_questions_autodiagnostico on mods.id = mdl_questions_autodiagnostico.module_assing
      inner join mdl_Answer_autodiagnostico on mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id
      inner join mdl_answer_users_questions on (mdl_questions_autodiagnostico.Id = mdl_answer_users_questions.Question_id and mdl_Answer_autodiagnostico.Id = mdl_answer_users_questions.Answer_id)
      WHERE mdl_answer_users_questions.User_id_moodle = {$USER->id}
      ORDER BY mdl_answer_users_questions.Question_id desc limit 1";

      //die(var_dump($sql));
      $result = $DB->get_records_sql($sql);
      $courses_mod = 1;

      foreach ($result as $key => $value) {
        $courses_mod = ($value->next_modulo ? $value->next_modulo : $value->actual_mod);

      }
      //die(var_dump($courses_mod));
      if (!$result){

      }

      return ( (int) $courses_mod);

    }

    public function register_answers($data){

      global $DB, $USER; // Accede al objeto global de la base de datos

      $sql = "SELECT mdl_modulos.id as m_id ,mdl_modulos.Name_m as name_m, mdl_modulos.min_points, mdl_modulos.max_points, mdl_modulos.relative_activity_moodle,
      mdl_questions_autodiagnostico.id as question_id, mdl_questions_autodiagnostico.question_text, mdl_questions_autodiagnostico.type_answer,
      mdl_Answer_autodiagnostico.Id as answer_id, mdl_Answer_autodiagnostico.Answer_text as answer_text, mdl_Answer_autodiagnostico.points
      FROM mdl_modulos
      inner join mdl_questions_autodiagnostico on mdl_modulos.id = mdl_questions_autodiagnostico.module_assing
      inner join mdl_Answer_autodiagnostico on mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id
      inner join mdl_answer_users_questions on (mdl_questions_autodiagnostico.Id = mdl_answer_users_questions.Question_id and mdl_Answer_autodiagnostico.Id = mdl_answer_users_questions.Answer_id)
      WHERE mdl_modulos.Id = {$data['mod_id']} and mdl_answer_users_questions.User_id_moodle = {$data['user_id']}";

      $stmt = $this->pdo->query($sql);
      $res_user_ans = $stmt->fetchAll(PDO::FETCH_ASSOC);
      //die(var_dump($res_user_ans));
      if (!count($res_user_ans)){
        $sql = "SELECT * FROM mdl_questions_autodiagnostico
                WHERE module_assing = {$data['mod_id']}";

        $stmt = $this->pdo->query($sql);
        $questions_mod = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data_set = new stdClass();
        $data_set->User_id_moodle = $data['user_id'];

        foreach ($questions_mod as $key => $quest) {
          //die(var_dump(is_array($data['group-'.$quest['Id']])));
          if (is_array($data['group-'.$quest['Id']])){

            foreach ($data['group-'.$quest['Id']] as $key => $value) {

              $data_set->Question_id = $quest['Id'];
              $data_set->Answer_id = $value;
              $data_set->correct = 0;
              //die(var_dump($value));
              $inserted_id[] = $DB->insert_record('answer_users_questions', $data_set);
            }
          }else{
            $data_set->Question_id = $quest['Id'];
            $data_set->Answer_id = $data['group-'.$quest['Id']];
            $data_set->correct = 0;
            $inserted_id[] = $DB->insert_record('answer_users_questions', $data_set);
          }
          // Insertar los datos en la base de datos

        }

        if($data['mod_id'] == 9 && $inserted_id){
            $data_s = new stdClass();
            $data_s->id_user = $data['user_id'];
            $data_s->estatus = 'completado';

            $data_s_res[] = $DB->insert_record('autotest_finish', $data_s);

            $couurses_obligatorios = $this->return_table_modulos_required();

            $couurses_obligatorios = $this->return_table_modulos_required();
            $courses_opcionales = $this->return_table_modulos_opcional();
            $pdo = getMoodlePdoConnection();
            $mapa = new mapa($pdo);
            // Obtener la URL base del sitio en Moodle
            $base_url = $CFG->wwwroot; // Esto te da la URL base de tu instalación de Moodle

            // URL de la API de Moodle
            $api_url = 'https://universidadmondragon.kiubix.biz/webservice/rest/server.php';

            // Token del usuario
            $token = 'd59b9cda8a6dc06fd91b08439d844994';

            // Función a invocar
            $function = 'enrol_manual_enrol_users';

            foreach ($couurses_obligatorios as $key => $required) {

              $courses = $mapa->get_courses_by_modulos($required[0]);

              foreach ($courses as $key => $course) {
                //var_dump($course->course_id);

                // Parámetros para inscribir al usuario
                $params = array(
                    'enrolments' => array(
                        array(
                            'roleid' => 5,  // El rol 5 en Moodle normalmente es el de estudiante
                            'userid' => (int) $USER->id, // ID del usuario a inscribir
                            'courseid' => (int) $course->course_id, // ID del curso en el que se va a inscribir
                        )
                    )
                );

                // Verificar que el usuario no esté ya inscrito
                $response='';
                if ($DB->record_exists('user_enrolments', array('userid' => $USER->id, 'enrolid' => $course->course_id))) {
                    echo 'El usuario ya está inscrito en el curso.';
                } else {
                    // Enrolar al usuario manualmente
                    $enrol = enrol_get_plugin('manual');

                    $instance = $DB->get_record('enrol', array('courseid' => $course->course_id, 'enrol' => 'manual'), '*', MUST_EXIST);
                    //die(var_dump($course));
                    // Inscripción del usuario
                    $response = $enrol->enrol_user($instance, $USER->id, 5);

                    //echo 'Usuario inscrito correctamente.';
                }

                $responses[] = $response;

              }

            }

            foreach ($courses_opcionales as $key => $opcional) {
              $courses = $mapa->get_courses_by_modulos($opcional[0]);

              foreach ($courses as $key => $course) {
                //var_dump($course->course_id);

                // Parámetros para inscribir al usuario
                $params = array(
                    'enrolments' => array(
                        array(
                            'roleid' => 5,  // El rol 5 en Moodle normalmente es el de estudiante
                            'userid' => (int) $USER->id, // ID del usuario a inscribir
                            'courseid' => (int) $course->course_id, // ID del curso en el que se va a inscribir
                        )
                    )
                );

                // Verificar que el usuario no esté ya inscrito
                $response='';
                if ($DB->record_exists('user_enrolments', array('userid' => $USER->id, 'enrolid' => $course->course_id))) {
                    echo 'El usuario ya está inscrito en el curso.';
                } else {
                    // Enrolar al usuario manualmente
                    $enrol = enrol_get_plugin('manual');

                    $instance = $DB->get_record('enrol', array('courseid' => $course->course_id, 'enrol' => 'manual'), '*', MUST_EXIST);
                    //die(var_dump($course));
                    // Inscripción del usuario
                    $response = $enrol->enrol_user($instance, $USER->id, 5);

                    //echo 'Usuario inscrito correctamente.';
                }

                $responses[] = $response;

              }
            }
        }

      }else if (count($res_user_ans) > 0){

      }
      if ($inserted_id) {
        //echo "Registro insertado con ID: " . $inserted_id;
      } else {
        //echo "Error al insertar el registro.";
      }

      return $inserted_id;
    }

    public function get_fish_autodiagnostico($user_id){
        global $DB;
        $sql = "SELECT * FROM mdl_autotest_finish
         WHERE id_user = {$user_id} limit 1;";

        $res_user_ans = $DB->get_records_sql($sql);

        return count($res_user_ans);

    }

    public function get_answer_by_question_per_user($question_id, $user_id){
      global $DB;
      $sql = "SELECT * FROM mdl_answer_users_questions
      WHERE Question_id = {$question_id} and User_id_moodle = {$user_id};";

      $res_user_ans = $DB->get_records_sql($sql);
      if ($question_id == 8){
        //die(var_dump($sql));
      }

      return $res_user_ans;
    }

    public function render_view_questions_by_sectión($id_modulo){
      global $USER;
      $imagen = "<img class=\"w-full md:max-w-[1000px] self-end mt-20\" src=\"data:image/svg+xml,%3csvg%20width='1033'%20height='61'%20viewBox='0%200%201033%2061'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M0%2061H1033V0H60C26.8629%200%200%2026.8629%200%2060V61Z'%20fill='%23B74121'/%3e%3cpath%20d='M258.85%2061H1033V0H318.85C285.713%200%20258.85%2026.8629%20258.85%2060V61Z'%20fill='%238EC100'/%3e%3cpath%20d='M505.715%2061H1033V0H565.715C532.578%200%20505.715%2026.8629%20505.715%2060V61Z'%20fill='%230984BA'/%3e%3c/svg%3e\" alt=\"colores header\">";
      $imagen2 = 'src="data:image/svg+xml,%3csvg%20width=\'62\'%20height=\'62\'%20viewBox=\'0%200%2062%2062\'%20fill=\'none\'%20xmlns=\'http://www.w3.org/2000/svg\'%3e%3ccircle%20cx=\'31\'%20cy=\'31\'%20r=\'31\'%20fill=\'white\'/%3e%3cpath%20d=\'M22.8771%2029.9163L34.104%2018.7479C34.6302%2018.1631%2035.5073%2018.1631%2036.0921%2018.7479C36.6183%2019.2741%2036.6183%2020.1512%2036.0921%2020.6775L25.8008%2030.9103L36.0336%2041.2016C36.6183%2041.7279%2036.6183%2042.605%2036.0336%2043.1313C35.5073%2043.716%2034.6302%2043.716%2034.104%2043.1313L22.8771%2031.9044C22.2924%2031.3781%2022.2924%2030.501%2022.8771%2029.9163Z\'%20fill=\'%230984BA\'/%3e%3c/svg%3e"';

      $html = '<style>
      .theme-container #page.drawers .main-inner {
          max-width: max-content;
          margin-top: 0;
          padding: 0;
      }
      #page #page-header {
          max-width: none;
          display: none;
          margin-bottom: 15px;
          padding-top: 0;
      }
      #region-main {
          float: none;
          padding: 0 0 0;
          border-radius: 10px;
          overflow-x: visible;
      }
      html{
        font-family:Montserrat !important;--tw-text-opacity: 1 !important;color:rgb(255 255 255 / var(--tw-text-opacity)) !important;
      }
      .font-bold{
          font-weight: 700;
          font-family: Montserrat;
          --tw-text-opacity: 1;
          margin: 15px;
      }
      .text-sm{
        font-weight: 700;
        font-family: Montserrat;
        --tw-text-opacity: 1;

      }
      .mt-20 {
        margin-top: 0 !important;
      }

      .py-16 {
      padding-top: 0 !important;
      padding-bottom: 4rem;
      }

      .w-full {
        margin: 0 !important;
      }

      .font-normal.text-sm {
          display: inline;
      }
      </style>';

      $html .= '<div id="app" class="flex w-full h-full min-h-[100dvh] justify-center relative">
      <main class="w-full lg:ml-44 lg:mr-0">
          <header id="head" class="flex w-full relative lg:py-24 py-16">
              <img class="absolute w-72 lg:w-auto top-0 right-0" src="data:image/svg+xml,%3csvg%20width=\'431\'%20height=\'61\'%20viewBox=\'0%200%20431%2061\'%20fill=\'none\'%20xmlns=\'http://www.w3.org/2000/svg\'%3e%3cpath%20d=\'M0%200H431V61H60C26.8629%2061%200%2034.1371%200%200.999999V0Z\'%20fill=\'%23B74121\'/%3e%3cpath%20d=\'M108%200H431V61H168C134.863%2061%20108%2034.1371%20108%200.999999V0Z\'%20fill=\'%238EC100\'/%3e%3cpath%20d=\'M211%200H431V61H271C237.863%2061%20211%2034.1371%20211%200.999999V0Z\'%20fill=\'%230984BA\'/%3e%3c/svg%3e" alt="">
              <a href="#progress-menu" class="lg:hidden font-semibold absolute top-28 right-5 bg-[var(--color-primary)] rounded-full py-2 px-5 flex items-center gap-x-3">
                Mapa de progreso <span class="bg-white size-5 rounded-full text-[var(--color-primary)] font-bold flex justify-center items-center">?</span>
              </a>
              <a class="w-40 lg:w-auto mx-5" href="/cook/course.php">
                  <img src="./universidad/assets/logo-BvwU9ppd.svg" alt="Logo del sitio">
              </a>
          </header>';

        $preguntas_modulos = $this->getQuestionsmodulos($id_modulo);

        $question_anterior = "";
        $total_modulos = count($preguntas_modulos['modulos']);
        foreach ($preguntas_modulos['modulos'] as $key => $pregunta) {
        $html .= '
        <form id="form-evaluation" action="/cook/autotest.php" method="POST">
        <section class="mx-5 sm:mx-10">';
          $html .= '<h1 id="title-theme" class="text-2xl mb-10 lg:mb-20 lg:text-3xl font-bold text-[var(--color-primary)]">'.$pregunta['name_mod'].'</h1>';
          $html .= '<ol class="list-decimal text-black px-5 lg:px-0 flex flex-col gap-y-12">';

          $filteredArray = array_filter($preguntas_modulos['questions'], function($item) use ($pregunta) {
            //var_dump($item['mod_id'], $pregunta['id_mod']);
            return $item['mod_id'] === $pregunta['id_mod'];
          });
          //die(var_dump($filteredArray));
          $count_ans = 0;
          foreach ($filteredArray as $key1 => $questions) {
            //var_dump($questions['type_answer']);
            //die(var_dump($get_question_user));
            $html .= '<li class="font-bold text-lg">
                          <p class="pb-4">'.$questions['question_text'].'</p>';

            $filteredArray_ans = array_filter($preguntas_modulos['answers'], function($item) use ($questions) {
                return $item['question_id'] === $questions['question_id'];
            });
            $get_question_user = $this->get_answer_by_question_per_user($questions['question_id'], $USER->id);
            $count_ans++;
            if($questions['question_id'] == 8){
              //die(var_dump($get_question_user));
            }
              $ans_conunt = 0;
              foreach ($filteredArray_ans as $key2 => $ans) {
                $required = ($ans_conunt == 0 ? 'required' : '');
                $keys_array = array_keys($get_question_user)[0];

                //Revisa las respuestas de los estudiantes que sean las correctas para cargarlas
                foreach ($get_question_user as $keys_array => $ans_get) {
                  $isChecked = ((int) $get_question_user[$keys_array]->answer_id == $ans['answer_id'] ? 1 : 0);

                  if ($isChecked){
                    break;
                  }
                }

                $html .= '<input type="'.$questions['type_answer'].'" id="option-'.$questions['question_id'].'-'.$ans['answer_id'].'" name="group-'.$questions['question_id'].($questions['type_answer'] =='checkbox' ? '[]' : '').'" value="'.$ans['answer_id'].'" '
                .($isChecked ? 'checked' : '').' '.$required.'>
                <label class="font-normal text-sm" for="option-1-1">'.$ans['answer_text'].'</label><br>';
                $ans_conunt++;
              }

            $html .= '</li>';

          }
          //die(var_dump($USER));
          $html .= '<input type="hidden" name="user_id" value="'.$USER->id.'">
                    <input type="hidden" name="mod_id" value="'.($pregunta['id_mod']).'">';
          $html .= '</ol></section></form>';
          $question_anterior = $pregunta['m_id'];
        }

        $modulos_get = $this->getModulos();
        //die(var_dump($modulos_get));
        $total_modulos = count($modulos_get);
        $las_mod_completed = "";
        foreach ($modulos_get as $ke_mod => $modulo) {

          $isCompleted = $this->isModCompleted($modulo['mod_id']);
          $html_lis .= '<li class="'.($isCompleted ? 'li-section-active-time-line ' : 'li-section-pending-time-line ').'" style="color: '.($id_modulo != $modulo['mod_id'] ? 'white' : '#8ec200').';">
                          <hr class="'.(($ke_mod > 0) ? (($ke_mod <= $total_modulos) ? ($isCompleted || $las_mod_completed ? 'right-time-line bg-white' : 'right-time-line bg-white line-empty-time-line') : '') : '').'">
                          <a href="'.($isCompleted || $las_mod_completed ? '/cook/autotest.php?mod='.$modulo['mod_id'] : '').'" class="a-time-line-'.($isCompleted || $las_mod_completed ? 'active' : 'lock').'">'.$modulo['name'].'</a>
                          <a class="'.($isCompleted ? 'point-time-line-check shadow-check-shadow-white': 'point-time-line-require').' bg-white "
                            href="/cook/autotest.php?mod='.$modulo['mod_id'].'">'.($isCompleted ? '<i class="fa-solid fa-check i-time-line"></i>' : '').'</a>
                          <hr class="'.(($ke_mod+1 < $total_modulos) ? ($isCompleted ? 'left-time-line bg-white' : 'left-time-line bg-white line-empty-time-line') : '').' bg-white">
                        </li>';
          //valida si ya es el ultimo modulo
          if (($ke_mod+1) < count($modulos_get)){
            $las_mod_completed = $isCompleted;
          }
        }

        $terminado_test = $this->get_fish_autodiagnostico($USER->id);
        if ($terminado_test){
            $btn_finish = '<a href="/cook/reports.php" id="btn-result" class="btn-auto-next">Ver resultados</a>';
        }else{
            $btn_finish = "";
        }

        $html .= '<footer class="my-10 flex justify-center lg:justify-start">
          <section class="text-black w-[90%]">
            <hr class="border-[var(--color-secondary)]">
            <div class="btn-container w-full my-5" style="text-align: right; justify-content: flex-start; padding-top: 20px;">
                <button id="btn-auto-back" style="display:'.($terminado_test ? 'none;': '').'" class="btn-auto-back">Volver</button>
                <button id="btn-auto-next" style="display:'.($terminado_test ? 'none;': '').'" class="btn-auto-next">'.($las_mod_completed ? 'Terminar' : 'Siguiente').'</button>
                '.$btn_finish.'
            </div>
          </section>
        </footer>
      </main>
      <aside id="progress-menu" class="h-full lg:static lg:overflow-auto lg:h-auto lg:translate-y-[initial] overflow-scroll w-[300px] lg:w-[500px] bg-[var(--color-primary)] px-5 pt-10 lg:px-10 lg:pt-16 fixed right-0 -translate-y-full transition-transform duration-300 target:translate-y-0">
        <a class="absolute top-6 right-10 lg:hidden" href="#header"><i class="fa-solid fa-close text-2xl"></i></a>
        <ul class="flex flex-col">';

        $html .= $html_lis;

        $html .= '</ul>
              </aside>
            </div>
            <script>
            document.getElementById("btn-auto-back").addEventListener("click", function() {
              document.getElementById("form-evaluation").submit();
            });

            document.getElementById("btn-auto-next").addEventListener("click", function() {

              let valido = true;
              let mensajeError = "";

              // Obtener el formulario
              const form = document.getElementById("form-evaluation");

              // Validar los checkboxes dentro del formulario
              const checkboxes = form.querySelectorAll("input[type=\'checkbox\']");

              let checkboxSeleccionado = false;
              if (checkboxes.length > 0){
                checkboxSeleccionado = false;
              }else{
                checkboxSeleccionado = true;
              }

              for (const checkbox of checkboxes) {
                if (checkbox.checked) {
                  checkboxSeleccionado = true;
                  break;
                }
              }

              if (!checkboxSeleccionado) {
                mensajeError += "Debes seleccionar al menos un interés.\n";
                valido = false;
              }

              // Validar los radios dentro del formulario
              const radios = form.querySelectorAll("input[type=\'radio\']");
              const radiosPorNombre = {}; // Agrupar radios por atributo \'name\'

              radios.forEach(radio => {
                if (!radiosPorNombre[radio.name]) {
                  radiosPorNombre[radio.name] = [];
                }
                radiosPorNombre[radio.name].push(radio);
              });

              for (const grupo in radiosPorNombre) {
                let radioSeleccionado = false;

                for (const radio of radiosPorNombre[grupo]) {
                  if (radio.checked) {
                    radioSeleccionado = true;
                    break;
                  }
                }

                if (!radioSeleccionado) {
                  mensajeError += `Debes seleccionar una opción para la pregunta ${grupo.replace("group-", "")}.\n`;
                  valido = false;
                }
              }

              if (valido) {
                form.submit();
              } else {
                alert(mensajeError); // Muestra los errores encontrados
              }
            });
            </script>';



      return $html;
    }
    //retorna un 1 si es obligatorio el modulo
    public function validate_points_required($points_answer, $min_points){
      return ($points_answer < $min_points ? 1 : 0);
    }

    public function return_table_results(){
      $array_required_mods = [];
      $results = $this->get_result_autodiagnostic();
      $html .= '<table class="w-full"><tbody>';

          foreach ($results as $key => $value) {
            $html .= '<tr>';
              $html .= '<td class="td-col-1">'.$value->m_name.'</td>';
              $html .= '<td class="td-col-2">'.$value->points_per_mod.' de '.$value->max_points.' pt.';
              if ($this->validate_points_required($value->points_per_mod, $value->min_points)){
                $array_required_mods[] = $value->m_name." <i class='fa-solid fa-exclamation-circle' style='color: #bb0c0c;'></i>";
                $html .= " <i class='fa-solid fa-exclamation-circle' style='color: #bb0c0c;'></i>";
              }
              $html .= '</td>';
            $html .= '</tr>';
          }
      $html .= '</tbody></table>';

      //die(var_dump($array_required_mods));
      $html .= '<p class="text-lg font-bold">Derivado de lo anterior, los módulos obligatorios que el o la participante debe cursar son:</p>';
      $html .= ' <ul class="flex flex-col gap-y-4">';
      foreach ($array_required_mods as $key => $names) {
        $html .= '<li>'.$names.'</li>';
      }
      $html .= '</ul>';

      return $html;
    }

    public function return_table_results_exports(){
      $array_results_mods = [];
      $results = $this->get_result_autodiagnostic();

      foreach ($results as $key => $value) {
        $array_results_mods[] = [$value->points_per_mod, $value->max_points];
      }

      return $array_results_mods;
    }

    public function return_requierd_results($course_id){
      $array_results_mods = [];
      $results = $this->get_result_autodiagnostic_by_course($course_id);

      foreach ($results as $key => $value) {
        $result = $this->validate_points_required($value->points_per_mod, $value->min_points);
        //$result = [$value->points_per_mod, $value->min_points];
      }

      return $result;
    }

    public function return_table_modulos_exports(){
      $array_results_mods = [];
      $results = $this->get_result_autodiagnostic();

      foreach ($results as $key => $value) {
        if ($this->validate_points_required($value->points_per_mod, $value->min_points)){
          $array_results_mods[] = $value->m_name;
        }
      }

      return $array_results_mods;
    }

    public function return_table_modulos_required(){
      $array_results_mods = [];
      $results = $this->get_result_autodiagnostic();

      foreach ($results as $key => $value) {
        if ($this->validate_points_required($value->points_per_mod, $value->min_points)){
          $array_results_mods[] = [$value->relative_activity_moodle, $value->m_name];
        }
      }

      return $array_results_mods;
    }

    public function return_table_modulos_opcional(){
      $array_results_mods = [];
      $results = $this->get_result_autodiagnostic();

      foreach ($results as $key => $value) {
        if (!$this->validate_points_required($value->points_per_mod, $value->min_points)){
          $array_results_mods[] = [$value->relative_activity_moodle, $value->m_name];
        }
      }

      return $array_results_mods;
    }

    public function view_render_report_results(){
      $array_json = json_encode($this->return_table_results_exports());
      $modulos_json = json_encode($this->return_table_modulos_exports());
      $html .= '
      <style>
      .theme-container #page.drawers .main-inner {
          max-width: max-content;
          margin-top: 0;
          padding: 0;
      }
      #page #page-header {
          max-width: none;
          display: none;
          margin-bottom: 15px;
          padding-top: 0;
      }
      #region-main {
          float: none;
          padding: 0 0 0;
          border-radius: 10px;
          overflow-x: visible;
      }
      </style>
      <div id="app" class="flex flex-col w-full h-full min-h-[100dvh] items-center relative">
        <img class="absolute w-72 lg:w-auto top-0 right-0" src="data:image/svg+xml,%3csvg%20width=%27431%27%20height=%2761%27%20viewBox=%270%200%20431%2061%27%20fill=%27none%27%20xmlns=%27http://www.w3.org/2000/svg%27%3e%3cpath%20d=%27M0%200H431V61H60C26.8629%2061%200%2034.1371%200%200.999999V0Z%27%20fill=%27%23B74121%27/%3e%3cpath%20d=%27M108%200H431V61H168C134.863%2061%20108%2034.1371%20108%200.999999V0Z%27%20fill=%27%238EC100%27/%3e%3cpath%20d=%27M211%200H431V61H271C237.863%2061%20211%2034.1371%20211%200.999999V0Z%27%20fill=%27%230984BA%27/%3e%3c/svg%3e" alt="">

        <main class="container">
          <header id="hed" class="flex container relative lg:py-24 py-16">
            <a class="w-40 lg:w-auto mx-5" href="/cook/autotest.php">
              <img src="./universidad/assets/logo-BvwU9ppd.svg" alt="Logo del sitio">
            </a>
          </header>

          <section class="text-black flex flex-col gap-y-10 mx-5 lg:mx-0">
            <label class="text-[var(--color-secondary)] font-bold text-2xl md:text-3xl">PRESENTACIÓN DE RESULTADOS</label>';

            $html .= $this->return_table_results();

            $html .= '
            <p class="whitespace-pre-line">A fin de que puedas obtener las herramientas que serán vitales para tu emprendimiento. Es indispensable acreditar los módulos obligatorios para acreditar el programa.<br>
            Los módulos restantes son opcionales, por lo cual, estarán disponibles por si quieres cursarlos, pero no será requisito que los abordes para acreditar el programa, sólo los módulos obligatorios.<br>
            Adicionalmente, podrás solicitar asesorías personalizadas en los temas donde sientas que necesitas reforzar conocimientos, o donde, a partir de los contenidos, requieras clarificar dudas, hacer alguna consulta o profundizar en algún aspecto.</p>

            <ul class="list-disc list-inside flex flex-col gap-y-2">
              <label>El Programa de Emprendimiento tiene un modelo que está orientado a:</label>
              <li class="pl-2">Lograr Emprendimientos</li>
              <li class="pl-2">Rentables Fortalecer el Liderazgo</li>
              <li class="pl-2">Fomentar la Sostenibilidad y la Cultura Cooperativa</li>
            </ul>

            <ul class="list-disc list-inside flex flex-col gap-y-2">
              <label>Recuerda que también podrás encontrar:</label>
              <li class="pl-2">Material de consulta o referencia</li>
              <li class="pl-2">Un tutorial de apoyo</li>
              <li class="pl-2">Solicitar asistencia técnica</li>
            </ul>

            <span>Para acceder al programa entra en:</span>
            <a href="/cook/course.php" class="btn-program self-start">Programa</a>

            <div class="flex flex-col items-center md:flex-row gap-3 md:justify-end">
              <button id="btn-result" class="btn-result">Descargar resultados</button>
              <a href="/cook/course.php" class="btn-program">Finalizar</a>
            </div>
          </section>
        </main>

        <footer class="flex w-full justify-end">
          <img class="w-full md:max-w-[1000px] mt-14" src="data:image/svg+xml,%3csvg%20width=%271033%27%20height=%2761%27%20viewBox=%270%200%201033%2061%27%20fill=%27none%27%20xmlns=%27http://www.w3.org/2000/svg%27%3e%3cpath%20d=%27M0%2061H1033V0H60C26.8629%200%200%2026.8629%200%2060V61Z%27%20fill=%27%23B74121%27/%3e%3cpath%20d=%27M258.85%2061H1033V0H318.85C285.713%200%20258.85%2026.8629%20258.85%2060V61Z%27%20fill=%27%238EC100%27/%3e%3cpath%20d=%27M505.715%2061H1033V0H565.715C532.578%200%20505.715%2026.8629%20505.715%2060V61Z%27%20fill=%27%230984BA%27/%3e%3c/svg%3e" alt="colores header">
        </footer>
      </div>';

      $html .= '<script src="./universidad/static/js/jspdf.umd.min.js"></script>';
      //$html .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.4/purify.min.js"></script>';
      $html .= "<script>
                const btnResult = document.querySelector('.btn-result');

                btnResult.addEventListener('click', () => {
                  const { jsPDF } = window.jspdf;
                  const doc = new jsPDF({
                     orientation: 'portrait',
                     unit: 'mm',
                     format: 'a4'
                  });

                  const puntajes = ".$array_json.";

                  const modulos = ".$modulos_json.";

                  const cantidadElementos = Object.keys(modulos).length;

                  const img = new Image();

                  if (cantidadElementos <= 3 ){
                    img.src = './universidad/static/images/resultados_v3.jpg';
                  }else if (cantidadElementos > 3 && cantidadElementos <= 6){
                    img.src = './universidad/static/images/resultados_v6.jpg';
                  }else{
                    img.src = './universidad/static/images/resultados_v9.jpg';
                  }

                  doc.addImage(img, 'JPEG', 0, 0, 210, 297);

                  doc.setFontSize(8);
                  doc.setTextColor(80);

                  puntajes.forEach((puntaje, index) => {
                   doc.text(puntaje[0] + ' de ' + puntaje[1] + 'pt.', 170, 51 + (index * 7.3));
                  });

                  modulos.forEach((modulo, index) => {
                    doc.text(modulo, 20, 127 + (index * 6));
                  });

                  doc.save('resultados.pdf');
                });

             </script>";

      return $html;
    }
}

?>

<?php

//  Display the course home page.

    require_once('../config.php');
    require_once('../course/lib.php');
    require_once($CFG->libdir.'/completionlib.php');
if (!isloggedin()) {
  redirect('https://empleabilidad.redmundua.com/');
}
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
    global $DB, $CFG, $USER;
    $identificador = $_GET['id'];

    switch((int)$identificador){
      case 1:
        $titulo = "";
        $texto = "";
        break;
      case 3:
        $titulo = "En el programa de alternancia Mundua te garantiza egresar con mínimo 12 meses de experiencia laboral, y 16 competencias profesionales experimentadas. Podrás estudiar y trabajar desarrollando proyectos para dar solución a problemáticas reales.  ";
        $texto = "<p>En el programa de Alternancia vas a desarrollar competencias profesionales para iniciar y crecer dentro del ámbito laboral, ya que está diseñado especialmente para ofrecerte herramientas específicas para integrarte al campo profesional de la manera más adecuada.

        Alternancia es un programa integral, basado en un análisis a través de la autoevaluación para mejorar tanto las soft como las hard skills, poniendo en práctica todo el conocimiento adquirido en tu formación académica.

        El programa está fundado para acompañarte en cada etapa de tu vida laboral y, sobre todo, estará centrado en tu Proyecto de Vida, donde evaluaremos diferentes procesos, como la introducción al mundo laboral, el desarrollado de un profesionista, identificación y liderazgo de equipos de alto rendimiento y todo apoyado en la capacidad de saber autoevaluarte para juzgar tus logros respecto a una tarea y/o proyecto determinado.

        Si tienes la curiosidad o deseo de adquirir experiencia a viva voz, eres una persona proactiva, responsable, te gusta estar enfocado en objetivos y con muchas ganas de aprender sobre tu área profesional, este programa es para ti, para adquirir la mejor experiencia y herramientas para desarrollarte profesionalmente. </p>
        <img style='width: 100%;' src='https://empleabilidad.redmundua.com/theme/moove/pix/ALTERNANCIA-03.jpg'></img>
        <img style='width: 100%;' src='https://empleabilidad.redmundua.com/theme/moove/pix/ALTERNANCIA-01.jpg'></img>
        <img style='width: 100%;' src='https://empleabilidad.redmundua.com/theme/moove/pix/ALTERNANCIA-02.jpg'></img>
        ";
        break;
      case 2:
        $titulo = "¿Pasión por emprender? Si escoges el programa de emprendizaje Mundua, aprenderás a hacerlo. Tienes la oportunidad de elegir crear tu propio negocio, con una guía y serie de herramientas para que al egresar tu negocio ya esté facturando.";
        $texto = "Desarrollarás competencias y capacidades, así como los saberes y los conocimientos integrados a la acción para la creación de modelos de negocio reales, viables y sostenibles.

          Emprendizaje es un programa autónomo, basado en la experiencia y llevado a cabo mediante un proceso auto-gestionado, en el que se desarrollan competencias en las áreas clave de cualquier negocio: Mercado, Desarrollo de producto / servicio, Regulatorio, Talento, Financiera / fondeo y Soft skills.

          Emprendizaje es un programa con una metodología única en el que, a través de diferentes pasos, desarrollarás tu idea de negocio con una visión sistémica, esto significa que cada elemento del programa está interrelacionado, de forma integral, lo cual promueve el pensamiento crítico y el razonamiento analítico sobre el desarrollo de tu idea de negocio.

          Si cuentas con un cuenta con un pensamiento enfocado en resultados, eres una persona creativa e innovadora y te gustan los retos y riesgos calculados este programa es para ti.
          <img style='width: 100%;' src='https://empleabilidad.redmundua.com/theme/moove/pix/EMPRENDIZAJE-01.jpg'></img>
          <img style='width: 100%;' src='https://empleabilidad.redmundua.com/theme/moove/pix/EMPRENDIZAJE-02.jpg'></img>
          <img style='width: 100%;' src='https://empleabilidad.redmundua.com/theme/moove/pix/EMPRENDIZAJE03.jpg'></img>
          ";
        break;
      case 4:
        $titulo = "";
        $texto = "";
        break;
      case 5:
        $titulo = "¿Qué es proyecto de Vida? ¿Y cómo funcionan los bloques?";
        $texto = "Hola, en esta primera etapa descubrirás o darás forma a Tu proyecto de vida, el plan personal y profesional de tu vida a mediano y largo plazo. En otras palabras, la congruencia entre aquello que da sentido a tu vida, tus valores y tus conductas cotidianas.
        <br><br>
        Prepárate para analizar y evaluar ciertas esferas de tu vida que quizás no te hayas parado a pensar hasta el momento. Cada etapa de este proceso va dividida en bloques que tienen como requisito para ir liberándose; el envío completo de todos los entregables asociados.
        <br><br>
        Para poder completar cada uno de los entregables, tendrás a disposición una serie de recursos y materiales didácticos amigables como videos y presentaciones prácticas que te permitirán comprender y aplicar cada tema en cuestión.
        <br><br>
        Proyecto de vida es la certeza de tener un horizonte y un rumbo ante la incertidumbre del futuro.";
        break;
      case 6:
        $titulo = "Elección";
        $texto = "La etapa de Elección de programa te ofrece herramientas
        para elegir con certeza el rumbo de aquello que hayas identificado en <strong>tu
        proyecto de vida.</strong>";
        break;
      case 8:
        $titulo = "Preparación Alternancia";
        $texto = "Alternando estudio y trabajo en una empresa, desarrollarás
        <strong>competencias profesionales</strong>
        para iniciar y crecer dentro del ámbito laboral, ya que está diseñado especialmente para ofrecerte herramientas para integrarte al campo profesional de la manera más adecuada y realista.";
        break;
      case 9:
        $titulo = "¿Pasión por emprender?";
        $texto = "En el programa de emprendizaje
        de la red Mundua aprenderás a hacerlo de manera sana y organizada. Aquí tienes la oportunidad de elegir crear tu propia empresa, con una guía y serie de herramientas para que al egresar tu negocio ya esté generando ingresos.<strong> ¡Aprender
        a emprender es Emprendizaje!</strong>";
        break;
    }

    $params = array();
    if (!empty($name)) {
        $params = array('shortname' => $name);
    } else if (!empty($idnumber)) {
        $params = array('idnumber' => $idnumber);
    } else if (!empty($id)) {
        $params = array('id' => $id);
    }else {
        print_error('unspecifycourseid', 'error');
    }

    $course = $DB->get_record('course', $params, '*', MUST_EXIST);

    $urlparams = array('id' => $course->id);

    // Sectionid should get priority over section number
    if ($sectionid) {
        $section = $DB->get_field('course_sections', 'section', array('id' => $sectionid, 'course' => $course->id), MUST_EXIST);
    }
    if ($section) {
        $urlparams['section'] = $section;
    }
    require_login($course);
    if ($section and $section > 0) {

        // Get section details and check it exists.
        $modinfo = get_fast_modinfo($course);
        $coursesections = $modinfo->get_section_info($section, MUST_EXIST);

        // Check user is allowed to see it.
        if (!$coursesections->uservisible) {
            // Check if coursesection has conditions affecting availability and if
            // so, output availability info.
            if ($coursesections->visible && $coursesections->availableinfo) {
                $sectionname     = get_section_name($course, $coursesections);
                $message = get_string('notavailablecourse', '', $sectionname);
                redirect(course_get_url($course), $message, null, \core\output\notification::NOTIFY_ERROR);
            } else {
                // Note: We actually already know they don't have this capability
                // or uservisible would have been true; this is just to get the
                // correct error message shown.
                require_capability('moodle/course:viewhiddensections', $context);
            }
        }
    }

    //$PAGE->set_heading($course->fullname);
    //echo $OUTPUT->header();
    //echo '';
    if ($USER->editing == 1) {

        // MDL-65321 The backup libraries are quite heavy, only require the bare minimum.
        require_once($CFG->dirroot . '/backup/util/helper/async_helper.class.php');

        if (async_helper::is_async_pending($id, 'course', 'backup')) {
            echo $OUTPUT->notification(get_string('pendingasyncedit', 'backup'), 'warning');
        }
    }

    // Course wrapper start.
    //echo html_writer::start_tag('div', array('class'=>'course-content'));

    // make sure that section 0 exists (this function will create one if it is missing)
    course_create_sections_if_missing($course, 0);

    // get information about course modules and existing module types
    // format.php in course formats may rely on presence of these variables
    $modinfo = get_fast_modinfo($course);
    $modnames = get_module_types_names();
    $modnamesplural = get_module_types_names(true);
    $modnamesused = $modinfo->get_used_module_names();
    $mods = $modinfo->get_cms();
    $sections = $modinfo->get_section_info_all();

    // CAUTION, hacky fundamental variable defintion to follow!
    // Note that because of the way course fromats are constructed though
    // inclusion we pass parameters around this way..
    $displaysection = $section;

    // Include the actual course format.
    //require($CFG->dirroot .'/course/format/'. $course->format .'/format2.php');
    $sections = $DB->get_records('course_sections', array('course' => $course->id), 'section', '*');
    //die(var_dump($sections));
    foreach ($sections as $key => $sec) {
      if ($sec->section == 0){
        continue;
      }

      $complete = 0;
      $completioninfo = new completion_info($course);
      $modinfo = get_fast_modinfo($course);
      $cancomplete = isloggedin() && !isguestuser();
      $completed = 0;
      $total = 0;
      //$next_active = 0;
      foreach ($modinfo->sections[$sec->section] as $cmid) {
          $thismod = $modinfo->cms[$cmid];

          if ($thismod->uservisible) {
              if (isset($sectionmods[$thismod->modname])) {
                  $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                  $sectionmods[$thismod->modname]['count']++;
              } else {
                  $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                  $sectionmods[$thismod->modname]['count'] = 1;
              }

              if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                  $total++;
                  $completiondata = $completioninfo->get_data($thismod, true);
                  if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                          $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                      $complete++;
                  }
              }
          }
      }

      $i++;
      if ($complete && $total ){
        $completed = ($complete == $total ? 1 : 0);
      }

      //var_dump($complete.$total);
      $url_link = "/course/view.php?id=".$course->id."&section=".$sec->section;
      //die(var_dump($sec));
      $title = ($sec->name ? (substr($sec->name, strrpos($sec->name, ':')+1)) : "Tema: ".$i );
      $nom_name = (substr($sec->name,0, strrpos($sec->name, ':')));
      $nom_name = $i;
      //$title = (substr($sec->name, strrpos($sec->name, ':')+1));
      $li_html .= '<li class="litime t'.$i.'" style="display:'.($i > 8 ? 'none' : 'block').' ;width:'.$porcentaje.'%;">
      <span class="point">
      <a href="'.($completed == 1 || $next_active == 1 || $i ==1 ? $url_link : '#').'" >
      <i id="'.$i.'" class="fas fa-circle fa-cube-'.$i.' fa-5x '.($completed == 1 ? 'cube-completed' : ($next_active == 1 || $i == 1 ? 'cube' : 'white-cube')).'">
      </i></a>
      '.($next_active == 1 ? ($completed == 1 ? '' : '') : ($completed == 1 ? '' : ($i == 1 && $completed == 0 ? '' :'
      </i>'))).'
      <a style="text-decoration: none; color: white;" href="'.($completed == 1 || $next_active == 1 || $i ==1 ? $url_link : $url_link).'"></span><p id="'.$i.'" class="diplome'.(count($sections) >= $i ? '-ultimo' : '').'"><strong style="font-size: 30px;">'.$nom_name.'</strong></p></a><a style="text-decoration: none; color: black;" href="'
      .($completed == 1 || $next_active == 1 || $i ==1 ? $url_link : '#').'"><p style="color: white !important; right: '.(count($sections) < 8 ? '-35px' : '-40px' ).' !important;"class="dip-title dip-title-'.$i.'"> '.$title.'</p></a></li>';
      //247 - </i>' : ($i == 1 && $completed == 0 ? '' :'<i class="fas fa-lock">
      //249 - <a style="text-decoration: none; color: white;" href="'.($completed == 1 || $next_active == 1 || $i ==1 ? $url_link : '#').'"></span><p id="'.$i.'" class="diplome'.(count($sections) >= $i ? '-ultimo' : '').'"><strong style="font-size: 30px;">'.$nom_name.'</strong></p></a><a style="text-decoration: none; color: black;" href="'.($completed == 1 || $next_active == 1 || $i ==1 ? $url_link : '#').'"><p style="color: white !important;"class="dip-title dip-title-'.$i.'"> '.$title.'</p></a></li>';
      $next_active = $completed;
    }
    //exit;
    $sql_result = "SELECT mdl_munduatest_questions.id_competencia, test_competencia.name, test_competencia.definicion, sum(val) as puntaje, max_ponit,ponderado_point FROM mdl_munduaanswer_users
    inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
    inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
    inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia where attempt_id = (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and test_id = (select t_id from test_quiz where id_course = {$course->id})) group by mdl_munduatest_questions.id_competencia";

    $array_series = [];
    $ultimo_intento = $DB->get_recordset_sql($sql_result);
    $rect .= "<div class='row'>";
    $estrella = "<span style='display: table-cell;vertical-align: middle; color:white;'><i class='fas fa-star fa-2x'></i></span>";
    foreach ($ultimo_intento as $key => $value) {
      $estrella1 = "";
      $estrella2 = "";
      $estrella3 = "";
      $estrella4 = "";
      $estrella5 = "";
      array_push($array_series, $value->name);
      $porcentaje = (100 / $value->max_ponit * $value->puntaje);
      if ($porcentaje >= 0 && $porcentaje <= 20){
        $estrella1 = $estrella;
          $estrellaTexto = "<p style='color: #de0a0d;'>Insuficiente</p>";
      }elseif ($porcentaje >= 20.01 && $porcentaje <= 40) {
        $estrella2 = $estrella;
          $estrellaTexto = "<p style='color: #e66c00;'>Básica</p>";
      }elseif ($porcentaje >= 40.01 && $porcentaje <= 60) {
        $estrella3 = $estrella;
        $estrellaTexto = "<p style='color: #fee000;'>Aceptable</p>";
      }elseif ($porcentaje >= 60.01 && $porcentaje <= 80) {
        $estrella4 = $estrella;
        $estrellaTexto = "<p style='color: #dcdc01;'>Notable</p>";
      }elseif ($porcentaje >= 80.01 && $porcentaje <= 100) {
        $estrella5 = $estrella;
        $estrellaTexto = "<p style='color: #4ba92e;'>Sobresaliente</p>";
      }

      $rect .="<div class='col-md-12'>";
        $rect .= "<div class='row'>";
          $rect .="<div class='col-md-4' style='margin: auto;'>";
            $rect .="<h7><strong>".$value->name."</strong></h7>";
            $rect .="<p>".$value->definicion."</p>";
          $rect .= "</div>";
          $rect .="<div class='col-md-8' style='margin: auto;'>";
            $rect .= "<div class='row' style='padding: 10px;'>";
              $rect .="<div class='col-md-2' style='background: #de0a0d;     display: table;border: solid 1px;min-height: 45px;margin: auto;border-radius: 25% 0px 0px 25%;'><br>";
                $rect .= $estrella1;
              $rect .= "</div>";
              $rect .="<div class='col-md-2' style='background: #e66c00;     display: table;border: solid 1px;height: 45px;margin: auto;'><br>";
                $rect .= $estrella2;
              $rect .= "</div>";
              $rect .="<div class='col-md-2' style='background: #fee000;     display: table;border: solid 1px;height: 45px;margin: auto;'><br>";
                $rect .= $estrella3;
              $rect .= "</div>";
              $rect .="<div class='col-md-2' style='background: #dcdc01;     display: table;border: solid 1px;height: 45px;margin: auto;'><br>";
                $rect .= $estrella4;
              $rect .= "</div>";
              $rect .="<div class='col-md-2' style='background: #4ba92e;     display: table;border: solid 1px;height: 45px;margin: auto;border-radius: 0 25% 25% 0;'><br>";
                $rect .= $estrella5;
              $rect .= "</div>";
              $rect .="<div class='col-md-2' style='margin: auto;'>";
                $rect .= "<h5>".round($porcentaje,2)."%</h5>"."";
                if($course->id ==5 || $course->id ==8 || $course->id ==9 ){

                  switch ($value->id_competencia) {
                    case '1':
                        $link_mejora = "http://alasvenezuela.com/2015/07/aprender-a-desarrollar-la-autonomia-personal/";
                      break;

                    case '2':
                        $link_mejora = "https://www.bbc.com/mundo/noticias/2015/11/151110_vert_cap_sociedad_objetivos_visualizar_comunicar_trabajo_exito_ppb";
                      break;

                    case '3':
                        $link_mejora = "https://psicologiaymente.com/vida/plan-de-vida";
                      break;

                    case '4':
                        $link_mejora = "https://bienestando.es/capacidad-de-anticipacion/";
                      break;

                    case '5':
                        $link_mejora = "https://habilidadsocial.com/como-desarrollar-la-inteligencia-emocional/";
                      break;

                    case '6':
                        $link_mejora = "http://www.aikaeducacion.com/consejos/8-consejos-desarrollar-pensamiento-critico/";
                      break;

                    case '7':
                        $link_mejora = "https://www.crehana.com/mx/blog/negocios/identificar-oportunidades-de-negocio/";
                      break;

                    case '8':
                        $link_mejora = "https://lamenteesmaravillosa.com/potenciar-tu-pensamiento-critico/";
                      break;

                    //case '9':
                        //$link_mejora = "https://psicologiaymente.com/vida/plan-de-vida";
                    //  break;

                    default:
                        $link_mejora = "/".$value->id_competencia;
                      break;
                  }
                  $rect .= "<div class='row'><a href='".$link_mejora."' target='_blank' style='width: 100%; margin: 10px;     white-space: normal; border-radius:25%;' class='btn btn-light'>Alternativas de mejora</a></div>";
                }
              $rect .= "</div>";
            $rect .= "</div>";
          $rect .= "</div>";
        $rect .= "</div>";
      $rect .= "</div>";
    }
    $rect .= "</div>";

    //die(var_dump($ultimo_intento));

    $sql_results = "SELECT attempt_id,test_competencia.name,(select fecha from mdl_munduaattemps where a_id = attempt_id) as fecha, mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit ,ponderado_point FROM mdl_munduaanswer_users
    inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
    inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
    inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia
    where attempt_id in (select a_id from mdl_munduaattemps where user_id = {$USER->id} and test_id = (select t_id from test_quiz where id_course = {$course->id})) group by attempt_id,(select fecha from mdl_munduaattemps
    where a_id = attempt_id),mdl_munduatest_questions.id_competencia,max_ponit,ponderado_point order by attempt_id,id_competencia";
    $todos_intentos = $DB->get_recordset_sql($sql_results);
    //die(var_dump($todos_intentos));

    $array_attemps =[];
    $array = "";
    $competencia_anterior = "";
    $attemp_id_anterior = "";
    foreach ($todos_intentos as $key => $at) {
      $porcentaje = (100 / $at->max_ponit * $at->puntaje);
      if ($array == ""){
        $array .= $at->fecha;
        //$array .= ",".$porcentaje;
      }
      if(($attemp_id_anterior != $at->attempt_id) && $attemp_id_anterior){
        array_push($array_attemps,explode(',',$array));
        $array = "";
        $array = $at->fecha;
      }
      $array .= ",".$porcentaje;

      //var_dump($array);
      //var_dump("anterior:".$competencia_anterior, "nuevo:".$at->id_competencia."<br>");
      $competencia_anterior = $at->id_competencia;
      $attemp_id_anterior = $at->attempt_id;
    }
    //die(var_dump($array_series));
    array_push($array_attemps,explode(',',$array));
    //die(var_dump($array_attemps));

    if ($course->id != 5 && $course->id != 8 && $course->id != 9 ){

      $sql_results_araña = "SELECT count(*) as inte, attempt_id,test_competencia.name,(select fecha from mdl_munduaattemps where a_id = attempt_id) as fecha, mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit ,ponderado_point FROM mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia
      where attempt_id = (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and test_id in (select t_id from test_quiz where id_course = {$course->id})) group by attempt_id,(select fecha from mdl_munduaattemps
      where a_id = attempt_id),mdl_munduatest_questions.id_competencia,max_ponit,ponderado_point order by attempt_id,id_competencia";
      $todos_intentos_araña = $DB->get_recordset_sql($sql_results_araña);
      //$array_int = (array) $todos_intentos_araña;
      $num_rows = 0;
      $array_attemps_araña =[];
      $array = "";
      $competencia_anterior = "";
      $attemp_id_anterior = "";
      //die(var_dump($array_int["*result"]));
      foreach ($todos_intentos_araña as $key => $araña) {
        //die(var_dump($todos_intentos_araña->inte));
        $num_rows++;
        $array = "";
        $porcentaje = (100 / $araña->max_ponit * $araña->puntaje);

        //$array .= $araña->name;
        if ($course->id == 3){
          $array .= substr($araña->name, 1, strpos($araña->name, ": ") - 1);
          //$list_item .= "<li>".$araña->name."</li>";
          $hidden = "display: block;";
        }else{
          $array .= $araña->name;
          $hidden = "display: none;";
        }

        $array .= ":-".$porcentaje;
        array_push($array_attemps_araña,explode(':-',$array));

      }

      if ($course->id == 2){
      $sql_competencia ="SELECT tet.*, test_quiz.id_course,max_ponit, (select sum(val) as puntaje from mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id
      and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia where attempt_id =
      (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and mdl_munduaattemps.test_id = (tet.test_id)) and test_competencia.c_id = tet.c_id) as porcentaje
      FROM test_competencia as tet
      JOIN test_quiz on test_quiz.t_id = tet.test_id
      where id_course in (2) order by porcentaje desc";
      //var_dump($sql_competencia);
      $competencias_rh = $DB->get_recordset_sql($sql_competencia);
      $li_competencia .= "<div class='col-md-12 text-center'><h3>Competencias</h3></div>";
      foreach ($competencias_rh as $key => $competencia) {
        //var_dump($competencia);
        $porcentaje = (100 / $competencia->max_ponit * $competencia->porcentaje);
        if ($porcentaje >= 0 && $porcentaje <= 20){
          $estrella1 = $estrella;
            $estrellaTexto = "<p style='color: #de0a0d;'>Insuficiente</p>";
        }elseif ($porcentaje >= 20.01 && $porcentaje <= 40) {
          $estrella2 = $estrella;
            $estrellaTexto = "<p style='color: #e66c00;'>Básica</p>";
        }elseif ($porcentaje >= 40.01 && $porcentaje <= 60) {
          $estrella3 = $estrella;
          $estrellaTexto = "<p style='color: #fee000;'>Aceptable</p>";
        }elseif ($porcentaje >= 60.01 && $porcentaje <= 80) {
          $estrella4 = $estrella;
          $estrellaTexto = "<p style='color: #dcdc01;'>Notable</p>";
        }elseif ($porcentaje >= 80.01 && $porcentaje <= 100) {
          $estrella5 = $estrella;
          $estrellaTexto = "<p style='color: #4ba92e;'>Sobresaliente</p>";
        }
        switch ($competencia->id_course) {
          case '2':
            $li_competencia .= "<div class='col-md-12 text-center'>";
              $li_competencia .= "<h4 style='color: #e66c00;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;
          case '5':
            $li_competencia .= "<div class='col-md-12 text-center'>";
              $li_competencia .= "<h4 style='color: #a344c7;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          case '8':
            $li_competencia .= "<div class='col-md-12'>";
              $li_competencia .= "<h4 style='color: #17a2b8;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          case '9':
            $li_competencia .= "<div class='col-md-12'>";
              $li_competencia .= "<h4 style='color: #fc6c3a;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          default:

          break;
        }
      }
    }else if ($course->id == 3){
      $sql_competencia ="SELECT tet.*, test_quiz.id_course,max_ponit, (select sum(val) as puntaje from mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id
      and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia where attempt_id =
      (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and mdl_munduaattemps.test_id = (tet.test_id)) and test_competencia.c_id = tet.c_id) as porcentaje
      FROM test_competencia as tet
      JOIN test_quiz on test_quiz.t_id = tet.test_id
      where id_course in (3) order by porcentaje desc";
      //var_dump($sql_competencia);
      $competencias_rh = $DB->get_recordset_sql($sql_competencia);
      //$li_competencia .= "<div class='col-md-12 text-center'><h3>Competencias</h3></div>";
      foreach ($competencias_rh as $key => $competencia) {
        //var_dump($competencia);

        $porcentaje = (100 / $competencia->max_ponit * $competencia->porcentaje);
        if ($porcentaje >= 0 && $porcentaje <= 20){
          $estrella1 = $estrella;
            $estrellaTexto = "<span style='color: #de0a0d;'>Insuficiente</span>";
            $href_a = "<a class='btn-suc' style='color:#082e9d;' target='_blank' href='/course/view.php?id=".$course->id."&section=".substr($competencia->name, 3, 2)."'><span title='Recuerda leer y aprovechar TODOS los materiales a disposición'> ¡Sigue trabajando en ello!</span> </a>";
        }elseif ($porcentaje >= 20.01 && $porcentaje <= 40) {
          $estrella2 = $estrella;
            $estrellaTexto = "<span style='color: #e66c00;'>Básica</span>";
            $href_a = "<a class='btn-suc' style='color:#082e9d;' target='_blank' href='/course/view.php?id=".$course->id."&section=".substr($competencia->name, 3, 2)."' title='Recuerda leer y aprovechar TODOS los materiales a disposición'><span> ¡Sigue trabajando en ello!</span> </a>";
        }elseif ($porcentaje >= 40.01 && $porcentaje <= 60) {
          $estrella3 = $estrella;
          $estrellaTexto = "<span style='color: #fee000;'>Aceptable</span>";
          $href_a = "<a class='btn-suc' style='color:#082e9d;' target='_blank' href='/course/view.php?id=".$course->id."&section=".substr($competencia->name, 3, 2)."' title='Recuerda leer y aprovechar TODOS los materiales a disposición'><span> ¡Sigue trabajando en ello! </span> </a>";
        }elseif ($porcentaje >= 60.01 && $porcentaje <= 80) {
          $estrella4 = $estrella;
          $estrellaTexto = "<span style='color: #dcdc01;'>Notable</span>";
        }elseif ($porcentaje >= 80.01 && $porcentaje <= 100) {
          $estrella5 = $estrella;
          $estrellaTexto = "<span style='color: #4ba92e;'>Sobresaliente</span>";
        }
        switch ($competencia->id_course) {

          case '3':
          //die(var_dump());

              $list_item .= "<li >".$competencia->name." ".$estrellaTexto.$href_a.$competencia->id_competencia."</li>";
          break;
          case '5':
            $li_competencia .= "<div class='col-md-12 text-center'>";
              $li_competencia .= "<h4 style='color: #a344c7;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          case '8':
            $li_competencia .= "<div class='col-md-12'>";
              $li_competencia .= "<h4 style='color: #17a2b8;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          case '9':
            $li_competencia .= "<div class='col-md-12'>";
              $li_competencia .= "<h4 style='color: #fc6c3a;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          default:

          break;
        }
      }

    }

      //var_dump($array_attemps_araña);
      //exit;

    }else{

      $sql_results_araña = "SELECT attempt_id,test_competencia.name,(select fecha from mdl_munduaattemps where a_id = attempt_id) as fecha, mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit ,ponderado_point FROM mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia
      where attempt_id = (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and test_id in (1)) group by attempt_id,(select fecha from mdl_munduaattemps
      where a_id = attempt_id),mdl_munduatest_questions.id_competencia,max_ponit,ponderado_point order by attempt_id,id_competencia";
      $todos_intentos_araña = $DB->get_recordset_sql($sql_results_araña);

      $sql_results_araña3 = "SELECT attempt_id,test_competencia.name,(select fecha from mdl_munduaattemps where a_id = attempt_id) as fecha, mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit ,ponderado_point FROM mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia
      where attempt_id = (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and test_id in (3)) group by attempt_id,(select fecha from mdl_munduaattemps
      where a_id = attempt_id),mdl_munduatest_questions.id_competencia,max_ponit,ponderado_point order by attempt_id,id_competencia";
      $todos_intentos_araña3 = $DB->get_recordset_sql($sql_results_araña3);

        //var_dump($todos_intentos_araña2);
      $array_attemps_araña =[];
      $array = "";
      $competencia_anterior = "";
      $attemp_id_anterior = "";
      foreach ($todos_intentos_araña as $key => $araña) {

        $array = "";
        $porcentaje = (100 / $araña->max_ponit * $araña->puntaje);

        $array .= $araña->name;
        $array .= ",'','',".$porcentaje;
        array_push($array_attemps_araña,explode(',',$array));

      }

      $sql_results_araña2 = "SELECT attempt_id,test_competencia.name,(select fecha from mdl_munduaattemps where a_id = attempt_id) as fecha, mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit ,ponderado_point FROM mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia
      where attempt_id = (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and test_id in (2)) group by attempt_id,(select fecha from mdl_munduaattemps
      where a_id = attempt_id),mdl_munduatest_questions.id_competencia,max_ponit,ponderado_point order by attempt_id,id_competencia";
      $todos_intentos_araña2 = $DB->get_recordset_sql($sql_results_araña2);

      $sql_competencia ="SELECT tet.*, test_quiz.id_course,max_ponit, (select sum(val) as puntaje from mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id
      and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia where attempt_id =
      (select max(a_id) from mdl_munduaattemps where user_id = {$USER->id} and mdl_munduaattemps.test_id = (tet.test_id)) and test_competencia.c_id = tet.c_id) as porcentaje
      FROM test_competencia as tet
      JOIN test_quiz on test_quiz.t_id = tet.test_id
      where id_course in (5,8,9) order by porcentaje desc";
      //var_dump($sql_competencia);
      $competencias_rh = $DB->get_recordset_sql($sql_competencia);
      $li_competencia .= "<div class='col-md-12 text-center'><h3>Competencias</h3></div>";
      foreach ($competencias_rh as $key => $competencia) {
        //var_dump($competencia);
        $porcentaje = (100 / $competencia->max_ponit * $competencia->porcentaje);
        if ($porcentaje >= 0 && $porcentaje <= 20){
          $estrella1 = $estrella;
            $estrellaTexto = "<p style='color: #de0a0d;'>Insuficiente</p>";
        }elseif ($porcentaje >= 20.01 && $porcentaje <= 40) {
          $estrella2 = $estrella;
            $estrellaTexto = "<p style='color: #e66c00;'>Básica</p>";
        }elseif ($porcentaje >= 40.01 && $porcentaje <= 60) {
          $estrella3 = $estrella;
          $estrellaTexto = "<p style='color: #fee000;'>Aceptable</p>";
        }elseif ($porcentaje >= 60.01 && $porcentaje <= 80) {
          $estrella4 = $estrella;
          $estrellaTexto = "<p style='color: #dcdc01;'>Notable</p>";
        }elseif ($porcentaje >= 80.01 && $porcentaje <= 100) {
          $estrella5 = $estrella;
          $estrellaTexto = "<p style='color: #4ba92e;'>Sobresaliente</p>";
        }
        switch ($competencia->id_course) {
          case '5':
            $li_competencia .= "<div class='col-md-12 text-center'>";
              $li_competencia .= "<h4 style='color: #a344c7;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          case '8':
            $li_competencia .= "<div class='col-md-12'>";
              $li_competencia .= "<h4 style='color: #17a2b8;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          case '9':
            $li_competencia .= "<div class='col-md-12'>";
              $li_competencia .= "<h4 style='color: #fc6c3a;'>".$competencia->name."</h4>";
              $li_competencia .= "<p>".$competencia->definicion." ".$estrellaTexto."</p>";
            $li_competencia .= "</div>";
          break;

          default:

          break;
        }
      }
      //exit;
      //die(var_dump($competencias_rh));
      //var_dump($todos_intentos_araña2);
      $array_attemps_araña2 =[];
      $array = "";
      $competencia_anterior = "";
      $attemp_id_anterior = "";
      foreach ($todos_intentos_araña2 as $key => $araña2) {
        $array = "";
        $porcentaje = (100 / $araña2->max_ponit * $araña2->puntaje);

        $array .= $araña2->name;
        $array .= ",'',".$porcentaje.",''";
        array_push($array_attemps_araña,explode(',',$array));
        //var_dump($array);

        //var_dump("anterior:".$competencia_anterior, "nuevo:".$at->id_competencia."<br>");
        $competencia_anterior = $araña2->id_competencia;
        $attemp_id_anterior = $araña2->attempt_id;
      }
      //var_dump($array_attemps_araña2);
      //exit;
      $array_attemps_araña3 =[];
      $array = "";
      $competencia_anterior = "";
      $attemp_id_anterior = "";
      foreach ($todos_intentos_araña3 as $key => $araña3) {
        //var_dump($araña3->puntaje);
        $array = "";
        $porcentaje = (100 / $araña3->max_ponit * $araña3->puntaje);

        $array .= $araña3->name;

        $array .= ",".$porcentaje.",'',''";
        array_push($array_attemps_araña,explode(',',$array));

      }
      //die(var_dump($array_attemps_araña));
    }

    $sqluser = "SELECT * FROM {$CFG->prefix}user where id in ({$USER->id}) limit 1";
    $users = $DB->get_records_sql($sqluser);
    $video_true = 1;
    switch ($course->id) {
      case '1':
        $class_style = "#7e17a5";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/2- ¿Qué es proyecto de Vida Y ¿Cómo funcionan los bloques.mp4";
        break;
      case '2':
        $url_logo = "https://empleabilidad.redmundua.com/cook/blank.php?fin=1";
        $class_style = "#fc6c3a";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/2- ¿Qué es proyecto de Vida Y ¿Cómo funcionan los bloques.mp4";
        // code...

        //die(var_dump($users->));
        foreach ($users as $key => $us) {
          $video_true = (!$us->vid7 ? 1 : 0);
        }
        if ($video_true) {
          $updateuser = "UPDATE {$CFG->prefix}user SET vid7 = 1 where id in ({$USER->id})";
          $DB->execute($updateuser);
        }
        break;
      case '3':
        $url_logo = "https://empleabilidad.redmundua.com/cook/blank.php?fin=1";
        $class_style = "#17a2b8";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/3- Momento de elegir, ¿Qué programa iniciar.mp4";
        // code...
        foreach ($users as $key => $us) {
          $video_true = (!$us->vid6 ? 1 : 0);
        }
        if ($video_true) {
          $updateuser = "UPDATE {$CFG->prefix}user SET vid6 = 1 where id in ({$USER->id})";
          $DB->execute($updateuser);
        }
        break;
      case '4':
        $class_style = "#a344c7";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/2- ¿Qué es proyecto de Vida Y ¿Cómo funcionan los bloques.mp4";
        // code...
        break;
      case '5':
        $url_logo = "https://empleabilidad.redmundua.com/cook/blank.php?sub_cat=2";
        $class_style = "#a344c7";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/2- ¿Qué es proyecto de Vida Y ¿Cómo funcionan los bloques.mp4";
        // code...
        foreach ($users as $key => $us) {
          $video_true = (!$us->vid5 ? 1 : 0);
        }
        if ($video_true) {
          $updateuser = "UPDATE {$CFG->prefix}user SET vid5 = 1 where id in ({$USER->id})";
          $DB->execute($updateuser);
        }
        break;
      case '6':
        $url_logo = "https://empleabilidad.redmundua.com/cook/blank.php?sub_cat=2";
        $class_style = "#24a137";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/3- Momento de elegir, ¿Qué programa iniciar.mp4";
        // code...
        foreach ($users as $key => $us) {
          $video_true = (!$us->vid4 ? 1 : 0);
        }
        if ($video_true) {
          $updateuser = "UPDATE {$CFG->prefix}user SET vid4 = 1 where id in ({$USER->id})";
          $DB->execute($updateuser);
        }
        break;
      case '7':
        $class_style = "#fc6c3a";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/2- ¿Qué es proyecto de Vida Y ¿Cómo funcionan los bloques.mp4";
        // code...
        break;
      case '8':
        $url_logo = "https://empleabilidad.redmundua.com/cook/blank.php?sub_cat=2";
        $class_style = "#17a2b8";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/3- Momento de elegir, ¿Qué programa iniciar.mp4";
        // code...
        foreach ($users as $key => $us) {
          $video_true = (!$us->vid3 ? 1 : 0);
        }
        if ($video_true) {
          $updateuser = "UPDATE {$CFG->prefix}user SET vid3 = 1 where id in ({$USER->id})";
          $DB->execute($updateuser);
        }
        break;
      case '9':
        $url_logo = "https://empleabilidad.redmundua.com/cook/blank.php?sub_cat=2";
        $class_style = "#fc6c3a";
        $url_video ="https://empleabilidad.redmundua.com/theme/moove/pix/3- Momento de elegir, ¿Qué programa iniciar.mp4";
        // code...
        foreach ($users as $key => $us) {
          $video_true = (!$us->vid2 ? 1 : 0);
        }
        if ($video_true) {
          $updateuser = "UPDATE {$CFG->prefix}user SET vid2 = 1 where id in ({$USER->id})";
          $DB->execute($updateuser);
        }
        break;

      default:
        // code...
        break;
    }
    //echo $OUTPUT->footer();

  ?>
  <HTML>
  <head>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.5.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="shortcut icon" href="https://empleabilidad.redmundua.com/pluginfile.php/1/theme_moove/favicon/1623988842/favicon.ico">
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/themes/sea.min.js"></script>
    <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
    <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
    <script src="https://cdn.anychart.com/releases/v8/js/anychart-radar.min.js"></script>
    <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" type="text/css" rel="stylesheet">
    <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" type="text/css" rel="stylesheet">
    <style type="text/css">

      html,
      body,
      #container, #container2 {
        width: 100%;
        height: 600PX;
        margin: 0;
        padding: 0;
      }

  </style>
  </head>
  <BODY>
    <div class="" style="box-shadow: 0 0 20px rgb(0 0 0 / 10%);
      background-color: #fff;">
      <nav class="row fixed-top navbar navbar-light navbar-expand moodle-has-zindex" style="background: white !important;">
        <div class="col-md-2 text-center">
          <a href="/cook/mapa.php?id=<?= $course->id ?>"><img style="width: 80%;" src="//empleabilidad.redmundua.com/pluginfile.php/1/theme_moove/logo/1623988842/LogoMundua_3.png"/></a>
        </div>
        <div class="col-md-6">

        </div>
        <div class="col-md-1">
          <!-- Button trigger modal -->
          <a href="/cook/test.php?id=<?= $course->id ?>" type="button" style="width: 100%; color: black; background: white !important; border-radius: 7px; border: solid 1px gray;" class="btn btn-default btn-video2">
            <i class="fab fa-tumblr"></i>
          </a>
        </div>
        <div class="col-md-1">
          <!-- Button trigger modal -->
          <button type="button" style="width: 100%; background: white !important; border-radius: 7px; border: solid 1px gray;" class="btn btn-default btn-video2" data-toggle="modal" data-target="#exampleModalInfo">
            <i class="fas fa-info"></i>
          </button>
        </div>
        <div class="col-md-1">
          <!-- Button trigger modal -->
          <button type="button" style="width: 100%; background: white !important; border-radius: 7px; border: solid 1px gray;" class="btn btn-default btn-video" data-toggle="modal" data-target="#exampleModalLong">
            <i class="fa fa-play"></i>
          </button>
        </div>
        <div class="col-md-1" style="border-left: 1px dashed gray;">
          <div class="dropdown">
            <a class="btn btn-default dropdown-toggle" style="background: white !important;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span  class="userbutton"><span class="usertext"></span><span class="avatars"><span class="avatar current"><img style="border-radius: 50%;"src="https://empleabilidad.redmundua.com/theme/image.php/moove/core/1623988842/u/f2" class="userpicture defaultuserpic" width="35" height="35" alt=""></span></span></span>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="https://empleabilidad.redmundua.com/login/logout.php?sesskey=tWxdkn8lRi">Salir</a>
            </div>
          </div>
        </div>
      </nav>
      <div class="row" style="background-size: cover; background-image: url('https://empleabilidad.redmundua.com/theme/moove/pix/h3.jpg'); height: calc(30%); background-position: right; background-size: cover;">
        <div class="col-md-12">
          <div class="row text-center">
          </div>
        </div>
      </div>
      <div class="row" style="height: auto;">
        <div class="col-md-12 text-center">
          <div class="row">
            <div class="col-md-11" style="margin:auto;" style="padding: 20px;">
              <h4 style="padding:15px">Resultados de test de competencias</h4>
              <h5 style="padding-bottom:15px"><?= $course->fullname ?></h5>
              <div class="row">
                <div class="col-md-4">
                  <h5>Competencias</h5>
                </div>
                <div class="col-md-8">
                  <div class="row">
                    <div class="col-md-2">
                      <h5>Insuficiente</h5>

                    </div>
                    <div class="col-md-2">
                      <h5>Básica</h5>

                    </div>
                    <div class="col-md-2">
                      <h5>Aceptable</h5>

                    </div>
                    <div class="col-md-2">
                      <h5>Notable</h5>

                    </div>
                    <div class="col-md-2">
                      <h5>Sobresaliente</h5>

                    </div>
                  </div>
                </div>
              </div>
                <?= $rect ?>
                <h5 style="padding:15px">Historico de intentos</h5>
                <div id="container"></div>
                <h5 style="padding:15px">Gráfico de radar</h5>
                <div class="row">

                  <div class="col-md-<?php echo ($course->id == 3 ? '12' : ($course->id == 5 || $course->id == 8 || $course->id == 9 ? '12' : '12')) ?>">
                    <div id="container2"></div>
                  </div>
                  <div class="col-md-12 text-center" style="<?= $hidden ?>">
                    <ul style="text-align: left; list-style: none;">
                        <?= $list_item ?>
                    </ul>

                  </div>
                </div>

                <?= $li_competencia ?>

                <?= $competencia_texto ?>


                <div class='row'><div style='margin-left: -30px; margin-top: 30px; margin-bottom: 30px;' class='col-md-12' >
                  <a href="/cook/test.php?id=<?=$course->id?>" class='btn btn-danger' style='float: right; padding-left:15px; padding-right:15px'>Volverlo a intentar</a>
                  <a href="/cook/mapa.php?id=<?=$course->id?>" class='btn btn-light' style='float: right; padding-left:15px; padding-right:15px'>Regresar</a>
                </div></div>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="width: 100%;">
              <video src="<?=$url_video?>" controls width="100%" height="500" autoplay="true"
    muted="muted" >
              </video>
              <!-- <iframe width="100%" height="500" src="https://www.youtube.com/embed/xcJtL7QggTI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->
            </div>
          </div>
        </div>
      </div>
      <!--other modal -->
      <div class="modal fade" id="exampleModalInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-video" role="document">
          <div class="modal-content" style="background: white !important;">
            <div class="modal-header">
            <b><?= $titulo ?></b>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span style="color: black !important;" aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="width: 100%;">
              <p>

                <?= $texto ?>

              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <style media="screen">
      .color-white{
        color: white;
      }
      .font-big{
        font-size: 50px;
        font-weight: bold;;
      }
      .modal-content{
        background: rgba(0,0,0,.7);
      }
      .close{
        color: white;
      }
      .modal.show .modal-dialog {
        -webkit-transform: translate(0,0);
        transform: translate(0,5%) !important;
      }
    </style>
    <style>

      li:not(:last-child) .diplome {
          -webkit-transform: translateX(17%);
          -moz-transform: translateX(17%);
          -ms-transform: translateX(17%);
          -o-transform: translateX(17%);
          transform: translateX(17%);
      }
      .modal-dialog {
        max-width: 70%;
        margin: 1.75rem auto;
      }

      .modal-video{
        transform: translate(0%,5%) !important;

      }
      .btn-suc {width:6em}
      /*.btn-suc:hover span {display:none}*/
      /*.btn-suc:hover:before {content:" Recuerda leer y aprovechar TODOS los materiales a disposición"; color: #082e9d;}*/
      </style>

  <script>

      anychart.onDocumentReady(function () {
	     // set chart theme
       //anychart.theme('sea');
      // create line chart
      var chart = anychart.line();

      // set chart padding
    //  chart.padding([10, 20, 5, 20]);

      // turn on chart animation
      chart.animation(true);

      // turn on the crosshair
      chart.crosshair(true);

      // set chart title text settings
      chart.title('Competencias');

      // set y axis title
      chart.yAxis().title('Porcentaje');

      // create logarithmic scale
      var logScale = anychart.scales.log();
      logScale.minimum(1).maximum(100).ticks().interval(10);
      chart.yScale().minimum(0).maximum(100).ticks({ interval: 20 });

      // set scale for the chart, this scale will be used in all scale dependent entries such axes, grids, etc
      //chart.yScale(logScale);
      var dataattemps = <?= json_encode($array_attemps); ?>;
      var series2 = <?= json_encode($array_series); ?>
      //alert(dataattemps);
      // create data set on our data,also we can pud data directly to series
      var dataSet = anychart.data.set([
        ['Monday', '75', '100', '100'],
        ['Tuesday', '100', '50', '100'],
        ['Wednesday', '70', '70', '80'],
        ['Thursday', '100', '100', '90'],
        ['Friday', '85', '87 ', '92'],
        ['Saturday', '90', '45', '100'],
        ['Sunday', '77', '61', '100']
      ]);

      var dataSet = anychart.data.set(dataattemps);
       var series;
       var i = 1;
      for(var k in series2) {
         var firstSeriesData = dataSet.mapAs({ x: 0, value: i });

         // setup first series
         series = chart.line(firstSeriesData);
         series.name(series2[k]);
         // enable series data labels
         series.labels().enabled(true).anchor('left-bottom').padding(5);
         // enable series markers
         series.markers(true);
         i++;
      }
      // map data for the first series,take value from first column of data set


      // map data for the second series,take value from second column of data set
      //var secondSeriesData = dataSet.mapAs({ x: 0, value: 2 });

      // map data for the third series, take x from the zero column and value from the third column of data set
      //var thirdSeriesData = dataSet.mapAs({ x: 0, value: 3 });

      // temp variable to store series instance
      //var series;

      // setup first series
      //series = chart.line(firstSeriesData);
      //series.name('Competencia 1');
      // enable series data labels
      //series.labels().enabled(true).anchor('left-bottom').padding(5);
      // enable series markers
      //series.markers(true);

      // setup second series
      //series = chart.line(secondSeriesData);
      //series.name('Competencia 2');
      // enable series data labels
      //series.labels().enabled(true).anchor('left-bottom').padding(5);
      // enable series markers
      //series.markers(true);

      // setup third series
      //series = chart.line(thirdSeriesData);
      //series.name('Competencia 3');
      // enable series data labels
      //series.labels().enabled(true).anchor('left-bottom').padding(5);
      // enable series markers
      //series.markers(true);

      // turn the legend on
      chart.legend().enabled(true).fontSize(13).padding([0, 0, 20, 0]);

      // set container for the chart and define padding
      chart.container('container');
      // initiate chart drawing
      chart.draw();
    });

</script>

<script>

    anychart.onDocumentReady(function () {
      // create data set on our data
      var dataattemps = <?= json_encode($array_attemps_araña); ?>;
      var dataSet = anychart.data.set([
        ['GDP',0,0,89],
        ['GDP Real Growth Rate',0,0,46],
        ['Infant Mortality',0,90,0],
        ['Life Expectancy',0,85,0],
        ['Population',0,45,0],
        ['Area', 25,0,0],
        ['Density',90,0,0],
        ['Population Growth Rate',90,0,0]
      ]);
      var dataSet = anychart.data.set(dataattemps);
      // map data for the first series, take x from the zero column and value from the first column of data set
      var data1 = dataSet.mapAs({ x: 0, value: 1 });
      // map data for the second series, take x from the zero column and value from the second column of data set
      var data2 = dataSet.mapAs({ x: 0, value: 2 });
      // map data for the third series, take x from the zero column and value from the third column of data set
      var data3 = dataSet.mapAs({ x: 0, value: 3 });

      // create radar chart
      var chart = anychart.radar();

      // set chart title text settings
      chart
        .title('Etapas')
        // set chart legend
        .legend(true);

      // set chart padding settings
      chart.padding().bottom(70);

      // set chart yScale settings
      chart.yScale().minimum(0).maximum(100).ticks({ interval: 20 });

      // create chart label with description
      chart
        .label()
        .text(
          '' +
          '' +
          ''
        )
        .anchor('center-bottom')
        .position('center-bottom')
        .fontWeight('normal')
        .fontSize(11)
        .fontFamily('tahoma')
        .fontColor('rgb(35,35,35)')
        .offsetY(15);

      var color1 = [255, 0, 0];
      var color2 = [0, 0, 255];
      var color3 = [0, 0, 255];
      // create first series with mapped data
      //chart.line(data1).name('PREVIO EMPRENDIZAJE').markers(true);
      if (<?= $course->id ?> == 5 || <?= $course->id ?> == 8 || <?= $course->id ?> == 9){
        chart.line(data1).name('PREPARACIÓN EMPRENDIZAJE').markers(true).stroke('3, orange');
        // create second series with mapped data
        chart.line(data2).name('PREPARACIÓN ALTERNANCIA').markers(true).stroke('3, #17a2b8');;
        // create third series with mapped data
        chart.line(data3).name('PROYECTO DE VIDA').markers(true).stroke('3, #a344c7');;
      }else if (<?= $course->id ?> == 2){
        chart.line(data1).name('EMPRENDIZAJE').markers(true).stroke('3, orange');
      }else{
        chart.line(data1).name('ALTERNANCIA').markers(true).stroke('3, #17a2b8');
      }

      // set tooltip format
      chart.tooltip().format('Valor: {%Value}{decimalsCount: 2}');

      // set container id for the chart
      chart.container('container2');
      // initiate chart drawing
      chart.draw();
    });

</script>
  </BODY>
  </HTML>

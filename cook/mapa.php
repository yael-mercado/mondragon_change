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

    $num_sec = count($sections);
    $html_row = "<div class='row' style='border-bottom: 1px solid;'>";
    $html_col_1 = "<div class='col-md-1' style='padding: 20%; height: 50px;'>";
    $html_icon = '<i class="fa fa-4x fa-chalkboard"></i>';
    $html_end = "</div>";

    $porcentaje = 100 / (count($sections) > 8 ? 8 : (count($sections) == 2 ? 2 : count($sections)-1));

    if (count($sections) > 8){
      $html_btn = '<div class="row" style="padding-bottom: 25px;">
        <div class="col-md-12">
          <button class="btn btn-light float-left prev"><i class="fa fa-arrow-circle-left"></i> Anterior</button>
          <button class="btn btn-light float-right next">Siguiente <i class="fa fa-arrow-alt-circle-right"></i></button>
        </div>
      </div>';
    }
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
    $coursecontext = get_context_instance(CONTEXT_COURSE, $COURSE->id);
    if(has_capability('moodle/site:config', $coursecontext)) {
      $url_test = "/cook/test_admin.php?id=".$COURSE->id;
    } else {
      $url_test = "/cook/test.php?id=".$COURSE->id;
    }
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
  </head>
  <BODY>
    <div class="" style="box-shadow: 0 0 20px rgb(0 0 0 / 10%);
      background-color: #fff;">
      <nav class="row fixed-top navbar navbar-light navbar-expand moodle-has-zindex" style="background: white !important;">
        <div class="col-md-2 text-center">
          <a href="<?= $url_logo ?>"><img style="width: 80%;" src="//empleabilidad.redmundua.com/pluginfile.php/1/theme_moove/logo/1623988842/LogoMundua_3.png"/></a>
        </div>
        <div class="col-md-6">

        </div>
        <div class="col-md-1">
          <!-- Button trigger modal -->
          <a href="<?= $url_test ?>" type="button" style="color: black;width: 100%; background: white !important; border-radius: 7px; border: solid 1px gray; display: <?= ($course->id == 6 ? 'none': 'block') ?> !important;" class="btn btn-default btn-video2">
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
      <div class="row" style="background-size: cover; background-image: url('//empleabilidad.redmundua.com/theme/moove/pix/Headers-Plataforma2.jpg'); height: calc(100%);">
        <div class="col-md-12">
          <div class="row text-center">
            <div class="col-md-12 text-center" style="margin: auto;
              position: absolute;
              top: 55%;
              left: 50%;
              transform: translate(-50%,-50%);">
              <div class="row">
                <div class="col-md-12 text-center">
                  <h1 style="color: black;"><span style="color: <?= $class_style ?>"><?= $course->fullname ?></span><br> HOJA DE RUTA</h1>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-10" style="width: 100%; background: rgba(0,0,0,0.8);
                  padding-left: 60px;
                  padding-right: 60px;
                  border-radius: 30px;">
                  <ol class="oltime" style=" background: -moz-linear-gradient(left, #000000 0%, #000000 1033.3333333333%, #000000 1033.3333333333%, #000000 100%);
                                             background: -webkit-linear-gradient(left, #000000 1033.3333333333%,#000000 1033.3333333333%,#000000 50%,#00000 100%);
                                             background: linear-gradient(to right, #000000 0%, #000000 1033.3333333333%,#000000 1033.3333333333%,#000000 100%); ">
                    <?= $li_html ?>
                  </ol>
                  <?= $html_btn ?>
                </div>
                <div class="col-md-1"></div>
              </div>
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
      .btn-previo{
        background-color: #7e17a5;
        border-color: #7e17a5;
        color: white;
      }
      .btn-emp{
        background-color: #fc6c3a;
        border-color: #fc6c3a;
        color: white;
      }
      .btn-elecciones{
        background-color: #24a137;
        border-color: #24a137;
        color: white;
      }
      .modal.show .modal-dialog {
        -webkit-transform: translate(0,0);
        transform: translate(0,5%) !important;
      }
    </style>
    <style>
    li .diplome {
        position: absolute;
        top: -20px;
        right: 0px;
        color: #fff;
        width: 70px;
    }
    li .diplome-ultimo {
        position: absolute;
        top: -20px;
        right: -9px;
        color: #fff;
        width: 70px;
    }
    li .dip-title {
        position: absolute;
        top: 40px;
        right: -10%;
        color: #000;
        width: 120px;
        color: white;
    }

    li .dip-title {
        display: block;;
        color: white;
    }
    .fa-check-circle{
      position: absolute !important;
      top: 115% !important;
      left: 55% !important;
    }
    .fa-lock{
      position: absolute !important;
      top: 115% !important;
      left: 55% !important;
    }
    li .point {
    content: '';
    top: -75%;
    right: 0;
    display: block;
    width: 62px;
    height: 50px;
    border: 4px solid transparent;
    border-radius: 10px;
    background: transparent;
    position: absolute;
    text-align: right;
    }
    .cube{
      color: <?= $class_style ?>;
      -webkit-text-stroke-width: 2px;
      -webkit-text-stroke-color: <?= $class_style ?>;
      border-radius: 50%;
      height: 60PX;
      width: 1em;
    }
    .cube-completed{
      color: <?= $class_style ?>;
      -webkit-text-stroke-width: 2px;
      -webkit-text-stroke-color: <?= $class_style ?>;
      background: green;
      border-radius: 50%;
      height: 60PX;
      width: 1em;
    }
    .white-cube{
      color: #a3a4a5;
    -webkit-text-stroke-width: 2px;
    -webkit-text-stroke-color: #a3a4a5;
    border-radius: 50%;
    height: 60PX;
    width: 1em;
    }
  .oltime {
          position: relative;
          display: block;
          margin: 100px auto;
          height: 4px;
          -webkit-padding-start: 0px!important;
          width: 100%;
      }
      .oltime::before {
          content: '';
          position: absolute;
          top: -8px;
          display: block;
          width: 0;
          height: 0;
          border-radius: 10px;
          border: 10px solid <?= $class_style ?>;
          left: -5px;
      }
      .litime {
        position: relative;
        display: inline-block;
        float: left;
        font: bold 14px arial;
        height: 50px;
      }
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
      </style>
    <script>
    $(document).ready(function (){
      $('.close').click(function (){
        $('video').trigger('pause');
      })
      if (<?= $video_true ?>){
        $('.btn-video').click();
        $('video').trigger('click');
        $('video').trigger('play');
      }
      var ultima = 8;
      var ocultar = 1;
      $('.prev').css('display', 'none');

      $('.next').click(function (){

        if (ultima < <?= count($sections)-2 ?>){
          $('.prev').css('display', 'block');
        }else{
          $('.next').css('display', 'none');
        }

        ultima++;
        $('.t'+ultima).css("display", "block");
        $('.t'+ocultar).css("display", "none");
        ocultar++;
      });

      $('.prev').click(function (){

        if (ultima <= <?= count($sections) ?>){
          $('.next').css('display', 'block');
        }else {
          $('.next').css('display', 'none');
        }

        if (ocultar > 2){
          $('.prev').css('display', 'block');
        }else{
          $('.prev').css('display', 'none');
        }


        ocultar--;
        $('.t'+ultima).css("display", "none");
        $('.t'+ocultar).css("display", "block");
        ultima--;
      });

      /*$( ".fa-circle" ).mouseout(function() {
        var id = $(this).prop('id');
        $( ".dip-title-"+id).css('display', 'none');
      });*/

      /*$( ".diplome" ).mouseout(function() {
        var id = $(this).prop('id');
        $( ".dip-title-"+id).css('display', 'none');
      });*/

      /*$( ".fa-circle" ).mouseover(function() {
        var id = $(this).prop('id');
        console.log(id);
        $( ".dip-title-"+id ).css('display', 'block');
      });*/

      /*$( ".diplome" ).mouseover(function() {
        var id = $(this).prop('id');
        console.log(id);
        $( ".dip-title-"+id ).css('display', 'block');
      });*/
    })
    </script>
  </BODY>
  </HTML>

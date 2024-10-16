<?php
require_once(dirname(__FILE__) . '/../config.php');

require_once('../course/lib.php');
require_once($CFG->libdir.'/completionlib.php');
$PAGE->set_context(get_context_instance(CONTEXT_SYSTEM));
$PAGE->set_url('/cook/blank.php');
$PAGE->requires->js('/cook/alert.js');
include('/config.php');
if (!isloggedin()) {
  $loginurl = new moodle_url('/login/index.php');
  redirect($loginurl);
}

global $DB, $CFG, $USER, $OUTPUT;

// Obtener el tema actual
$theme = $PAGE->theme->name;
$site_url = $CFG->wwwroot;
// Generar la URL del logo
$logo = $site_url.'/pluginfile.php/1/core_admin/logocompact/300x300/1724804791/Log%20Formaci%C3%B3n.png';
//$user_picture = $OUTPUT->user_picture($user, array('size' => 100));

//die(var_dump($logo));
$fin_previo = (int)($_GET['fin'] ? $_GET['fin'] : 0);
$sub_cat = (int)($_GET['sub_cat'] ? $_GET['sub_cat'] : 0);
$sqluser = "SELECT * FROM {$CFG->prefix}user where id in ({$USER->id}) limit 1";
$users = $DB->get_records_sql($sqluser);
$video_true = 1;

/*foreach ($users as $key => $us) {
  $video_true = (!$us->vid1 ? 1 : 0);
}

if ($video_true) {
  $updateuser = "UPDATE {$CFG->prefix}user SET vid1 = 1 where id in ({$USER->id})";
  $DB->execute($updateuser);
}*/

if ($fin_previo && !$sub_cat){
  $sql = "SELECT * FROM {$CFG->prefix}course where id in (2,3)";
  $courses = $DB->get_records_sql($sql);
}else if ($sub_cat){
  $sql = "SELECT * FROM {$CFG->prefix}course where category in ($sub_cat, 5) ";
  $courses = $DB->get_records_sql($sql);
}else{
  $courses = $DB->get_records('course', ['showactivitydates' => '1', 'id' => '4']);
  $courses = $DB->get_records('course_categories', ['id' => '2']);
}
$i = 0;
$completed =0;
$html_content .= "<div class='row' style='padding-top: 10px;'>";
$html .= "<div class='row' style=''>";
foreach ($courses as $key => $course) {
  $i++;
  //Revisar que este completo el curso
  $sections = $DB->get_records('course_sections', array('course' => $course->id), 'section', '*');

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

    $next_active = $completed;
  }

  //validaciones del curso
  if ($fin_previo){
    $class = '6';
    $url = "/cook/mapa.php?id=";
  }else if ($sub_cat){
    $class = '3';
    $url = "/cook/mapa.php?id=";
  }else{
    $class = '6';
    $html .= "<div class='col-md-3'>";
    $html .= "</div>";
    $url = "/cook/blank.php?sub_cat=";
  }

  if ($fin_previo){
    $url_logo = $site_url."/cook/blank.php?sub_cat=2";
  }
  if($sub_cat){
     $url_logo = $site_url."/cook/blank.php";
  }

  switch ($course->id) {
    case '1':
      $class_style = "previo";
      $etapa = 1;
      break;
    case '2':

      if (!$sub_cat && !$fin_previo){
        $class_style = "previo";
      }else{
        $class_style = "emp";
      }

      if ($completed == 0){
        $desbloqueo = 1;
        $completed = 1;
      }else{
        $desbloqueo = 0;
        $completed = 0;
      }
      $etapa = 3;
      $class_content = "0";
      // code...
      break;
    case '3':
      $class_style = "info";
      $class_content = "0";
      $etapa = 3;
      // code...
      break;
    case '4':
      $class_style = "previo";
      $etapa = 1;
      // code...
      break;
    case '5':
      $class_style = "previo";
      $class_content = "3";
      $etapa = 1;
      // code...
      break;
    case '6':
      $class_style = "elecciones";
      $class_content = "3";
      $etapa = 2;
      // code...
      break;
    case '7':
      $class_style = "emp";
      $etapa = 3;
      // code...
      break;
    case '8':
      $etapa = 3;
      $class_style = "info";
        $class_content = "6";
      $next_bloque = 0;
      if ($completed == 0){
        $desbloqueo = 1;
        $completed = 1;
      }else{
        $desbloqueo = 0;
        $completed = 0;
      }

      // code...
      break;
    case '9':
      $etapa = 3;
      $class_style = "emp";
      $class_content = "0";
      // code...
      break;

    default:
      // code...
      break;
  }

    $html_content .= "<div class='col-md-".$class_content." '>".($sub_cat && $class_content ? "<span style='color: white;'>Etapa ".$etapa."</span><br><span style='border-left: thick solid #ffffff;'></span>" : "")."</div>";
    $html .= "<div class='col-md-".$class." '>";
    //backgroun negro de bloqueo
    //$html .= "<div class='".($next_bloque == 1 ? 'bloqueo' : '')."'>";
    $html .= "<div class='".($next_bloque == 1 ? '' : '')."'>";
    if ($next_bloque){
      //candado de bloqueo
      /*$html .= '<div class="fa  block" style="display:none;">
                  <i class="fa fa-lock"></i>
                </div>';*/
    }


    if ($course->id == 8 || $course->id == 9){
      $course_fullname = str_replace(" ","<br>",$course->fullname);
      $padding = "padding: 20px;";
    }else if ($course->id == 5 || $course->id == 6){
      $course_fullname = $course->fullname;
      $padding = "    padding-top: 17%;
    padding-bottom: 17%;";

  }else{
    $course_fullname = $course->fullname;
    $padding = "padding: 20px;";
  }
    //var_dump($course_fullname);

    $html .= "<a href='".$url.$course->id."' style='width: 100%; ".$padding." ' type='button' class='btn btn-".$class_style."'><span>".($sub_cat ? ($course_fullname ? $course_fullname : $course->name) : ($course->name ? $course->name : $course_fullname) )."</span></a>";
    $html .= "</div>";
    if ($completed  == 1 && $desbloqueo == 0) {
      $html .= '<div class="" style="    background: rgb(0 0 0 / 70%);
                  color: #73c78a;
                  border-radius: 0px 0px 20px 20px;">
                  Completado <i class="fa fa-check-circle" style="color:green;"></i>
                </div>';
    }
    $html .= "</div>";
    $next_bloque = ($completed != 1 ? 1 : 0);
}
  //exit;
$html .= "</div>";
$html_content .= "</div>";
$html .= "</div>";

if ($sub_cat || $fin_previo){
  $btn_des = '<a class="btn btn-success" href="'.(!$fin_previo ? $site_url.'/cook/blank.php?fin=1' : $site_url.'/cook/blank.php?sub_cat=2').'">'.(!$fin_previo ? 'Desarrollo de cursos' : 'Regresar') .'</a>';
}
?>
<HTML>
<head>
  <link rel="shortcut icon" href="/pluginfile.php/1/theme_moove/favicon/1623988842/favicon.ico">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
</head>
<BODY>
  <div class="" style="box-shadow: 0 0 20px rgb(0 0 0 / 10%);
    background-color: #fff;">
    <nav class="row fixed-top navbar navbar-light navbar-expand moodle-has-zindex" style="background: white !important;">
      <div class="col-md-2 text-center">
        <a href="<?= $url_logo ?>"><img style="width: 80%;" src="<?= $logo ?>"/></a>
      </div>
      <div class="col-md-7">

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
            <span  class="userbutton"><span class="usertext"></span><span class="avatars"><span class="avatar current"><img style="border-radius: 50%;"src="<?= $user_picture ?>" class="userpicture defaultuserpic" width="35" height="35" alt=""></span></span></span>
          </a>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="/login/logout.php?sesskey=tWxdkn8lRi">Salir</a>
          </div>
        </div>
      </div>
    </nav>
    <div class="row" style="background-position-y: center; background-size: cover; background-image: url('//empleabilidad.redmundua.com/theme/moove/pix/Headers-Plataforma1.jpg'); height: calc(100%);">
      <div class="col-md-12">
        <div class="row text-center">
          <div class="col-md-8 text-center" style="margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%); background-color: rgba(0,0,0,0.8); padding: 20px;">

              <?php echo (!$fin_previo ? '<h1 class="color-white font-big">Bienvenidos al</h1>
              <h1 class="color-white font-big">Programa de Empleabilidad</h1>

              <h5 class="color-white">Plataforma de Estudiante Moodle</h2>' : '<h1 class="color-white">Es el momento de elegir:</h1>')?>
              <?= $btn_des ?>
              <?= $html_content ?>
              <?= $html ?>

          </div>

        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog modal-video" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="width: 100%;">

              <video class="video" src="/theme/moove/pix/video_intro.mp4" controls width="100%" height="500" autoplay="true"
    muted="muted">
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
          <b>¿Dónde estás?</b>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span style="color: black !important;" aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="width: 100%;">
            <p>
Bienvenido al programa de empleabilidad de la red Mundua. Un programa diseñado específicamente para tu desarrollo y adquisición de competencias personales y profesionales para el empleo.

Mediante este programa, tendrás la oportunidad de prepararte profesionalmente durante al menos un año, en el camino que más te apasione y te impulse hacia el cumplimiento de tu proyecto de vida. <br><br>

¿Qué vas a ver? <br><br>

Empezarás por un programa general dividido en 3 etapas clave antes de iniciar la hoja de ruta del programa específico que decidas completar.

Iniciarás con la etapa de Proyecto de vida para definir qué quieres hacer profesionalmente, seguirás con la de elección para conocer las opciones, y te prepararás por último para emprender, trabajar en una empresa o incluso ambos si lo deseas.


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
    .modal-dialog {
      max-width: 70%;
      margin: 1.75rem auto;
    }

    .modal-video{
      transform: translate(0%,5%) !important;

    }

    .bloqueo::before {
      margin-left: 15px;
      margin-right: 15px;
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.7);
    }
    .block {
        display: flex !important;
        z-index: 3;
        color: white;
        font-size: 2em;
        text-align: center;
        top: 50%;
        transform: translate(-50%, -50%);
        left: 50%;
        position: absolute;
    }

  </style>
  <script>
  $(document).ready(function (){
    if (<?= $video_true ?>){
      $('.btn-video').click();
      $('video').trigger('click');
      $('video').trigger('play');
    }

    $('.close').click(function (){
      $('video').trigger('pause');
    })
  })
  </script>
</BODY>
</HTML>

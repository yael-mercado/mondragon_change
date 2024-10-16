<?php

//  Display the course home page.
    require_once('../config.php');
    global $DB, $CFG, $USER;
    $user_id = $USER->id;
    $test_id = $_POST['test_id'];
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
      //die(var_dump($identificador));
    global $DB, $CFG, $USER;
    $identificador = $_GET['id'];

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
    $course_id = $_GET['id'];

      $sql_puntuación = "";

      $user_id_view = $USER->id;
      if ($_GET['user_id']){
        $user_id_view = ($_GET['user_id'] ? $_GET['user_id'] : $USER->id);
      }

      //$user_info = $DB->get_record('user', array('id' => $user_id_view), '*', MUST_EXIST);
      //die(var_dump($user_info));
      header("Pragma: public");
      header("Expires: 0");
      $filename = $course->fullname.$user_id_view.".xls";
      header("Content-type: application/x-msdownload");
      header("Content-Disposition: attachment; filename=$filename");
      header("Pragma: no-cache");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

      $sql_results = "SELECT user_id, attempt_id,test_competencia.name,(select fecha from mdl_munduaattemps where a_id = attempt_id) as fecha, mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit ,ponderado_point FROM mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia
      where attempt_id in (select max(a_id) from mdl_munduaattemps where test_id = (select t_id from test_quiz where id_course = {$course->id}) group by user_id) group by attempt_id,(select fecha from mdl_munduaattemps
      where a_id = attempt_id),mdl_munduatest_questions.id_competencia,max_ponit,ponderado_point order by attempt_id,id_competencia";
      $todos_intentos = $DB->get_recordset_sql($sql_results);

      $array_attemps =[];
      $array = "";
      $competencia_anterior = "";
      $attemp_id_anterior = "";
      $table .= "<tbody style='border: solid 1px;'>";
      $cont_competencia = 0;

      foreach ($todos_intentos as $key => $at) {
          //var_dump($todos_intentos);
          $grupos = groups_get_user_groups($course->id, $at->user_id);
          if($_GET['group'] && $_GET['group'] != 'all' && $_GET['group'] != $grupos[0][0]){
            //$array = "";
            //var_dump($grupos[0][0]);
            //$cont_competencia=0;
            continue;
          }
          $user_visible++;
          $grupo = groups_get_group_name($grupos[0][0]);

          $context = get_context_instance(CONTEXT_COURSE,$COURSE->id);

          $rol = get_user_roles($context, $intento->user_id, false);
          $role = key($rol);
          $roleid = $rol[$role]->roleid;
          $rolename = $rol[$role]->shortname;

          if ($rolename == "student"){
            $rol = "Estudiante";
          }else{
            $rol = "Profesor";
          }
          //var_dump($rolename);
          if (!$grupo){
            $grupo = "Sin grupo";
          }

          $porcentaje = 0;

          $porcentaje = (100 / $at->max_ponit * $at->puntaje);

          if ($array == "" && $cont_competencia == 0){
            $user_info = $DB->get_record('user', array('id' => $at->user_id), '*', MUST_EXIST);
            $table .= "<td style='border: solid 1px;'>";
              $table .= $at->attempt_id;
            $table .= "</td>";
            $table .= "<td style='border: solid 1px;'>";
              $table .= $at->user_id;
            $table .= "</td>";
            $table .= "<td style='border: solid 1px;'>";
              $table .= $user_info->firstname." ".$user_info->lastname." (".$user_info->email.")";
            $table .= "</td>";
            $table.="<td style='border: solid 1px;' >".$rol."</td>";
            $table.="<td style='border: solid 1px;' >".$grupo."</td>";
            $table .= "<td style='border: solid 1px;'>";
              $table .= $at->fecha;
            $table .= "</td>";
          }

          if(($attemp_id_anterior != $at->attempt_id) && $attemp_id_anterior){

            $array = str_replace( 'ght', "", $array);
            $puntajes = explode('-aqui',$array);
            //var_dump($array);
            /*if ($cont_competencia){
              $table .= "<td style='border: solid 1px;'>";
                $table .= $at->attempt_id;
              $table .= "</td>";
              $table .= "<td style='border: solid 1px;'>";
                $table .= $at->user_id;
              $table .= "</td>";
              $table .= "<td style='border: solid 1px;'>";
                $table .= $at->fecha;
              $table .= "</td>";
            }*/
            //var_dump($puntajes);
            foreach ($puntajes as $key => $value) {
              //$table .= "<td>";
                $table .= $value;
                //$encabezados = "";
              //$table .= "</td>";
            }
            $table .= "</tr>";
            //$array = "";
            $array = "ght";
            $cont_competencia++;
            //continue;
          }

          if ($array == "" && $cont_competencia > 0 ){

          }else{
            //$table .= "<tr>";
            if (($attemp_id_anterior != $at->attempt_id) && $attemp_id_anterior ){
              $user_info = $DB->get_record('user', array('id' => $at->user_id), '*', MUST_EXIST);
              $table .= "<td style='border: solid 1px;'>";
                $table .= $at->attempt_id;
              $table .= "</td>";
              $table .= "<td style='border: solid 1px;'>";
                $table .= $at->user_id;
              $table .= "</td>";
              $table .= "<td style='border: solid 1px;'>";
                $table .= $user_info->firstname." ".$user_info->lastname." (".$user_info->email.")";
              $table .= "</td>";
              $table.="<td style='border: solid 1px;' >".$rol."</td>";
              $table.="<td style='border: solid 1px;' >".$grupo."</td>";
              $table .= "<td style='border: solid 1px;'>";
                $table .= $at->fecha;
              $table .= "</td>";
            }

            //$array .= "<td style='border: solid 1px;'>".round($porcentaje,2)."%"."</td>-aqui";
            $array .= "<td style='border: solid 1px;'>".round($porcentaje,2)."%"."</td>-aqui";
            if ($cont_competencia < 1) {
              $encabezados .= "<td style='border: solid 1px;'>".$at->name."</td>";
            }

          }

        $competencia_anterior = $at->id_competencia;
        $attemp_id_anterior = $at->attempt_id;
        //$table .= "</tr>";
      }
      if (!$user_visible){
        $table.="<tr>";
          $table.="<td colspan='6' style='text-align: center;'>Sin Registros</td>";
        $table.="</tr>";
      }
      $array = str_replace( 'ght', "", $array);
      $puntajes = explode('-aqui',$array);

      foreach ($puntajes as $key => $value) {
        //$table .= "<td>";
          $table .= $value;
          //$encabezados = "";
        //$table .= "</td>";
      }
      $table .= "</tr>";
      //$array = "";
      $array = "ght";
      $cont_competencia++;
      $table .= "</tbody>";
      //die(var_dump($array_series));
      //array_push($array_attemps,explode(',',$array));
      //var_dump($array_attemps);

  ?>

  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title></title>
    </head>
    <body>
      <div class="row">
        <div class="col-md-12 table-responsive">
          <table style="border: solid 1px;">
            <thead>
              <tr style="border: solid 1px;">
                <th style="border: solid 1px;">#</th>
                <th style="border: solid 1px;">User ID</th>
                <th style="border: solid 1px;">Nombre (Correo)</th>
                <th style="border: solid 1px;">Rol</th>
                <th style="border: solid 1px;">Grupo</th>
                <th style="border: solid 1px;">Fecha</th>
                <?= $encabezados?>
              <tr>
            </thead>
            <?= $table ?>
          </table>
        </div>
      </div>
    </body>
  </html>

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
    global $DB, $CFG, $USER;
    $identificador = $_GET['id'];

    $course_id = $_GET['id'];
    $correct = 1;

    if ($user_id && $_POST){

      $attempt_id = 1;
      $puntaje = 1;
      $ins = (object)array('user_id' => $user_id, 'test_id' => $test_id, 'puntaje' => $puntaje);
      $attempt_id = $DB->insert_record_raw('attemps', $ins);
      //$attemps = "INSERT INTO attemps(user_id, test_id, puntaje) VALUES({{$user_id}},{{$test_id}},{{$puntaje}})";
      //$attempt_id = $DB->query_start($attemps);
      //die(var_dump($_POST));
      foreach ($_POST as $key => $quest) {
        if (strpos($key, 'question_') !== false ){
          $question_id = str_replace('question_',"",$key);
          $option_id = $quest;
          $answers = (object)array('user_id' => $user_id, 'test_id' => $test_id, 'option_id' => $option_id, 'question_id' => $question_id, 'attempt_id' => $attempt_id, 'correct' => $correct);
          //var_dump($answers);
          $answers_id = $DB->insert_record_raw('answer_users',$answers);
          //var_dump($answers_id);
        }
      }

      $sql_puntuación = "SELECT mdl_munduatest_questions.id_competencia, sum(val) as puntaje, max_ponit,ponderado_point FROM mdl_munduaanswer_users
      inner join mdl_munduatest_questions on mdl_munduatest_questions.id = mdl_munduaanswer_users.question_id
      inner join test_rel_options on (mdl_munduaanswer_users.question_id = test_rel_options.question_id and mdl_munduaanswer_users.option_id = test_rel_options.option_id)
      inner join test_competencia on test_competencia.c_id = mdl_munduatest_questions.id_competencia where attempt_id = {$attempt_id} group by mdl_munduatest_questions.id_competencia";

      $puntuacion = $DB->get_records_sql($sql_puntuación);

      foreach ($puntuacion as $key => $punt) {
        $porcentaje_punt += ($punt->ponderado_point / 100 * (100 / $punt->max_ponit * $punt->puntaje));
      }
      $por_puntaje = "UPDATE {$CFG->prefix}attemps SET puntaje = {$porcentaje_punt} where a_id in ({$attempt_id})";
      //$DB->execute($por_puntaje);
      if ($DB->execute($por_puntaje)){
        redirect('/cook/result.php?id='.$identificador);
      }
      //die(var_dump($porcentaje_punt));
      //exit;
    }

  ?>

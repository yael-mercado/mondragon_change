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
    $question_id = $_POST['question_id'];

    if ($user_id && $_POST){
      $question_id = $_POST['question_id'];
      $desc_question = $_POST['question'];

      if ($desc_question && $question_id){
        $update_question = "UPDATE {$CFG->prefix}test_questions SET descripcion = '{$desc_question}' where q_id = {$question_id}";

        $record = new stdclass;
        $record->id = $question_id;
        //$record->q_id = $question_id;
        $record->descripcion = $desc_question;

        $sql = $DB->update_record_raw('test_questions', $record);

        if ($sql){
          redirect('/cook/config_questions.php?id='.$course_id."&save=1");
        }else{
          redirect('/cook/config_questions.php?id='.$course_id."&save=0");
        }

      }else{
        redirect('/cook/config_questions.php?id='.$course_id."&save=0");
      }
    }

  ?>

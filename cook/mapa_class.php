<?php
require_once(dirname(__FILE__) . '/../config.php');
require_once('../course/lib.php');
require_once 'autodiagnostico.php';
require_once($CFG->libdir . '/completionlib.php');
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
class mapa
{
  private $pdo;

  function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  //imprime la base principal de links en las categorias de cursos
  public function get_links(){
    $html = '<meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Universidad Mondragón</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" crossorigin href="./universidad/assets/main-DalE6wwn.css">
    <link rel="stylesheet" crossorigin href="./universidad/assets/global-CPC3-saQr.css">';
    return $html;
  }

  //Imprime los links requeridos en las vistas a la medida
  public function get_links_modulos(){
    $html = '<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" crossorigin href="./universidad/assets/modulo-DadkwsGC.css">
    <link rel="stylesheet" crossorigin href="./universidad/assets/global-CPC3-saQr.css">';
    return $html;
  }

   /**
   * Obtener el porcentaje de avance de un usuario en un curso.
   *
   * @param int $userid ID del usuario.
   * @param int $courseid ID del curso.
   * @return float Porcentaje de avance en el curso.
   */
   function obtener_porcentaje_avance($userid, $courseid) {
      global $DB, $USER;
      //die(var_dump($userid));
      // Cargar el curso.
      $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

      // Verificar si la finalización está habilitada en el curso.
      if (!$course->enablecompletion) {
          return 0;
      }

      // Crear objeto de información de finalización.
      $completion = new completion_info($course);

      // Obtener todas las actividades en el curso.
      $activities = $DB->get_records_select('course_modules',
          'course = :courseid AND completion IN (1, 2)',
          array('courseid' => $courseid)
      );

      $total_activities = 0;
      $completed_activities = 0;

      foreach ($activities as $activity) {
          $total_activities++;

          $completionstatus = $DB->get_record('course_modules_completion', array(
              'coursemoduleid' => $activity->id,
              'userid' => $userid
          ));

          if ($completionstatus && $completionstatus->completionstate == COMPLETION_COMPLETE) {
              $completed_activities++;
          }
      }

      // Calcular el porcentaje de avance.
      if ($total_activities > 0) {
          return round(($completed_activities / $total_activities) * 100, 2);
      } else {
          return 0;
      }
  }

  //Metodo de finalización de curso por Usuario
  function obtener_fecha_finalizacion_curso($courseid, $userid, $progress) {
      global $DB; // Accede a la base de datos de Moodle

      // Consulta la tabla 'course_completions' para obtener el campo 'timecompleted'
      $completion = $DB->get_record('course_completions', array('course' => $courseid, 'userid' => $userid), 'timecompleted');

      if ($completion && $completion->timecompleted) {
          // Si el curso ha sido completado, convierte el timestamp a una fecha legible
          return "Finalizado el ".date('d-m-Y H:i:s', $completion->timecompleted);
      }else if ($progress == 100){
          return "Finalizado el ".date('d-m-Y H:i:s');
      } else {
          // Si no ha sido completado, devuelve un mensaje indicando esto
          return "Pendiente";
      }
  }

  public function get_avg_course_category_total($programs_courses, $value_mod){
    global $USER;
    $filteredArray_cursos = array_filter($programs_courses, function($cursos_item) use ($value_mod) {
        return $cursos_item['category_course_id'] === $value_mod['category_id'];
    });

    //Obtener el porcentaje de avance de los cursos por modulo
    //incializar variable de promedio de curso en cada ciclo
    $avg_porcentaje_course = 0;
    foreach ($filteredArray_cursos as $key => $course_filter) {
      $avg_porcentaje_course += $this->obtener_porcentaje_avance($USER->id,$course_filter['course_id']);
    }

    return $avg_porcentaje_course /= count($filteredArray_cursos);
  }

  //Metodo que genera la vista de linea tipo time con vista para estudiantes
  public function generate_lime_time_items(){
    global $DB, $USER;
    $programas = $this->get_user_rol_courses($USER->id);

    $html = '<style>

    .details-details-time-line {
      width: 16rem;
      border-radius: .75rem;
      color: white !important;
    }
    #region-main {
    float: none;
    padding: 0 0 0;
    border-radius: 10px;
    overflow-x: visible;
    }
    .theme-container #page.drawers .main-inner {
      margin-top: 0;
      max-width: max-content;
      padding: 0;
    }
    .header-maxwidth {
      display:none !important;
    }
    #page{padding: 0 !important; margin: 0 !important;}
    #topofscroll{
      padding-bottom: 0px !important;
      margin-bottom: 0px !important;
      padding-left: 0px !important;
      padding-right: 0px !important;
    }
    #page-content{
        padding-bottom: 0px !important;
    }
    [role="main"] {
    padding-left: 0px !important;
    padding-right: 0px !important;
    }
   </style><div id="app" class="flex w-full h-full min-h-[100dvh]">
    <aside class="hidden flex-grow lg:block w-1/12 bg-[var(--color-primary)]"></aside>

    <main
      class="w-full flex-grow lg:gap-y-24 lg:w-11/12 grid lg:grid-rows-[50px_1fr_1fr_1fr_100px] bg-hero-wave bg-no-repeat bg-[length:200%_auto] lg:bg-[length:100%_auto] bg-center">

      <header class="w-full relative px-10">
        <a class="absolute w-40 lg:w-auto right-3 lg:right-12 top-6 lg:top-10" href="./universidad/index.html"> <img src="./universidad/assets/logo-BvwU9ppd.svg" alt="Logo del sitio"></a>

        <details class="details-details-time-line">
          <summary class="details-summary-time-line">Progreso</summary>
          <ul class="mt-8 mb-2 flex flex-col gap-y-5">
            <li class="flex gap-4">
              <span class="point-time-line-lock max-w-6 min-h-6 bg-white shadow-check-shadow-white">
                <i class="fa-solid fa-check text-[var(--color-primary)] text-sm"></i>
              </span>
              <span>Módulo completado</span>
            </li>
            <li class="flex gap-4">
              <span class="point-time-line-require max-w-6 min-h-6 bg-white"></span>
              <span>Módulo obligatorio</span>
            </li>
            <li class="flex gap-4">
              <span class="point-time-line-empty border-white max-w-6 min-h-6"></span>
              <span>Módulo opcional</span>
            </li>
            <li class="flex gap-4">
              <span class="point-time-line-active max-w-6 min-h-6">
                <i class="fa-solid fa-user i-time-line"></i>
              </span>
              <span>Módulo en curso</span>
            </li>
            <li class="flex gap-4">
              <span class="point-time-line-lock max-w-6 min-h-6 bg-[var(--color-blue-lock)]">
                <i class="fa-solid fa-lock i-time-line"></i>
              </span>
              <span>Módulo a desbloquear</span>
            </li>
          </ul>
        </details>
      </header>';

      $array_img = [
        ['imagen' => "data:image/svg+xml,%3csvg%20width='65'%20height='76'%20viewBox='0%200%2065%2076'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M44.1071%2019.5714C44.1071%2015.5089%2041.7857%2011.7366%2038.3036%209.56027C34.6763%207.52902%2030.1786%207.52902%2026.6964%209.56027C23.0692%2011.7366%2020.8929%2015.5089%2020.8929%2019.5714C20.8929%2023.779%2023.0692%2027.5513%2026.6964%2029.7277C30.1786%2031.7589%2034.6763%2031.7589%2038.3036%2029.7277C41.7857%2027.5513%2044.1071%2023.779%2044.1071%2019.5714ZM13.9286%2019.5714C13.9286%2013.0424%2017.4107%206.94866%2023.2143%203.61161C28.8728%200.274555%2035.9821%200.274555%2041.7857%203.61161C47.4442%206.94866%2051.0714%2013.0424%2051.0714%2019.5714C51.0714%2026.2455%2047.4442%2032.3393%2041.7857%2035.6763C35.9821%2039.0134%2028.8728%2039.0134%2023.2143%2035.6763C17.4107%2032.3393%2013.9286%2026.2455%2013.9286%2019.5714ZM7.10937%2068.3214H57.7455C56.4397%2059.1808%2048.6049%2052.0714%2039.029%2052.0714H25.8259C16.25%2052.0714%208.41518%2059.1808%207.10937%2068.3214ZM0%2071.0781C0%2056.7143%2011.4621%2045.1071%2025.8259%2045.1071H39.029C53.3929%2045.1071%2065%2056.7143%2065%2071.0781C65%2073.3996%2062.9687%2075.2857%2060.6473%2075.2857H4.20759C1.88616%2075.2857%200%2073.3996%200%2071.0781Z'%20fill='%239665EC'/%3e%3c/svg%3e", "class" => "text-[var(--color-blue-check)]", "class-color" => 'bg-[var(--color-blue-check)]'],
        ['imagen' => "data:image/svg+xml,%3csvg%20width='97'%20height='79'%20viewBox='0%200%2097%2079'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M10.4051%202.04543L17.6434%206.87099C19.3022%208.07738%2019.7546%2010.3394%2018.699%2011.9981C17.4926%2013.6569%2015.2307%2014.1093%2013.5719%2012.9029L6.33354%208.07738C4.67476%207.02179%204.22236%204.75981%205.42875%203.10102C6.48434%201.44224%208.74632%200.989843%2010.4051%202.04543ZM90.0267%208.07738L82.7884%2012.9029C81.1296%2014.1093%2078.8677%2013.6569%2077.8121%2011.9981C76.6057%2010.3394%2077.0581%208.07738%2078.7169%206.87099L85.9552%202.04543C87.614%200.989843%2089.876%201.44224%2091.0823%203.10102C92.1379%204.75981%2091.6855%207.02179%2090.0267%208.07738ZM3.61917%2025.57H13.2703C15.2307%2025.57%2016.8894%2027.2288%2016.8894%2029.1892C16.8894%2031.3004%2015.2307%2032.8083%2013.2703%2032.8083H3.61917C1.50799%2032.8083%200%2031.3004%200%2029.1892C0%2027.2288%201.50799%2025.57%203.61917%2025.57ZM83.2408%2025.57H92.8919C94.8523%2025.57%2096.5111%2027.2288%2096.5111%2029.1892C96.5111%2031.3004%2094.8523%2032.8083%2092.8919%2032.8083H83.2408C81.1296%2032.8083%2079.6216%2031.3004%2079.6216%2029.1892C79.6216%2027.2288%2081.1296%2025.57%2083.2408%2025.57ZM17.6434%2051.5074L10.4051%2056.3329C8.74632%2057.5393%206.48434%2057.0869%205.42875%2055.4281C4.22236%2053.7693%204.67476%2051.5074%206.33354%2050.301L13.5719%2045.4754C15.2307%2044.4198%2017.4926%2044.8722%2018.699%2046.531C19.7546%2048.1898%2019.3022%2050.4518%2017.6434%2051.5074ZM82.7884%2045.6262L90.0267%2050.4518C91.6855%2051.5074%2092.1379%2053.7693%2091.0823%2055.4281C89.876%2057.0869%2087.614%2057.5393%2085.9552%2056.4837L78.7169%2051.6582C77.0581%2050.4518%2076.6057%2048.1898%2077.8121%2046.531C78.8677%2044.8722%2081.1296%2044.4198%2082.7884%2045.6262ZM67.5578%2027.9828C67.5578%2017.4269%2058.8114%208.68057%2048.2555%208.68057C37.5488%208.68057%2028.9533%2017.4269%2028.9533%2027.9828C28.9533%2032.2051%2030.1597%2035.9751%2032.2709%2038.9911C32.8741%2039.8959%2033.4773%2040.8007%2034.2313%2041.7055C36.1917%2044.4198%2038.4536%2047.5866%2040.2632%2050.7534C41.7712%2053.6185%2042.676%2056.6345%2042.9776%2059.3489H35.7393C35.2869%2057.5393%2034.8345%2055.8805%2033.9297%2054.2217C32.4217%2051.5074%2030.6121%2048.9438%2028.6517%2046.3802C27.8977%2045.3246%2027.1437%2044.269%2026.3898%2043.2134C23.3738%2038.8403%2021.715%2033.7131%2021.715%2027.9828C21.715%2013.3553%2033.4773%201.44224%2048.2555%201.44224C62.883%201.44224%2074.7961%2013.3553%2074.7961%2027.9828C74.7961%2033.7131%2072.9865%2038.8403%2069.9705%2043.2134C69.2165%2044.269%2068.4626%2045.3246%2067.7086%2046.3802C65.899%2048.9438%2063.9386%2051.5074%2062.4306%2054.2217C61.5258%2055.8805%2061.0734%2057.5393%2060.7718%2059.3489H53.3827C53.8351%2056.6345%2054.5891%2053.6185%2056.0971%2050.7534C57.9067%2047.5866%2060.1686%2044.4198%2062.129%2041.7055C62.883%2040.8007%2063.4862%2039.8959%2064.0894%2038.9911C66.2006%2035.9751%2067.5578%2032.2051%2067.5578%2027.9828ZM41.0172%2027.9828C41.0172%2029.34%2039.8108%2030.3956%2038.6044%2030.3956C37.2472%2030.3956%2036.1917%2029.34%2036.1917%2027.9828C36.1917%2021.3477%2041.4696%2015.9189%2048.2555%2015.9189C49.4619%2015.9189%2050.6683%2017.1253%2050.6683%2018.3317C50.6683%2019.6889%2049.4619%2020.7445%2048.2555%2020.7445C44.184%2020.7445%2041.0172%2024.062%2041.0172%2027.9828ZM60.3194%2066.5872C60.3194%2073.3732%2054.8907%2078.6511%2048.2555%2078.6511C41.4696%2078.6511%2036.1917%2073.3732%2036.1917%2066.5872V64.1744H60.3194V66.5872Z'%20fill='%230984BA'/%3e%3c/svg%3e", "class" => "text-[var(--color-primary)]" , "class-color" => "bg-[var(--color-primary)]"],
        ['imagen' => "data:image/svg+xml,%3csvg%20width='93'%20height='75'%20viewBox='0%200%2093%2075'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M48.75%208.125V70.8036C48.75%2072.8348%2047.154%2074.2857%2045.2679%2074.2857C43.2366%2074.2857%2041.7857%2072.8348%2041.7857%2070.8036V8.125C41.7857%203.77232%2045.4129%209.53674e-07%2049.9107%209.53674e-07H66.1607C70.5134%209.53674e-07%2074.2857%203.77232%2074.2857%208.125V27.8571H78.9286V17.4107C78.9286%2015.5246%2080.3795%2013.9286%2082.4107%2013.9286C84.2969%2013.9286%2085.8929%2015.5246%2085.8929%2017.4107V28.0022C89.8103%2028.5826%2092.8571%2031.9196%2092.8571%2035.9821V70.8036C92.8571%2072.8348%2091.2612%2074.2857%2089.375%2074.2857C87.3437%2074.2857%2085.8929%2072.8348%2085.8929%2070.8036V35.9821C85.8929%2035.4018%2085.3125%2034.8214%2084.7321%2034.8214H82.4107H70.8036C68.7723%2034.8214%2067.3214%2033.3705%2067.3214%2031.3393V8.125C67.3214%207.54464%2066.7411%206.96429%2066.1607%206.96429H49.9107C49.1853%206.96429%2048.75%207.54464%2048.75%208.125ZM53.3929%2013.9286C53.3929%2012.7679%2054.4085%2011.6071%2055.7143%2011.6071H60.3571C61.5179%2011.6071%2062.6786%2012.7679%2062.6786%2013.9286V18.5714C62.6786%2019.8772%2061.5179%2020.8929%2060.3571%2020.8929H55.7143C54.4085%2020.8929%2053.3929%2019.8772%2053.3929%2018.5714V13.9286ZM55.7143%2025.5357H60.3571C61.5179%2025.5357%2062.6786%2026.6964%2062.6786%2027.8571V32.5C62.6786%2033.8058%2061.5179%2034.8214%2060.3571%2034.8214H55.7143C54.4085%2034.8214%2053.3929%2033.8058%2053.3929%2032.5V27.8571C53.3929%2026.6964%2054.4085%2025.5357%2055.7143%2025.5357ZM53.3929%2041.7857C53.3929%2040.625%2054.4085%2039.4643%2055.7143%2039.4643H60.3571C61.5179%2039.4643%2062.6786%2040.625%2062.6786%2041.7857V46.4286C62.6786%2047.7344%2061.5179%2048.75%2060.3571%2048.75H55.7143C54.4085%2048.75%2053.3929%2047.7344%2053.3929%2046.4286V41.7857ZM74.2857%2039.4643H78.9286C80.0893%2039.4643%2081.25%2040.625%2081.25%2041.7857V46.4286C81.25%2047.7344%2080.0893%2048.75%2078.9286%2048.75H74.2857C72.9799%2048.75%2071.9643%2047.7344%2071.9643%2046.4286V41.7857C71.9643%2040.625%2072.9799%2039.4643%2074.2857%2039.4643ZM71.9643%2055.7143C71.9643%2054.5536%2072.9799%2053.3929%2074.2857%2053.3929H78.9286C80.0893%2053.3929%2081.25%2054.5536%2081.25%2055.7143V60.3571C81.25%2061.6629%2080.0893%2062.6786%2078.9286%2062.6786H74.2857C72.9799%2062.6786%2071.9643%2061.6629%2071.9643%2060.3571V55.7143ZM32.5%2023.2143C32.5%2024.0848%2032.3549%2024.8103%2032.2098%2025.5357C35.1116%2027.567%2037.1429%2031.0491%2037.1429%2034.8214C37.1429%2041.3504%2031.9196%2046.4286%2025.5357%2046.4286H23.2143V69.6429C23.2143%2072.2545%2021.0379%2074.2857%2018.5714%2074.2857C15.9598%2074.2857%2013.9286%2072.2545%2013.9286%2069.6429V46.4286H11.6071C5.07812%2046.4286%200%2041.3504%200%2034.8214C0%2031.0491%201.88616%2027.567%204.78795%2025.5357C4.64286%2024.8103%204.64286%2024.0848%204.64286%2023.2143C4.64286%2015.5246%2010.7366%209.28572%2018.5714%209.28572C26.2612%209.28572%2032.5%2015.5246%2032.5%2023.2143Z'%20fill='%23B74121'/%3e%3c/svg%3e", "class" => "text-[var(--color-tertiary)]", "class-color" => "bg-[var(--color-tertiary)]"],
      ];

      foreach ($programas->programas as $key1 => $value_pro) {
        //recorre cada modulo de los programas
        $last_required = '';
        $next_modulo = 0;
        $opcional_completed = 0;
        //var_dump($value_pro['subcategory_id']);
        $html .= '<section class="grid grid-cols-1 mt-20 px-10 lg:px-0 lg:mt-0 lg:grid-cols-3 lg:items-center '.($array_img[$key1]['class']).'" style="padding-left: 2.5rem;
      padding-right: 2.5rem;">';
        //Mostrar subcategorias de los modulos

        $html .= '<figure class="flex flex-col lg:justify-center lg:items-center gap-2 lg:gap-3">
                    <img class="w-10 lg:w-16" src="'.($array_img[$key1]['imagen']).'" alt="Universidad Mondragón" />
                    <label class="text-2xl lg:text-3xl font-bold">'.$value_pro['name_subcategory'].'</label>
                  </figure>';
        $html .= '<ul class="flex flex-col lg:flex-row lg:items-center lg:col-span-2">';

        //Mostrar modulos crrepodnientes de la subcategoria
        $completed = 0;

        //inicial item de mapa
        $key_pro=0;

        $filteredArray = array_filter($programas->Modulos, function($item) use ($value_pro) {
            return $item['sub_cat_category_id'] === $value_pro['subcategory_id'];
        });
        //var_dump($value_pro['subcategory_id']);
        $total_mods = count($filteredArray);
        $last_completed = 0;
        $pdo = getMoodlePdoConnection();

        // Instanciar la clase Autodiagnostico
        if ($pdo){
          $autodiagnostico = new Autodiagnostico($pdo);
          $isCompleted_auto = (count($autodiagnostico->get_result_autodiagnostic()) == 9 ? true : false);
        }else{
          $isCompleted_auto=false;
        }

        if ($value_pro['subcategory_id'] == 12){
          $html .= '<li class="li-section-time-line">
          <label class="h3-time-line-up">Autodiagnóstico Empresarial</label>
          <a class=" point-time-line-check  bg-[var(--color-primary)]  " ;="" href="/cook/autotest.php">
          <i class="fa-solid '.($isCompleted_auto ? 'fa-check' : '').' i-time-line"></i></a>
          <hr class="'.($isCompleted_auto ? '' : 'line-empty-time-line').' left-time-line '.($array_img[$key1]['class-color']).' bg-[var(--color-primary)]"></li>';
          $last_completed = 1;
          $last_required = 1;
          $next_modulo = 1;
          $opcional_completed = 2;
          $anterio_completado = 1;
        }


        $anterio_completado = 0;

        foreach ($filteredArray as $key => $value_mod) {

          if ($value_mod['category_id'] == 21){
            $anterio_completado = 1;
          }

          $isRequiredMod = 0;
          $isRequiredMod = $autodiagnostico->return_requierd_results($value_mod['category_id']);

          $class_point = "";
          $class_shadow = "";
          //Se coloca la información de los avances del curso en cada modulo
          $total_avg_per_course = $this->get_avg_course_category_total($programas->Cursos, $value_mod);
          $completed = ($total_avg_per_course == 100 ? 1 : 0);
          $icon_status = ($total_avg_per_course == 100 ? '<i class="fa-solid fa-check i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : 'bo').'"></i></a>': '<i class="fa-solid fa-user i-time-line"></i></a>');

          //se diferencian los elementos de cada una de las categorias
          $text_course = explode(".", $value_mod['name_category']);

          $hr_rigth= "";
          $hr_left="";
          //var_dump($last_completed, $value_mod['name_category']);
          switch ($last_completed) {
            case 0:
              if ($key_pro == 0 && $total_avg_per_course < 100 ){
                if ($key_pro+1 <= $total_mods){
                  if ($total_avg_per_course > 0){
                    $hr_left = '<hr class="left-time-line '.($array_img[$key1]['class-color']).'">';
                  }else{
                    $hr_left = '<hr class="line-empty-time-line left-time-line '.($array_img[$key1]['class-color']).'">';
                  }
                }

                if ($value_pro['subcategory_id'] == 12 ){
                  $hr_rigth = '<hr class="'.($isCompleted_auto ? '' : 'line-empty-time-line').' right-time-line '.($array_img[$key1]['class-color']).'">';
                }

                $class_point = ' point-time-line-check ';
                $class_shadow = '';
              }else if ($total_avg_per_course == 100 && $key_pro == 0){

                $hr_left = '<hr class="left-time-line '.($array_img[$key1]['class-color']).'">';
                $class_point = ' point-time-line-check ';
                $class_shadow = ' shadow-check-shadow-blue ';
              }else if ($key_pro != 0 && $total_avg_per_course == 0){
                if ($key_pro+1 < $total_mods){
                  $hr_left = '<hr class="left-time-line line-empty-time-line '.($array_img[$key1]['class-color']).'">';
                }

                $hr_rigth = '<hr class="line-empty-time-line right-time-line '.($array_img[$key1]['class-color']).'">';

                $icon_status = '<i class="fa-solid fa-lock i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : 'bu').'"></i></a>';
                //$class_point = ' point-time-line-lock ';
                $class_shadow = ' line-empty-time-line ';

                if ($isRequiredMod && $last_completed){
                  $class_point = ' point-time-line-check ';
                  //$hr_rigth = '<hr class="right-time-line '.($array_img[$key1]['class-color']).'">';
                }else{
                  $class_point = ' point-time-line-lock ';
                }
                //var_dump($value_mod);
              }else{
                $hr_left = '<hr class="left-time-line '.($array_img[$key1]['class-color']).'">';
                $class_point = ' point-time-line-lock';
                $class_shadow = ' line-empty-time-line';
              }
              break;

            case 1:

                if (!$last_required && $isRequiredMod ){
                  $hr_rigth = '<hr class="line-empty-time-line right-time-line '.($array_img[$key1]['class-color']).'">';
                }else{
                  if ($anterio_completado){
                    $hr_rigth = '<hr class="right-time-line '.($array_img[$key1]['class-color']).'">';

                  }else{
                    $hr_rigth = '<hr class="right-time-line line-empty-time-line '.($array_img[$key1]['class-color']).'">';
                  }

                }

                if ($total_avg_per_course > 0)
                {
                  if ($key_pro+1 < $total_mods){
                  $hr_left = '<hr class="left-time-line '.($array_img[$key1]['class-color']).'">';
                  }
                }else{
                  if ($key_pro+1 < $total_mods){
                    $hr_left = '<hr class="line-empty-time-line left-time-line '.($array_img[$key1]['class-color']).'">';
                  }

                }

                if ($completed == 0){
                  $class_shadow = ' shadow-check-shadow-blue ';
                }

                $class_point = ' point-time-line-check ';

            break;

            default:

              break;
          }

          if ($key_pro == 0 && !$isRequiredMod){
            $last_completed = 1;
          }

          if ($key_pro == 0 && !$next_modulo){
            $icon_status = '<i class="fa-solid '.($completed ? ' fa-check ' : '').' i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : '').'"></i></a>';
            $class_shadow =  '';
          }else if ($next_modulo && $isRequiredMod && $last_completed){

            if (!$completed){
              $icon_status = '<i class="fa-solid fa-user i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : '').'"></i></a>';
              $class_shadow =  'shadow-check-shadow-blue';

              if ($last_completed && $next_modulo){
                //comentario este
                //$hr_rigth = '<hr class="right-time-line '.($array_img[$key1]['class-color']).'">';
              }
            }else if ($last_completed && $next_modulo){
              //var_dump($opcional_completed);
              //var_dump($opcional_completed, $anterio_completado);
              if ($opcional_completed == 2 && $anterio_completado){
                $hr_rigth = '<hr class="right-time-line '.($array_img[$key1]['class-color']).'">';
              }
            }

          }else if ($next_modulo && !$isRequiredMod) {
            $icon_status = '<i class="fa-solid '.($completed ? ' fa-check ' : '').' i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : '').'"></i></a>';
            $class_shadow =  '';
            $class_point = ' point-time-line-check ';
          }else if ($opcional_completed == 2 && $last_completed){
            if ($completed){
              $hr_rigth = '<hr class="line-empty-time-line right-time-line '.($array_img[$key1]['class-color']).'">';
            }
            $icon_status = '<i class="fa-solid '.($completed ? ' fa-check ' : 'fa-user').' i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : '').'"></i></a>';
            $class_shadow =  ($opcional_completed == 2 && !$completed? 'shadow-check-shadow-blue' : '');
            $class_point = ' point-time-line-check ';
          }

          if ($value_mod['sub_cat_category_id'] == 12){
            if (!$isCompleted_auto){
              //$icon_status = '<i class="fa-solid fa-lock i-time-line '.(!$isRequiredMod ? str_replace('bg', 'text', $array_img[$key1]['class-color']) : 'ba').'"></i></a>';
              //$class_point = ' point-time-line-lock ';
              //$class_shadow = ' line-empty-time-line ';
            }
          }

          $html .= '<li class="li-section-time-line">';
            $html .= '<label class="h3-time-line-'.($key % 2 == 0 ? 'up' : 'down').'">'.$text_course[1].'</label>';
            $html .= '<a title="'.($isRequiredMod ? 'Obligatorio' : 'Opcional').'" class="'.$class_point.' '.($isRequiredMod ? $array_img[$key1]['class-color'] : ' point-time-line-empty '.str_replace('bg', 'border', $array_img[$key1]['class-color'])).' '.$class_shadow.' ";
              href="/cook/course_mod.php?cat_id='.$value_mod['category_id'].'">'.$icon_status;
            $html .= $hr_left;
            $html .= $hr_rigth;
          $html .= '</li>';

          //banderas de completado
          $key_pro++;
          if (!$isRequiredMod){
            $opcional_completed = 2;
          }else{
            $opcional_completed = 1;
          }

          if ($isRequiredMod){
            $last_completed = $completed;
            $opcional_completed = 0;
          }else if ($completed){
            $last_completed = $completed;
          }

          $anterio_completado = $completed;
          $last_required = $isRequiredMod;
          $next_modulo = (!$isRequiredMod  && $opcional_completed == 2 ? 1 : 0);
          $completed = 0;

        }


        $html .= '</ul>';
        $html .= '</section>';
        //exit;
      }


      $html .= '<footer class="flex w-full justify-end">
                <img class="w-full md:max-w-[450px] self-end mt-20" src="data:image/svg+xml,%3csvg%20width=\'420\'%20height=\'61\'%20viewBox=\'0%200%20420%2061\'%20fill=\'none\'%20xmlns=\'http://www.w3.org/2000/svg\'%3e%3cpath%20d=\'M0%2061H431V0H60C26.8629%200%200%2026.8629%200%2060V61Z\'%20fill=\'%23B74121\'/%3e%3cpath%20d=\'M108%2061H431V0H168C134.863%200%20108%2026.8629%20108%2060V61Z\'%20fill=\'%238EC100\'/%3e%3cpath%20d=\'M211%2061H431V0H271C237.863%200%20211%2026.8629%20211%2060V61Z\'%20fill=\'%230984BA\'/%3e%3c/svg%3e" alt="colores header">
            </footer>
            </main>
          </div>';

      $html.= "";

    return $html;
  }

  //Metodo para obtener los cursos y categorias de un usario con parametro: $user_id
  public function get_user_rol_courses($user_id){
    global $DB;
    $sql_query = "SELECT
                      c.id AS course_id,
                      c.fullname AS course_name,
                      cc.id AS category_id,
                      cc.name AS category_name,
                      c.sortorder AS course_sortorder,
                      cc.sortorder AS category_sortorder,
                      c2.id AS parent_category_id,
                      c2.name AS parent_category_name
                  FROM
                      mdl_user_enrolments ue
                  JOIN
                      mdl_enrol e ON ue.enrolid = e.id
                  JOIN
                      mdl_course c ON e.courseid = c.id
                  JOIN
                      mdl_course_categories cc ON c.category = cc.id
                  LEFT JOIN
                      mdl_course_categories c2 ON cc.parent = c2.id  -- Unir con la tabla de categorías para obtener la categoría padre
                  WHERE
                      ue.userid = {$user_id}
                      and c.shortname != 'PEMPRENDIMIENTO'
                  ORDER BY
                      cc.sortorder,          -- Ordenar primero por el orden de las categorías
                      c.sortorder; ";

    $programas = $DB->get_records_sql($sql_query);
    $progras = [];
    $modulos = [];
    $cursos = [];
    $final_pro = "";
    $final_mod = "";
    $final_cour = "";

    foreach ($programas as $key => $value) {

      if ($value->parent_category_name != $final_pro){
        $progras[] = ['subcategory_id' => $value->parent_category_id, 'name_subcategory' => $value->parent_category_name];
      }
      $final_pro = $value->parent_category_name;

      if ($value->category_name != $final_mod){
        $modulos[] = ['sub_cat_category_id' => $value->parent_category_id, 'category_id' => $value->category_id, 'name_category' => $value->category_name];
      }
      $final_mod = $value->category_name;

      if ($value->course_name != $final_cour){
        $cursos[] = ['category_course_id' => $value->category_id, 'course_id' => $value->course_id, 'course_name' => $value->course_name];
      }
      $final_cour = $value->course_name;

    }

    $categories_courses_object = ['programas' => $progras, 'Modulos' => $modulos, 'Cursos' => $cursos];

    return ((object)$categories_courses_object);
  }

  public function get_courses_by_modulos($subcat){
    global $DB, $USER;

    $sql_query = "SELECT
                  c.id AS course_id,
                  c.fullname AS course_name,
                  cc.id AS category_id,
                  cc.name AS category_name,
                  c.sortorder AS course_sortorder,
                  cc.sortorder AS category_sortorder,
                  c2.id AS parent_category_id,
                  c2.name AS parent_category_name
              FROM
                  mdl_course c
              JOIN
                  mdl_course_categories cc ON c.category = cc.id
              LEFT JOIN
                  mdl_course_categories c2 ON cc.parent = c2.id
              WHERE
                  c.category = {$subcat}
              ORDER BY
                  cc.sortorder,
                  c.sortorder";

              $courses_mod = ($DB->get_records_sql($sql_query));

              return ((object) $courses_mod);
  }

  //metodo para obtener los cursos del usuario logueado con modulos y categorias
  public function get_courses_modulos($subcat){
    global $DB, $USER;

    $sql_query2 = "SELECT
                      c.id AS course_id,
                      c.fullname AS course_name,
                      cc.id AS category_id,
                      cc.name AS category_name,
                      c.sortorder AS course_sortorder,
                      cc.sortorder AS category_sortorder,
                      c2.id AS parent_category_id,
                      c2.name AS parent_category_name
                  FROM
                      mdl_user_enrolments ue
                  JOIN
                      mdl_enrol e ON ue.enrolid = e.id
                  JOIN
                      mdl_course c ON e.courseid = c.id
                  JOIN
                      mdl_course_categories cc ON c.category = cc.id
                  LEFT JOIN
                      mdl_course_categories c2 ON cc.parent = c2.id
                  WHERE
                      ue.userid = {$USER->id} and c.category = {$subcat}
                  ORDER BY
                      cc.sortorder,
                      c.sortorder;";

    $courses_mod = ($DB->get_records_sql($sql_query2));

    return ((object) $courses_mod);
  }

  //Metodo para obtener los cursos de una categoria con parametro: $category_id
  public function get_category_name($category_id){
    global $DB, $USER;
    $sql_query2 = "SELECT
                      *
                  FROM
                      mdl_course_categories cc
                  WHERE
                      cc.id = {$category_id}
                  ORDER BY
                      cc.sortorder;";

    $courses_mod = ($DB->get_records_sql($sql_query2));

    return ((object) $courses_mod);
  }

  //renderiza las vistas de los modulos
  public function view_render_modulos($courses_mod){
    global $DB, $USER;
    $title_curse = $this->get_category_name($courses_mod);
    $cursos = $this->get_courses_modulos($courses_mod);
    $imagen = "<img class=\"max-w-64 md:max-w-[430px] self-end\" src=\"data:image/svg+xml,%3csvg%20width='431'%20height='61'%20viewBox='0%200%20431%2061'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M0%200H431V61H60C26.8629%2061%200%2034.1371%200%200.999999V0Z'%20fill='%23B74121'/%3e%3cpath%20d='M108%200H431V61H168C134.863%2061%20108%2034.1371%20108%200.999999V0Z'%20fill='%238EC100'/%3e%3cpath%20d='M211%200H431V61H271C237.863%2061%20211%2034.1371%20211%200.999999V0Z'%20fill='%230984BA'/%3e%3c/svg%3e\" alt=\"colores header\">";
    $imagen2 = 'src="data:image/svg+xml,%3csvg%20width=\'62\'%20height=\'62\'%20viewBox=\'0%200%2062%2062\'%20fill=\'none\'%20xmlns=\'http://www.w3.org/2000/svg\'%3e%3ccircle%20cx=\'31\'%20cy=\'31\'%20r=\'31\'%20fill=\'white\'/%3e%3cpath%20d=\'M22.8771%2029.9163L34.104%2018.7479C34.6302%2018.1631%2035.5073%2018.1631%2036.0921%2018.7479C36.6183%2019.2741%2036.6183%2020.1512%2036.0921%2020.6775L25.8008%2030.9103L36.0336%2041.2016C36.6183%2041.7279%2036.6183%2042.605%2036.0336%2043.1313C35.5073%2043.716%2034.6302%2043.716%2034.104%2043.1313L22.8771%2031.9044C22.2924%2031.3781%2022.2924%2030.501%2022.8771%2029.9163Z\'%20fill=\'%230984BA\'/%3e%3c/svg%3e"';

    $html .= '<div id="app" class="flex flex-col items-center h-[100dvh] relative">'.$imagen.'
              <style>
              html{
                font-family:Montserrat !important;--tw-text-opacity: 1 !important;color:rgb(255 255 255 / var(--tw-text-opacity)) !important;
              }
              footer#page-footer {
                  display: none;
              }
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
              .font-bold{
                  font-weight: 700;
                  font-family: Montserrat;
                  --tw-text-opacity: 1;
                  color: rgb(255 255 255);
                  margin: 15px;
              }
              .text-sm{
                font-weight: 700;
                font-family: Montserrat;
                --tw-text-opacity: 1;
                color: rgb(255 255 255);
              }
              </style>
              <main class="container px-5 flex flex-col gap-10">
              <header class="h-24 flex items-center">
                <a href="./universidad/course.php"> <img src="./universidad/assets/logo-BvwU9ppd.svg" alt="Logo del sitio"></a>
              </header>';
    foreach ($title_curse as $key => $title_mod) {
      //Header de encabezado

    $array_images_m1 = [
      ['imagen' => './universidad/static/images/idea.svg', 'cat_id' => '21', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/shield.svg', 'cat_id' => '21', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/graphic.svg', 'cat_id' => '21', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/gift.svg', 'cat_id' => '21', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/dollar.svg', 'cat_id' => '21', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/unbalanced-flip.svg', 'cat_id' => '21', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/table-columns.svg', 'cat_id' => '22', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/chart-line.svg', 'cat_id' => '22', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/pen-nib.svg', 'cat_id' => '22', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/megaphone.svg', 'cat_id' => '22', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/thumbs-up.svg', 'cat_id' => '22', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/vacuum-robot.svg', 'cat_id' => '22', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/file-invoice.svg', 'cat_id' => '23', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/book.svg', 'cat_id' => '23', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/chart-network.svg', 'cat_id' => '23', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/building-circle-check.svg', 'cat_id' => '23', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/money-bills.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/money-check-dollar.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/money-check-dollar-pen.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/file.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/list-ol.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/list-tree.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/cart-flatbed-boxes.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/file-contract.svg', 'cat_id' => '25', 'class-baner' => 'bg-[var(--color-primary)]', 'class-card' => 'bg-card-gradient-blue'],
      ['imagen' => './universidad/static/images/idea-red.svg', 'cat_id' => '32', 'class-baner' => 'bg-[var(--color-tertiary)]', 'class-card' => 'bg-card-gradient-red'],
      ['imagen' => './universidad/static/images/shield_red.svg', 'cat_id' => '32', 'class-baner' => 'bg-[var(--color-tertiary)]', 'class-card' => 'bg-card-gradient-red'],
      ['imagen' => './universidad/static/images/idea-red.svg', 'cat_id' => '31', 'class-baner' => 'bg-[var(--color-tertiary)]', 'class-card' => 'bg-card-gradient-red'],
      ['imagen' => './universidad/static/images/shield_red.svg', 'cat_id' => '31', 'class-baner' => 'bg-[var(--color-tertiary)]', 'class-card' => 'bg-card-gradient-red'],
      ['imagen' => './universidad/static/images/idea-purple.svg', 'cat_id' => '28', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/shield-purple.svg', 'cat_id' => '28', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/graphic-purple.svg', 'cat_id' => '28', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/idea-purple.svg', 'cat_id' => '29', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/shield-purple.svg', 'cat_id' => '29', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/graphic-purple.svg', 'cat_id' => '29', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/idea-purple.svg', 'cat_id' => '30', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/shield-purple.svg', 'cat_id' => '30', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple'],
      ['imagen' => './universidad/static/images/graphic-purple.svg', 'cat_id' => '30', 'class-baner' => 'bg-[var(--color-blue-check)]', 'class-card' => 'bg-card-gradient-purple']
    ];

    $filteredArray = array_filter($array_images_m1, function($item) use ($courses_mod) {
        return $item['cat_id'] === $courses_mod;
    });
    $keys_array = array_keys($filteredArray);

    $html .= '<section class="'.$filteredArray[$keys_array[0]]['class-baner'].' banner flex flex-col gap-y-6 md:flex-row bg-no-repeat bg-cover min-h-72 bg-hero-pattern overflow-hidden rounded-xl px-6 md:px-10 py-7">
                  <article class="relative w-full md:w-2/3 flex items-end pt-14 md:pt-0">
                    <a class="btn-back-banner" href="./course.php"><img '.$imagen2.' alt=""></a>
                    <h1 class="title-h1 text-3xl md:text-4xl font-bold whitespace-pre-line max-w-[550px]">
                      '.$title_mod->name.'</h1>
                  </article>
                  <article class="w-full md:w-1/3 flex justify-center items-center">

                    <div class="relative size-40">
                      <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-white" stroke-width="3"></circle>
                        <circle id="progressCircle" cx="18" cy="18" r="16" fill="none" class="stroke-current text-[var(--color-secondary)]" stroke-width="3" stroke-dasharray="100" stroke-dashoffset="70" stroke-linecap="round"></circle>
                      </svg>

                      <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2 flex flex-col">
                        <span id="progressValue" class="text-center text-2xl font-bold">30%</span>
                        <span class="text-center text-xl font-bold">Completado</span>
                      </div>
                    </div>

                  </article>
                </section>';
    }

    $html .= '<section class="cards-container grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">';
    $count = 0;
    $total_avg = 0;
    $last_completed = 1;
    foreach ($cursos as $key => $curso) {

      $avg_curso_user = $this->obtener_porcentaje_avance($USER->id,$curso->course_id);
      $enddate = $this->obtener_fecha_finalizacion_curso($curso->course_id, $USER->id, $avg_curso_user);

      //validamos si el modulo anterior esta completado, solo aplicara en los modulos requeridos
      if ($avg_curso_user == 100){
        $botones = '<a class="btn-details" href="/course/view.php?id='.$curso->course_id.'">Detalles</a>';
        $last_completed = 1;
      }else if ($last_completed == 1){
        $botones = '<a class="btn-continue" href="/course/view.php?id='.$curso->course_id.'">Continuar</a>';
        $last_completed = 0;
      }else{
        $botones = '<a class="btn-block" style="width: 40%;" href="#">Comenzar</a>';
        $last_completed = 0;
      }

      $html .= '
              <!-- card structure -->
              <article class="card active">
                <header class="grid bg-card-gradient grid-cols-3 h-40 items-center '.$filteredArray[$keys_array[$count]]['class-card'].'">
                  <figure class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 p-4 bg-white rounded-full flex justify-center items-center">
                    <img src="'.$filteredArray[$keys_array[$count]]['imagen'].'" alt="icono de tarjeta">
                  </figure>
                  <div class="col-span-2">
                    <span class="text-sm">M1.1</span>
                    <label class="text-2xl font-bold">'.$curso->course_name.'</label>
                  </div>
                </header>
                <div class="content-card">
                  <span class="text-base font-bold" style="color: black !important;">Programa</span>
                  <div class="flex gap-1 items-center">
                    <figure class="status-icon '.$filteredArray[$keys_array[$count]]['class-baner'].'">
                      <i class="fa-solid fa-check i-icon-text"></i>
                    </figure>
                    <span class="'.($avg_curso_user == 100 ? 'complete' : 'incomplete').'">'.($avg_curso_user == 100 ? 'Completado': 'Requerido').'</span>
                  </div>
                  <span>'.$enddate.'</span>
                  <span>'.$avg_curso_user.'%</span>
                  '.$botones.'
                </div>
              </article>';
              $count++;
              $total_avg += $avg_curso_user;
    }

    $avg_curso_user_general = ((int) ($total_avg / $count));

    $html .= "</main>
            <img class=\"w-full md:max-w-[1000px] self-end mt-20\" src=\"data:image/svg+xml,%3csvg%20width='1033'%20height='61'%20viewBox='0%200%201033%2061'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M0%2061H1033V0H60C26.8629%200%200%2026.8629%200%2060V61Z'%20fill='%23B74121'/%3e%3cpath%20d='M258.85%2061H1033V0H318.85C285.713%200%20258.85%2026.8629%20258.85%2060V61Z'%20fill='%238EC100'/%3e%3cpath%20d='M505.715%2061H1033V0H565.715C532.578%200%20505.715%2026.8629%20505.715%2060V61Z'%20fill='%230984BA'/%3e%3c/svg%3e\" alt=\"colores header\">
          </div>
          <script>
              const updateProgress = (offset) => {
                const progressCircle = document.querySelector('#progressCircle');
                const progressValue = document.querySelector('#progressValue');
                const progress = 100 - offset;
                progressCircle.setAttribute('stroke-dashoffset', progress);
                progressValue.textContent = `".$avg_curso_user_general."%`;
              };

          // Ejecutar updateProgress después de que el DOM se haya cargado completamente
          document.addEventListener('DOMContentLoaded', () => {
              // Puedes ajustar el offset aquí como sea necesario
              const offset = ".$avg_curso_user_general.";  // Ejemplo de porcentaje
              updateProgress(offset);
          });
          </script>";

    return $html;
  }

}

?>

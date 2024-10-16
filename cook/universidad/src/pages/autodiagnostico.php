<?php
require_once '../../class_autodiagnostico.php'; // Asegúrate de que la ruta sea correcta
require_once '../../db.php'; // Asegúrate de que la ruta sea correcta

// Obtener la conexión a la base de datos
$pdo = getConnection();

// Instanciar la clase Autodiagnostico
$autodiagnostico = new Autodiagnostico($pdo);

// Llamar al método getModulos
$modulosJson = $autodiagnostico->getModulos();
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/svg+xml" href="/universidad/vite.svg" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Universidad Mondragón</title>


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
  <link rel="stylesheet" crossorigin href="/universidad/assets/autodiagnostico-Di8n6sjF.css">
  <link rel="stylesheet" crossorigin href="/universidad/assets/global-CPC3-saQ.css">
</head>

<body>
  <div id="app" class="flex w-full h-full min-h-[100dvh] justify-center relative">
    <main class="w-full lg:ml-44 lg:mr-0">
      <header id="header" class="flex w-full relative lg:py-24 py-16">
        <img class="absolute w-72 lg:w-auto top-0 right-0" src="data:image/svg+xml,%3csvg%20width='431'%20height='61'%20viewBox='0%200%20431%2061'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M0%200H431V61H60C26.8629%2061%200%2034.1371%200%200.999999V0Z'%20fill='%23B74121'/%3e%3cpath%20d='M108%200H431V61H168C134.863%2061%20108%2034.1371%20108%200.999999V0Z'%20fill='%238EC100'/%3e%3cpath%20d='M211%200H431V61H271C237.863%2061%20211%2034.1371%20211%200.999999V0Z'%20fill='%230984BA'/%3e%3c/svg%3e" alt="">
        <a href="#progress-menu" class="lg:hidden font-semibold absolute top-28 right-5 bg-[var(--color-primary)] rounded-full py-2 px-5 flex items-center gap-x-3">
          Mapa de progreso <span class="bg-white size-5 rounded-full text-[var(--color-primary)] font-bold flex justify-center items-center">?</span>
        </a>
        <a class="w-40 lg:w-auto mx-5" href="/universidad/index.html"> <img src="/universidad/assets/logo-BvwU9ppd.svg" alt="Logo del sitio"></a>
      </header>
      <section class="mx-5 sm:mx-10">
        <h1 id="title-theme" class="text-2xl mb-10 lg:mb-20 lg:text-3xl font-bold text-[var(--color-primary)]">M2. MERCADOTECNIA Y ENFOQUE AL CLIENTE</h1>

        <ol class="list-decimal text-black px-5 lg:px-0 flex flex-col gap-y-12">
          <!-- Preguntas 
          <li class="font-bold text-lg">
            <h2 class="pb-4">¿Cuenta con un plan de negocio s?</h2>
            <input type="radio" id="option-1-1" name="group1" value="option1">
            <label class="font-normal text-sm" for="option-1-1">NO.</label><br>
            <input type="radio" id="option-1-2" name="group1" value="option2">
            <label class="font-normal text-sm" for="option-1-2">SÍ, pero no lo he aplicado tal cual.</label><br>
            <input type="radio" id="option-1-3" name="group1" value="option3">
            <label class="font-normal text-sm" for="option-1-3">SÍ, es mi guía y lo actualizo cada cierto tiempo.</label><br>
          </li>

          <li class="font-bold text-lg">
            <h2 class="pb-4">En cuanto a la función comercial, ¿con qué respuesta se siente más identificado?</h2>
            <input type="radio" id="option-2-1" name="group2" value="option1">
            <label class="font-normal text-sm" for="option-2-1">Es imprescindible una buena organización comercial.</label><br>
            <input type="radio" id="option-2-2" name="group2" value="option2">
            <label class="font-normal text-sm" for="option-2-2">Depende del producto que se venta.</label><br>
            <input type="radio" id="option-2-3" name="group2" value="option3">
            <label class="font-normal text-sm" for="option-2-3">Lo importante es disponer de un buen producto.</label><br>
          </li>

          <li class="font-bold text-lg">
            <h2 class="pb-4">¿Qué elementos de los siguientes considera imprescindibles para poner en marcha su negocio?</h2>
            <input type="radio" id="option-3-1" name="group3" value="option1">
            <label class="font-normal text-sm" for="option-3-1">Definir cuáles van a ser mis clientes.</label><br>
            <input type="radio" id="option-3-2" name="group3" value="option2">
            <label class="font-normal text-sm" for="option-3-2">Disponer de dinero y de un producto interesante.</label><br>
            <input type="radio" id="option-3-3" name="group3" value="option3">
            <label class="font-normal text-sm" for="option-3-3">Trabajar duro.</label><br>
          </li>

          <li class="font-bold text-lg">
            <h2 class="pb-4">En cuanto a la función comercial, ¿con qué respuesta se siente más identificado?</h2>
            <input type="radio" id="option-4-1" name="group4" value="option1">
            <label class="font-normal text-sm" for="option-4-1">Es imprescindible una buena organización comercial.</label><br>
            <input type="radio" id="option-4-2" name="group4" value="option2">
            <label class="font-normal text-sm" for="option-4-2">Depende del producto que se venta.</label><br>
            <input type="radio" id="option-4-3" name="group4" value="option3">
            <label class="font-normal text-sm" for="option-4-3">Lo importante es disponer de un buen producto.</label><br>
          </li>

          <li class="font-bold text-lg">
            <h2 class="pb-4">¿Con qué medios de publicidad cuentas?  (Cada casilla marcada vale 1 pt)</h2>
            <input type="checkbox" id="option-5-1" value="option1">
            <label class="font-normal text-sm" for="option-5-1">Es imprescindible una buena organización comercial.</label><br>
            <input type="checkbox" id="option-5-2" value="option2">
            <label class="font-normal text-sm" for="option-5-2">Depende del producto que se venta.</label><br>
            <input type="checkbox" id="option-5-3" value="option3">
            <label class="font-normal text-sm" for="option-5-3">Lo importante es disponer de un buen producto.</label><br>
          </li>
          -->
        </ol>
      </section>

      <footer class="my-10 flex justify-center lg:justify-start">
        <section class="text-black w-[90%]">
          <hr class="border-[var(--color-secondary)]">
          <div class="btn-container flex w-full my-5">
            
          </div>
        </section>
      </footer>
    </main>
    <aside id="progress-menu" class="h-full lg:static lg:overflow-auto lg:h-auto lg:translate-y-[initial] overflow-scroll w-[300px] lg:w-[500px] bg-[var(--color-primary)] px-5 pt-10 lg:px-10 lg:pt-16 fixed right-0 -translate-y-full transition-transform duration-300 target:translate-y-0">
      <a class="absolute top-6 right-10 lg:hidden" href="#header"><i class="fa-solid fa-close text-2xl"></i></a>
      <ul class="flex flex-col">
        <li class="li-section-active-time-line">
          <a href="" class="a-time-line-active">M1. IDEA DE NEGOCIO</a>
          <a class="point-time-line-check bg-white shadow-check-shadow-white"
            href="/universidad/universidad/src/pages/modulo.html"><i class="fa-solid fa-check i-time-line"></i></a>
          <hr class="left-time-line bg-white">
        </li>

        <li class="li-section-active-time-line">
          <hr class="right-time-line bg-white">
          <a href="" class="a-time-line-active">M2. MERCADOTECNIA Y ENFOQUE AL CLIENTE</a>
          <a class="point-time-line-check bg-white shadow-check-shadow-white"
            href="/universidad/universidad/src/pages/modulo.html"><i class="fa-solid fa-check i-time-line"></i></a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">M3. DESARROLLO DE PRODUCTOS O SERVICIOS</a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">M4. GESTIÓN FINANCIERA Y LEGAL</a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">L1. EL COMPROMISO DE EMPRENDER</a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">L2. GESTIÓN DE EQUIPOS COOPERATIVOS</a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">L3. LIDERAZGO TRANSFORMADOR</a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">S.1. EMPRENDER CON ENFOQUE DE ECONOMÍA SOCIAL</a>
          <hr class="left-time-line bg-white line-empty-time-line">
        </li>

        <li class="li-section-pending-time-line">
          <hr class="right-time-line bg-white line-empty-time-line">
          <a class="point-time-line-require bg-white" href=""></a>
          <a href="" class="a-time-line-lock">S.2 INNOVACIÓN SOCIAL Y DESARROLLO SOSTENIBLE</a>
        </li>

      </ul>

    </aside>
  </div>
  
  <script>
    const titles = <?= $modulosJson; ?>;

    const preguntas = {
      'M1':{
        'title': 'M1. IDEA DE NEGOCIO',
        'start': 1,
        'pagination': 'start',
        'questions': [
          {
            'title': '¿Ya tienes un emprendimiento en marcha?',
            'type': 'radio',
            'options': [
              'NO.',
              'SÍ, con menos de 2 años de antigüedad.',
              'SÍ, con más de 3 años de antigüedad.'
            ]
          },
          {
            'title': '¿Considera que tiene una idea que le va a permitir montar una empresa?',
            'type': 'radio',
            'options': [
              'Creo que tengo una idea.',
              'El mercado es lo verdaderamente importante.',
              'Considero que la idea es el principio del camino de emprender.'
            ]
          },
          {
            'title': '¿Va a montar su empresa porque está convencido de que tiene un producto o servicio que se puede vender con éxito?',
            'type': 'radio',
            'options': [
              'Mi idea central es el cliente.',
              'Sí, con mi producto o servicio voy a competir con éxito.',
              'Cuando se monta una empresa hay que saber lo que hay que hacer.'
            ]
          },
        ]
      },
      'M2': {
        'title': 'M2. MERCADOTECNIA Y ENFOQUE AL CLIENTE',
        'start': 4,
        'pagination': 'middle',
        'questions': [
          {
            'title': '¿Cuenta con un plan de negocios?',
            'type': 'radio',
            'options': [
              'NO.',
              'SÍ, pero no lo he aplicado tal cual.',
              'SÍ, es mi guía y lo actualizo cada cierto tiempo.'
            ]
          },
          {
            'title': 'En cuanto a la función comercial, ¿con qué respuesta se siente más identificado?',
            'type': 'radio',
            'options': [
              'Es imprescindible una buena organización comercial.',
              'Depende del producto que se venta.',
              'Lo importante es disponer de un buen producto.'
            ]
          },
          {
            'title': '¿Qué elementos de los siguientes considera imprescindibles para poner en marcha su negocio?',
            'type': 'radio',
            'options': [
              'Definir cuáles van a ser mis clientes.',
              'Disponer de dinero y de un producto interesante.',
              'Trabajar duro.'
            ]
          },
          {
            'title': 'En cuanto a la función comercial, ¿con qué respuesta se siente más identificado?',
            'type': 'radio',
            'options': [
              'Es imprescindible una buena organización comercial.',
              'Depende del producto que se venta.',
              'Lo importante es disponer de un buen producto.'
            ]
          },
          {
            'title': '¿Con qué medios de publicidad cuentas?  (Cada casilla marcada vale 1 pt)',
            'type': 'checkbox',
            'options': [
              'Es imprescindible una buena organización comercial.',
              'Depende del producto que se venta.',
              'Lo importante es disponer de un buen producto.'
            ]
          },
          
        ]
      },
      'S2':{
        'title': 'S.2 INNOVACIÓN SOCIAL Y DESARROLLO SOSTENIBLE',
        'start': 32,
        'pagination': 'end',
        'questions': [
          {
            'title': '¿Ya tienes un emprendimiento en marcha?',
            'type': 'radio',
            'options': [
              'NO.',
              'SÍ, con menos de 2 años de antigüedad.',
              'SÍ, con más de 3 años de antigüedad.'
            ]
          },
          {
            'title': '¿Considera que tiene una idea que le va a permitir montar una empresa?',
            'type': 'radio',
            'options': [
              'Creo que tengo una idea.',
              'El mercado es lo verdaderamente importante.',
              'Considero que la idea es el principio del camino de emprender.'
            ]
          },
          {
            'title': '¿Va a montar su empresa porque está convencido de que tiene un producto o servicio que se puede vender con éxito?',
            'type': 'radio',
            'options': [
              'Mi idea central es el cliente.',
              'Sí, con mi producto o servicio voy a competir con éxito.',
              'Cuando se monta una empresa hay que saber lo que hay que hacer.'
            ]
          },
        ]
      },
    };

    const updateView = (module) => {
      
      const title = document.getElementById('title-theme');
      const btnContainer = document.querySelector('.btn-container');
      title.innerHTML = module.title;

      const ol = document.querySelector('ol');
      ol.innerHTML = '';
      ol.setAttribute('start', module.start);

      module.questions.forEach((question, i) => {
        const li = document.createElement('li');
        li.classList.add('font-bold', 'text-lg');
        const h2 = document.createElement('h2');
        h2.classList.add('pb-4');
        h2.innerHTML = question.title;
        li.appendChild(h2);

        if (question.type === 'radio') {
          question.options.forEach((option, j) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'radio');
            input.setAttribute('id', `option-${i + 1}-${j + 1}`);
            input.setAttribute('name', `group${i + 1}`);
            input.setAttribute('value', `option${i + 1}`);
            input.classList.add('mr-2');
            li.appendChild(input);

            const label = document.createElement('label');
            label.classList.add('font-normal', 'text-sm');
            label.setAttribute('for', `option-${i + 1}-${j + 1}`);
            label.innerHTML = option;
            li.appendChild(label);

            const br = document.createElement('br');
            li.appendChild(br);
          });
        } else if (question.type === 'checkbox') {
          question.options.forEach((option, j) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'checkbox');
            input.setAttribute('id', `option-${i + 1}-${j + 1}`);
            input.setAttribute('value', `option${i + 1}`);
            input.classList.add('mr-2');
            li.appendChild(input);

            const label = document.createElement('label');
            label.classList.add('font-normal', 'text-sm');
            label.setAttribute('for', `option-${i + 1}-${j + 1}`);
            label.innerHTML = option;
            li.appendChild(label);

            const br = document.createElement('br');
            li.appendChild(br);
          });
        }

        ol.appendChild(li);
      });

      if(module.pagination === 'start') {
        btnContainer.classList.remove('justify-between');
        btnContainer.classList.add('justify-end');
        btnContainer.innerHTML = `
          <button class="btn-auto-next">Siguiente</button>
        `;
      } else if(module.pagination === 'middle') {
        btnContainer.classList.remove('justify-end');
        btnContainer.classList.add('justify-between');
        btnContainer.innerHTML = `
          <button class="btn-auto-back">Volver</button>
          <button class="btn-auto-next">Siguiente</button>
        `;
      } else if(module.pagination === 'end') {
        btnContainer.classList.remove('justify-end');
        btnContainer.classList.add('justify-between');
        btnContainer.innerHTML = `
          <button class="btn-auto-back">Volver</button>
          <button class="btn-auto-send">Enviar</button>
        `;
      }

      updateMap(module.title);
    };

    updateView(preguntas['M2']);
    
  </script>
</body>

</html>
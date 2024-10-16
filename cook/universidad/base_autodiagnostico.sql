SELECT mdl_modulos.Name_m, mdl_modulos.min_points, mdl_modulos.max_points, mdl_modulos.relative_activity_moodle, mdl_questions_autodiagnostico.question_text, mdl_questions_autodiagnostico.type_answer, mdl_Answer_autodiagnostico.Id, mdl_Answer_autodiagnostico.Answer_text, mdl_Answer_autodiagnostico.points FROM mdl_modulos inner join mdl_questions_autodiagnostico on mdl_modulos.id = mdl_questions_autodiagnostico.module_assing inner join mdl_Answer_autodiagnostico on mdl_Answer_autodiagnostico.Question_id = mdl_questions_autodiagnostico.Id order by mdl_modulos.id, mdl_questions_autodiagnostico.Id, mdl_Answer_autodiagnostico.Id;

CREATE TABLE mdl_modulos (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name_m TEXT NOT NULL,
    relative_activity_moodle INT DEFAULT NULL,
    max_points int DEFAULT 0,
    min_points int DEFAULT 0,
    created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_At DATETIME DEFAULT NULL
);

CREATE TABLE mdl_questions_autodiagnostico (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    type_answer varchar(30) NOT NULL,
    module_assing INT NOT NULL,
    points INT NOT NULL,
    created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_At DATETIME DEFAULT NULL,
    FOREIGN KEY (module_assing) REFERENCES mdl_modulos(Id)
);

CREATE TABLE mdl_Answer_autodiagnostico (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Answer_text TEXT not null,
    Question_id int not null,
    points int not null,
    created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_At DATETIME DEFAULT null,
    FOREIGN KEY (Question_id) REFERENCES mdl_questions_autodiagnostico(Id)
)

CREATE TABLE mdl_answer_users_questions (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Question_id INT not null,
    Answer_id INt not null,
    User_id_moodle int not null,
    correct int not null,
    created_At DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_At DATETIME DEFAULT null,
    FOREIGN KEY (Answer_id) REFERENCES mdl_Answer_autodiagnostico(Id),
    FOREIGN KEY (Question_id) REFERENCES mdl_questions_autodiagnostico(Id)
);

-- Modulos de autodiagnostico --
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('M1. IDEA DE NEGOCIO', 0, 8, 5);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('M2. MERCADOTECNIA Y ENFOQUE AL CLIENTE', 0, 15, 11);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('M3. DESARROLLO DE PRODUCTOS O SERVICIOS', 0, 9, 6);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('M4. GESTIÓN FINANCIERA Y LEGAL', 0, 11, 8);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('L1. EL COMPROMISO DE EMPRENDER', 0, 14, 9);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('L2.  GESTIÓN DE EQUIPOS COOPERATIVOS', 0, 9, 6);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('L3.  LIDERAZGO TRANSFORMADOR', 0, 11, 8);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('S.1. EMPRENDER CON ENFOQUE DE ECONOMÍA SOCIAL', 0, 8, 5);
INSERT INTO mdl_modulos(Name_m, relative_activity_moodle, max_points, min_points) VALUES('S.2 INNOVACIÓN SOCIAL Y DESARROLLO SOSTENIBLE', 0, 9, 6);


-- Insertas preguntas de autodiagnostico M1 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('1. ¿Ya tienes un emprendimiento en marcha?', 'radio', 1, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('2. ¿Considera que tiene una idea que le va a permitir montar una empresa?', 'radio', 1, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('3. ¿Va a montar su empresa porque está convencido de que tiene un producto o servicio que se puede vender con éxito?', 'radio', 1, 3);

-- Insertas preguntas de autodiagnostico M2 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('4. ¿Cuenta con un plan de negocios?', 'radio', 2, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('5. En cuanto a la función comercial, ¿con qué respuesta se siente más identificado?', 'radio', 2, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('6. ¿Qué elementos de los siguientes considera imprescindibles para poner en marcha su negocio?', 'radio', 2, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('7. En su opinión ¿qué es más importante: vender o planear su proyecto?0', 'radio', 2, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('8. ¿Con qué medios de publicidad cuentas?  (Cada casilla marcada vale 1 pt)', 'checkbox', 2, 1);

-- Insertas preguntas de autodiagnostico M3 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('9. ¿Cuál de los siguientes elementos es fundamental al desarrollar un plan de producción?', 'radio', 3, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('10. ¿Qué metodología se utiliza comúnmente para mejorar la eficiencia en los procesos de producción?', 'radio', 3, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('11. ¿Cuando hay un cambio inesperado en la demanda, ¿cuál es la mejor práctica para ajustar el proceso de producción?', 'radio', 3, 3);

-- Insertas preguntas de autodiagnostico M4 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('12. ¿Aproximadamente cuánto factura mensualmente tu emprendimiento?', 'radio', 4, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('13. ¿Cuenta con un contador y un abogado de confianza?', 'radio', 4, 1);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('14. ¿Cómo ha capitalizado o piensa capitalizar su emprendimiento? ', 'radio', 4, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('15. ¿Sabe analizar e interpretar análisis financieros?', 'radio', 4, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('16. ¿Conoce los requisitos legales para abrir una empresa?', 'radio', 4, 2);

-- Insertas preguntas de autodiagnostico L1 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('17. Desde el punto de vista de la motivación, señale la respuesta con las que más se identifique:', 'radio', 5, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('18. ¿Qué factores son importantes para usted a la hora de emprender?', 'radio', 5, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('19. ¿Con cuál de las siguientes definiciones se siente más identificado?', 'radio', 5, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('20. ¿Cuál es su postura ante el tiempo que debe dedicar a su empresa?', 'radio', 5, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('21. ¿Cuál es su actitud ante los cambios inesperados y frecuentes en su jornada?', 'radio', 5, 3);

-- Insertas preguntas de autodiagnostico L2 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('22. ¿Qué hace para que los demás le escuchen en una discusión?', 'radio', 6, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('23. Ante la necesidad de liderar un equipo, usted…', 'radio', 6, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('24. En el trabajo con personas usted...', 'radio', 6, 3);

-- Insertas preguntas de autodiagnostico L3 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('25. ¿En qué medida considera que hay que arriesgarse al tomar decisiones?', 'radio', 7, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('26. Ante situaciones imponderables ¿cómo evalúa estas situaciones?', 'radio', 7, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('27. Si tiene que cambiar de opinión, usted…', 'radio', 7, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('28. ¿Cómo manejas los conflictos dentro de tu equipo?', 'radio', 7, 3);

-- Insertas preguntas de autodiagnostico S1 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('29. ¿Sabe usted en qué consiste la Economía Social?', 'radio', 8, 2);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('30. Desde su punto de visto, una empresa o emprendimiento debe gobernarse:', 'radio', 8, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('31. Un emprendimiento es responsable socialmente sí:', 'radio', 8, 3);

-- Insertas preguntas de autodiagnostico S2 --
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('32. ¿Cree que su emprendimiento puede tener impacto social?', 'radio', 9, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('33. El desarrollo sostenible consiste en:', 'radio', 9, 3);
INSERT INTO mdl_questions_autodiagnostico (question_text, type_answer, module_assing, points) VALUES ('34. ¿Cómo abordas la resolución de problemas complejos en tu trabajo?', 'radio', 9, 3);


-- Insertar answers de autodiagnostico M1--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('NO', 1, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, con menos de 2 años de antigüedad. ', 1, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, con más de 3 años de antigüedad.', 1, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Creo que tengo una idea.', 2, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('El mercado es lo verdaderamente importante.', 2, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Considero que la idea es el principio del camino de emprender.', 2, 3);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Mi idea central es el cliente.', 3, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Sí, con mi producto o servicio voy a competir con éxito.   ', 3, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Cuando se monta una empresa hay que saber lo que hay que hacer.   ', 3, 1);

-- Insertar answers de autodiagnostico M2--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('NO', 4, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, pero no lo he aplicado tal cual', 4, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, es mi guía y lo actualizo cada cierto tiempo.', 4, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Es imprescindible una buena organización comercial', 5, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Depende del producto que se venta', 5, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Lo importante es disponer de un buen producto.', 5, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Definir cuáles van a ser mis clientes', 6, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Disponer de dinero y de un producto interesante', 6, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Trabajar duro', 6, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Es necesario platificar el proyecto', 7, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Es necesario plantearse objetivos', 7, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Los importante es vender.', 7, 3);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Ninguno', 8, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Redes sociales', 8, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Página web', 8, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Publicidad impresa', 8, 1);

-- Insertar answers de autodiagnostico M3--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('La decoración del espacio de trabajo', 9, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('La identificación de la demanda del mercado', 9, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('La elección de un logo atractivo', 9, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Análisis FODA', 10, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Lean Manufacturing', 10, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No lo sé', 10, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Ignorar el cambio y seguir con el plan original', 11, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Reducir la calidad del producto para mantener los costos bajos', 11, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Revisar y ajustar el plan de producción basado en la nueva demanda', 11, 3);

-- Insertar answers de autodiagnostico M4--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('$0 - $5 mil pesos', 12, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('$6 mil - $10 mil pesos', 12, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('$11 mil - $25 mil pesos', 12, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('$25 mil - $50 mil pesos', 12, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Más de $50mil pesos', 12, 3);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SI', 13, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('NO', 13, 0);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Aún no', 14, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Con recursos propios (ya invertí o cuento con algunos ahorros para hacerlo)', 14, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Con préstamo bancario', 14, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Con financiamiento de programas gubernamentales', 14, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Con financiamiento internacional', 14, 3);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('NO', 15, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, pero a nivel muy básico', 15, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, los utilizo para la toma de decisiones de mi negocio', 15, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('NO', 16, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, pero nunca he abierto una empresa', 16, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('SÍ, los conocí cuando formalicé mi emprendimiento.', 16, 2);

-- Insertar answers de autodiagnostico L1--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Me gustaría ser mi propio jefe', 17, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No quiero que la disciplina de trabajo me la imponga otra persona', 17, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No me gusta trabajar para otros', 17, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Es importante tener ingresos propios y satisfacer mis necesidades', 18, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Lo más relevante es poder satisfacer las necesidades de las personas a través de un producto o servicio que sea responsable de forma integral', 18, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('A mí me encantan las ventas y eso es fundamental para que un negocio funcione', 18, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Me gusta organizar nuevos proyectos', 19, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Tengo iniciativa cuando es necesaria', 19, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No me gusta emprender nuevos proyectos.', 19, 0);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Dedicación total', 20, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No me importa trabajar más, cuando sea necesario.', 20, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('El trabajo tiene sus horas.', 20, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Me gustan los cambios', 21, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No me gustan los cambios', 21, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Procuro evitarlos, aunque si se producen, los priorizo y resuelvo', 21, 2);

-- Insertar answers de autodiagnostico L2--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Elevar el tono frente a los demás.', 22, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Siempre escuchan lo que digo', 22, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No soy capaz de que los demás me escuchen.', 22, 0);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Muestra capacidad de comprender a sus colaboradores, es muy empático.', 23, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Me oriento a los resultados, no hay tiempo para hacer amigos', 23, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Es asertivo y se muestra flexible en sus planteamientos siempre que se consiga el resultado', 23, 3);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Prefiere trabajar solo/a, así avanza más rápido', 24, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Prefiere trabajar solo/a, así avanza más rápido', 24, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Le gusta delegar, que cada quien aporte, pero lleva un puntual seguimiento para cumplir objetivos', 24, 2);

-- Insertar answers de autodiagnostico L3--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('La toma de decisiones siempre comporta riesgo.', 25, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Necesito conocer el riesgo y saber si puedo afrontarlo', 25, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Prefiero no arriesgarme', 25, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('En ocasiones conviene buscar asesoramiento externo', 26, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('La intuición y la experiencia me permiten sopesar el riesgo', 26, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No me gustan los cambios, procuro evitarlos.', 26, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Suele cambiar rápidamente de opinión', 27, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Le cuesta mucho porque está convencido de lo que piensa', 27, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Cambia de opinión cuando se da cuenta de que estaba equivocado/a', 27, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Evito confrontaciones y dejo que los miembros del equipo resuelvan los problemas por sí mismos.', 28, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Intervengo cuando es necesario para mediar y buscar una solución equitativa para todos los involucrados.', 28, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Tiendo a tomar decisiones rápidas y firmes para resolver el conflicto, a veces sin consultar al equipo.', 28, 2);

-- Insertar answers de autodiagnostico S1--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('No, nunca lo había escuchado.', 29, 0);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Sí, pero no tengo conocimientos claros o son muy básicos.', 29, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Sí, yo soy parte de la economía social desde hace tiempo.', 29, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Haciendo lo que el dueño/a dice, para eso le paga a los demás', 30, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Mediante acuerdos participativos para que todos los asuman ', 30, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Mediante códigos y políticas de comportamiento.', 30, 2);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Se avoca a cumplir la ley', 31, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Si además de cumplir la ley, desarrolla actividades en favor de la comunidad como parte de su perfil estratégico.', 31, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Si da donativos de vez en cuando.', 31, 1);

-- Insertar answers de autodiagnostico S2--
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Sin duda, se pueden resolver problemas sociales a través de un negocio', 32, 3);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Sin duda, pero para hacerlo, primero hay que hacer dinero y después preocuparse por los demás.', 32, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('El impacto social sólo pueden lograrlo las grandes empresas.', 32, 1);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Hacer acciones verdes para cuidar el medio ambiente', 33, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('En tener negocios que no se mueran en el tiempo, que se sostengan.', 33, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('En cuidar a las personas y al planeta, y asegurar la properidad para todos.', 33, 3);

INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Prefiero usar soluciones que ya han sido probadas y aprobadas para garantizar resultados previsibles.', 34, 1);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Tomo enfoques y métodos previos y los ajusto para que se adapten a la situación actual, realizando pequeñas innovaciones', 34, 2);
INSERT INTO mdl_Answer_autodiagnostico(Answer_text, Question_id, points) VALUES ('Investigo y experimento con ideas innovadoras y enfoques no convencionales para encontrar soluciones originales a problemas complejos', 34, 3);

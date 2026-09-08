
DROP TABLE IF EXISTS receta_detalle;
DROP TABLE IF EXISTS recetas;
DROP TABLE IF EXISTS medicamentos;
DROP TABLE IF EXISTS diagnosticos;
DROP TABLE IF EXISTS enfermedades;
DROP TABLE IF EXISTS controles_antropometricos;
DROP TABLE IF EXISTS consultas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS personal_salud;
DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS zonas;
DROP TABLE IF EXISTS ciudades;
DROP TABLE IF EXISTS provincias;
/* =========================================================
   TABLAS DE UBICACIÓN
========================================================= */
CREATE TABLE provincias (
    id_provincia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

CREATE TABLE ciudades (
    id_ciudad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_provincia INT NOT NULL,
    FOREIGN KEY (id_provincia) REFERENCES provincias(id_provincia)
);

CREATE TABLE zonas (
    id_zona INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Urbana','Rural') NOT NULL
);

/* =========================================================
   TABLA PACIENTES
========================================================= */
CREATE TABLE pacientes (
    id_paciente INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    sexo ENUM('M','F') NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    id_ciudad INT NOT NULL,
    id_zona INT NOT NULL,
    direccion VARCHAR(200),
    telefono VARCHAR(20),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ciudad) REFERENCES ciudades(id_ciudad),
    FOREIGN KEY (id_zona) REFERENCES zonas(id_zona)
);

/* =========================================================
   PERSONAL DE SALUD
========================================================= */
CREATE TABLE personal_salud (
    id_personal INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    cargo ENUM('Médico','Enfermería','Nutricionista','Administrador') NOT NULL
);

/* =========================================================
   TABLA DE USUARIOS DEL SISTEMA
========================================================= */
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    id_personal INT UNIQUE NOT NULL, -- Vinculado al personal de salud
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL, -- Para almacenar contraseñas encriptadas
    email VARCHAR(100) UNIQUE,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    ultimo_acceso DATETIME,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_personal) REFERENCES personal_salud(id_personal) ON DELETE CASCADE
);


/* =========================================================
   CONSULTAS MÉDICAS
========================================================= */
CREATE TABLE consultas (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    id_personal INT NOT NULL,
    fecha_consulta DATE NOT NULL,
    motivo VARCHAR(200),
    observaciones TEXT,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente),
    FOREIGN KEY (id_personal) REFERENCES personal_salud(id_personal)
);

/* =========================================================
   CONTROLES ANTROPOMÉTRICOS
========================================================= */
CREATE TABLE controles_antropometricos (
    id_control INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    edad INT NOT NULL,
    peso_kg DECIMAL(5,2) NOT NULL,
    altura_m DECIMAL(4,2) NOT NULL,
    imc DECIMAL(5,2),
    diagnostico_nutricional VARCHAR(50),
    FOREIGN KEY (id_consulta) REFERENCES consultas(id_consulta)
);


/* =========================================================
   CATÁLOGO DE ENFERMEDADES
========================================================= */
CREATE TABLE enfermedades (
    id_enfermedad INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL
);

CREATE TABLE diagnosticos (
    id_diagnostico INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    id_enfermedad INT NOT NULL,
    FOREIGN KEY (id_consulta) REFERENCES consultas(id_consulta),
    FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad)
);

/* =========================================================
   MEDICAMENTOS Y RECETAS
========================================================= */
CREATE TABLE medicamentos (
    id_medicamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL
);

CREATE TABLE recetas (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_consulta INT NOT NULL,
    indicaciones TEXT,
    FOREIGN KEY (id_consulta) REFERENCES consultas(id_consulta)
);

CREATE TABLE receta_detalle (
    id_receta_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_receta INT NOT NULL,
    id_medicamento INT NOT NULL,
    dosis VARCHAR(100),
    FOREIGN KEY (id_receta) REFERENCES recetas(id_receta),
    FOREIGN KEY (id_medicamento) REFERENCES medicamentos(id_medicamento)
);





/* =========================================================
   INSERCIÓN DE DATOS MAESTROS (Dependencias)
========================================================= */

INSERT INTO provincias (nombre) VALUES ('Pichincha'), ('Guayas'), ('Azuay'), ('Manabí');

INSERT INTO ciudades (nombre, id_provincia) VALUES 
('Quito', 1), ('Guayaquil', 2), ('Cuenca', 3), ('Manta', 4), ('Portoviejo', 4);

INSERT INTO zonas (tipo) VALUES ('Urbana'), ('Rural');

INSERT INTO personal_salud (nombres, apellidos, cargo) VALUES 
('Juan', 'Pérez', 'Médico'),
('María', 'García', 'Enfermería'),
('Luis', 'Rodríguez', 'Nutricionista'),
('Ana', 'Martínez', 'Médico');

/* =========================================================
   REGISTROS PARA LOS 4 MIEMBROS DEL PERSONAL EXISTENTES
========================================================= */
INSERT INTO usuarios (id_personal, username, password_hash, email, estado) VALUES 
(1, 'jperez_med', '$2y$10$ysVG5WQQNxE5fBqQisv6dOc2KPJBWyvUtufkSTECO1Xxttfo7sS5O', 'juan@salud.gob.ec', 'Activo'),
(2, 'mgarcia_enf', '$2y$10$ysVG5WQQNxE5fBqQisv6dOc2KPJBWyvUtufkSTECO1Xxttfo7sS5O', 'maria@salud.gob.ec', 'Activo'),
(3, 'lrodriguez_nut', '$2y$10$ysVG5WQQNxE5fBqQisv6dOc2KPJBWyvUtufkSTECO1Xxttfo7sS5O', 'luis@salud.gob.ec', 'Activo'),
(4, 'amartinez_med', '$2y$10$ysVG5WQQNxE5fBqQisv6dOc2KPJBWyvUtufkSTECO1Xxttfo7sS5O', 'ana@salud.gob.ec', 'Activo');

INSERT INTO enfermedades (nombre) VALUES 
('Hipertensión Arterial'), ('Diabetes Mellitus Tipo 2'), 
('Anemia Ferropénica'), ('Obesidad Grado I'), ('Rinofaringitis Aguda');

INSERT INTO medicamentos (nombre) VALUES 
('Paracetamol 500mg'), ('Metformina 850mg'), 
('Losartán 50mg'), ('Amoxicilina 500mg'), ('Sulfato Ferroso');

/* =========================================================
   INSERCIÓN DE 100 PACIENTES
========================================================= */

INSERT INTO pacientes (cedula, nombres, apellidos, sexo, fecha_nacimiento, id_ciudad, id_zona, direccion, telefono) VALUES
('1712345601', 'Carlos Alberto', 'Gómez Ruiz', 'M', '2023-03-12', 1, 1, 'Av. Amazonas N32', '0998765432'),
('1712345602', 'Ana Lucía', 'Torres Mora', 'F', '2023-07-24', 1, 1, 'Calle Larga 456', '0987654321'),
('0912345603', 'Roberto José', 'Caicedo Vera', 'M', '2023-11-05', 2, 1, 'Urdesa Central', '0991234567'),
('0912345604', 'Elena María', 'Salazar Paz', 'F', '2023-01-15', 2, 2, 'Vía a la Costa km 12', '0992345678'),
('0112345605', 'Miguel Ángel', 'Cárdenas Solís', 'M', '2023-05-20', 3, 1, 'El Barranco', '0993456789'),
('1312345606', 'Carmen Sofía', 'Mendoza Loor', 'F', '2023-09-30', 4, 1, 'Barrio Perpetuo Socorro', '0994567890'),
('1312345607', 'Diego Armando', 'Pico Zambrano', 'M', '2023-12-12', 5, 2, 'Rocafuerte e/ Chile', '0995678901'),
('1712345608', 'Patricia Inés', 'Vaca Castro', 'F', '2023-04-08', 1, 1, 'La Floresta', '0996789012'),
('0912345609', 'Javier Enrique', 'Ortiz Luna', 'M', '2024-08-19', 2, 1, 'Sauces 8', '0997890123'),
('0112345610', 'Rosa Elena', 'Guamán Quizhpe', 'F', '2024-02-28', 3, 2, 'Baños de Cuenca', '0998901234'),
('1712345611', 'Fernando Daniel', 'Sánchez Real', 'M', '2024-06-14', 1, 1, 'Cotocollao', '0981112223'),
('1712345612', 'Mónica Alexandra', 'Peña Herrera', 'F', '2024-10-10', 1, 1, 'Quitumbe', '0984445556'),
('0912345613', 'Ricardo Luis', 'Holguín Vera', 'M', '2024-03-22', 2, 1, 'Los Ceibos', '0987778889'),
('0912345614', 'Gabriela Fernanda', 'Rivas Sosa', 'F', '2024-07-07', 2, 2, 'Durán - Arbolito', '0980001112'),
('0112345615', 'Jorge Luis', 'Espinoza Cordero', 'M', '2024-09-15', 3, 1, 'Totoracocha', '0982223334'),
('1312345616', 'Isabel Cristina', 'Palma Arteaga', 'F', '2024-12-01', 4, 1, 'Manta 2000', '0985556667'),
('1312345617', 'Kevin Bryan', 'Velez Delgado', 'M', '2024-05-25', 5, 2, 'Crucita', '0988889990'),
('1712345618', 'Sandra Elizabeth', 'Quishpe Calo', 'F', '2024-11-11', 1, 1, 'Carcelén', '0981231234'),
('0912345619', 'Andrés Mauricio', 'Guerrero Gil', 'M', '2024-01-30', 2, 1, 'Kennedy Norte', '0984564567'),
('0112345620', 'Beatriz Eugenia', 'Morocho Zhunio', 'F', '2024-04-18', 3, 2, 'Ricaurte', '0987897890'),
('1712345621', 'Hugo Alberto', 'Chávez López', 'M', '2024-08-05', 1, 1, 'San Bartolo', '0971231231'),
('1712345622', 'Valeria Denisse', 'Paredes Villacís', 'F', '2024-03-14', 1, 1, 'Chimbacalle', '0972342342'),
('0912345623', 'Esteban René', 'Navarrete Icaza', 'M', '2024-05-22', 2, 1, 'Mapasingue', '0973453453'),
('0912345624', 'Lissette Tamara', 'García Noboa', 'F', '2024-09-09', 2, 2, 'Pascuales', '0974564564'),
('0112345625', 'Mario César', 'Orellana Vega', 'M', '2024-02-12', 3, 1, 'San Sebastián', '0975675675'),
('1312345626', 'Yolanda Maribel', 'Cevallos Saltos', 'F', '2024-06-21', 4, 1, 'Tarqui', '0976786786'),
('1312345627', 'Paúl Sebastián', 'Moreira Pico', 'M', '2024-10-10', 5, 2, 'Santa Ana', '0977897897'),
('1712345628', 'Silvia Marina', 'Andrade Mena', 'F', '2024-12-25', 1, 1, 'El Condado', '0978908908'),
('0912345629', 'Óscar Iván', 'Barrios Jara', 'M', '2022-07-01', 2, 1, 'Samanes', '0979019019'),
('0112345630', 'Julia Mercedes', 'Brito Alvarado', 'F', '2022-01-11', 3, 2, 'Tarqui Cuenca', '0970120120'),
('1712345631', 'Guillermo José', 'Arias Ponce', 'M', '2022-11-20', 1, 1, 'La Vicentina', '0961111111'),
('1712345632', 'Tatiana Lizbeth', 'Crespo Flores', 'F', '2022-04-15', 1, 1, 'Chillogallo', '0962222222'),
('0912345633', 'Ramiro Alfonso', 'Estrada Cruz', 'M', '2022-08-30', 2, 1, 'Guasmo Sur', '0963333333'),
('0912345634', 'Natalia Carolina', 'Valdez Vera', 'F', '2022-12-05', 2, 2, 'Chongón', '0964444444'),
('0112345635', 'Wilson Patricio', 'Suárez Calle', 'M', '2022-02-28', 3, 1, 'Monay', '0965555555'),
('1312345636', 'Lorena Alexandra', 'Macías Barberán', 'F', '2022-06-18', 4, 1, 'Los Esteros', '0966666666'),
('1312345637', 'César Augusto', 'Briones Intriago', 'M', '2022-09-24', 5, 2, 'Picoazá', '0967777777'),
('1712345638', 'Verónica Jeaneth', 'Terán Vaca', 'F', '2022-01-12', 1, 1, 'Solanda', '0968888888'),
('0912345639', 'Leonardo Fabio', 'Castillo Reyes', 'M', '2022-05-05', 2, 1, 'Alborada', '0969999999'),
('0112345640', 'Martha Cecilia', 'Tenorio Loja', 'F', '2022-10-31', 3, 2, 'Misicata', '0960000000'),
('1712345641', 'Daniel Alejandro', 'Viteri Paz', 'M', '2022-12-12', 1, 1, 'Ponciano', '0951122334'),
('1712345642', 'Karla Viviana', 'Romo Egas', 'F', '2022-02-02', 1, 1, 'La Magdalena', '0952233445'),
('0912345643', 'Manuel Mesías', 'Zavala Toala', 'M', '2022-06-25', 2, 1, 'La Garzota', '0953344556'),
('0912345644', 'Adriana Paola', 'Burgos Lindao', 'F', '2022-03-14', 2, 2, 'Posorja', '0954455667'),
('0112345645', 'Fabián Rodrigo', 'Pulla Tacuri', 'M', '2022-11-20', 3, 1, 'Narancay', '0955566778'),
('1312345646', 'Gladys María', 'Anchundia Mero', 'F', '2022-08-08', 4, 1, 'San Mateo', '0956677889'),
('1312345647', 'Byron Javier', 'Lucas Parrales', 'M', '2022-01-01', 5, 2, 'Calderón Portoviejo', '0957788990'),
('1712345648', 'Liliana Raquel', 'Acurio Santillán', 'F', '2022-05-30', 1, 1, 'El Recreo', '0958899001'),
('0912345649', 'Santiago Ismael', 'Baque Pincay', 'M', '2022-09-15', 2, 1, 'Parroquia Ximena', '0959900112'),
('0112345650', 'Norma Alicia', 'Yanza Baculima', 'F', '2022-04-04', 3, 2, 'San Joaquín', '0950011223'),
('1712345651', 'Francisco Javier', 'Loor Zambrano', 'M', '2022-08-14', 1, 1, 'Inaquito', '0941122334'),
('1712345652', 'Andrea Belén', 'Heredia Vallejo', 'F', '2022-12-25', 1, 1, 'Mariscal Sucre', '0942233445'),
('0912345653', 'Luis Alfredo', 'Mejía Castro', 'M', '2022-02-10', 2, 1, 'Fertisa', '0943344556'),
('0912345654', 'Domenica Nicole', 'Lara Vite', 'F', '2022-05-18', 2, 2, 'Tenguel', '0944455667'),
('0112345655', 'Edgar Vinicio', 'Cabrera Pesantez', 'M', '2022-07-22', 3, 1, 'Huayna Cápac', '0945566778'),
('1312345656', 'Janeth Narcisa', 'Quijije Tumbaco', 'F', '2022-11-12', 4, 1, 'El Murciélago', '0946677889'),
('1312345657', 'Walter Rolando', 'Cedeño Loor', 'M', '2022-01-28', 5, 2, 'Alajuela', '0947788990'),
('1712345658', 'Mayra Alejandra', 'Guerra Ortega', 'F', '2022-06-03', 1, 1, 'Guamaní', '0948899001'),
('0912345659', 'Christian David', 'Vera Villao', 'M', '2023-10-15', 2, 1, 'Pradera', '0949900112'),
('0112345660', 'Sonia Elizabeth', 'Reyes Zhapa', 'F', '2023-12-08', 3, 2, 'Sayausí', '0940011223'),
('1712345661', 'Pablo Andrés', 'Muñoz Elías', 'M', '2023-04-22', 1, 1, 'La Carolina', '0931122334'),
('1712345662', 'Cristina María', 'Narváez Pozo', 'F', '2023-09-02', 1, 1, 'Cumbayá', '0932233445'),
('0912345663', 'Gonzalo Arturo', 'Morales Pino', 'M', '2023-11-30', 2, 1, 'Barrio Centenario', '0933445566'),
('0912345664', 'Fátima Lucía', 'Salgado Coello', 'F', '2023-08-14', 2, 2, 'Progreso', '0934455667'),
('0112345665', 'Hugo Rodrigo', 'Maldonado Criollo', 'M', '2023-01-25', 3, 1, 'Cañaribamba', '0935566778'),
('1312345666', 'Mery Johanna', 'García Cevallos', 'F', '2023-04-12', 4, 1, 'Civica', '0936677889'),
('1312345667', 'Jimmy Alexander', 'Alvarado Ruiz', 'M', '2023-07-07', 5, 2, 'Crucita Playa', '0937788990'),
('1712345668', 'Lorena Isabel', 'Burbano Galvis', 'F', '2023-02-18', 1, 1, 'Nayón', '0938899001'),
('0912345669', 'Raúl Armando', 'García Parra', 'M', '2023-06-28', 2, 1, 'Florida Norte', '0939900112'),
('0112345670', 'Lourdes María', 'Quezada Pintado', 'F', '2023-03-22', 3, 2, 'Molleturo', '0930011223'),
('1712345671', 'Esteban Josué', 'Aguirre Silva', 'M', '2023-11-05', 1, 1, 'Pomasqui', '0921122334'),
('1712345672', 'Pamela Sofía', 'Velasco Ortiz', 'F', '2023-05-15', 1, 1, 'Puembo', '0922233445'),
('0912345673', 'Julio César', 'Tello Mieles', 'M', '2023-08-20', 2, 1, 'Pascuales Centro', '0923344556'),
('0912345674', 'Gisella Estefanía', 'Párraga Vera', 'F', '2023-02-11', 2, 2, 'Puná', '0924455667'),
('0112345675', 'Vinicio Ramiro', 'Saca Zhumi', 'M', '2023-09-30', 3, 1, 'Bellavista', '0925566778'),
('1312345676', 'Rosario del Pilar', 'Flores Mero', 'F', '2023-12-14', 4, 1, 'Eloy Alfaro', '0926677889'),
('1312345677', 'Ángel David', 'Bailón Chancay', 'M', '2023-04-25', 5, 2, 'Pueblo Nuevo', '0927788990'),
('1712345678', 'Nancy Fabiola', 'Luzuriaga Jaramillo', 'F', '2023-10-10', 1, 1, 'El Inca', '0928899001'),
('0912345679', 'Marco Antonio', 'Solórzano Pluas', 'M', '2023-12-25', 2, 1, 'Mapasingue Este', '0929900112'),
('0112345680', 'Cecilia Inés', 'Ochoa Arévalo', 'F', '2023-06-19', 3, 2, 'Sinincay', '0920011223'),
('1712345681', 'Victor Hugo', 'Pazmiño Tapia', 'M', '2023-03-08', 1, 1, 'San Antonio', '0911122334'),
('1712345682', 'Diana Carolina', 'Vallejo Espín', 'F', '2023-07-28', 1, 1, 'Calderón', '0912233445'),
('0912345683', 'Félix Ismael', 'Quinde Holguín', 'M', '2023-09-12', 2, 1, 'Trinitaria', '0913344556'),
('0912345684', 'Melany Briggite', 'Reyes Lino', 'F', '2023-01-20', 2, 2, 'Tenguel', '0914455667'),
('0112345685', 'Patricio Javier', 'Maza Suquilanda', 'M', '2023-05-15', 3, 1, 'Miraflores', '0915566778'),
('1312345686', 'Teresa de Jesús', 'Canchignia Mero', 'F', '2023-02-02', 4, 1, 'Manta Beach', '0916677889'),
('1312345687', 'Edison Roberto', 'Cusme Velez', 'M', '2023-10-30', 5, 2, 'Riochico', '0917788990'),
('1712345688', 'Rosa Amelia', 'Guanoluisa Chasi', 'F', '2023-11-22', 1, 1, 'Zambiza', '0918899001'),
('0912345689', 'Galo Enrique', 'Suárez Bajaña', 'M', '2023-04-14', 2, 1, 'Suburbio', '0919900112'),
('0112345690', 'Blanca Nieves', 'Villa Tacuri', 'F', '2023-08-05', 3, 2, 'Quingeo', '0910011223'),
('1712345691', 'Santiago Efraín', 'Mendoza Vera', 'M', '2023-09-18', 1, 1, 'El Batán', '0901122334'),
('1712345692', 'Jessica Paola', 'Soto Jaramillo', 'F', '2023-01-12', 1, 1, 'Conocoto', '0902233445'),
('0912345693', 'Nelson Fabián', 'Poveda Sánchez', 'M', '2023-05-30', 2, 1, 'Bastión Popular', '0903344556'),
('0912345694', 'Gema María', 'Zambrano Loor', 'F', '2023-11-14', 2, 2, 'El Morro', '0904455667'),
('0112345695', 'Luis Eduardo', 'Peralta Calle', 'M', '2023-12-25', 3, 1, 'Yanuncay', '0905566778'),
('1312345696', 'Miriam Raquel', 'Anchundia Pincay', 'F', '2023-03-21', 4, 1, 'Jaramijó', '0906677889'),
('1312345697', 'José Manuel', 'Barcia Moreira', 'M', '2023-06-15', 5, 2, 'San Plácido', '0907788990'),
('1712345698', 'Ximena del Rocío', 'Cifuentes Lara', 'F', '2023-08-08', 1, 1, 'Amaguaña', '0908899001'),
('0912345699', 'Rafael Alberto', 'Vinces Parrales', 'M', '2023-10-10', 2, 1, 'Mucho Lote', '0909900112'),
('0112345700', 'Clara Elena', 'Zhunio Morocho', 'F', '2023-02-14', 3, 2, 'Victoria del Portete', '0900011223');

/* =========================================================
   INSERCIÓN DE EJEMPLO DE CONSULTA Y CONTROL (Para probar)
========================================================= */




/* =========================================================
   1. POBLAR PACIENTES (100 NIÑOS DE 0 A 5 AÑOS)
========================================================= */
-- Limpiamos para asegurar que los IDs empiecen de 1


/* =========================================================
   1. CONSULTAS MÉDICAS (100 REGISTROS)
========================================================= */
-- Vinculamos cada uno de los 100 pacientes con una consulta
INSERT INTO consultas (id_paciente, id_personal, fecha_consulta, motivo, observaciones)
SELECT 
    p.id_paciente,
    (p.id_paciente % 4) + 1,
    CURDATE(),
    'Control de Crecimiento y Desarrollo',
    'Evaluación nutricional infantil'
FROM pacientes p;


/* =========================================================
   2. CONTROLES ANTROPOMÉTRICOS (DATOS CRUDOS PARA EL SISTEMA)
========================================================= */
INSERT INTO controles_antropometricos
(id_consulta, edad, peso_kg, altura_m, imc, diagnostico_nutricional)
SELECT
    c.id_consulta,
    TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) AS edad,
    ROUND(5 + (RAND() * 10), 2) AS peso_kg,
    ROUND(0.60 + (RAND() * 0.40), 2) AS altura_m,
    NULL,
    CASE
        WHEN p.id_paciente % 4 = 0 THEN 'Desnutrición'
        WHEN p.id_paciente % 4 = 1 THEN 'Normal'
        WHEN p.id_paciente % 4 = 2 THEN 'Sobrepeso'
        ELSE 'Riesgo nutricional'
    END
FROM consultas c
JOIN pacientes p ON p.id_paciente = c.id_paciente
WHERE TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) < 5;


/* =========================================================
   3. DIAGNÓSTICOS (RELACIÓN CON ENFERMEDADES)
========================================================= */
INSERT INTO diagnosticos (id_consulta, id_enfermedad)
SELECT
    ca.id_consulta,
    CASE
        WHEN ca.diagnostico_nutricional = 'Desnutrición' THEN 3
        WHEN ca.diagnostico_nutricional = 'Sobrepeso' THEN 4
        ELSE 5
    END
FROM controles_antropometricos ca;


/* =========================================================
   4. RECETAS Y DETALLES
========================================================= */
INSERT INTO recetas (id_consulta, indicaciones)
SELECT
    id_consulta,
    'Plan nutricional según evaluación y control en 30 días'
FROM controles_antropometricos;


-- Detalle de receta: Suplementos para los de bajo peso o multivitamínicos
INSERT INTO receta_detalle (id_receta, id_medicamento, dosis)
SELECT
    r.id_receta,
    CASE
        WHEN ca.diagnostico_nutricional = 'Desnutrición' THEN 5
        ELSE 1
    END,
    'Dosis según peso y edad'
FROM recetas r
JOIN controles_antropometricos ca ON ca.id_consulta = r.id_consulta;


-- Total niños
SELECT COUNT(DISTINCT p.id_paciente)
FROM pacientes p
JOIN consultas c ON c.id_paciente = p.id_paciente
JOIN controles_antropometricos ca ON ca.id_consulta = c.id_consulta
WHERE ca.edad < 5;

-- Niños desnutridos
SELECT COUNT(DISTINCT p.id_paciente)
FROM pacientes p
JOIN consultas c ON c.id_paciente = p.id_paciente
JOIN controles_antropometricos ca ON ca.id_consulta = c.id_consulta
WHERE ca.edad < 5
AND ca.diagnostico_nutricional = 'Desnutrición';

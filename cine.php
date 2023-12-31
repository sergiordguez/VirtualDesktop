<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Escritorio Virtual - Resultados de Fútbol</title>

    <meta name="author" content="Sergio Rodriguez Garcia" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="estilo/estilo.css" />
    <link rel="stylesheet" type="text/css" href="estilo/layout.css" />
    <link rel="stylesheet" type="text/css" href="estilo/cine.css" />
    <link rel="icon" href="multimedia/imagenes/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <!-- Datos con el contenidos que aparece en el navegador -->
    <header>    
        <h1>Escritorio Virtual</h1>
        <nav>
            <a title="Escritorio"
                accesskey="E"
                tabindex="1"
                href="index.html">Escritorio</a>
            <a title="Sobre Mi"
                accesskey="S"
                tabindex="2"
                href="sobremi.html">Sobre mí</a>
            <a title="Noticias"
                accesskey="N"
                tabindex="3"
                href="noticias.html">Noticias</a>
            <a title="Agenda"
                accesskey="A"
                tabindex="4"            
                href="agenda.html">Agenda</a>
            <a title="Meteorologia"
                accesskey="M"
                tabindex="5"
                href="meteorologia.html">Meteorología</a>
            <a title="Viajes"
                accesskey="V"
                tabindex="6"
                href="viajes.php">Viajes</a>
            <a title="Juegos"
                accesskey="J"
                tabindex="7"
                href="juegos.html">Juegos</a>
        </nav>
    </header>

    <h2>Juegos</h2>

    <nav>
        <a title="Memoria"
            accesskey="M"
            tabindex="8"
            href="memoria.html">Juego de memoria</a>
        <a title="Sudoku"
            accesskey="S"
            tabindex="9"
            href="sudoku.html">Sudoku</a>
        <a title="Crucigrama"
            accesskey="C"
            tabindex="10"
            href="crucigrama.php">Crucigrama</a>
        <a title="Lienzo"
            accesskey="L"
            tabindex="11"
            href="api.html">Lienzo</a>
        <a title="Cartelera"
            accesskey="C"
            tabindex="12"
            href="cine.php">Cartelera</a>
    </nav>

    <h3>Cartelera de cine</h3>

    <?php

        session_start();

        class BaseDatos {
            protected $usuario;
            protected $contraseña;
            protected $db;
            protected $msgAviso;

            public function __construct() {
                $this->usuario = "DBUSER2023";
                $this->contraseña = "DBPSWD2023";
                $this->msgAviso = "Esperando acciones...";
                $this->creardb();
                $this->crearTablas();
            }

            public function buscarPeliculas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $busqueda = "SELECT * FROM PELICULA WHERE CODPELICULA=?";
                $pst = $this->db->prepare($busqueda);
                if ($_REQUEST["codpelicula"] != null) {
                    $pst->bind_param("s", $_REQUEST["codpelicula"]);
                    $pst->execute();
                    $resultado = $pst->get_result();

                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_array()) {
                            $this->msgAviso = "Datos buscados: " .
                                "\n\t➞ Código de la película: " . $fila["CODPELICULA"] .
                                "\n\t➞ Título de la película: " . $fila["TITULO"] .
                                "\n\t➞ Duración de la película: " . $fila["DURACION"];
                        }
                    } else {
                        $this->msgAviso = "ERROR: No se han encontrado resultados.";
                    }
                } else {
                    $this->msgAviso = "ERROR: Por favor, introduzca el código de la película.";
                }
            }

            public function buscarCines() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $busqueda = "SELECT * FROM CINE WHERE CODCINE=?";
                $pst = $this->db->prepare($busqueda);
                if ($_REQUEST["codcine"] != null) {
                    $pst->bind_param("s", $_REQUEST["codcine"]);
                    $pst->execute();
                    $resultado = $pst->get_result();

                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_array()) {
                            $this->msgAviso = "Datos buscados: " .
                                "\n\t➞ Código del cine: " . $fila["CODCINE"] .
                                "\n\t➞ Localidad del cine: " . $fila["LOCALIDAD"];
                        }
                    } else {
                        $this->msgAviso = "ERROR: No se han encontrado resultados.";
                    }
                } else {
                    $this->msgAviso = "ERROR: Por favor, introduzca el código del cine.";
                }
            }

            public function buscarSalas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $busqueda = "SELECT * FROM SALA WHERE CODSALA=? AND CODCINE=?";
                $pst = $this->db->prepare($busqueda);
                if ($_REQUEST["codsala"] != null && $_REQUEST["codcine"] != null) {
                    $pst->bind_param("ss", $_REQUEST["codsala"], $_REQUEST["codcine"]);
                    $pst->execute();
                    $resultado = $pst->get_result();

                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_array()) {
                            $this->msgAviso = "Datos buscados: " .
                                "\n\t➞ Código de la sala: " . $fila["CODSALA"] .
                                "\n\t➞ Aforo de la sala: " . $fila["AFORO"] .
                                "\n\t➞ Código del cine: " . $fila["CODCINE"];
                        }
                    } else {
                        $this->msgAviso = "ERROR: No se han encontrado resultados.";
                    }
                } else {
                    $this->msgAviso = "ERROR: Por favor, introduzca el código de la sala.";
                }
            }

            public function buscarProyecciones() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $busqueda = "SELECT * FROM PROYECTA WHERE CODPELICULA=? AND CODSALA=? AND SESION=? AND FECHA=?";
                $pst = $this->db->prepare($busqueda);
                if ($_REQUEST["codpelicula"] != null && $_REQUEST["codsala"] != null && $_REQUEST["sesion"] != null && $_REQUEST["fecha"] != null) {
                    $pst->bind_param("ssis", $_REQUEST["codpelicula"], $_REQUEST["codsala"], $_REQUEST["sesion"], $_REQUEST["fecha"]);
                    $pst->execute();
                    $resultado = $pst->get_result();

                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_array()) {
                            $this->msgAviso = "Datos buscados: " .
                                "\n\t➞ Código de la película: " . $fila["CODPELICULA"] .
                                "\n\t➞ Código de la sala: " . $fila["CODSALA"] .
                                "\n\t➞ Sesion de la proyección: " . $fila["SESION"] .
                                "\n\t➞ Fecha de la proyección: " . $fila["FECHA"] .
                                "\n\t➞ Entradas vendidas de la proyección: " . $fila["ENTRADAS_VENDIDAS"];
                        }
                    } else {
                        $this->msgAviso = "ERROR: No se han encontrado resultados.";
                    }
                } else {
                    $this->msgAviso = "ERROR: Por favor, introduzca el código de la película, la sala, la sesión y la fecha";
                }
            }

            public function buscarEntradas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $busqueda = "SELECT * FROM ENTRADA WHERE CODENTRADA=?";
                $pst = $this->db->prepare($busqueda);
                if ($_REQUEST["codentrada"] != null) {
                    $pst->bind_param("s", $_REQUEST["codentrada"]);
                    $pst->execute();
                    $resultado = $pst->get_result();

                    if ($resultado->num_rows > 0) {
                        while ($fila = $resultado->fetch_array()) {
                            $this->msgAviso = "Datos buscados: " .
                                "\n\t➞ Código de la entrada: " . $fila["CODENTRADA"] .
                                "\n\t➞ Precio de la entrada: " . $fila["PRECIO"] .
                                "\n\t➞ Código de la película: " . $fila["CODPELICULA"] .
                                "\n\t➞ Código de la sala: " . $fila["CODSALA"] .
                                "\n\t➞ Sesion de la proyección: " . $fila["SESION"] .
                                "\n\t➞ Fecha de la proyección: " . $fila["FECHA"];
                        }
                    } else {
                        $this->msgAviso = "ERROR: No se han encontrado resultados.";
                    }
                } else {
                    $this->msgAviso = "ERROR: Por favor, introduzca el código de la entrada.";
                }
            }

            public function cargarPeliculas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $nombre = null;
                if (isset($_FILES["csv"])) {
                    $nombre = $_FILES["csv"]["tmp_name"];
                }

                $fichero = fopen($nombre, "r");                
                while (($datos = fgetcsv($fichero)) != false) {
                    $insercion = "INSERT INTO PELICULA (CODPELICULA, TITULO, DURACION) values (?,?,?)";
                    $pst = $this->db->prepare($insercion);
                    $pst->bind_param(
                        "ssi",
                        $datos[0],
                        $datos[1],
                        $datos[2],
                    );
                    $resultado = $pst->execute();

                    if ($resultado) {
                        $this->msgAviso = "Se han cargado las películas con éxito";
                    } else {
                        $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                    }
                }
                fclose($fichero);
            }

            public function cargarCines() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $nombre = null;
                if (isset($_FILES["csv"])) {
                    $nombre = $_FILES["csv"]["tmp_name"];
                }

                $fichero = fopen($nombre, "r");                
                while (($datos = fgetcsv($fichero)) != false) {
                    $insercion = "INSERT INTO CINE (CODCINE, LOCALIDAD) values (?,?)";
                    $pst = $this->db->prepare($insercion);
                    $pst->bind_param(
                        "ss",
                        $datos[0],
                        $datos[1]
                    );
                    $resultado = $pst->execute();

                    if ($resultado) {
                        $this->msgAviso = "Se han cargado los cines con éxito";
                    } else {
                        $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                    }
                }
                fclose($fichero);
            }

            public function cargarSalas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $nombre = null;
                if (isset($_FILES["csv"])) {
                    $nombre = $_FILES["csv"]["tmp_name"];
                }

                $fichero = fopen($nombre, "r");                
                while (($datos = fgetcsv($fichero)) != false) {
                    $insercion = "INSERT INTO SALA (CODSALA, AFORO, CODCINE) values (?,?,?)";
                    $pst = $this->db->prepare($insercion);
                    $pst->bind_param(
                        "sis",
                        $datos[0],
                        $datos[1],
                        $datos[2]
                    );
                    $resultado = $pst->execute();

                    if ($resultado) {
                        $this->msgAviso = "Se han cargado las salas con éxito";
                    } else {
                        $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                    }
                }
                fclose($fichero);
            }

            public function cargarProyecciones() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $nombre = null;
                if (isset($_FILES["csv"])) {
                    $nombre = $_FILES["csv"]["tmp_name"];
                }

                $fichero = fopen($nombre, "r");                
                while (($datos = fgetcsv($fichero)) != false) {
                    $insercion = "INSERT INTO PROYECTA (CODPELICULA, CODSALA, SESION, FECHA, ENTRADAS_VENDIDAS) values (?,?,?,?,?)";
                    $pst = $this->db->prepare($insercion);
                    $pst->bind_param(
                        "ssisi",
                        $datos[0],
                        $datos[1],
                        $datos[2],
                        $datos[3],
                        $datos[4]
                    );
                    $resultado = $pst->execute();

                    if ($resultado) {
                        $this->msgAviso = "Se han cargado las proyecciones con éxito";
                    } else {
                        $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                    }
                }
                fclose($fichero);
            }

            public function cargarEntradas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $nombre = null;
                if (isset($_FILES["csv"])) {
                    $nombre = $_FILES["csv"]["tmp_name"];
                }

                $fichero = fopen($nombre, "r");                
                while (($datos = fgetcsv($fichero)) != false) {
                    $insercion = "INSERT INTO ENTRADA (CODENTRADA, PRECIO, CODPELICULA, CODSALA, SESION, FECHA) values (?,?,?,?,?,?)";
                    $pst = $this->db->prepare($insercion);
                    $pst->bind_param(
                        "sissis",
                        $datos[0],
                        $datos[1],
                        $datos[2],
                        $datos[3],
                        $datos[4],
                        $datos[5]
                    );
                    $resultado = $pst->execute();

                    if ($resultado) {
                        $this->msgAviso = "Se han cargado las proyecciones con éxito";
                    } else {
                        $this->msgAviso = "ERROR: No se ha podido cargar el fichero CSV.";
                    }
                }
                fclose($fichero);
            }

            public function exportarCSV() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $resultado = $this->ejecutarQuery("SELECT * FROM PELICULA");
                $this->exportar($resultado);
                
                $resultado = $this->ejecutarQuery("SELECT * FROM CINE");
                $this->exportar($resultado);

                $resultado = $this->ejecutarQuery("SELECT * FROM SALA");
                $this->exportar($resultado);

                $resultado = $this->ejecutarQuery("SELECT * FROM PROYECTA");
                $this->exportar($resultado);

                $resultado = $this->ejecutarQuery("SELECT * FROM ENTRADA");
                $this->exportar($resultado);
            }

            protected function exportar($resultado) {
                if ($resultado->fetch_assoc() != null) {
                    $fichero = fopen("baseDeDatos.csv", "w");

                    foreach ($resultado as $fila) {
                        fputcsv($fichero, $fila);
                    }

                    fclose($fichero);

                    $nombreDescarga = basename("baseDeDatos.csv");
                    $filePath = "" . $nombreDescarga;
                    if (!empty($nombreDescarga) && file_exists($filePath)) {
                        header("Cache-Control: public");
                        header("Content-Description: File Transfer");
                        header("Content-Disposition: attachment; filename=$nombreDescarga");
                        header("Content-Type: text/csv");
                        header("Content-Transfer-Encoding: binary");
                        readfile($filePath);
                    }
                }
            }

            public function creardb() {
                $this->conectarBaseDatos();
                $this->ejecutarQuery("CREATE DATABASE IF NOT EXISTS dbGestorCines;");
                $this->db->select_db("dbGestorCines");
                $this->msgAviso = "Se ha creado la base de datos con éxito.";
            }

            public function crearTablas() {
                $this->conectarBaseDatos();
                $this->db->select_db("dbGestorCines");

                $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS CINE 
                (
                    CODCINE varchar(4),
                    LOCALIDAD varchar(20),   
                    PRIMARY KEY (CODCINE)
                );");
                
                $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS SALA 
                (
                    CODSALA varchar(4),
                    AFORO decimal(3,0),   
                    CODCINE varchar(4) NOT NULL,
                    PRIMARY KEY (CODSALA),
                    FOREIGN KEY (CODCINE) REFERENCES CINE (CODCINE)            
                );");
                
                $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS PELICULA
                (
                    CODPELICULA varchar(4),
                    TITULO varchar(20), 
                    DURACION decimal(2,0),   
                    PRIMARY KEY (CODPELICULA)
                );");
                
                $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS PROYECTA
                (
                    CODPELICULA varchar(4),
                    CODSALA varchar(4),
                    SESION decimal(2,0),
                    FECHA date,
                    ENTRADAS_VENDIDAS decimal(3,0),
                    PRIMARY KEY (CODPELICULA,CODSALA,SESION,FECHA),  
                    FOREIGN KEY (CODPELICULA) REFERENCES PELICULA (CODPELICULA),
                    FOREIGN KEY (CODSALA) REFERENCES SALA (CODSALA),           
                    CHECK (SESION IN (5,7,10))      
                );");
                
                $this->ejecutarQuery("CREATE TABLE IF NOT EXISTS ENTRADA
                (
                    CODENTRADA varchar(4),
                    PRECIO decimal(3,0),   
                    CODPELICULA varchar(4) NOT NULL,
                    CODSALA varchar(4) NOT NULL,
                    SESION decimal(2,0) NOT NULL,
                    FECHA date NOT NULL,  
                    PRIMARY KEY (CODENTRADA),
                    FOREIGN KEY (CODPELICULA,CODSALA,SESION,FECHA) REFERENCES PROYECTA (CODPELICULA,CODSALA,SESION,FECHA)
                );");

                $this->msgAviso = "Se han creado las tablas con éxito.";
            }

            public function conectarBaseDatos() {
                $this->db = new mysqli("localhost", $this->usuario, $this->contraseña);
                if ($this->db->connect_errno) {
                    $this->msgAviso = "ERROR: No se ha podido conectar a la base de datos.";
                } else {
                    $this->msgAviso = "Se ha conectado a la base de datos con éxito.";
                }
            }

            public function desconectarBaseDatos() {
                $this->db->close();
            }

            public function ejecutarQuery($query) {
                $resultado = $this->db->query($query);
                if ($resultado) {
                    return $resultado;
                }
            }

            public function getMsgAviso() {
                return $this->msgAviso;
            }
        }

        if (!isset($_SESSION["db"])) {
            $db = new BaseDatos();
            $db->conectarBaseDatos();
            $_SESSION["db"] = $db;
        }

        if (count($_POST) > 0) {
            $db = $_SESSION["db"];

            if (isset($_POST["cargarPeliculas"])) $db->cargarPeliculas();
            if (isset($_POST["cargarCines"])) $db->cargarCines();
            if (isset($_POST["cargarSalas"])) $db->cargarSalas();
            if (isset($_POST["cargarProyecciones"])) $db->cargarProyecciones();
            if (isset($_POST["cargarEntradas"])) $db->cargarEntradas();

            if (isset($_POST["buscarPeliculas"])) $db->buscarPeliculas();
            if (isset($_POST["buscarCines"])) $db->buscarCines();
            if (isset($_POST["buscarSalas"])) $db->buscarSalas();
            if (isset($_POST["buscarProyecciones"])) $db->buscarProyecciones();
            if (isset($_POST["buscarEntradas"])) $db->buscarEntradas();          

            if (isset($_POST["exportarCSV"])) $db->exportarCSV();

            $db->desconectarBaseDatos();
            $_SESSION["db"] = $db;
        }
    ?>

    <pre><?php echo $_SESSION["db"]->getMsgAviso(); ?></pre>
    <article>
        <h3>Cargar datos desde un archivo CSV</h3>
        <aside>
        <section>
            <h4>Películas</h4>
            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Cargue el archivo CSV de películas: <input type="file" name="csv" /></label>
                <input type="submit" value="Presione para cargar el archivo" name="cargarPeliculas" />
            </form>
        </section>
        <section>
            <h4>Cines</h4>
            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Cargue el archivo CSV de cines: <input type="file" name="csv" /></label>
                <input type="submit" value="Presione para cargar el archivo" name="cargarCines" />
            </form>
        </section>
        <section>
            <h4>Salas</h4>
            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Cargue el archivo CSV de salas: <input type="file" name="csv" /></label>                        
                <input type="submit" value="Presione para cargar el archivo" name="cargarSalas" />
            </form>
        </section>
        <section>
            <h4>Proyecciones</h4>
            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Cargue el archivo CSV de proyecciones: <input type="file" name="csv" /></label>
                <input type="submit" value="Presione para cargar el archivo" name="cargarProyecciones" />
            </form>
        </section>
        <section>
            <h4>Entradas</h4>
            <form action="#" method="POST" enctype="multipart/form-data">
                <label>Cargue el archivo CSV de entradas: <input type="file" name="csv" /></label>
                <input type="submit" value="Presione para cargar el archivo" name="cargarEntradas" />
            </form>
        </section>
        <aside>
    </article>
    
    <article>
        <h3>Búsqueda</h3>
        <aside>
            <section>
                <h4>Películas</h4>
                <form action="#" method="POST">
                    <label>Código de la película: <input type="text" name="codpelicula" maxlength="4" required /></label>                        
                    <input type="submit" value="Presione para buscar" name="buscarPeliculas" />
                </form>
            </section>
            <section>
                <h4>Cines</h4>
                <form action="#" method="POST">
                    <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>                        
                    <input type="submit" value="Presione para buscar" name="buscarCines" />
                </form>
            </section>
            <section>
                <h4>Salas</h4>
                <form action="#" method="POST">
                    <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>
                    <label>Código del cine: <input type="text" name="codcine" maxlength="4" required /></label>                                      
                    <input type="submit" value="Presione para buscar" name="buscarSalas" />
                </form>
            </section>
            <section>
                <h4>Proyecciones</h4>
                <form action="#" method="POST">
                    <label>Código de la película: <input type="text" maxlength="4" name="codpelicula" required /></label>
                    <label>Código de la sala: <input type="text" name="codsala" maxlength="4" required /></label>                      
                    <label>Sesión de la proyección: <input type="number" name="sesion" max="10" required /></label> 
                    <label>Fecha de la proyección: <input type="date" name="fecha" required /></label>               
                    <input type="submit" value="Presione para buscar" name="buscarProyecciones" />
                </form>
            </section>
            <section>
                <h4>Entradas</h4>
                <form action="#" method="POST">
                    <label>Código de la entrada: <input type="text" name="codentrada" maxlength="4" required /></label>             
                    <input type="submit" value="Presione para buscar" name="buscarEntradas" />
                </form>
            </section>
        </aside>
    </article>
    <article>
        <h3>Exportar datos a archivos CSV</h3>
        <form action="#" method="POST">
            <input type="submit" value="Presione para exportar los archivos" name="exportarCSV" />
        </form>
    </article>
</body>
</html>
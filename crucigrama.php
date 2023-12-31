<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Escritorio Virtual - Crucigrama</title>

    <meta name="author" content="Sergio Rodriguez Garcia" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="estilo/estilo.css" />
    <link rel="stylesheet" type="text/css" href="estilo/layout.css" />
    <link rel="stylesheet" type="text/css" href="estilo/crucigrama.css" />
    <link rel="icon" href="multimedia/imagenes/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/crucigrama.js"></script>
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

    <h3>Crucigrama</h3>

    <main></main>

    <script>
        const crucigrama = new Crucigrama();
        crucigrama.paintMathword();

        document.addEventListener("keydown", function (event) {
            const key = event.key;
            let selectedCell = document.querySelector('[data-state="clicked"]');

            if(selectedCell !== null){
                crucigrama.introduceElement(key);
            }
        });
    </script>

    <section data-type="botonera">
        <h2>Botonera</h2>
        <button onclick="crucigrama.introduceElement(1)">1</button>
        <button onclick="crucigrama.introduceElement(2)">2</button>
        <button onclick="crucigrama.introduceElement(3)">3</button>
        <button onclick="crucigrama.introduceElement(4)">4</button>
        <button onclick="crucigrama.introduceElement(5)">5</button>
        <button onclick="crucigrama.introduceElement(6)">6</button>
        <button onclick="crucigrama.introduceElement(7)">7</button>
        <button onclick="crucigrama.introduceElement(8)">8</button>
        <button onclick="crucigrama.introduceElement(9)">9</button>
        <button onclick="crucigrama.introduceElement('*')">*</button>
        <button onclick="crucigrama.introduceElement('+')">+</button>
        <button onclick="crucigrama.introduceElement('-')">-</button>
        <button onclick="crucigrama.introduceElement('/')">/</button>
    </section>

    <?php
        class Record{
            protected $server;
            protected $user;
            protected $pass;
            protected $dbname;
            protected $conn;

            public function __construct(){
                $this->server = "localhost";
                $this->user = "DBUSER2023";
                $this->pass = "DBPSWD2023";
                $this->dbname = "records";

                $this->conn = new mysqli($this->server, $this->user, $this->pass, $this->dbname);

                if ($this->conn->connect_error) {
                    die("Error de conexión a la base de datos: " . $this->conn->connect_error);
                  }
            }

            public function saveRecord($nombre, $apellidos, $nivel, $tiempo) {
                $nombre = $this->conn->real_escape_string($nombre);
                $apellidos = $this->conn->real_escape_string($apellidos);
                $nivel = $this->conn->real_escape_string($nivel);
                $tiempo = $this->conn->real_escape_string($tiempo);

                $sql = "INSERT INTO registro (nombre, apellidos, nivel, tiempo) VALUES ('$nombre', '$apellidos', '$nivel', '$tiempo')";
            
                if ($this->conn->query($sql) === TRUE) {
                  echo "Registro guardado con éxito.";
                } else {
                  echo "Error al guardar el registro: " . $this->conn->error;
                }
            }

            public function getTopRecords($nivel) {
                $nivel = $this->conn->real_escape_string($nivel);
            
                $sql = "SELECT nombre, apellidos, tiempo FROM registro WHERE nivel = '$nivel' ORDER BY tiempo LIMIT 10";
                $result = $this->conn->query($sql);
            
                $recordsList = '<ol>';
                while ($row = $result->fetch_assoc()) {
                  $tiempoFormateado =$row['tiempo'];
            
                  $recordsList .= "<li>{$row['nombre']} {$row['apellidos']} - Tiempo: $tiempoFormateado</li>";
                }
                $recordsList .= '</ol>';

                return $recordsList;
              }
        }
        $record = new Record();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $nivel = $_POST["nivel"];
            $tiempo = $_POST["tiempo"];
          
            $record->saveRecord($nombre, $apellidos, $nivel, $tiempo);

            $topRecordsList = $record->getTopRecords($nivel);
          }
    ?>

    <?php if (!empty($topRecordsList)) : ?>
        <h2>Top 10 Récords del Nivel <?php echo $nivel; ?></h2>
        <?php echo $topRecordsList; ?>
    <?php endif; ?>
</body>
</html>
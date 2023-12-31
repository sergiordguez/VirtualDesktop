<!DOCTYPE HTML>

<html lang="es">
<head>
    <!-- Datos que describen el documento -->
    <meta charset="UTF-8" />
    <title>Escritorio Virtual - Viajes</title>

    <meta name="author" content="Sergio Rodriguez Garcia" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="estilo/estilo.css" />
    <link rel="stylesheet" type="text/css" href="estilo/layout.css" />
    <link rel="stylesheet" type="text/css" href="estilo/viajes.css" />
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.0.0/mapbox-gl.css" rel="stylesheet">
    <link rel="icon" href="multimedia/imagenes/favicon.ico" />
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.0.0/mapbox-gl.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/viajes.js"></script>
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

    <h2>Viajes</h2>

    <?php
        class Carrusel{
            private $capital;
            private $pais;
            private $fotos = array();

            public function __construct($capital, $pais){
                $this->capital = $capital;
                $this->pais = $pais;
                $this->getCarrusel();
            }

            private function getCarrusel(){
                $api_key = "9a7e349658e8a04e6e59dcdb2f2eeb61";
                $url = "https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key={$api_key}&text={$this->capital}&per_page=10&format=json&nojsoncallback=1";
                $response = file_get_contents($url);
                $data = json_decode($response, true);

                if ($data && $data['stat'] == 'ok') {
                    foreach ($data['photos']['photo'] as $photo) {
                        $farm = $photo['farm'];
                        $server = $photo['server'];
                        $id = $photo['id'];
                        $secret = $photo['secret'];
                        $photoUrl = "https://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}.jpg";
                        $this->fotos[] = $photoUrl;
                    }
                }
            }

            public function mostrarCarrusel(){
                echo "<article>";
                echo "<h3>Carrusel de Imágenes</h3>";
                foreach ($this->fotos as $foto) {
                    echo "<img src=\"$foto\" alt=\"Foto\">";
                }
                echo '</article>';
                echo "<button data-action='next'> > </button>";
                echo "<button data-action='prev'> < </button>";
            }
        }

        class Moneda{
            private $siglasMonedaAlbanesa;
            private $siglasMonedaCambio;
            private $cambio;

            public function __construct($siglasMonedaAlbanesa, $siglasMonedaCambio) {
                $this->siglasMonedaAlbanesa = $siglasMonedaAlbanesa;
                $this->siglasMonedaCambio = $siglasMonedaCambio;
                $this->obtenerCambio();
            }

            private function obtenerCambio() {
                $api_key = '73ea8578764b9c28058b8bcf';
                $url = "https://open.er-api.com/v6/latest?base={$this->siglasMonedaAlbanesa}&symbols={$this->siglasMonedaCambio}";

                $response = file_get_contents($url);
                $data = json_decode($response, true);

                if ($data && $data['result'] == 'success') {
                    $this->cambio = $data['rates'][$this->siglasMonedaCambio];
                }
            }

            public function mostrarCambio() {
                echo "<p>Tipo de cambio: 1 {$this->siglasMonedaAlbanesa} = {$this->cambio} {$this->siglasMonedaCambio}</p>";
            }
        }

        $miMoneda = new Moneda("ALL", "EUR"); 
        $miMoneda->mostrarCambio();
        $c = new Carrusel("Tirana", "Albania");
        $c->mostrarCarrusel();
    ?>

    <script>
        var miMapa = new Viajes();
        miMapa.nextImage();
        miMapa.prevImage();
    </script>

    <button onclick="miMapa.getMapas()">Mostrar Mapas</button>

    <nav>
        <input type="file" data-type="subirArchivosKML" onchange="miMapa.readFilesKML(event)" multiple accept=".kml">
        <input type="file" data-type="fileInput" onchange="miMapa.readInputFile()" accept=".xml"/>
        <input type="file" data-type="subirArchivosSVG" onchange="miMapa.readFilesSVG()" multiple accept=".svg">
    </nav>

    <section id="dinamico">
    </section>

    <section id="mapa"></section>

    <section data-type="svg"></section>

    <section id="kml">
    </section>
</body>
</html>
class Viajes{
    constructor(){
        navigator.geolocation.getCurrentPosition(this.getPosicion.bind(this), this.verErrores.bind(this));
    }

    getPosicion(posicion){
        this.mensaje = "Se ha realizado correctamente la petición de geolocalización";
        this.longitud         = posicion.coords.longitude; 
        this.latitud          = posicion.coords.latitude;  
        this.precision        = posicion.coords.accuracy;
        this.altitud          = posicion.coords.altitude;
        this.precisionAltitud = posicion.coords.altitudeAccuracy;
        this.rumbo            = posicion.coords.heading;
        this.velocidad        = posicion.coords.speed;       
    }

    verErrores(error){
        switch(error.code) {
        case error.PERMISSION_DENIED:
            this.mensaje = "El usuario no permite la petición de geolocalización"
            break;
        case error.POSITION_UNAVAILABLE:
            this.mensaje = "Información de geolocalización no disponible"
            break;
        case error.TIMEOUT:
            this.mensaje = "La petición de geolocalización ha caducado"
            break;
        case error.UNKNOWN_ERROR:
            this.mensaje = "Se ha producido un error desconocido"
            break;
        }
    }

    getLongitud(){
        return this.longitud;
    }

    getLatitud(){
        return this.latitud;
    }

    getAltitud(){
        return this.altitud;
    }

    getMapaEstatico(longitud, latitud){
        this.url = "https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/" + longitud + "," + latitud + "," + 13 + "/800x600?access_token=pk.eyJ1IjoidW8yODI1OTgiLCJhIjoiY2xwY2s0Mm9pMHFpZTJqcXR3ZTg1M2ZmbCJ9.70fdF6iTxulJzks0wJ0OfA";
        $("#dinamico").append("<img src='"+ this.url +"' alt='Mapa estático' />");
    }

    getMapaDinamico(longitud, latitud){
        mapboxgl.accessToken = 'pk.eyJ1IjoidW8yODI1OTgiLCJhIjoiY2xwY2s0Mm9pMHFpZTJqcXR3ZTg1M2ZmbCJ9.70fdF6iTxulJzks0wJ0OfA';
        var map = new mapboxgl.Map({
            container: "mapa",
            style: "mapbox://styles/mapbox/streets-v11",
            center: [longitud,latitud], 
            zoom: 8 
        });

        map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },                
                trackUserLocation: true,                
                showUserHeading: true
            })
        );  
        
        const marker1 = new mapboxgl.Marker()
        .setLngLat([longitud,latitud])
        .addTo(map)
    }

    getMapas(){
        $("#dinamico").append("<h3>Mapa estático con ubicación real</h3>");
        this.getMapaEstatico(this.longitud, this.latitud);
        $("#mapa").append("<h3>Mapa dinámico con ubicación real</h3>");
        this.getMapaDinamico(this.longitud, this.latitud);
    }

    readInputFile(){
        var fileInput = document.querySelector('input[data-type="fileInput"]');
        var file = fileInput.files[0];

        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var xmlString = e.target.result;
                const parser = new DOMParser();
                const xmlDoc = parser.parseFromString(xmlString, 'application/xml');

                const rutas = xmlDoc.getElementsByTagName('ruta');

                const section = document.createElement('section');

                const h3 = document.createElement('h3');
                h3.textContent = "Contenido para el archivo XML";

                section.appendChild(h3);

                for (let i = 0; i < rutas.length; i++) {
                    const ruta = rutas[i];
                    const nombre = ruta.getElementsByTagName('nombre')[0].textContent;
                    const tipo = ruta.getElementsByTagName('tipo')[0].textContent;
                    const medioTransporte = ruta.getElementsByTagName('medio_transporte')[0].textContent;
                    const fechaInicio = ruta.getElementsByTagName('fecha_inicio')[0].textContent;
                    const horaInicio = ruta.getElementsByTagName('hora_inicio')[0].textContent;
                    const duracion = ruta.getElementsByTagName('duracion')[0].textContent;
                    const agencia = ruta.getElementsByTagName('agencia')[0].textContent;
                    const descripcion = ruta.getElementsByTagName('descripcion')[0].textContent;
                    const personasAdecuadas = ruta.getElementsByTagName('personas_adecuadas')[0].textContent;
                    const inicio = ruta.getElementsByTagName('inicio')[0].textContent;
                    const recomendacion = ruta.getElementsByTagName('recomendacion')[0].textContent;
                    const hitos  = ruta.getElementsByTagName('hitos')[0].textContent;

                    const h4 = document.createElement('h4');
                    h4.textContent = nombre;

                    const pTipo = document.createElement('p');
                    pTipo.textContent = `Tipo: ${tipo}`;

                    const pMedioTransporte = document.createElement('p');
                    pMedioTransporte.textContent = `Medio de transporte: ${medioTransporte}`;

                    const pFechaInicio = document.createElement('p');
                    pFechaInicio.textContent = `Fecha de inicio: ${fechaInicio}`;

                    const pHoraInicio = document.createElement('p');
                    pHoraInicio.textContent = `Hora de inicio: ${horaInicio}`; 

                    const pDuracion = document.createElement('p');
                    pDuracion.textContent = `Duración: ${duracion}`;

                    const pAgencia = document.createElement('p');
                    pAgencia.textContent = `Agencia: ${agencia}`;

                    const pDescripcion = document.createElement('p');
                    pMedioTransporte.textContent = `Descripción: ${descripcion}`;

                    const pPersonasAdecuadas = document.createElement('p');
                    pPersonasAdecuadas.textContent = `Personas adecuadas: ${personasAdecuadas}`;
                    
                    const pInicio = document.createElement('p');
                    pInicio.textContent = `Inicio: ${inicio}`;

                    const pRecomendacion = document.createElement('p');
                    pRecomendacion.textContent = `Recomendación: ${recomendacion}`;

                    const pHitos = document.createElement('p');
                    pHitos.textContent = `Hitos: ${hitos}`;

                    section.appendChild(h4);
                    section.appendChild(pTipo);
                    section.appendChild(pMedioTransporte);
                    section.appendChild(pFechaInicio);
                    section.appendChild(pHoraInicio);
                    section.appendChild(pDuracion);
                    section.appendChild(pAgencia);
                    section.appendChild(pDescripcion);
                    section.appendChild(pPersonasAdecuadas);
                    section.appendChild(pInicio);
                    section.appendChild(pRecomendacion);
                    section.appendChild(pHitos);

                    document.body.appendChild(section);
                }
            };

            reader.readAsText(file);
        } else {
            alert("Selecciona un archivo XML válido.");
        }
    } 

    readFilesKML(event){
        $("#kml").append("<h3>Mapa dinámico para los archivos KML</h3>");
        var files = event.target.files;

        mapboxgl.accessToken = 'pk.eyJ1Ijoib21pdGciLCJhIjoiY2xiNWF4OWp4MDE2bDNub2FlbHp3dmZvcyJ9.mQp5NnAxt9CuOm7GuD1ODg';
        var map = new mapboxgl.Map({
            container: "kml",
            style: "mapbox://styles/mapbox/streets-v11",
            center: [-3.70275, 40.41831],
            zoom: 3
        });

        map.addControl(new mapboxgl.NavigationControl());
        map.addControl(new mapboxgl.FullscreenControl());
        map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true,
                showUserHeading: true
            })
        );

        for (var i = 0, f; (f = files[i]); i++) {
            var reader = new FileReader();

            reader.onload = (function(file) {
                return function(e) {
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(e.target.result, 'text/xml');

                var coordinates = xmlDoc.querySelector('coordinates').textContent.split('\n');
                var coords = coordinates[1].split(",");

                for (var j=0; j<coords.length; j++) {
                    if(j%2===0 && coords[j] !== "0.0"){
                        if(coords[j].length > 17){
                            var lng = coords[j].substring(3, 21);
                            var lat = coords[j+1];

                            new mapboxgl.Marker().setLngLat([lng, lat])
                            .addTo(map);
                        } else{
                            var lng = coords[j];
                            var lat = coords[j+1];

                            new mapboxgl.Marker().setLngLat([lng, lat])
                            .addTo(map);
                        }
                    }
                }            
                };
            })(f);

            reader.readAsText(f);
        }
        
    }

    readFilesSVG(){
        var fileInput = document.querySelector('input[data-type="subirArchivosSVG"]');
        var files = fileInput.files;

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
  
            if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var svgString = e.target.result;
                var svg = $(svgString);
                var img = $("<img>");
                img.attr("src", "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svgString));

                $("section[data-type='svg']").append("<h3>Contenido del Archivo SVG:</h3>");
                $("section[data-type='svg']").append(img);
            };

            reader.readAsText(file);
            } else {
              alert("Selecciona un archivo SVG válido.");
            }
        }
    }

    nextImage(){
        const slides = document.querySelectorAll("img");

        const nextSlide = document.querySelector("button[data-action='next']");
        let curSlide = 3;
        let maxSlide = slides.length - 1;

        nextSlide.addEventListener("click", function () {
        if (curSlide === maxSlide) {
            curSlide = 0;
        } else {
            curSlide++;
        }

        slides.forEach((slide, indx) => {
            var trans = 100 * (indx - curSlide);
            $(slide).css('transform', 'translateX(' + trans + '%)')
        });
        });
    }

    prevImage(){
        const prevSlide = document.querySelector("button[data-action='prev']");
        let curSlide = 3;
        let maxSlide = slides.length - 1;

        prevSlide.addEventListener("click", function () {
        if (curSlide === 0) {
            curSlide = maxSlide;
        } else {
            curSlide--;
        }

        slides.forEach((slide, indx) => {
            var trans = 100 * (indx - curSlide);
            $(slide).css('transform', 'translateX(' + trans + '%)')
        });
        });
    }
}
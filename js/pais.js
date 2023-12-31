class Pais {
    constructor(nombre, capital, poblacion) {
        this.nombre = nombre;
        this.capital = capital;
        this.poblacion = poblacion;
        this.formaDeGobierno = "";
        this.coordenadas = { latitud: 0, longitud: 0 };
        this.religion = "";
    }

    llenarAtributos(formaDeGobierno, coordenadas, religion) {
        this.formaDeGobierno = formaDeGobierno;
        this.coordenadas = coordenadas;
        this.religion = religion;
    }

    obtenerNombre() {
        return this.nombre;
    }

    obtenerCapital() {
        return this.capital;
    }

    obtenerPoblacion() {
        return this.poblacion;
    }

    obtenerFormaDeGobierno() {
        return this.formaDeGobierno;
    }

    obtenerReligion() {
        return this.religion;
    }

    obtenerInfoPrincipal() {
        return `País: ${this.nombre}
            Capital: ${this.capital}
            Población: ${this.poblacion}
            Forma de Gobierno: ${this.formaDeGobierno}
            Religión Mayoritaria: ${this.religion}`;
    }

    obtenerInfoSecundariaHTML() {
        return `<ul>
            <li>Población: ${this.poblacion}</li>
            <li>Forma de Gobierno: ${this.formaDeGobierno}</li>
            <li>Religión Mayoritaria: ${this.religion}</li>
        </ul>`;
    }

    escribirCoordenadasEnDocumento() {
        document.write(`Coordenadas de la capital: Latitud ${this.coordenadas.latitud}, Longitud ${this.coordenadas.longitud}`);
    }
    
    obtenerPronostico() {
        var apiKey = "fe57cae61422e3426c07a46a1ebf024d";
        this.unidades = "&units=metric";
        var apiUrl = 'https://api.openweathermap.org/data/2.5/forecast?lat=' + this.coordenadas.latitud + '&lon=' + this.coordenadas.longitud + '&appid=' + apiKey + this.unidades;

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                mostrarPronostico(data);
            },
            error: function (error) {
                console.log('Error:', error);
            }
        });

        function mostrarPronostico(pronostico) {
            var contenedorPronostico = $('main');

            contenedorPronostico.empty();

            $.each(pronostico.list, function (index, dia) {
                if(index<5){
                    var fechaActual = new Date();
                    var fecha = new Date();
                    fecha.setDate(fechaActual.getDate() + index);

                    contenedorPronostico.append('<section><h3>' + fecha.toDateString() + '</h3>' +
                                    '<p>Temp Max: ' + (dia.main.temp_max) + '°C</p>' +
                                    '<p>Temp Min: ' + (dia.main.temp_min) + '°C</p>' +
                                    '<p>Humedad: ' + dia.main.humidity + '%</p>' +
                                    '<p>Cantidad de Lluvia: ' + (dia.rain ? dia.rain['3h'] : 0) + 'mm</p>' +
                                    '<img src="https://openweathermap.org/img/wn/' + dia.weather[0].icon + '.png" alt="Icono del tiempo"></section>');
                }
            });
        }
    }
}
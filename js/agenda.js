class Agenda {
    constructor() {
        this.url = "https://ergast.com/api/f1/current.json";
        this.lastApiCall = null;
        this.lastApiResult = null;
    }

    async consultarCarreras() {
        if (this.haPasadoIntervalo()) {
            try {
                const response = await $.ajax({
                    url: this.url,
                    method: "GET",
                    dataType: "json",
                });

                this.lastApiCall = new Date();
                this.lastApiResult = response;

                return response;
            } catch (error) {
                console.error("Error al consultar la API:", error);
                return null;
            }
        } else {
            console.log("No ha pasado el intervalo de tiempo entre consultas. Usando last_api_result.");
            return this.lastApiResult;
        }
    }

    haPasadoIntervalo() {
        if (!this.lastApiCall) {
            return true;
        }

        const ahora = new Date();
        const minutosTranscurridos = (ahora - this.lastApiCall) / (1000 * 60); 
        return minutosTranscurridos >= 10; 
    }

    mostrarCarrerasEnHTML(carreras) {
        if (carreras) {
            const carrerasContainer = $("section");
            carrerasContainer.empty();

            carrerasContainer.append("<h3>Calendario Formula 1</h3>");

            carreras.MRData.RaceTable.Races.forEach(function(carrera) {
                carrerasContainer.append(
                    `<section>
                        <h3>${carrera.raceName}</h3>
                        <p>Circuito: ${carrera.Circuit.circuitName}</p>
                        <p>Coordenadas: ${carrera.Circuit.Location.lat}, ${carrera.Circuit.Location.long}</p>
                        <p>Fecha y Hora: ${carrera.date} ${carrera.time}</p>
                    </section>`
                );
            });
        }
    }
}
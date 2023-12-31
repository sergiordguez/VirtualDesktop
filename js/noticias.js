class Noticias{
    constructor() {
        if (!(window.File && window.FileReader && window.FileList && window.Blob)){  
            document.write("<p>¡¡¡ Este navegador NO soporta el API File y este programa puede no funcionar correctamente !!!</p>");
        }
        
        this.noticias = [];
    }

    readInputFile(){
        const fileInput = $('input[data-type="fileInput"]')[0];
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                const content = e.target.result;
                const lineas = content.split('\n');

                for (const linea of lineas) {
                    const partes = linea.split('_');
                    const titulo = partes[0].trim();
                    const entradilla = partes[1].trim();
                    const texto = partes[2].trim();
                    const autor = partes[3].trim();

                    noticias.addNoticia(titulo, entradilla, texto, autor);
                }
            };

            reader.readAsText(file);
        }
    }

    addNoticia(titulo, entradilla, texto, autor) {
        const nuevaNoticia = { titulo, entradilla, texto, autor };
        this.noticias.push(nuevaNoticia);

        this.actualizarHTML();
    }

    actualizarHTML() {
        const noticiasContainer = $('main');
        noticiasContainer.empty();

        for (const noticia of this.noticias) {
            const noticiaS = $('<section>');
            noticiaS.append(`<h3>${noticia.titulo}</h3>`);
            noticiaS.append(`<p>${noticia.entradilla}</p>`);
            noticiaS.append(`<p>${noticia.texto}</p>`);
            noticiaS.append(`<p>Autor: ${noticia.autor}</p>`);

            noticiasContainer.append(noticiaS);
        }
    }

    agregarNoticia() {
        const tituloInput = $('input[data-type="tituloInput"]').val();
        const entradillaInput = $('input[data-type="entradillaInput"]').val();
        const textoInput = $('input[data-type="textoInput"]').val();
        const autorInput = $('input[data-type="autorInput"]').val();
    
        if (tituloInput && entradillaInput && textoInput && autorInput) {
            noticias.addNoticia(tituloInput, entradillaInput, textoInput, autorInput);
        }
    }
}

const noticias = new Noticias();
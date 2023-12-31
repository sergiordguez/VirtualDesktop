class Crucigrama{
    constructor(){
        this.board = [];
        this.columns = 9;
        this.rows = 11;
        this.init_time = null;
        this.end_time = null;
        this.level = "";
        this.start();
    }

    start(){
        const facil = "4,*,.,=,12,#,#,#,5,#,#,*,#,/,#,#,#,*,4,-,.,=,.,#,15,#,.,*,#,=,#,=,#,/,#,=,.,#,3,#,4,*,.,=,20,=,#,#,#,#,#,=,#,#,8,#,9,-,.,=,3,#,.,#,#,-,#,+,#,#,#,*,6,/,.,=,.,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,6,#,8,*,.,=,16";
        const medio = "12,*,.,=,36,#,#,#,15,#,#,*,#,/,#,#,#,*,.,-,.,=,.,#,55,#,.,*,#,=,#,=,#,/,#,=,.,#,15,#,9,*,.,=,45,=,#,#,#,#,#,=,#,#,72,#,20,-,.,=,11,#,.,#,#,-,#,+,#,#,#,*,56,/,.,=,.,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,12,#,16,*,.,=,32";
        const dificil = "4,.,.,=,36,#,#,#,25,#,#,*,#,.,#,#,#,.,.,-,.,=,.,#,15,#,.,*,#,=,#,=,#,.,#,=,.,#,18,#,6,*,.,=,30,=,#,#,#,#,#,=,#,#,56,#,9,-,.,=,3,#,.,#,#,*,#,+,#,#,#,*,20,.,.,=,18,#,#,#,.,#,#,=,#,=,#,#,#,=,#,#,18,#,24,.,.,=,72";

        let index = 0;
        const lista = facil.split(",");
        this.level = "facil";
        for (let i = 0; i < this.rows; i++) {
            this.board[i] = [];
            for (let j = 0; j < this.columns; j++) {
                const character = lista[index++];
                const value = character === "." ? 0 : character === "#" ? -1 : character;
                this.board[i][j] = value;
            }
        }
    }

    paintMathword(){
        const mainElement = $('main');

        for (let i = 0; i < this.rows; i++) {
            for (let j = 0; j < this.columns; j++) {
                const value = this.board[i][j];
                const paragraph = $('<p></p>');

                if (value === 0) {
                    paragraph.click(function() {
                        $(this).attr('data-state', 'clicked');
                    });
                } else {
                    if (value === -1) {
                        paragraph.text('');
                        paragraph.attr('data-state', 'empty');
                    } else {
                        paragraph.text(value);
                        paragraph.attr('data-state', 'blocked');
                    }
                }

                mainElement.append(paragraph);
            }
        }

        this.init_time = new Date();
    }

    check_win_condition(){
        for (let i = 0; i < this.rows; i++) {
            for (let j = 0; j < this.columns; j++) {
                const value = this.board[i][j];
                if(value == 0){
                    return false;
                }
            }
        }

        return true;
    }

    calculate_date_difference(){
        const timeDifference = this.end_time - this.init_time;

        const seconds = Math.floor((timeDifference / 1000) % 60);
        const minutes = Math.floor((timeDifference / (1000 * 60)) % 60);
        const hours = Math.floor((timeDifference / (1000 * 60 * 60)) % 24);

        return hours + ':' + minutes + ':' + seconds;
    }

    introduceElement(value){
        let selectedCell = document.querySelector('[data-state="clicked"]');

        if (selectedCell === null) {
            alert("Debe seleccionar una celda antes de pulsar cualquier tecla.");
            return;
        }

        const validKeyRegex = /^[1-9+\-*/]$/;

        if (!validKeyRegex.test(value)) {
            alert("Tecla no válida. Solo se permiten números y operadores aritméticos.");
            return;
        }
 
        const elemento = document.querySelector("p[data-state='clicked']");
        const allCells = document.querySelectorAll('main p');
        
        let currentCol = 0;
        let currentRow = 0;

        for(let i=1; i<allCells.length; i++){
            if(allCells[i].getAttribute("data-state") === elemento.getAttribute("data-state")){
                currentCol = Math.trunc(i / 9);
                currentRow = i % 9;
            }
        }

        let expression_row = true;
        let expression_col = true;

        this.board[currentCol][currentRow] = value;

        let nextRow = currentRow + 1;
        if (this.board[currentCol][nextRow] === '=') {
            const first_number = this.board[currentCol][nextRow - 3];
            const expression = this.board[currentCol][nextRow - 2];
            const second_number = this.board[currentCol][nextRow - 1];
            const result = this.board[currentCol][nextRow + 1];

            if (first_number !== 0 && second_number !== 0 && expression !== 0 && result !== 0) {
                const mathExpression = [first_number, expression, second_number].join('');
                if (eval(mathExpression) !== parseInt(result)) {
                    expression_col = false;
                }
            }
        }

        let nextCol = currentCol + 1;
        if(nextCol < this.columns){
            if (this.board[nextCol][currentRow] === '=') {
                const first_number = this.board[nextCol - 3][currentRow];
                const expression = this.board[nextCol - 2][currentRow];
                const second_number = this.board[nextCol - 1][currentRow];
                const result = this.board[nextCol + 1][currentRow];
                            
                if (first_number !== 0 && second_number !== 0 && expression !== 0 && result !== 0) {
                    const mathExpression = [first_number, expression, second_number].join('');
                    if (eval(mathExpression) !== parseInt(result)) {
                        expression_row = false;
                    }
                }
            }
        }

        if (expression_row && expression_col) {
            elemento.innerHTML = value;
            elemento.dataset.state = 'correct';
        } else {
            alert("El elemento introducido no es correcto para la casilla seleccionada.");
        }

        if (this.check_win_condition()) {
            this.end_time = new Date();
            const time_difference = this.calculate_date_difference();
            alert('¡Has completado el crucigrama en ' + time_difference + ' segundos!');
            this.createRecordForm();
        }
    }

    createRecordForm(){
        const form = $('<form>', {
            action: '#',
            method: 'post'
          });

          form.append($('<label>', { text: 'Nombre:' }));
          form.append($('<input>', { type: 'text', name: 'nombre' }));
      
          form.append($('<label>', { text: 'Apellidos:' }));
          form.append($('<input>', { type: 'text', name: 'apellidos' }));
      
          form.append($('<label>', { text: 'Nivel:' }));
          form.append($('<input>', { type: 'text', name: 'nivel', value: this.level, readonly: 'readonly' }));
      
          const time_difference = this.calculate_date_difference();
      
          form.append($('<label>', { text: 'Tiempo empleado:' }));
          form.append($('<input>', { type: 'text', name: 'tiempo', value: time_difference, readonly: 'readonly' }));

          form.append("<button type='submit' name='guardar'>Guardar en la Base de Datos</button>");

          $('body').append(form);
    }
}
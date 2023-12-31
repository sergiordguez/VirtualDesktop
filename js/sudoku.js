class Sudoku {
  constructor() {
    this.board = [];
    this.rows = 9;
    this.cols = 9;
    this.start();
  }

  start() {
    const sudokuString = "3.4.69.5....27...49.2..4....2..85.198.9...2.551.39..6....8..5.32...46....4.75.9.6";
    this.board = new Array(this.rows).fill(0).map(() => new Array(this.cols).fill(0));
    this.initializeBoard(sudokuString);
    this.createStructure();
    this.paintSudoku();
  }

  initializeBoard(sudokuString) {
    let index = 0;
    for (let i = 0; i < this.rows; i++) {
      for (let j = 0; j < this.cols; j++) {
        if (sudokuString[index] !== '.') {
          this.board[i][j] = parseInt(sudokuString[index]);
        }
        index++;
      }
    }
  }

  createStructure() {
    const main = document.querySelector('main');
    for (let i = 0; i < this.rows; i++) {
        for (let j = 0; j < this.cols; j++) {
            const paragraph = document.createElement('p');
            paragraph.addEventListener('click', () => this.handleCellClick(i, j));
            paragraph.setAttribute("data-fila", i);
            main.appendChild(paragraph);
        }
    }
  }

  handleCellClick(row, col) {
    const clickedCells = document.querySelectorAll('main p[data-state="clicked"]');
    clickedCells.forEach(cell => {
        cell.dataset.state = '';
    });

    const clickedCell = document.querySelector(`main p:nth-child(${row * this.cols + col + 1})`);
    clickedCell.dataset.state = 'clicked';
  }

  paintSudoku() {
    const cells = document.querySelectorAll('main p');
    for (let i = 0; i < this.rows; i++) {
      for (let j = 0; j < this.cols; j++) {
        const cellValue = this.board[i][j];
        const cell = cells[i * this.cols + j];
        if (cellValue !== 0) {
          cell.textContent = cellValue;
          cell.setAttribute('data-state', 'blocked');
        } else {
          cell.textContent = '';
        }
      }
    }
  }

  handleKeyPress(key) {
    const selectedCell = this.getSelectedCell();

    if (key >= 1 && key <= 9) {
        if (selectedCell) {
            this.introduceNumber(selectedCell, parseInt(key));
        } else {
            alert("Selecciona una celda antes de introducir un número.");
        }
    }
  }

  getSelectedCell() {
    const clickedCells = document.querySelectorAll('main p[data-state="clicked"]');
    return clickedCells.length === 1 ? clickedCells[0] : null;
  }

  introduceNumber(cell, number) {
    const row = Math.floor(Array.from(cell.parentNode.children).indexOf(cell) / this.cols);
    const col = Array.from(cell.parentNode.children).indexOf(cell) % this.cols;

    if (this.isValidMove(row, col, number)) {
        cell.removeEventListener('click', () => this.handleCellClick(row, col));

        cell.dataset.state = 'correct';
        cell.textContent = number;

        if (this.isSudokuCompleted()) {
            alert('¡Sudoku completado!');
        }
    } else {
        alert('Número no válido para esta casilla. Inténtalo de nuevo.');
    }
  }

  isValidMove(row, col, number) {
    return this.isValidInRow(row, number) &&
            this.isValidInColumn(col, number) &&
            this.isValidInSubgrid(row, col, number);
  }

  isValidInRow(row, number) {
    return !this.board[row].includes(number);
  }

  isValidInColumn(col, number) {
    for (let i = 0; i < this.rows; i++) {
      if (this.board[i][col] === number) {
        return false;
      }
    }
    return true;
  }

  isValidInSubgrid(row, col, number) {
    const subgridRowStart = Math.floor(row / 3) * 3;
    const subgridColStart = Math.floor(col / 3) * 3;

    for (let i = subgridRowStart; i < subgridRowStart + 3; i++) {
      for (let j = subgridColStart; j < subgridColStart + 3; j++) {
        if (this.board[i][j] === number) {
          return false;
        }
      }
    }

    return true;
  }

  isSudokuCompleted() {
    for (let i = 0; i < this.rows; i++) {
      for (let j = 0; j < this.cols; j++) {
        if (this.board[i][j] === 0) {
          return false;
        }
      }
    }
    return true;
  }
}
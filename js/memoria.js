class Memoria {
  constructor() {
    this.hasFlippedCard = false;
    this.lockBoard = false;
    this.firstCard = null;
    this.secondCard = null;
    this.elements = [
      { element: 'HTML5', source: 'https://upload.wikimedia.org/wikipedia/commons/3/38/HTML5_Badge.svg' },
      { element: 'CSS3', source: 'https://upload.wikimedia.org/wikipedia/commons/6/62/CSS3_logo.svg' },
      { element: 'JS', source: 'https://upload.wikimedia.org/wikipedia/commons/b/ba/Javascript_badge.svg' },
      { element: 'PHP', source: 'https://upload.wikimedia.org/wikipedia/commons/2/27/PHP-logo.svg' },
      { element: 'SVG', source: 'https://upload.wikimedia.org/wikipedia/commons/4/4f/SVG_Logo.svg' },
      { element: 'W3C', source: 'https://upload.wikimedia.org/wikipedia/commons/5/5e/W3C_icon.svg' },
      { element: 'HTML5', source: 'https://upload.wikimedia.org/wikipedia/commons/3/38/HTML5_Badge.svg' },
      { element: 'CSS3', source: 'https://upload.wikimedia.org/wikipedia/commons/6/62/CSS3_logo.svg' },
      { element: 'JS', source: 'https://upload.wikimedia.org/wikipedia/commons/b/ba/Javascript_badge.svg' },
      { element: 'PHP', source: 'https://upload.wikimedia.org/wikipedia/commons/2/27/PHP-logo.svg' },
      { element: 'SVG', source: 'https://upload.wikimedia.org/wikipedia/commons/4/4f/SVG_Logo.svg' },
      { element: 'W3C', source: 'https://upload.wikimedia.org/wikipedia/commons/5/5e/W3C_icon.svg' }
    ];

    this.shuffleElements();
    this.createElements();
    this.addEventListeners();
  }

  shuffleElements() {
    for (let i = this.elements.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [this.elements[i], this.elements[j]] = [this.elements[j], this.elements[i]];
    }
  }

  unflipCards() {
    this.lockBoard = true;
    setTimeout(() => {
      this.firstCard.setAttribute("data-state", "unflipped");
      this.secondCard.setAttribute("data-state", "unflipped");
      this.resetBoard();
    }, 1000);
}

  resetBoard() {
    this.hasFlippedCard = false;
    this.lockBoard = false;
    this.firstCard = null;
    this.secondCard = null;
  }

  checkForMatch() {
    const isMatch = this.firstCard.getAttribute("data-element") === this.secondCard.getAttribute("data-element");
    isMatch ? this.disableCards() : this.unflipCards();
  }

  disableCards() {
    this.firstCard.setAttribute("data-state", "revealed");
    this.secondCard.setAttribute("data-state", "revealed");
    this.resetBoard();
  }

  createElements() {
    const memoryBoard = document.querySelector('section');
    
    const heading = document.createElement('h3');
    heading.textContent = 'Juego de Memoria';

    memoryBoard.appendChild(heading);

    this.shuffleElements();

    this.elements.forEach(element => {
      const card = document.createElement('article');
      card.dataset.element = element.element;

      const h2 = document.createElement('h2');
      h2.textContent = 'Tarjeta de memoria';

      const img = document.createElement('img');
      img.src = element.source;
      img.alt = element.element;

      card.appendChild(h2);
      card.appendChild(img);

      memoryBoard.appendChild(card);
    });
  }

  addEventListeners() {
    let cards = document.querySelectorAll("article");
    cards.forEach((card) => {
        card.addEventListener("click", this.flipCard.bind(card, this));
    });
  }

  flipCard(game) {
    if (this.getAttribute("data-state") == "revealed") return;
    if (game.lockBoard) return;
    if (this == game.firstCard) return;

    this.setAttribute("data-state", "flip");

    if (!game.hasFlippedCard) {
      game.hasFlippedCard = true;
      game.firstCard = this;
      return;
    } else{
      game.secondCard = this;
      game.checkForMatch();
    }

  }
}
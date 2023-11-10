htmx.on("htmx:load", function (evt) {

});

htmx.on("htmx:configRequest", function (evt) {
  // Get the content within "div#styles-container" and add it to the request.
  evt.detail.headers['styles'] = document.querySelector('div#styles-container').innerHTML;
});

// HTMX Logger
htmx.logger = function (elt, event, data) {
  if (console) {
    console.log(event, elt, data);
  }
};

document.addEventListener('DOMContentLoaded', function () {
  const cards = document.querySelectorAll('.card');
  const specialCard = document.querySelector('.card-special');

  let hasFlippedCard = false;
  let firstCard, secondCard;
  let matchedCards = 0;

  cards.forEach(card => {
    card.addEventListener('click', flipCard);
  });

  function flipCard() {
    this.querySelector('.card-inner').style.transform = "rotateY(180deg)";

    if (!hasFlippedCard) {
      hasFlippedCard = true;
      firstCard = this;
    } else {
      secondCard = this;
      checkForMatch();
    }
  }

  function checkForMatch() {
    if (firstCard === specialCard || secondCard === specialCard) {
      // If one of the flipped cards is the special card, just reset.
      setTimeout(unflipCards, 1000);
    } else if (firstCard.dataset.id === secondCard.dataset.id) {
      disableCards();
      matchedCards += 2;

      // Check if all matchable cards are matched
      if (matchedCards === cards.length - 1) {
        activateSpecialCard();
      }
    } else {
      unflipCards();
    }
  }

  function activateSpecialCard() {
    // Add the wiggle animation to the special card once all other cards are matched.
    specialCard.querySelector('.card-inner').classList.add('wiggle');

    // Add click event listener to the special card.
    specialCard.addEventListener('click', function () {
      // Remove the wiggle animation.
      specialCard.querySelector('.card-inner').classList.remove('wiggle');

      // Change the card's contents.
      specialCard.querySelector('.card-back').innerHTML = "🎉";

      // Flip the special card.
      specialCard.querySelector('.card-inner').style.transform = "rotateY(180deg)";

      // Remove the click event listener.
      specialCard.removeEventListener('click', this);

      fadeOutGameAndShowContactForm();
    });
  }

  function disableCards() {
    firstCard.removeEventListener('click', flipCard);
    secondCard.removeEventListener('click', flipCard);
    resetBoard();
  }

  function unflipCards() {
    firstCard.querySelector('.card-inner').style.transform = "";
    secondCard.querySelector('.card-inner').style.transform = "";
    resetBoard();
  }

  function resetBoard() {
    [hasFlippedCard, firstCard, secondCard] = [false, null, null];
  }

  function fadeOutGameAndShowContactForm() {
    const gameContainer = document.querySelector('.game-container');
    const contactFormContainer = document.querySelector('.contact-form');

    // Start fading out the game board.
    gameContainer.style.opacity = '0';

    // After the game board has completely faded out (using the same duration as your CSS transition),
    // show the contact form.
    setTimeout(() => {
      gameContainer.style.display = 'none';
      contactFormContainer.style.display = 'block';

      // Begin the fade-in transition for the contact form.
      setTimeout(() => {
        contactFormContainer.style.opacity = '1';
      }, 50); // Small delay to trigger the transition.

    }, 500); // This value should match the duration of your CSS transition.
  }
});



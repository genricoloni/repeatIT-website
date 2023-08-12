function onload(){
// Seleziona l'elemento del titolo dell'esercizio
const exerciseTitle = document.querySelector('.left h1');

// Aggiungi un listener per l'evento mouseover
exerciseTitle.addEventListener('mouseover', () => {
  exerciseTitle.style.color = '#ff9900'; // Cambia il colore del testo al passaggio del mouse
  exerciseTitle.style.transition = 'color 0.3s'; // Aggiungi una transizione per l'effetto sfumatura
});

// Aggiungi un listener per l'evento mouseout
exerciseTitle.addEventListener('mouseout', () => {
  exerciseTitle.style.color = '#333'; // Ripristina il colore del testo dopo il passaggio del mouse
});

}
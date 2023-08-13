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

//aggiungo event al pulsante 'show_solution'
var add_solution = document.querySelector(".show_solution");
add_solution.addEventListener("click", show_solution);


}

function show_solution(event){
    let id = event.currentTarget.id.replace("show_solution", "");

    //faccio chiamata ajax per ottenere le soluzioni
    let url = "../php/get_solutions.php?id=" + id;
    fetch(url).then(onResponse).then(onSolutionJson);
}

function onResponse(response){
    return response.json();
}

function onSolutionJson(json){

  //rimuovo il pulsante visualizza soluzione
  let add_solution = document.querySelector(".show_solution");
  add_solution.parentNode.removeChild(add_solution);



  //faccio un div per ogni soluzione, con dentro il codice e il linguaggio

  //esso sarà dentro il div container
  let container = document.querySelector(".left");

  for (let solution of json){
    let div = document.createElement("div");
    div.classList.add("solution");

    let code = document.createElement("p");
    code.textContent = solution.text;

    let language = document.createElement("p");
    language.textContent = solution.language;

    //aggiungo una linea di separazione
    let line = document.createElement("hr");
    line.classList.add("line");

    div.appendChild(language);

    div.appendChild(code);

    div.appendChild(line);

    container.appendChild(div);
  }
}

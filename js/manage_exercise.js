function onload(){
    //aggiungo event listener ad ogni exercise_preview
    var exercise_preview = document.getElementsByClassName("prev");
    for(var i = 0; i < exercise_preview.length; i++){
        exercise_preview[i].addEventListener("click", show_exercise);
    }

}

function show_exercise(event){

    //recupero l'id dell'esercizio che ha scatenato l'evento
    var id = event.currentTarget.id;

    //recupero l'id dell'esercizio
    id = id.replace("prev_", "");


    let text = document.getElementById("exercise_text_" + id);

    if(text.style.display == "none"){
        text.style.display = "block";

        //rendo invisibili gli eventuali altri testi
        var texts = document.getElementsByClassName("exercise_text");
        for(var i = 0; i < texts.length; i++){
            if(texts[i].id != text.id){
                texts[i].style.display = "none";
            }
        }
    } else {
        text.style.display = "none";
        
    }
}


function showSolution(id){

    //se ci sono delle soluzioni già presenti nel div right le rimuovo
    var right = document.querySelector(".right");
    var solutions = document.getElementsByClassName("solution");
    if(solutions.length > 0){
        for(var i = 0; i < solutions.length; i++){
            right.removeChild(solutions[i]);
        }
    }

    //rimuovo il pulsante 'aggiungi soluzione' se è presente
    var add_solution = document.querySelector(".add_solution");
    if(add_solution != null){
        right.removeChild(add_solution);
    }

    


    //tramite chiamata ajax recupero la soluzione dell'esercizio
    fetch("get_solutions.php?id=" + id).then(onResponse).then(onSolutionJson);

}


function onResponse(response){
    //verifico che la risposta sia ok
    if(!response.ok){
        throw new Error("HTTP error, status = " + response.status);
    }
    return response.json();
}

function onSolutionJson(json){

    //cancello tutto il contenuto del div right
    var right = document.querySelector(".right");
    right.innerHTML = "";





    //se c'è solo un elemento e nel campo text c'è scritto 'no solution' allora non ci sono soluzioni
    if(json.length == 1 && json[0]['text'] == "no solution"){
        //e poi ci rimetto <h1>Gestione soluzione</h1>
        var title = document.createElement("h1");
        title.textContent = "Gestione soluzioni";
        right.appendChild(title);
        //inserisco un pulsante 'aggiungi soluzione' in cima al div right
        var add_solution = document.createElement("button");
        add_solution.textContent = "Aggiungi soluzione";
        add_solution.classList.add("add_solution");
        add_solution.addEventListener("click", addSolution);
        var right = document.querySelector(".right");

        //imposto id del pulsante come add_id
        var id = json[0]['exercise'];
        add_solution.id = "add_" + id;
    
    

        var no_solution = document.createElement("p");
        no_solution.textContent = "Non ci sono soluzioni per questo esercizio";
        no_solution.classList.add("no_solution");
        right.appendChild(no_solution);

        right.appendChild(add_solution);


        return;
        
        
    }

    //e poi ci rimetto <h1>Gestione soluzione</h1>
    var title = document.createElement("h1");
    console.log(json[0]);
    title.textContent = "Gestione soluzioni: ";
    //inserisco il titolo dell'esercizio dall'altro div
    for (i of document.getElementsByClassName("exercise_preview")){
        if(i.id == json[0]['exercise']){
            //prendo il figlio del figlio del div
            title.textContent += i.children[0].children[0].textContent;
            
        } }

    right.appendChild(title);

    //inserisco un pulsante 'aggiungi soluzione' in cima al div right
    var add_solution = document.createElement("button");
    add_solution.textContent = "Aggiungi soluzione";
    add_solution.classList.add("add_solution");
    add_solution.addEventListener("click", addSolution);
    var right = document.querySelector(".right");

    //imposto id del pulsante come add_id
    var id = json[0]['exercise'];
    add_solution.id = "add_" + id;


    right.appendChild(add_solution);





    //creo un div per ogni soluzione
    for(var i = 0; i < json.length; i++){
        //il div ha un titolo, che è il linguaggio della soluzione
        var title = document.createElement("h3");
        title.textContent = json[i]['language'];
        title.classList.add("solution_title");

        //e un testo, che è la soluzione
        var text = document.createElement("p");
        text.textContent = json[i]['text'];
        text.classList.add("solution_text");

        //aggiungo una linea di separazione
        var line = document.createElement("hr");
        line.classList.add("line");

        
        //creo un div che contiene il titolo e il testo
        var solution = document.createElement("div");
        solution.classList.add("solution");
        solution.appendChild(title);
        solution.appendChild(text);
        solution.appendChild(line);

        //aggiungo il div al div right
        var right = document.querySelector(".right");
        right.appendChild(solution);


    }

}

//funzione che aggiunge una soluzione prendendo l'id dell'esercizio
function addSolution(event){
    id = event.currentTarget.id.replace("add_", "");

    console.log(id);

    //reindirizzo l'utente alla pagina di aggiunta soluzione
    window.open("add_solution.php?id=" + id, "_self", "");

}



  
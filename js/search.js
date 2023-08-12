function setup(){
    //aggiungo event ai bottoni per visualizzare gli esercizi
    let show_exercises = document.querySelectorAll(".btn");
    for(let show_exercise of show_exercises){
        show_exercise.addEventListener("click", showExercise);
    }

    document.getElementById("reset").addEventListener("click", reset);
}


function show(){
    //elimina tutti gli esercizi, prende i dati dal form e fa una richiesta ajax
    //per ottenere gli esercizi che rispettano i criteri di ricerca
    //e li mostra
    //recupero i dati dal form
    let form = document.getElementById("form");
    //ci sono i campi titolo, categoria, difficoltà, autore
    let title = form.title.value;
    let category = form.category.value;
    let difficulty = form.difficulty.value;
    let creator = form.creator.value;
    
    // se il titolo è vuoto lo metto a any
    if(title == ""){
        title = "any";
    }

    //se tutti i parametry sono any non faccio la richiesta ajax
    if(title == "any" && category == "any" && difficulty == "any" && creator == "any")
        return;

    //elimino tutti gli esercizi
    let exercises = document.querySelectorAll(".exercise_preview");
    for(let exercise of exercises){
        exercise.parentNode.removeChild(exercise);
    }


    //faccio la richiesta ajax
    let url = "../php/search_exercise.php?title=" + title + "&category=" + category + "&difficulty=" + difficulty + "&creator=" + creator;
    console.log(url);

    fetch(url).then(onResponse).then(onJson);
    
}


function onResponse(response){
    //verifico che la risposta sia ok
    if(!response.ok){
        console.log("Errore nella risposta del fetch");
        return null;
    }

    return response.json();
}
function onJson(json){
    console.log(json);

    //verifico prima di tutto che ci siano degli esercizi
    if(json.length == 0){
        let div = document.querySelector(".exercises");
        let p = document.createElement("p");
        p.textContent = "Non ci sono esercizi che rispettano i criteri di ricerca";
        div.appendChild(p);
        return;
    }

    //per ogni esercizio creo un div che contiene le informazioni
    for(let i = 0; i < json.length; i++){
        //recupero i dati
        let id = json[i]['exercise_id'];
        let title = json[i]['title'];
        let category = json[i]['category'];
        let difficulty = json[i]['difficulty'];
        let creator = json[i]['creator'];

        //creo un div exercise_preview
        let exercise_preview = document.createElement("div");
        exercise_preview.classList.add("exercise_preview");
        exercise_preview.id =  id;

        //creo div prev
        let prev = document.createElement("div");
        prev.classList.add("prev");
        prev.id = "prev_" + id;

        //div title
        let div_title = document.createElement("div");
        div_title.classList.add("title");
        //h1 title
        let h1_title = document.createElement("h1");
        h1_title.textContent = title;
        div_title.appendChild(h1_title);

        //div category
        let div_category = document.createElement("div");
        div_category.classList.add("category");
        //h2 category
        let h2_category = document.createElement("h2");
        h2_category.textContent = category;
        div_category.appendChild(h2_category);

        //div difficulty
        let div_difficulty = document.createElement("div");
        div_difficulty.classList.add("difficulty");
        //h2 difficulty
        let h2_difficulty = document.createElement("h2");
        h2_difficulty.textContent = difficulty;
        div_difficulty.appendChild(h2_difficulty);

        //div creator
        let div_creator = document.createElement("div");
        div_creator.classList.add("creator");
        //h2 creator
        let h2_creator = document.createElement("h2");
        h2_creator.textContent = creator;
        div_creator.appendChild(h2_creator);

        //appendo div_title, div_category, div_difficulty, div_creator a prev
        prev.appendChild(div_title);
        prev.appendChild(div_category);
        prev.appendChild(div_difficulty);
        prev.appendChild(div_creator);
        

        //creo un div per il bottone
        let div_button = document.createElement("div");
        div_button.classList.add("button");

        //aggiungo un bottone per visualizzare l'esercizio
        let show = document.createElement("button");
        show.textContent = "Visualizza";
        show.classList.add("btn");
        //esso ha id show_exerciseID
        show.id = "show_exercise" + id;
        show.addEventListener("click", showExercise);

        //il bottone viene aggiunto al div button
        div_button.appendChild(show);
        
        //appendo prev e button a exercise_preview
        exercise_preview.appendChild(prev);
        exercise_preview.appendChild(div_button);

        //appendo exercise_preview a exercises 
        let exercises = document.querySelector(".exercises");
        exercises.appendChild(exercise_preview);
        
                
    }

}

function reset(){
    //la funzione resetta i campi del form
    let form = document.getElementById("form");
    form.title.value = "";
    form.category.value = "any";
    form.difficulty.value = "any";
    form.creator.value = "any";

    //elimino tutti gli esercizi
    let exercises = document.querySelectorAll(".exercise_preview");
    for(let exercise of exercises){
        exercise.parentNode.removeChild(exercise);
    }

    //faccio una richiesta ajax per ottenere tutti gli esercizi
    let url = "../php/search_exercise.php?title=any&category=any&difficulty=any&creator=any";
    console.log(url);

    //uso la stessa funzione onJson
    fetch(url).then(onResponse).then(onJson);


    
}



function showExercise(event){
    let id = event.target.id;

    id = id.replace("show_exercise", "");
    console.log(id);

    //apro la pagina dell'esercizio
    window.location.href = "../php/exercise.php?exercise_id=" + id;

}




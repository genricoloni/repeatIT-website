function setup(role){
    //se l'utente è un tutor
    if(role == "tutor"){
        let suggest_form = document.querySelector("#sendSuggestion");
        suggest_form.addEventListener("click", addExercise);
    } else {
        //è uno studente
        console.log("studente");
        //aggiungo event a tutti i tr con classe suggestedExercise
        let suggested_exercises = document.querySelectorAll(".suggestedExercise");
        for(let suggested_exercise of suggested_exercises){
            suggested_exercise.addEventListener("click", showExercise);
            console.log(suggested_exercise);
        }
    }
}


function showExercise(event){
    let id = event.currentTarget.id;
}

function addExercise(){

    //recupero i dati dai div student e exercise
    let student = document.querySelector("#student");
    let exercise = document.querySelector("#exercise");

    //recupero i valori
    let student_id = student.value;
    let exercise_id = exercise.value;



    //converto i valori in stringhe
    student_id = encodeURIComponent(student_id);
    exercise_id = encodeURIComponent(exercise_id);

    console.log(student_id);
    console.log(exercise_id);

    //faccio richiesta ajax con POST
    let url = "suggest.php?student=" + student_id + "&exercise=" + exercise_id;
    console.log(url);

    fetch(url).then(onResponse).then(onSuggestionJson);
    
    
}

function onResponse(response){
    return response.json();
}

function onSuggestionJson(json){

    console.log(json);
    //leggo il campo status
    if(json['status'] == "success"){
        //metto un messaggio di successo dentro al div suggestForm che sparisce dopo 5 secondi
        let suggestForm = document.querySelector(".suggest");
        //creo un p che contiene il messaggio
        let p = document.createElement("p");
        p.textContent = "Esercizio suggerito con successo";
        suggestForm.appendChild(p);

        //elimino solo il p dopo 5 secondi
        setTimeout(function(){suggestForm.removeChild(p);}, 5000);



    } else{
        //metto un messaggio di errore dentro al div suggestForm che sparisce dopo 5 secondi
        let suggestForm = document.querySelector(".suggest");

        //creo un p che contiene il messaggio
        let p = document.createElement("p");
        p.textContent = "Si è verificato un errore, riprova";
        suggestForm.appendChild(p);

        //elimino solo il p dopo 5 secondi
        setTimeout(function(){suggestForm.removeChild(p);}, 5000);



    }

}
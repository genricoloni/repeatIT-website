function setup(){

    //per ogni pulsante con classe show_exercises

    for (i of document.querySelectorAll(".show_exercises")){
        i.addEventListener("click", showExercises);
    }

    //per ogni pulsante con classe send_request
    for (i of document.querySelectorAll(".send_request")){
        i.addEventListener("click", sendRequest);
    }


}



function showExercises(event){

    //recupero l'oggetto che ha scatenato l'evento
    var obj = event.currentTarget;

    var id = obj.id.replace("show", "");

    //cambio il testo del pulsante da 'mostra' a 'nascondi' e viceversa
    if(obj.textContent == "Mostra esercizi"){
        var buttons = document.querySelectorAll(".show_exercises");
        for(var i = 0; i < buttons.length; i++){
            if(buttons[i].textContent == "Nascondi esercizi"){
                buttons[i].textContent = "Mostra esercizi";
            }
        }
        obj.textContent = "Nascondi esercizi";

        //se ci sono altri bottoni con contenuto 'nascondi' li cambio in 'mostra'


        //se ci sono altri div con classe exercises_ li elimino
        var div = document.querySelectorAll(".exercises_");
        for(var i = 0; i < div.length; i++){
            div[i].remove();    
        }
        fetch("../php/get_exercises.php?tutor_id=" + id).then(onResponse).then(onJson);

        
    }

    else{
        obj.textContent = "Mostra esercizi";
        //elimino il div che contiene gli esercizi del tutor
        var div = document.getElementById("exercises_" + id);
        div.remove();
    }

    //recupero l'id del pulsante
    



}

function onResponse(response){
    //verifico che la risposta sia ok
    if(!response.ok){
        return null;
    }

    return response.json();
}

function onJson(json){

    //creo un div che conterrà gli esercizi
    var div = document.createElement("div");
    div.classList.add("exercises_");

    //aggiungo come id exercises_ + id del tutor
    div.id = "exercises_" + json[0]['tutor_id'];

    div.innerHTML = "<h3> Esercizi </h3>";


    //per ogni esercizio creo un div che contiene le informazioni
    for(var i = 0; i < json.length; i++){
        //creo un div che contiene le informazioni
        var exercise = document.createElement("div");   
        exercise.classList.add("exercise");
        exercise.id = "exercise_" + json[i]['exercise_id'] + "_" + json[i]['tutor_id'];

        //creo un h3 che contiene il titolo dell'esercizio
        var title = document.createElement("h3");
        title.textContent = json[i]['title'];
        title.classList.add("exercise_title");

        //converto la difficoltà in parole


        var difficulty = "";
        switch(json[i]['difficulty']){
            case 1:
                difficulty = "Facile";
                break;
            case 2:
                difficulty = "Facile";
                break;
            case 3:
                difficulty = "Media";
                break;
            case 4:
                difficulty = "Media";
                break;
            default:
                difficulty = "Difficile";
                break;
        }


        //creo un p che contiene categoria e difficoltà
        var category = document.createElement("p");
        category.textContent = "Categoria: " + json[i]['category'] + " - Difficoltà: " + difficulty;

        //appendo h3 e p al div exercise
        exercise.appendChild(title);
        exercise.appendChild(category);

        //appendo il div exercise al div exercises  
        div.appendChild(exercise);


    }
    //appendo div al div di id tutor+id
    var tutor = document.getElementById("tutor" + json[0]['tutor_id']);
    tutor.appendChild(div);



}

function sendRequest(event){
    //recupero l'id del pulsante
    var id = event.currentTarget.id.replace("send", "");

    //recupero l'id del tutor
    var tutor_id = document.getElementById("tutor" + id).id.replace("tutor", "");


    //invio una richiesta di aggiunta al database mettendo come parametro solo l'id del tutor
    fetch("../php/send_tutor_request.php?id=" + tutor_id).then(onResponse).then(onJson2);
    //cambio il testo del pulsante
    var button = document.getElementById("send" + id);
    button.textContent = "Richiesta inviata";
    //disabilito il pulsante
}



function onJson2(json){
    //apro il json ricevuto 

    //è presente il campo status
    if(json['status'] == "success"){
        //modifico il testo del pulsante
        var button = document.getElementById("send" + json['id']);
        button.textContent = "Richiesta inviata";
        //disabilito il pulsante
        button.disabled = true;

    }
    else{
        //modifico il testo del pulsante
        var button = document.getElementById("send" + json['id']);
        button.textContent = "Si è verificato un errore, clicca per riprovare";


    }




}




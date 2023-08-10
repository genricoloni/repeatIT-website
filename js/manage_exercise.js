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

    console.log(id);
    //recupero l'id dell'esercizio
    id = id.replace("prev_", "");

    console.log(id);

    let text = document.getElementById("exercise_text_" + id);
    console.log(text);

    if(text.style.display == "none"){
        text.style.display = "block";
        //imposto larghezza massima di exercise_preview a 55%
        document.getElementById(id).style.maxWidth = "55%";
    }else{
        text.style.display = "none";
        //rimuovo larghezza massima di exercise_preview
        document.getElementById(id).style.maxWidth = "none";
        
    }
}


function showSolution(id){
    console.log(id);

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
    console.log(json[0]['text']);



    //scrivo il contenuto del json nel div right
    document.querySelector(".right").innerHTML += json[0]['text'];

}
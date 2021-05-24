function onResponse(response){
    return response.json();
}

function onJsonTeam(json){
    //console.log(json);
    const section = document.querySelector('#team');

    for(let i=0; i<json.data.length && i < 15 ; i++){
        const new_h3 = document.createElement('h3');
        new_h3.textContent = json.data[i].attributes.name;
        
        new_h3.setAttribute('id',json.data[i].id.toString());
        new_h3.addEventListener('click',searchTeamSport);
        section.appendChild(new_h3);
    }
}

function initializeTeamSport(event){
    fetch("http://localhost/hw1/pagina2/api_sport.php?groups=all").then(onResponse).then(onJsonTeam);
}
initializeTeamSport();


//////////////////////////////////////////////////////////////////////////////////////////////
function onJson(json){
    const section = document.querySelector('#sport');
    section.innerHTML = "";
    
    if(json.length === 0){
        const new_p = document.createElement('p');
        new_p.textContent = "Nessun risultato trovato";

        section.appendChild(new_p);
    }else{
        for(let i = 0; i < json.length && i < 10; i++){

            if(json[i].relationships.images.data.length != 0){
                const new_div = document.createElement('div');
                const new_img = document.createElement('img');
                const new_h3 = document.createElement('h3');
                const new_p = document.createElement('p');
            
                new_h3.textContent = json[i].attributes.name;
                new_img.src = json[i].relationships.images.data[0].url;
                if(json[i].attributes.description === null){
                    new_p.textContent = "Al momento non abbiamo nessuna informazione";
                }else{
                    new_p.textContent = json[i].attributes.description;
                }
                new_div.appendChild(new_h3);
                new_div.appendChild(new_img);
                new_div.appendChild(new_p);
        
                section.appendChild(new_div);
            }
            
        }
    }
}

function search(event){
    event.preventDefault();

    const input = document.querySelector('#nameSport');
    const testo = encodeURIComponent(input.value.toLowerCase());
    
    if(!testo){
        return;
    }else{
        fetch("http://localhost/hw1/pagina2/api_sport.php?sport=" + testo).then(onResponse).then(onJson);
    }
}

function clean(event){
    const button = event.target;
    if(button.value === ""){
        document.querySelector('#sport').innerHTML = "";
    }
}
const form = document.querySelector('form');
form.addEventListener('submit',search);
form.addEventListener('keyup',clean);

///////////////////////////////////////////////////////////////////////////////
function showResultSport(json){
    const view = document.querySelector('#resultTeam');

    if(json.data.relationships.images.data.length === 0){
        return;
    }else{    
        const new_div = document.createElement('div');
        const new_img = document.createElement('img');
        const new_h3 = document.createElement('h3');
        const new_p = document.createElement('p');
                
        new_h3.textContent = json.data.attributes.name;
        new_img.src = json.data.relationships.images.data[0].url;
        if(json.data.attributes.description === null){
            new_p.textContent = "Al momento non abbiamo nessuna informazione";
        }else{
            new_p.textContent = json.data.attributes.description;
        }
        new_div.appendChild(new_h3);
        new_div.appendChild(new_img);
        new_div.appendChild(new_p);
            
        view.appendChild(new_div);
    }    
}

function resultSport(json){
    const view = document.querySelector('#team');
    view.classList.add('hidden');
    document.querySelector('#reset').classList.remove('hidden');

    if(json.data.relationships.sports.data.length === 0){
        const new_p = document.createElement('p');
        new_p.textContent = "Nessun elemento disponbile";

        document.querySelector('#resultTeam').appendChild(new_p);
    }else{
        let max = 10;
        if(json.data.relationships.sports.data.length < max){
            max = json.data.relationships.sports.data.length;
        }

        for(let i = 0; i < max; i++){
            //const url = "https://sports.api.decathlon.com/sports/" + json.data.relationships.sports.data[i].id;    //ricerca sports appartenenti ad una categoria
            fetch("http://localhost/hw1/pagina2/api_sport.php?sports_group=" + json.data.relationships.sports.data[i].id).then(onResponse).then(showResultSport);
        }   
    }
}

function searchTeamSport(event){
    const h3 = event.currentTarget;
    const id_elemento = h3.getAttribute('id');

    fetch("http://localhost/hw1/pagina2/api_sport.php?groups=" + id_elemento).then(onResponse).then(resultSport);
}
////////////////////////////////////////////////////////////////////
function reset(event){
    const group = document.querySelector('#team');
    const result = document.querySelector('#resultTeam');

    event.currentTarget.classList.add('hidden');
    group.classList.remove('hidden');
    result.innerHTML = "";

}
const reset_button = document.querySelector('#reset');
reset_button.addEventListener('click',reset);

//Script 'pulsante' Menu
function scopriMenu(event){
    const link = document.querySelector('#menuAperto');

    event.currentTarget.removeEventListener('click',scopriMenu);
    event.currentTarget.addEventListener('click',nascondiMenu);
    link.classList.remove('hidden');
}

function nascondiMenu(event){
    const link = document.querySelector('#menuAperto');

    event.currentTarget.removeEventListener('click',nascondiMenu);
    event.currentTarget.addEventListener('click',scopriMenu);
    link.classList.add('hidden');

}
const menu = document.querySelector('#menu');
menu.addEventListener('click',scopriMenu);

const menu_tendina = document.querySelector('.menu_tendina');
menu_tendina.addEventListener('click',scopriMenu);
function onResponse(response){
    return response.json();
}

function onJson(json){
    const section = document.querySelector('#view');
    section.innerHTML = "";

    if(json.results.length === 0){
        const new_p = document.createElement('p');
        new_p.textContent = "Nessun risultato trovato";

        section.appendChild(new_p);
    }else{
        for(let i = 0; i < json.results.length && i < 10; i++){
            const new_div = document.createElement('div');
            const new_img = document.createElement('img');
            const new_h3 = document.createElement('h3');
            const new_p = document.createElement('p');
            const new_button = document.createElement('button');
            new_button.addEventListener('click',mostraModale);

            new_h3.textContent = json.results[i].title;
            new_img.src = json.results[i].cover_image;
            new_p.textContent = "Genere: " + json.results[i].genre[0];
            new_button.textContent = "Aggiungi";

            new_div.appendChild(new_h3);
            new_div.appendChild(new_img);
            new_div.appendChild(new_p);
            new_div.appendChild(new_button);
        
            section.appendChild(new_div);
        }
    }
}

function search(event){
    event.preventDefault();

    const input = document.querySelector('#music');
    const testo = encodeURIComponent(input.value.toLowerCase());
    
    if(!testo){
        return;
    }else{
        fetch("http://localhost/hw1/pagina1/api_music.php?q=" + testo).then(onResponse).then(onJson);
    }
      
}

function clean(event){
    const button = event.target;
    if(button.value === ""){
        document.querySelector('#view').innerHTML = "";
    }
}
const form = document.querySelector('form');
form.addEventListener('submit',search);
form.addEventListener('keyup',clean);

/////////////////////////////////////////////////////////////////
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
//////////////////////////////////////////////////////////////////
function mostraModale(event){
    const modal = document.querySelector('#modal-view');
    document.body.classList.add('no-scroll');
    modal.style.top = window.pageYOffset + 'px';
    modal.classList.remove('hidden');
}

function togliModale(event){
    document.body.classList.remove('no-scroll');
    document.querySelector('#modal-view').classList.add('hidden');
}
const buttonModal = document.querySelector('#modal-view button');
buttonModal.addEventListener('click',togliModale);
//Invio form per cambiare dettagli account
function onResponse(response){
    return response.json();
}

function removeResponseChangeDetails(event){
    const span = document.querySelector('#modal-view span');
    span.remove();
}

function responseChangeDetails(json){ 
    const modal_view = document.querySelector('#modal-view');
    const span = document.createElement('span');
    
    for (let i = 0; i < json.length; i++){
        const new_p = document.createElement('p');
        new_p.textContent = json[i];
        span.appendChild(new_p);
    }

    const new_button = document.createElement('button');
    new_button.textContent = "Avanti";
    new_button.addEventListener('click',removeResponseChangeDetails);
    
    span.appendChild(new_button);

    modal_view.appendChild(span);
}

function cambiaDettagliAccount(event){
    const form = event.currentTarget;
    const form_data = {method:'POST', body: new FormData(form)};
    fetch("http://localhost/hw1/changeDetailsAccount.php",form_data).then(onResponse).then(responseChangeDetails);
    event.preventDefault();
}

function togliModale(event){
    window.location.reload();
    
    /*const modal_view = document.querySelector('#modal-view');
    document.body.classList.remove('no-scroll');
    modal_view.classList.add('hidden');

    modal_view.querySelector('ul').classList.add('hidden');
    const forms = modal_view.querySelectorAll('form');
    for(form of forms){
        form.classList.add('hidden');
    }*/
}

function mostraModale(event){
    const modal_view = document.querySelector('#modal-view');
    modal_view.style.top = window.pageYOffset + 'px';
    document.body.classList.add('no-scroll');
    modal_view.classList.remove('hidden'); 

    modal_view.querySelector('button').addEventListener('click',togliModale);

    let form = null;
    if(event.currentTarget.getAttribute('id') === 'email_button'){
        form = document.forms['email'];
        form.classList.remove('hidden');

    }else{
        modal_view.querySelector('ul').classList.remove('hidden');
        form = document.forms['password'];
        form.classList.remove('hidden');
    }

    form.addEventListener('submit', cambiaDettagliAccount);
}
document.getElementById("email_button").addEventListener('click',mostraModale);
document.getElementById("password_button").addEventListener('click',mostraModale);  

//Script per gli ordini effettuati dall'utente

function inizializzazioneOrdini(json){
    const grid_ordini = document.querySelector('#ordini');
    grid_ordini.innerHTML = "";

    if(json.length > 0){
        for( let i = 0; i < json.length; i++){
            const new_div = document.createElement('div');
            const new_h3 = document.createElement('h3');
            new_h3.textContent = "Numero ordine: " + json[i][0]["ordine"];
            new_div.appendChild(new_h3);

            const new_table = document.createElement('table');
            const tr = document.createElement('tr');
            const th1 = document.createElement('th');
            th1.textContent = "Prodotto";
            const th2 = document.createElement('th');
            th2.textContent = "Quantità";

            tr.appendChild(th1);
            tr.appendChild(th2);
            new_table.appendChild(tr);

            for(let j = 0; j < json[i].length; j++ ){
                const tr_prodotto = document.createElement('tr');
                const td_nome_prodotto = document.createElement('td');
                td_nome_prodotto.textContent = json[i][j]["nome"];
                const td_quantità_prodotto = document.createElement('td');
                td_quantità_prodotto.textContent = json[i][j]["quantità"];

                tr_prodotto.appendChild(td_nome_prodotto);
                tr_prodotto.appendChild(td_quantità_prodotto);
                new_table.appendChild(tr_prodotto);
            }
            new_div.appendChild(new_table);
            grid_ordini.appendChild(new_div);
            
        }
    }else{
        const strong = document.createElement('strong');
        strong.textContent = "Non hai effettuato nessun ordine!";
        grid_ordini.appendChild(strong);
    }

}

fetch("http://localhost/hw1/ordini_utente.php").then(onResponse).then(inizializzazioneOrdini);
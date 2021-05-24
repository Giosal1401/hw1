function creazioneForm(quantità){
    const new_form = document.createElement('form');
    const new_p = document.createElement('p');
    const new_label = document.createElement('label');
    new_label.textContent  = "Quantità: ";
    const new_input = document.createElement('input');
    new_input.type = "text";
    new_input.name = "quantità";
    new_input.value = quantità;
    new_p.appendChild(new_label);
    new_label.appendChild(new_input);
    new_form.appendChild(new_p);

    const new_p2 = document.createElement('p');
    const new_input2 = document.createElement('input');
    const new_input3 = document.createElement('input');
    const new_input4 = document.createElement('input');
    
    new_input3.type = "button";
    new_input3.name = "rimuovi_button";
    new_input3.value = "Rimuovi";
    new_input3.addEventListener('click',add_remove_item);

    new_input2.type = "button";
    new_input2.name = "aggiungi_button";
    new_input2.value = "Aggiungi";
    new_input2.addEventListener('click',add_remove_item);

    new_p2.appendChild(new_input3);
    new_p2.appendChild(new_input2);

    new_input4.type = "hidden";
    new_input4.name= "hidden_quantità";
    new_input4.value = quantità;
    
    new_form.appendChild(new_p);
    new_form.appendChild(new_p2);
    new_form.appendChild(new_input4);
    new_form.addEventListener('submit', no_reload);
    return new_form;
}

function no_reload(event){
    event.preventDefault();
}

function creazioneElementoCarrello(array){
    const new_div = document.createElement('div');
    new_div.setAttribute('id',array.id);

    const new_img = document.createElement('img');
    new_img.src = array.url_immagine;

    const div_aux = document.createElement('div');
    const new_h3 = document.createElement('h3');
    new_h3.textContent = array.nome;
    
    const new_em = document.createElement('em');
    if(array.nutrizione === '1'){
        new_em.textContent = "Prezzo: " + array.prezzo + " €/Kg";
    }else{
        new_em.textContent = "Prezzo: " + array.prezzo + " Euro";
    }
    
    const new_form = creazioneForm(array.quantità);
    div_aux.appendChild(new_h3);
    div_aux.appendChild(new_form);
    div_aux.appendChild(new_em);

    new_div.appendChild(new_img);
    new_div.appendChild(div_aux);

    const img_button = document.createElement('img');
    img_button.src = "images/x_button.png";
    img_button.classList.add('button_remove');
    img_button.addEventListener('click',delete_item);
    new_div.appendChild(img_button);

    return new_div;
}

function aggiungiProdotti(json){
    const grid_carrello = document.querySelector('#carrello');
    grid_carrello.innerHTML = "";

    if(json.length > 0){
        let nprodotti = 0;
        let prezzo_totale = 0;
        for(let i = 0; i < json.length; i++){
            const div_prodotto = creazioneElementoCarrello(json[i]);
            grid_carrello.appendChild(div_prodotto);
            nprodotti += parseInt(json[i].quantità);
            prezzo_totale += json[i].prezzo * json[i].quantità;
        }

        const new_div = document.createElement('div');
        new_div.classList.add('summary');
        const h2 = document.createElement('h2');
        h2.textContent = "Riepilogo";
        const p1 = document.createElement('p');
        p1.textContent= "Totale prodotti: " + nprodotti;
        const p2 = document.createElement('p');
        p2.textContent = "Totale: " + (Math.round(prezzo_totale *100)/100) + " Euro";
        const buy_button = document.createElement('button');
        buy_button.textContent = "Acquista";
        buy_button.classList.add('lightblue_button');
        buy_button.addEventListener('click',checkoutCart);
        new_div.appendChild(h2);
        new_div.appendChild(p1);
        new_div.appendChild(p2);
        new_div.appendChild(buy_button);
        grid_carrello.appendChild(new_div);

        /*const new_div = document.createElement('div');
        new_div.classList.add('summary');
        const p1 = document.createElement('p');
        const p2 = document.createElement('p');
        p1.textContent= "Totale prodotti: " + nprodotti;
        p2.textContent = "Totale: " + (Math.round(prezzo_totale *100)/100) + " Euro";
        new_div.appendChild(p1);
        new_div.appendChild(p2);
        grid_carrello.appendChild(new_div);*/
    }else{
        const new_strong = document.createElement('strong');
        new_strong.textContent = "Non hai nessun prodotto nel carrello!";
        grid_carrello.appendChild(new_strong);
    }
    fetch("http://localhost/hw1/prodotti_preferiti.php").then(onResponse).then(aggiungiGridPreferiti);
}

function onResponse(response){
    return response.json();
}
fetch("http://localhost/hw1/prodotti_carrello.php").then(onResponse).then(aggiungiProdotti);

function creazioneElementoPreferiti(contenuto,div){
    div.setAttribute('id',contenuto.id);
    
    const new_h3 = document.createElement('h3');
    new_h3.textContent = contenuto.nome;
    const new_img = document.createElement('img');
    new_img.src = contenuto.url_immagine;

    const new_span = document.createElement('span');
    const new_p = document.createElement('p');
    if(contenuto.descrizione === null){
        new_p.textContent = "Al momento non abbiamo nessun informazione";
    }else{
        new_p.textContent = contenuto.descrizione;
    }
    new_p.classList.add('hidden');
    
    const new_em = document.createElement('em');
    if(contenuto.nutrizione === '1'){
        new_em.textContent = "Prezzo: " + contenuto.prezzo + "€/kg";
    }else{
        new_em.textContent = "Prezzo: " + contenuto.prezzo + "Euro";
    }
    const img_cart = document.createElement('img');
    img_cart.src = "images/carrello-vuoto.png";
    img_cart.addEventListener('click', mostraModale);

    new_span.appendChild(new_em);
    new_span.appendChild(img_cart);
    
    div.appendChild(new_h3);
    div.appendChild(new_img);
    div.appendChild(new_p);
    div.appendChild(new_span);
}

function aggiungiGridPreferiti(json){
    const grid_favorites = document.querySelector('#preferiti');
    grid_favorites.innerHTML = "";
    
    if(json.length > 0){
        grid_favorites.parentNode.classList.remove('hidden');
        for(let i = 0; i < json.length; i++){
            if(document.getElementById(json[i].id) === null){
                const new_div = document.createElement('div');
                creazioneElementoPreferiti(json[i],new_div);
                grid_favorites.appendChild(new_div);
            }
        }
        if(grid_favorites.childNodes.length === 0){
            grid_favorites.parentNode.classList.add('hidden');
        }
    }   
}

function aggiorna(){
    fetch("http://localhost/hw1/prodotti_carrello.php").then(onResponse).then(aggiungiProdotti);
}

function add_remove_item(event){
    const div_prodotto = event.currentTarget.parentNode.parentNode.parentNode.parentNode;
    const operazione = event.currentTarget.value;
    
    const form = document.createElement('form');
    const input = document.createElement('input');
    input.type = "text";
    input.name = "operazione";
    input.value = operazione;
    const input_2 = document.createElement('input');
    input_2.type = "text";
    input_2.name = "id_prodotto";
    input_2.value = div_prodotto.getAttribute('id');
    const input_3 = document.createElement('input');
    input_3.type = "hidden";
    input_3.name= "quantità";
    input_3.value = event.currentTarget.parentNode.parentNode['hidden_quantità'].value;
    
    
    form.appendChild(input);
    form.appendChild(input_2);
    form.appendChild(input_3);
    
    const form_data = {method:'POST', body: new FormData(form)};
    fetch("http://localhost/hw1/addToCart.php", form_data).then(aggiorna);
}

function delete_item(event){
    const div_prodotto = event.currentTarget.parentNode;
  
    const form = document.createElement('form');
    const input = document.createElement('input');
    input.type = "text";
    input.name = "operazione";
    input.value = "cancella";
    const input_2 = document.createElement('input');
    input_2.type = "text";
    input_2.name = "id_prodotto";
    input_2.value = div_prodotto.getAttribute('id');

    form.appendChild(input);
    form.appendChild(input_2);

    const form_data = {method:'POST', body: new FormData(form)};
    fetch("http://localhost/hw1/addToCart.php", form_data).then(aggiorna);
}

function togliModale(event){
    const modal_view = document.querySelector('#modal-view');
    document.body.classList.remove('no-scroll');
    modal_view.classList.add('hidden');

    modal_view.firstElementChild.firstElementChild.remove();
    modal_view.querySelector('form').classList.add('hidden');

    aggiorna();
}

function mostraModale(event){
    const modal_view = document.querySelector('#modal-view');

    const button = document.querySelector('#modal-view div button');
    button.addEventListener('click',togliModale);
    
    
    const new_div = document.createElement('div');
    const div_prodotto = event.currentTarget.parentNode.parentNode;
    
    const h3 = document.createElement('h3');
    h3.textContent = div_prodotto.firstChild.textContent;
    const img = document.createElement('img');
    img.src = div_prodotto.childNodes[1].src;
    const p = document.createElement('p');
    p.textContent = div_prodotto.childNodes[2].firstChild.textContent;
    const em = document.createElement('em');
    em.textContent = div_prodotto.childNodes[3].firstChild.textContent;

    new_div.appendChild(h3);
    new_div.appendChild(img);
    new_div.appendChild(p);
    new_div.appendChild(em);
    
    modal_view.style.top = window.pageYOffset + 'px';
    document.body.classList.add('no-scroll');
    modal_view.classList.remove('hidden'); 
    modal_view.classList.add('grid');

    const form = document.querySelector('#modal-view div form');
    form.classList.remove('hidden');

    form.id_prodotto.setAttribute('value',div_prodotto.getAttribute('id'));
    form.addEventListener('submit',aggiungiAlCarrello);

    modal_view.firstElementChild.insertBefore(new_div,modal_view.firstElementChild.firstElementChild);   
}

function removeResponseAddCart(event){
    const span = document.querySelector('#modal-view span');
    span.remove();
}

function responseAddCart(json){
    const modal_view = document.querySelector('#modal-view');
    
    const new_p = document.createElement('p');
    new_p.textContent = json;
    const new_button = document.createElement('button');
    new_button.textContent = "Avanti";
    new_button.addEventListener('click',removeResponseAddCart);
    
    const span = document.createElement('span');
    span.appendChild(new_p);
    span.appendChild(new_button);

    modal_view.appendChild(span);
}

function aggiungiAlCarrello(event){    
    const form = document.querySelector('#modal-view form');
    const form_data = {method:'POST', body: new FormData(form)};
    fetch("http://localhost/hw1/addToCart.php", form_data).then(onResponse).then(responseAddCart);
    event.preventDefault();    
}

function removeResponseCheckout(event){
    event.currentTarget.parentNode.remove();
    aggiorna();
}

function responseCheckout(json){
    const div_summary = document.querySelector('#carrello').lastElementChild;

    const new_span = document.createElement('span');
    const new_p = document.createElement('p');
    new_p.textContent = json;

    const new_button = document.createElement('button');
    new_button.textContent = "Avanti";
    new_button.addEventListener('click', removeResponseCheckout);
    
    new_span.appendChild(new_p);
    new_span.appendChild(new_button);

    div_summary.appendChild(new_span);
}

function checkoutCart(event){
    fetch("http://localhost/hw1/checkoutCart.php").then(onResponse).then(responseCheckout);
}
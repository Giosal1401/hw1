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
const menu_tendina = document.querySelector('.menu_tendina');
menu_tendina.addEventListener('click',scopriMenu);
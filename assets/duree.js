console.log('duree.js');


selecteur = document.querySelector('#offre_type_contrat');
dureeField = document.querySelector('#offre_duree');

dureeField.parentElement.style.display = 'none';

selected = 1;

selecteur.addEventListener('change', function() {
    console.log(this.value);
    selected = this.value;
    if (selected == 2) {
        dureeField.parentElement.style.display = 'none';
    } else {
        dureeField.parentElement.style.display = 'block';
    }
})
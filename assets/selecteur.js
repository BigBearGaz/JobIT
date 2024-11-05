console.log('selecteur.js');


selecteur = document.querySelector('#registration_form_statut');
nomField = document.querySelector('#registration_form_nom');
prenomField = document.querySelector('#registration_form_prenom');
entrepriseField = document.querySelector('#registration_form_entreprise');
urlField = document.querySelector('#registration_form_url');
naissanceField = document.querySelector('#registration_form_date_naissance');

selected = 1;
entrepriseField.parentElement.style.display = 'none';
urlField.parentElement.style.display = 'none';

selecteur.addEventListener('change', function() {
    console.log(this.value);
    selected = this.value;
    if (selected == 1) {
        nomField.parentElement.style.display = 'block';
        prenomField.parentElement.style.display = 'block';
        naissanceField.disabled = false;
        naissanceField.parentElement.style.display = 'block';
        entrepriseField.parentElement.style.display = 'none';
        urlField.parentElement.style.display = 'none';
    } else if (selected == 2) {
        nomField.parentElement.style.display = 'none';
        prenomField.parentElement.style.display = 'none';
        naissanceField.disabled = true;
        naissanceField.parentElement.style.display = 'none';
        entrepriseField.parentElement.style.display = 'block';
        urlField.parentElement.style.display = 'block';
    }
})
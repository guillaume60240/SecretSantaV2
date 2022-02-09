import Button from './components/Button';
import Small from './components/Small';
import Form from './components/Form';
import Input from './components/Input';

let updateSantaNameBtn = document.querySelectorAll('.changeNameBtn');
let updateSantaMailBtn = document.querySelectorAll('.changeEmailBtn');

if(updateSantaNameBtn) {
    updateSantaNameBtn.forEach(btn => {
        btn.addEventListener('click', (e) => {

            let activeName = document.querySelector('#firstName-' + btn.id);
            console.log(activeName);
            
            let nameForm = new Form('form-group, w-75', '#', 'post', activeName, 'updateSantaName'+btn.id);
                nameForm.createForm();
            
            let nameInput = new Input('text', 'inputName-' + btn.id, 'form-control', 'width: 100%;', 'memberName', activeName.innerHTML, 'updateSantaName'+btn.id);
                nameInput.createInput();

            let helpName = new Small('small-' + btn.id, 'form-text text-muted', 'Entre 3 et 100 caractères', 'inputName-' + btn.id);
                helpName.createSmall();

            let nameUpdateBtn = new Button('updateSantaName'+btn.id,'submit', 'form-control btn-sm btn-primary', 'submitName-' + btn.id, 'updateSantaName', btn.id, 'Valider', true);
                nameUpdateBtn.createBtn();
            
            btn.remove();

            let input = document.getElementById('inputName-' + btn.id);

            input.addEventListener('input', (e) => {
                let regex = new RegExp(/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i);

                if(regex.test(input.value)) {
                    nameInput.isValid();
                    helpName.isValid();
                    nameUpdateBtn.setDisabled(false);
                    
                } else {
                    nameInput.isInvalid();
                    helpName.isInvalid();
                    nameUpdateBtn.setDisabled(true);
                }
            });
        })
    })
}

if(updateSantaMailBtn) {
    updateSantaMailBtn.forEach(btn => {
        btn.addEventListener('click', (e) => {
            console.log(btn);
            let activeMail = document.querySelector('#email-' + btn.id);
            console.log(activeMail);
            
            let mailForm = new Form('form-group, w-75', '#', 'post', activeMail, 'updateSantaMail'+btn.id);
                mailForm.createForm();
            
            let mailInput = new Input('email', 'inputMail-' + btn.id, 'form-control', 'width: 100%;', 'memberEmail', activeMail.innerHTML, 'updateSantaMail'+btn.id);
                mailInput.createInput();

            let helpMail = new Small('small-' + btn.id, 'form-text text-muted', 'Format mail@exemple.fr', 'inputMail-' + btn.id);
                helpMail.createSmall();
            
            let mailUpdtaeBtn = new Button('updateSantaMail'+btn.id,'submit', 'form-control btn-sm btn-primary', 'submitMail-' + btn.id, 'updateSantaMail',  btn.id, 'Valider', true);
                mailUpdtaeBtn.createBtn();
            
            btn.remove();

            let input = document.getElementById('inputMail-' + btn.id);

            input.addEventListener('input', (e) => {
                let regex = new RegExp(/([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})/);
                console.log('test');
                if(regex.test(input.value)) {
                    mailInput.isValid();
                    helpMail.isValid();
                    mailUpdtaeBtn.setDisabled(false);
                } else {
                    mailInput.isInvalid();
                    helpMail.isInvalid();
                    mailUpdtaeBtn.setDisabled(true);
                }
            })
        })
    })
}




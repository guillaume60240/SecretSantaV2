let inputs = refreshInputs();
let eventDescription = document.getElementById('eventDescription');
let numberOfMembers = 1;
let btnAdd = document.querySelector('.addMemberBtn');
let submitBtnAddSanta = document.querySelector('button[name="submitAddSanta"]');
let submitBtnCreateList = document.querySelector('button[name="submitCreateList"]');
let submitBtnCreate = document.querySelector('button[name="createMember"]');


function refreshInputs() {
    return inputs = document.querySelectorAll('input'), startValidation();
}

//switch pour donner les id d'inputs et de span d'info-inputs et récupérer le validator
function startValidation() {
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            let inputInfo = null;
            if (input.name === 'memberName') {
                inputInfo = document.querySelector('small[id="memberNameHelpInline"]');
            } else {
                inputInfo = document.querySelector(`small[id="${input.id}HelpInline"]`)
            }
            let validator = getValidator(input);

            input.addEventListener('input', () => {
                if (input.id === 'repeatPassword') {
                    validationPassword(input, inputInfo, validator);
                } else if(input.id === 'checkValidation') {
                    if (input.checked) {
                        trueInputIndication(input);
                        trueinputInfoIndication(inputInfo);
                    }  else {
                        falseInputIndication(input);
                        falseinputInfoIndication(inputInfo);
                    }
                } else {
                    validationInput(input, inputInfo, validator);
                }    
            })
        });
    });
}
//méthode pour récupérer le validator de l'input
function getValidator(input) {
    
    switch (input.name) {
        case 'email':
            return /([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})/;
        case 'password':
        case 'repeatPassword':
            return /(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,50}/;
        case 'eventName':
        case 'memberName':
        case 'firstName':
        case 'lastName':
            return /[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i;
        case 'eventDate':
            return /([0-9]{4}-[0-9]{2}-[0-9]{2})/;
    }  
}

//fonction pour vérifier les inputs et lancer les méthodes d'ajout d'attributs
function validationInput(input, inputInfo, validator) {
    let regex = new RegExp(validator);
    
    if (regex.test(input.value)) {
        if (input.id === 'eventDate') {
            dateVerify(input, inputInfo);
        } else {
            trueInputIndication(input);
            trueinputInfoIndication(inputInfo);
            return true;
        }
    } else {
        falseInputIndication(input);
        falseinputInfoIndication(inputInfo);
        if (input.name === 'memberName' && input.value.length == 0) {
            input.classList.remove('is-invalid');
            trueinputInfoIndication(inputInfo);
        }
        return false
    }
}

//gestion du textarea
if(eventDescription) {
    eventDescription.addEventListener('input', () => {

        let eventDescriptionInfo = document.querySelector('small[id="eventDescriptionHelpInline"]');
        let charatersLeft = (250 - eventDescription.value.length);
        eventDescriptionInfo.innerHTML = charatersLeft + " caractères restants";

        validTextArea(eventDescription, eventDescriptionInfo);
    });
}

function validTextArea(eventDescription, eventDescriptionInfo) {
    if (eventDescription.value.length > 0 && eventDescription.value.length < 250) {
        trueInputIndication(eventDescription);
        trueinputInfoIndication(eventDescriptionInfo);
    } else if (eventDescription.value.length == 0) {
        eventDescription.classList.remove('is-invalid');
        eventDescription.classList.remove('is-valid');
        trueinputInfoIndication(eventDescriptionInfo);
    } else {
        eventDescriptionInfo.innerHTML = 'Description trop longue';
        falseInputIndication(eventDescription);
        falseinputInfoIndication(eventDescriptionInfo);
    }
}

//gestion du repeatPassword
function validationPassword(repeatPassword, repeatPasswordInfo, validator){
    let password = document.querySelector('input[name="password"]');
    
    repeatPassword.addEventListener('input', () => {
        if (repeatPassword.value === password.value) {
            let verif =   validationInput(repeatPassword, repeatPasswordInfo, validator);
            if (verif) {
                trueInputIndication(repeatPassword);
                trueinputInfoIndication(repeatPasswordInfo);
                repeatPasswordInfo.innerHTML = 'Les mots de passe sont identiques';
            }
        } else {
            falseInputIndication(repeatPassword);
            falseinputInfoIndication(repeatPasswordInfo);
            repeatPasswordInfo.innerHTML = 'Saisie non valide';
        }  
    });
}

//les fonctions d'ajout d'attributs aux inputs et aux info-inputs
function falseInputIndication(input) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
}

function falseinputInfoIndication(inputInfo) {
    inputInfo.classList.remove('text-muted');
    inputInfo.classList.add('text-danger');
}

function trueInputIndication(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
}

function trueinputInfoIndication(inputInfo) {
    inputInfo.classList.remove('text-danger');
    inputInfo.classList.add('text-muted');
}

function dateVerify(input, inputInfo) {
    if (input.value < new Date().toISOString().slice(0, 10)) {
        inputInfo.innerHTML = 'Date invalide';
        falseInputIndication(input);
        falseinputInfoIndication(inputInfo);
    } else {
        trueInputIndication(input);
        trueinputInfoIndication(inputInfo);
        inputInfo.innerHTML = 'Date valide';
    }
}

//Ajout de noms de membres
if(btnAdd){

    btnAdd.addEventListener('click', function (e) {
        console.log('add');
        let key = numberOfMembers;
        let div = document.createElement('div');
        div.classList.add('form-group');
        let input = document.createElement('input');
        input.classList.add('form-control');
        input.classList.add('mt-2');
        input.name = 'memberName';
        input.placeholder = 'Nom, prénom ou pseudo';
        input.id = `memberName${key}`;
        div.appendChild(input);
        btnAdd.parentNode.insertBefore(div, btnAdd);
        
        return numberOfMembers = key + 1,refreshInputs();
    })
}

//validation des champs au submit

if(submitBtnCreate) {
    submitBtnCreate.addEventListener('click', function (e) {
        let inputs = document.querySelectorAll('input');
        let error = 0;
        let inputInfo = null;

        //on vérifie que tous les inputs sont valides
        inputs.forEach(input => {
        let validator = getValidator(input);
            if (input.id === 'mail' || input.id === 'password' || input.id === 'eventName' || input.id === 'eventDate' || input.id === 'firstName' || input.id === 'lastName') {
                inputInfo = document.querySelector(`small[id="${input.id}HelpInline"]`)
                validationInput(input, inputInfo, validator);
            } else if (input.id === 'repeatPassword') {
                inputInfo = document.querySelector(`small[id="${input.id}HelpInline"]`)
                let password = document.querySelector('input[name="password"]');
                if (input.value === password.value && password.classList.contains('is-valid')) {
                    trueInputIndication(input);
                } else {
                    falseInputIndication(input);
                    falseinputInfoIndication(inputInfo);
                }
            } else if (input.name === 'memberName') {
                inputInfo = document.querySelector('small[id="memberNameHelpInline"]');
                validationInput(input, inputInfo, validator);
            } else if(input.id === 'checkValidation') {
                inputInfo = document.querySelector(`small[id="${input.id}HelpInline"]`)
                if (input.checked) {
                    trueInputIndication(input);
                    trueinputInfoIndication(inputInfo);
                    input.value = true;
                }  else {
                    falseInputIndication(input);
                    falseinputInfoIndication(inputInfo);
                    input.value = false;
                }
            } else if (input.id === 'userIsMember') {
                if (input.checked) {
                    input.value = true;
                } else {
                    input.value = false;
                }
            }

            if (input.classList.contains('is-invalid')) {
                error++;
            }
        });
        //on vérifie le textArea
        let eventDescription = document.querySelector('textarea[name="eventDescription"]');
        let eventDescriptionInfo = document.querySelector('small[id="eventDescriptionHelpInline"]');
        validTextArea(eventDescription, eventDescriptionInfo);
        if (eventDescription.classList.contains('is-invalid')) {
            error++;
        }

        //on compte les erreurs et on affiche le message d'erreur sinon on remplit l'input hidden pour les membres et on envoie le formulaire
        if (error != 0) {
            e.preventDefault();
            let div = document.createElement('div');
            div.classList.add('alert', 'alert-danger', 'error-submission-message');
            div.innerHTML = 'Vous avez ' + error + ' erreur(s)';
            body = document.querySelector('body');
            submitBtnCreate.appendChild(div);

            setTimeout(() => {
                div.remove();
            }, 3000);

        } else {

            let memberParticipation = document.querySelectorAll('input[name="memberName"]');
            addMemberParticipation(memberParticipation);
        }
    });
}

//validation des champs au submit addSanta
if(submitBtnAddSanta) {
    submitBtnAddSanta.addEventListener('click', function (e) {
        let inputs = document.querySelectorAll('input');
        let error = 0;
        let inputInfo = null;
        inputs.forEach(input => {
            let validator = getValidator(input);
            if (input.name === 'memberName') {
                inputInfo = document.querySelector('small[id="memberNameHelpInline"]');
                validationInput(input, inputInfo, validator);
            }
            if (input.classList.contains('is-invalid')) {
                error++;
            }
        })
        if (error != 0) {
            e.preventDefault();
            let div = document.createElement('div');
            div.classList.add('alert', 'alert-danger', 'error-submission-message');
            div.innerHTML = 'Vous avez ' + error + ' erreur(s)';
            body = document.querySelector('body');
            submitBtnAddSanta.appendChild(div);

            setTimeout(() => {
                div.remove();
            }, 3000);

        } else {

            let memberParticipation = document.querySelectorAll('input[name="memberName"]');
            addMemberParticipation(memberParticipation);
        }
    })
}

//Validation des champs au submit createList
if(submitBtnCreateList) {
    submitBtnCreateList.addEventListener('click', function (e) {
        let inputs = document.querySelectorAll('input');
        let error = 0;
        let inputInfo = null;
        inputs.forEach(input => {
            let validator = getValidator(input);
            if (input.id === 'mail' || input.id === 'eventName' || input.id === 'eventDate') {
                inputInfo = document.querySelector(`small[id="${input.id}HelpInline"]`)
                validationInput(input, inputInfo, validator);
            }  else if (input.name === 'memberName') {
                inputInfo = document.querySelector('small[id="memberNameHelpInline"]');
                validationInput(input, inputInfo, validator);
            } else if(input.id === 'checkValidation') {
                inputInfo = document.querySelector(`small[id="${input.id}HelpInline"]`)
                if (input.checked) {
                    trueInputIndication(input);
                    trueinputInfoIndication(inputInfo);
                    input.value = true;
                }  else {
                    falseInputIndication(input);
                    falseinputInfoIndication(inputInfo);
                    input.value = false;
                }
            } else if (input.id === 'userIsMember') {
                if (input.checked) {
                    input.value = true;
                } else {
                    input.value = false;
                }
            }

            if (input.classList.contains('is-invalid')) {
                error++;
            }
        });
        //on vérifie le textArea
        let eventDescription = document.querySelector('textarea[name="eventDescription"]');
        let eventDescriptionInfo = document.querySelector('small[id="eventDescriptionHelpInline"]');
        validTextArea(eventDescription, eventDescriptionInfo);
        if (eventDescription.classList.contains('is-invalid')) {
            error++;
        }

        //on compte les erreurs et on affiche le message d'erreur sinon on remplit l'input hidden pour les membres et on envoie le formulaire
        if (error != 0) {
            e.preventDefault();
            let div = document.createElement('div');
            div.classList.add('alert', 'alert-danger', 'error-submission-message');
            div.innerHTML = 'Vous avez ' + error + ' erreur(s)';
            body = document.querySelector('body');
            submitBtnCreateList.appendChild(div);
            
            setTimeout(() => {
                div.remove();
            }, 3000);

        } else {

            let memberParticipation = document.querySelectorAll('input[name="memberName"]');
            addMemberParticipation(memberParticipation);
        }
    })
}


//ajout des membres à la liste pour les récupérer dans la requête
function addMemberParticipation(memberParticipation) {
    let allMembers = [];
    memberParticipation.forEach(member => {
        if (member.value !== '') {
            allMembers.push(member.value);
        }
    });
    let allMembersName = document.querySelector('input[name="allMembersName"]');
    
    allMembersName.value = allMembers;
    
    console.log(allMembers);
}


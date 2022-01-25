let updateSantaNameBtn = document.querySelectorAll('.changeNameBtn');

if(updateSantaNameBtn) {
    updateSantaNameBtn.forEach(btn => {
        btn.addEventListener('click', (e) => {
            console.log(btn);
            let activeName = document.querySelector('#firstName-' + btn.id);
            console.log(activeName);
            let form = document.createElement('form');
            form.classList.add('form-group', 'w-75');
            form.setAttribute('action', '#');
            form.setAttribute('method', 'post');
            activeName.replaceWith(form);
            
            let input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', 'input-' + btn.id);
            // input.setAttribute('value', activeName.innerHTML);
            input.setAttribute('class', 'form-control');
            input.setAttribute('style', 'width: 100%;');
            input.setAttribute('name', 'memberName'); 
            input.setAttribute('placeholder', 'Entrez le nouveau nom ( ' + activeName.innerHTML + ' )');
            form.appendChild(input);

            let small = document.createElement('small');
            small.setAttribute('id', 'small-' + btn.id);
            small.setAttribute('class', 'form-text text-muted');
            small.innerHTML = 'Entre 3 et 100 caractères';
            input.after(small);

            let updateBtn = document.createElement('button');
            updateBtn.setAttribute('type', 'submit');
            updateBtn.setAttribute('class', 'form-control btn-sm btn-primary');
            updateBtn.setAttribute('id',  btn.id);
            updateBtn.setAttribute('name', 'updateSantaName');
            updateBtn.setAttribute('value', btn.id);
            updateBtn.setAttribute('disabled', 'disabled');
            updateBtn.innerHTML = 'Valider';
            updateBtn.setAttribute('style', 'margin-left: 10px;');
            form.appendChild(updateBtn);
            
            btn.remove();

            input.addEventListener('input', (e) => {
                let regex = new RegExp(/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i);
                console.log('test');
                if(regex.test(input.value)) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    small.classList.remove('text-danger');
                    small.classList.remove('text-muted');
                    small.classList.add('text-success');
                    updateBtn.removeAttribute('disabled');
                    
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                    small.classList.remove('text-muted');
                    small.classList.add('text-danger');
                }
            });
        })
    })
}




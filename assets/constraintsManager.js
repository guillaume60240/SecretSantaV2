let userForConstraint = document.querySelectorAll('.userForConstraint');
let constraintsContainer = document.querySelector('.constraintsContainer'); 
let submitConstraints = document.querySelector('button[name="submitConstraints"]');

if(userForConstraint) {

    console.log(userForConstraint.length);

    userForConstraint.forEach(user =>  {
        if(user.classList.contains('alert-danger')) {
            addConstraintInput(user);
        }
        user.addEventListener('click', function() {
            user.classList.toggle('alert-success');
            user.classList.toggle('alert-danger');

            if(user.classList.contains('alert-danger')) {
                addConstraintInput(user);
            } else if (user.classList.contains('alert-success')) {
                removeConstraintInput(user);
            }
        });   
    });  
    
    function addConstraintInput(user) {
        let newConstraint = document.createElement('input');
        newConstraint.setAttribute('type', 'hidden');
        newConstraint.setAttribute('name', 'constraint-'+ user.id);
        newConstraint.setAttribute('id', 'constraint-'+ user.id);
        newConstraint.setAttribute('class', 'constraint');
        newConstraint.setAttribute('value', user.id);

        constraintsContainer.appendChild(newConstraint);
    }

    function removeConstraintInput(user) {
        let constraint = document.querySelector('#constraint-'+ user.id);
        constraintsContainer.removeChild(constraint);
    }
}

if(submitConstraints) {
    
    submitConstraints.addEventListener('click', (e) => {
        let constraintsNumber = 0;
        //on vérifie si tous les membres ne sont pas notés en contrainte
        userForConstraint.forEach(user => {
            if(user.classList.contains('alert-danger')) {
                constraintsNumber++;
            }
        });
        
        if(constraintsNumber == userForConstraint.length) {
            e.preventDefault();
            alert('Attention, vous avez choisi trop de contraintes, le calcul ne sera pas possible!');
            return false;
        }
    });
}

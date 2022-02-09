export default class Small {
    constructor(id, classList, text, parent) {
        this.id = id;
        this.classList = classList;
        this.text = text;
        this.parent = parent;
    }

    createSmall() {

        let small = document.createElement('small');
            small.id = this.id;
            small.className = this.classList;
            small.innerHTML = this.text;

        let input = document.getElementById(this.parent);
            input.after(small);
    }

    isValid() {
        let small = document.getElementById(this.id);
            small.classList.remove('text-danger', 'text-muted');
            small.classList.add('text-success');
            
    }

    isInvalid() {
        let small = document.getElementById(this.id);
            small.classList.remove('text-success', 'text-muted');
            small.classList.add('text-danger');
    }

}
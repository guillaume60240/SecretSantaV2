export default class Input {
    constructor(type, id, classList, style, name, value, parent) {
        this.type = type;
        this.id = id;
        this.classList = classList;
        this.style = style;
        this.name = name;
        this.value = value;
        this.parent = parent;
    }

    createInput() {
            
        let input = document.createElement('input');
            input.type = this.type;
            input.id = this.id;
            input.className = this.classList;
            input.style = this.style;
            input.name = this.name;
            input.value = this.value;

        let form = document.getElementById(this.parent);
            form.appendChild(input);
    }

    isValid() {
        let input = document.getElementById(this.id);
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
    }

    isInvalid() {
        let input = document.getElementById(this.id);
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
    }
}
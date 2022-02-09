export default class Button {
    constructor(parent, type, classList, id, name, value, text, disabled) {
        this.parent = parent;
        this.type = type;
        this.classList = classList;
        this.id = id;
        this.name = name;
        this.value = value;
        this.text = text;
        this.disabled = disabled;
    }
  
    createBtn(){
        
        let btn = document.createElement('button');
            btn.type = this.type;
            btn.className = this.classList;
            btn.id = this.id;
            btn.name = this.name;
            btn.value = this.value;
            btn.textContent = this.text;
            btn.disabled = this.disabled;
        
            let form = document.getElementById(this.parent);
                form.appendChild(btn);
    }
    
    setDisabled(disabled) {
        let btn = document.getElementById(this.id);
            btn.disabled = disabled;
    }
}
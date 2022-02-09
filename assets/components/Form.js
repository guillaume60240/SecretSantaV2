export default class Form {
    constructor(classlist, action, method, parent, id) {
        this.classlist = classlist;
        this.action = action;
        this.method = method;
        this.parent = parent;
        this.id = id;
    }

    createForm() {
            
        let form = document.createElement('form');
            form.className = this.classlist;
            form.action = this.action;
            form.method = this.method;
            form.id = this.id;

        this.parent.replaceWith(form);
    }
}
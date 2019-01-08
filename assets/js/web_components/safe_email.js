class AppEmailElement extends HTMLAnchorElement {
    get email() {
        return this.getAttribute('email');
    }

    set email(val) {
        this.setAttribute('email', val);
    }

    attributeChangedCallback(name, oldValue, newValue) {
        switch (name) {
            case 'email':
                newValue = newValue.split('')
                    .reverse()
                    .join('')
                    .replace(/ \[dot] /g, '.')
                    .replace(/ \[at] /g, '@');

                this.innerHTML = '';
                this.appendChild(window.document.createTextNode(newValue));
                this.setAttribute('href', 'mailto:' + newValue);
                break;
        }
    }

    static get observedAttributes() {
        return ['email'];
    }
}

if (!window.customElements.get('safe-email')) {
    window.AppEmailElement = AppEmailElement;
    window.customElements.define('safe-email', AppEmailElement, {extends: "a"});
}

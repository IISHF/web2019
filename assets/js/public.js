import '../css/public.scss';

const $ = require('jquery');
require('bootstrap');

let appEmailTpl = window.document.createElement('template');
appEmailTpl.innerHTML = `
<style>
    :host {
    }
</style>
<a href="" rel="email"></a>
`;

window.customElements.define(
    'app-email',
    class extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({mode: 'open'});
            this.shadowRoot.appendChild(appEmailTpl.content.cloneNode(true));
            this.aEl = this.shadowRoot.querySelector('[rel="email"]');
        }

        get email() {
            return this.getAttribute('email');
        }

        set email(val) {
            this.setAttribute('email', val);
        }

        connectedCallback() {
            this._upgradeProperty('email');
        }

        _upgradeProperty(prop) {
            if (this.hasOwnProperty(prop)) {
                let value = this[prop];
                delete this[prop];
                this[prop] = value;
            }
        }

        attributeChangedCallback(name, oldValue, newValue) {
            switch (name) {
                case 'email':
                    newValue = newValue.split('')
                        .reverse()
                        .join('')
                        .replace(/ \[dot] /g, '.')
                        .replace(/ \[at] /g, '@');

                    this.aEl.innerHTML = '';
                    this.aEl.appendChild(window.document.createTextNode(newValue));
                    this.aEl.setAttribute('href', 'mailto:' + newValue);
                    break;
            }
        }

        static get observedAttributes() {
            return ['email'];
        }
    }
);

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    console.log('ready');
});

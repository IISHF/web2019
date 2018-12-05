import '../css/public.scss';

const $ = require('jquery');
require('bootstrap');
require('@webcomponents/webcomponentsjs');
require('@ungap/custom-elements-builtin');
require('time-elements');

window.customElements.define(
    'app-email',
    class extends HTMLAnchorElement {
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
    },
    { extends: "a" }
);

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    console.log('ready');
});

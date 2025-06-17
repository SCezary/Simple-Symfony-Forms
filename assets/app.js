import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

$(document).ready(() => {
    $('body').on('change', 'select#post_template', async function () {
        const formElement = document.getElementById('post_postValues');

        if (!formElement) {
            return;
        }

        formElement.innerHTML = '';

        if (!this.value || this.value === '') {
            return;
        }

        const response = await fetch('/post-values-form/' + this.value)

        if (response.ok) {
            const body = await response.text();

            const parser = new DOMParser()
            const doc = parser.parseFromString(body, 'text/html');

            const responseElement = doc.getElementById('post_postValues');
            if (responseElement && formElement) {
                formElement.innerHTML = responseElement.innerHTML;
            }
        }
    })

    $('button#js-template-field-add').on('click', (e) => {
        e.preventDefault();

        const wrapper = $('#js-template-field-wrapper');
        let prototype = wrapper.data('prototype');
        const index = wrapper.data('index');

        prototype = prototype.replace(/__name__/g, index);
        const element = document.createElement('div')
        element.classList.add('col-12')
        element.classList.add('template-field-item')
        element.setAttribute('id', 'js-template-field-item')
        element.innerHTML = `
            <div class="form-group">
                ${prototype}
                <button type="button" class="btn btn-danger btn-sm" id="js-template-field-remove">Remove</button>
                <span>Once you save new field, you will be able to set more options.</span>
            </div>
        `

        element.querySelector('input[data-field="js-order"]').value = index + 1
        $('#js-template-field-list').append(element);
        wrapper.data('index', index + 1)
    })

    $('body').on('click', 'button#js-template-field-remove', function (e) {
        e.preventDefault();
        $(this).closest('#js-template-field-item').remove()

        const wrapper = $('#js-template-field-wrapper');
        const index = wrapper.data('index');
        wrapper.data('index', index - 1)
    })
})


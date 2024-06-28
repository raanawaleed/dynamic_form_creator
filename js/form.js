$(document).ready(function () {
    // Fetch and display list of forms
    fetch('/api/list-forms.php')
        .then(response => response.json())
        .then(data => {
            const formList = $('#form-list');
            data.forEach(form => {
                const li = $('<li>')
                    .addClass('list-group-item')
                    .text(form.name)
                    .data('form-id', form.id)
                    .click(() => window.location.href = `/form.html?id=${form.id}`);
                formList.append(li);
            });
        });

    // If on form.html, fetch form details and render form
    const urlParams = new URLSearchParams(window.location.search);
    const formId = urlParams.get('id');

    if (formId) {
        fetch(`/api/get-form.php?id=${formId}`)
            .then(response => response.json())
            .then(form => {
                $('#form-title').text(form.name);
                const formElement = $('#dynamic-form');
                form.fields.forEach(field => {
                    let fieldElement;
                    switch (field.type) {
                        case 'input':
                            fieldElement = `<div class="mb-3">
                                <label for="${field.name}" class="form-label">${field.label}</label>
                                <input type="text" class="form-control" id="${field.name}" name="${field.name}" required>
                            </div>`;
                            break;
                        case 'textarea':
                            fieldElement = `<div class="mb-3">
                                <label for="${field.name}" class="form-label">${field.label}</label>
                                <textarea class="form-control" id="${field.name}" name="${field.name}" required></textarea>
                            </div>`;
                            break;
                        case 'select':
                            fieldElement = `<div class="mb-3">
                                <label for="${field.name}" class="form-label">${field.label}</label>
                                <select class="form-control" id="${field.name}" name="${field.name}" required>
                                    ${field.options.map(option => `<option value="${option.value}">${option.label}</option>`).join('')}
                                </select>
                            </div>`;
                            break;
                        case 'radio':
                            fieldElement = `<div class="mb-3">
                                <label class="form-label">${field.label}</label>
                                ${field.options.map(option => `<div class="form-check">
                                    <input class="form-check-input" type="radio" name="${field.name}" id="${field.name}_${option.value}" value="${option.value}">
                                    <label class="form-check-label" for="${field.name}_${option.value}">
                                        ${option.label}
                                    </label>
                                </div>`).join('')}
                            </div>`;
                            break;
                        case 'checkbox':
                            fieldElement = `<div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="${field.name}" name="${field.name}">
                                    <label class="form-check-label" for="${field.name}">
                                        ${field.label}
                                    </label>
                                </div>
                            </div>`;
                            break;
                    }
                    formElement.append(fieldElement);
                });
                formElement.append('<button type="submit" class="btn btn-primary">Submit</button>');
            });

        $('#dynamic-form').on('submit', function (e) {
            e.preventDefault();

            // Validate form fields
            if (!validateForm()) {
                return;
            }

            const formData = $(this).serializeArray();
            formData.push({ name: 'form_id', value: formId });

            $.ajax({
                url: '/api/submit-form.php',
                method: 'POST',
                data: formData,
                success: function (response) {
                    if (response.status === 'success') {
                        alert('Form submitted successfully!');
                        $('#dynamic-form')[0].reset(); // Reset form after submission
                    } else {
                        alert('Form submission failed.');
                    }
                },
                error: function () {
                    alert('Form submission failed!');
                }
            });
        });
    }
});
function validateForm() {
    let isValid = true;

    // Validate required fields
    $('#dynamic-form input[required], #dynamic-form textarea[required], #dynamic-form select[required]').each(function() {
        if (!$(this).val()) {
            isValid = false;
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Additional validation logic 

    return isValid;
}
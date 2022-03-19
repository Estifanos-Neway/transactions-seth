function validator() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                } else {
                    // @ts-ignore
                    document.getElementById("pay-now").disabled = true;
                }
                form.classList.add('was-validated')
            }, false)
        })
}

let form = document.getElementById("payment-card-form");
let form_members = document.getElementsByClassName('required');
form.addEventListener('submit', event => {
    let allValid = true;
    for (let form_member of form_members) {
        // @ts-ignore
        if (form_member.value == "") {
            // @ts-ignore
            form_member.style.borderColor = "red";
            allValid = false;
        }
    }
    if (!allValid) {
        event.preventDefault();
        event.stopPropagation();
    } else{
        // @ts-ignore
        document.getElementById("submit").disabled = true;
    }
}, false);

for (let form_member of form_members) {
    form_member.addEventListener('input', event => {
        // @ts-ignore
        form_member.style.borderColor = "rgba(6, 28, 51, .25)";
    })
}
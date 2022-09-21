const password = document.getElementById("new_password");

// VALIDAR PASSWORD - mínimo 8 caracteres, mayúsculas, minúsculas y números
if (password != null) {
    password.addEventListener("change", (event) => {
        validarPassword(event.target.value)
    });
}

function validarPassword(valor) {
    if ( /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(valor)){
        password.classList.remove("is-invalid");
    } else {
        password.classList.add("is-invalid");
    }
}
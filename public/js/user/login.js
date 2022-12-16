function userLogin(e) {
    event.preventDefault();

    const xhttp = new XMLHttpRequest();

    xhttp.onload = function () {
        setupForm();
        let res = JSON.parse(this.responseText).data;

        let entity = res.entity;
        if (res.logged) {
            postLogin();
        } else {
            setupErrors(entity);
        }
    }
    
    post(xhttp);
}

function setupErrors(entity) {
    let errorProp = entity.errors.login !== null ? "login" : "password";
    let errorTag = document.getElementById("error-" + errorProp);
    let errorMessage = entity.errors[errorProp][0];
    errorTag.style.display = "block";
    errorTag.innerHTML = errorMessage;
}

function post(xhttp) {
    let login = document.getElementById("login").value;
    let password = document.getElementById("password").value;
    xhttp.open("POST", "api/login");
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("login=" + login + "&password=" + password);
}

function setupForm() {
    document.getElementById("error-login").style.display = "none";
    document.getElementById("error-password").style.display = "none";
}

function postLogin() {
    let message = document.getElementById("message");
    message.classList.remove("d-none");
    document.getElementById("btn-login").disabled = true;
    message.innerHTML = "Vous vous êtes bien connectés!";
    setTimeout(
        () => location.href = "/",
        2000
    )
}

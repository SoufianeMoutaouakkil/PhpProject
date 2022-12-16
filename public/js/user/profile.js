function getProps() {
    return ["firstname", "lastname", "login", "mail"];
}

function checkChanges() {
    const xhttp = new XMLHttpRequest();
    
    xhttp.onload = function () {
        let entity = JSON.parse(this.responseText).data.entity;
        let cond = isDiff(entity.values, getProps())
        setupSaveBtn(cond)
    }
    
    get(xhttp);
}

function save() {
    event.preventDefault();
    const xhttp = new XMLHttpRequest();
    
    xhttp.onload = function () {
        setupForm(getProps());
        let res = JSON.parse(this.responseText).data;

        let entity = res.entity;
        if (res.updated) {
            afterUpdate();
        } else {
            setupErrors(entity, getProps());
        }
    }
    
    post(xhttp);
}

// useful functions
function afterUpdate() {
    let message = document.getElementById("message");
    setupSaveBtn(false)
    message.classList.remove("d-none");
    message.innerHTML = "Vos données ont été bien mises à jour!";
    setTimeout( () => {
        message.innerHTML = "";
        message.classList.add("d-none");
    },
    2000
    )
}

function postData(data) {
    return Object.entries(data).reduce((ini, ele, i) => {
        return ini + ele[0] + '=' + ele[1] + '&';
    }, ""
    )
}

function get(xhttp) {
    xhttp.open("GET", "api/profile/info");
    xhttp.send();
}

function post(xhttp) {
    xhttp.open("POST", "api/profile/update");
    data = {
        "firstname" : getValue('firstname'),
        "lastname" : getValue('lastname'),
        "login" : getValue('login'),
        "mail" : getValue('mail')
    };
    strData = postData(data)
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(strData);
}

function getValue(e) {
    return document.getElementById(e).value
}

function isDiff(user, props) {
    cond = false;
    props.forEach(e => {
        serverValue = user[e] === null ? "" : user[e];
        if (serverValue != getValue(e)) {
            cond = true;
        }  
    });
    return cond
}

function setupSaveBtn(cond) {
    let saveBtn = document.getElementById("btn-save")
    if (cond) {
        saveBtn.classList.remove("d-none")
    } else {
        saveBtn.classList.add("d-none")
    }
} 

function setupForm(props) {
    props.forEach(
        (prop) => {
            document.getElementById("error-" + prop).style.display = "none";
        }
    )
}
function setupErrors(entity, props) {
    props.forEach(
        (prop) => {
            let errorTag = document.getElementById("error-" + prop);
            if (typeof entity.errors[prop] !== 'undefined') {
                let errorMessage = entity.errors[prop][0];
                errorTag.style.display = "block";
                errorTag.innerHTML = errorMessage;
            }
        }
    )
}
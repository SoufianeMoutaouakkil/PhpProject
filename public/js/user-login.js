function userLogin(e) {
  event.preventDefault();
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function () {
    document.getElementById("error-login").style.display = "none";
    document.getElementById("error-password").style.display = "none";
    
    let res = JSON.parse(this.responseText).data;
    let entity = res.entity;
    console.log(res.logged);
    console.log(entity);
    if (res.logged) {
      document.getElementById("btn-login").disabled = true;
      
      let message = document.getElementById("message");
      message.classList.remove("d-none");
      message.innerHTML = "Vous vous êtes bien connectés!";
      
      setTimeout(
        () => location.href = "/",
        2000
        )
    } else {

      let errorProp = entity.login.errors !== null ? "login" : "password";
      let errorTag = document.getElementById("error-" + errorProp);
      let errorMessage = entity[errorProp].errors[0];

      errorTag.style.display = "block";
      errorTag.innerHTML = errorMessage;
    }
  }
  let login = document.getElementById("login").value;
  let password = document.getElementById("password").value;
  xhttp.open("POST", "api/login");
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("login=" + login + "&password=" + password);
}

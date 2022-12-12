function getPmInfo(e) {
  event.preventDefault()
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    // document.getElementById("demo").innerHTML = this.responseText;
    var res = JSON.parse(this.responseText).data;
    console.log(res);
    document.getElementById("pon").value = "";
    document.getElementById("oh").value = "";
    if (res.hasOwnProperty('entity') && res.entity != false) {
      let entity = res.entity
      document.getElementById("pon").value = entity.pon;
      document.getElementById("oh").value = entity.oh;
    }
  }
  var pm = document.getElementById("pm").value;
  xhttp.open("POST", "api/parc/"+pm);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("pm="+pm);
}
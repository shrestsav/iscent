
function passM() {
    var pass = document.getElementById("pass").value;
    var rpass = document.getElementById("rpass").value;
    if (pass.length >= 4) {
    if (pass == rpass) {
    document.getElementById("pm").style.color = "green";
    document.getElementById("pm").innerHTML = "Password Matched!";
    document.getElementById("signup_btn").disabled = false;
    }
else {
    document.getElementById("pm").style.color = "red";
    document.getElementById("pm").innerHTML = "Password Not Matched!";
    document.getElementById("signup_btn").disabled = true;
    }
}
else {
    document.getElementById("pm").style.color = "orange";
    document.getElementById("pm").innerHTML = "Atleat 4 characters!";
    document.getElementById("signup_btn").disabled = true;
    }
if(pass=='' && rpass==''){
    document.getElementById("signup_btn").disabled = false;
    }
}
function vali() {
    var u_l = document.getElementById("user").value.length;
    if (u_l <= 3) {
    document.getElementById("um").style.color = "red";
    document.getElementById("signup_btn").disabled = true;
    }
else {
    document.getElementById("um").style.color = "black";
    document.getElementById("signup_btn").disabled = false;
    }
}
function subf() {
    var terms = document.getElementById("ch").checked;
    if (terms == true) {
    document.getElementById("sf").submit();
    }
}

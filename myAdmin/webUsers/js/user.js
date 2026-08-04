function passM() {
    var pass = document.getElementById("pass").value;
    var rpass = document.getElementById("rpass").value;
    var current = document.getElementById("current_password").value;
    var pm = document.getElementById("pm");
    var btn = document.getElementById("signup_btn");

    if (pass === '' && rpass === '') {
        pm.innerHTML = '';
        btn.disabled = false;
        return;
    }

    if (current === '') {
        pm.style.color = "red";
        pm.innerHTML = "Current password is required to change password.";
        btn.disabled = true;
        return;
    }

    if (pass.length < 8) {
        pm.style.color = "orange";
        pm.innerHTML = "At least 8 characters required.";
        btn.disabled = true;
        return;
    }

    if (!/[A-Z]/.test(pass) || !/[a-z]/.test(pass) || !/[0-9]/.test(pass) || !/[^A-Za-z0-9]/.test(pass)) {
        pm.style.color = "orange";
        pm.innerHTML = "Use uppercase, lowercase, number and special character.";
        btn.disabled = true;
        return;
    }

    if (pass !== rpass) {
        pm.style.color = "red";
        pm.innerHTML = "Password Not Matched!";
        btn.disabled = true;
        return;
    }

    pm.style.color = "green";
    pm.innerHTML = "Password Matched!";
    btn.disabled = false;
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

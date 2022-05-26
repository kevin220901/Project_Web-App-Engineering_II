
// Toggle Light/Dark Mode
const darkModeButton = document.getElementById("darkModeButton");
if(darkModeButton !== null){
    darkModeButton.onclick = function(event) {
        const d = new Date();
        d.setTime(d.getTime() + (2*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = "darkMode" + "=" + true + ";" + expires + ";path=/; SameSite=Strict;";
        location.reload();
        return false;
    }
}
const lightModeButton = document.getElementById("lightModeButton");
if(lightModeButton !== null){
    lightModeButton.onclick = function(event) {
        const d = new Date();
        d.setTime(d.getTime() + (2*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = "darkMode" + "=" + false + ";" + expires + ";path=/; SameSite=Strict;";
        location.reload();
        return false;
    }
}

// login switch between login and register
const loginButton = document.getElementById("pill-login-button");
const registerButton = document.getElementById("pill-register-button");
if(loginButton !== null){
    if(registerButton !== null){
        loginButton.onclick = function(event) {
            if($('#pill-login-button').hasClass('btn-light')){
                $('#pill-login-button').removeClass('btn-light');
                $('#pill-register-button').addClass('btn-light');
            }
            else{
                $('#pill-login-button').removeClass('btn-dark');
                $('#pill-register-button').addClass('btn-dark');
            }

            $('#pill-register-button').removeClass('active');
            $('#pill-login-button').addClass('active');

            $('#pills-register-content').removeClass('active show').addClass('fade');
            $('#pills-login-content').addClass('active show');


        }
        registerButton.onclick = function(event) {
            if($('#pill-register-button').hasClass('btn-light')){
                $('#pill-register-button').removeClass('btn-light');
                $('#pill-login-button').addClass('btn-light');
            }
            else{
                $('#pill-register-button').removeClass('btn-dark');
                $('#pill-login-button').addClass('btn-dark');
            }


            $('#pill-login-button').removeClass('active');
            $('#pill-register-button').addClass('active');

            $('#pills-login-content').removeClass('active show').addClass('fade');
            $('#pills-register-content').addClass('active show');
        }
    }
}


// Toggle Light/Dark Mode
const darkModeButton = document.getElementById("darkModeButton");
if(darkModeButton !== null){
    darkModeButton.onclick = function(event) {
        const d = new Date();
        d.setTime(d.getTime() + (2*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        // Setzt einen Cookie mit dem Namen darkMode und der Value "true" als String !, path=/ sagt, dass der Cookie auf der ganzen Seite funktioniert
        // und SameSite=Strict sagt, dass der Cookie nur im Kontext dieser Seite gesetzt wird
        document.cookie = "darkMode" + "=" + true + ";" + expires + ";path=/; SameSite=Strict;";
        // Jetzt wird die Seite neu geladen, damit die css Klassen überschrieben werden können
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
            // Der if Block ist nur für dark/light mode und überprüft welcher btn stlye genutzt wird
            if(!$('#pill-login-button').hasClass('active')){
                if(($('#pill-login-button').hasClass('btn-light'))){
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
        }
        registerButton.onclick = function(event) {
            if(!$('#pill-register-button').hasClass('active')){
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
}



//Get the button
let mybutton = document.getElementById("btn-back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

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
<<<<<<< Updated upstream
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

=======
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
>>>>>>> Stashed changes

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
// Parser zu .md
//let text='# Ich bin eine Überschrift\n- Und ich bin ein Punkt! ```<p class="text-primary"><script>alert("XSS")</script></p>```<img src="x"/>'
let text='Marked - Markdown Parser\n' +
    '========================\n' +
    '\n' +
    '[Marked] lets you convert [Markdown] into HTML.  Markdown is a simple text format whose goal is to be very easy to read and write, even when not converted to HTML.  This demo page will let you type anything you like and see how it gets converted.  Live.  No more waiting around.\n' +
    '\n' +
    'How To Use The Demo\n' +
    '-------------------\n' +
    '\n' +
    '1. Type in stuff on the left.\n' +
    '2. See the live updates on the right.\n' +
    '\n' +
    'That\'s it.  Pretty simple.  There\'s also a drop-down option in the upper right to switch between various views:\n' +
    '\n' +
    '- **Preview:**  A live display of the generated HTML as it would render in a browser.\n' +
    '- **HTML Source:**  The generated HTML before your browser makes it pretty.\n' +
    '- **Lexer Data:**  What [marked] uses internally, in case you like gory stuff like this.\n' +
    '- **Quick Reference:**  A brief run-down of how to format things using markdown.\n' +
    '\n' +
    'Why Markdown?\n' +
    '-------------\n' +
    '\n' +
    'It\'s easy.  It\'s not overly bloated, unlike HTML.  Also, as the creator of [markdown] says,\n' +
    '\n' +
    '> The overriding design goal for Markdown\'s\n' +
    '> formatting syntax is to make it as readable\n' +
    '> as possible. The idea is that a\n' +
    '> Markdown-formatted document should be\n' +
    '> publishable as-is, as plain text, without\n' +
    '> looking like it\'s been marked up with tags\n' +
    '> or formatting instructions.\n' +
    '\n' +
    'Ready to start writing?  Either start changing stuff on the left or\n' +
    '[clear everything](/demo/?text=) with a simple click.\n' +
    '\n' +
    '[Marked]: https://github.com/markedjs/marked/\n' +
    '[Markdown]: http://daringfireball.net/projects/markdown/\n' +
    '\n' +
    '```C\n' +
    'Scanf("%s",&H);\n' +
    'assdasd\n' +
    'asddddadaadada\n' +
    '```\n' +
    '`Test`'
let dirtyText = marked.parse(text); //parsed md zu html
// Erlaubt nur HTML
let cleanText = DOMPurify.sanitize(dirtyText, { USE_PROFILES: {html: true} }); //Überprüft den Code und entfernt alle Text die nicht in standart html sind <script> etc
// Erlaubt HTML, SVG und MathML
//let cleanText = DOMPurify.sanitize(dirtyText);
$("#parseToMD").html(cleanText);

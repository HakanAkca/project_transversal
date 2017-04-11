window.onload = function(){
    registerForm = document.querySelector('#myRegister');
    registerForm.onsubmit = function(){
        var username = registerForm.querySelector('input[name=username]').value;
        var email = registerForm.querySelector('input[name=email]').value;
        var password = registerForm.querySelector('input[name=password]').value;
        var verifpassword = registerForm.querySelector('input[name=verifpassword]').value;
        var city = registerForm.querySelector('input[name=city]').value;
        
        console.log(username, email, password, verifpassword, city);
        return false;
    }
}
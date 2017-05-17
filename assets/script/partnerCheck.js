window.onload = function(){
    var errorBlock = document.querySelector('#errorBlock');
    var successBlock = document.querySelector('#successBlock');
    document.forms['myRegister'].onsubmit = function(){
        successBlock.innerHTML = '';
        errorBlock.innerHTML = '';
        var params = 'name='+this.elements['name'].value;
        params += '&email='+this.elements['email'].value;
        params += '&city='+this.elements['city'].value;
        params += '&phone='+this.elements['phone'].value;
        params += '&status='+this.elements['status'].value;
        console.log(params);
        var errorMessage = '';

        var http = new XMLHttpRequest();
        http.open("POST", "?action=partner", true);
        var url = "?action=partner";
        http.open("POST", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onload = function() {
            if(http.readyState == 4 && http.status == 200) {
                successBlock.innerHTML = 'Inscription réussi vous aller être rediriger';

            } else {
                var errors = JSON.parse(http.responseText);
                for (var error in errors['errors']) {
                    errorBlock.innerHTML += errors['errors'][error] + '<br>';
                }
            }
        };
        http.send(params);
        return false;
    };
};

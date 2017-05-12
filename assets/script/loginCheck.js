window.onload = function(){

    var errorBlock = document.querySelector('#errorBlock');
    var successBlock = document.querySelector('#successBlock');
    document.forms['myLogin'].onsubmit = function(){
        successBlock.innerHTML = '';
        errorBlock.innerHTML = '';
        var params = 'username='+this.elements['username'].value;
        params += '&password='+this.elements['password'].value;
        var errorMessage = '';

        var http = new XMLHttpRequest();
        http.open("POST", "?action=home", true);
        var url = "?action=home";
        http.open("POST", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onload = function() {
            if(http.readyState == 4 && http.status == 200) {
                window.location.href = 'http://localhost:8888/project_transversal/?action=home'
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
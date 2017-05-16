window.onload = function(){
    var errorBlock = document.querySelector('#errorBlockRemoveAccount');
    //var successBlock = document.querySelector('#successBlock');
    document.forms['removeAccountForm'].onsubmit = function(){
        //successBlock.innerHTML = '';
        errorBlock.innerHTML = '';
        var params = 'pseudo='+this.elements['username'].value;

        var http = new XMLHttpRequest();
        http.open("POST", "?action=admin", true);
        var url = "?action=admin";
        http.open("POST", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onload = function() {
            if(http.readyState == 4 && http.status == 200) {
               console.log("OK");
            } else {

                var errors = JSON.parse(http.responseText);
                console.log(errors);
                /*for (var error in errors['errors']) {
                    errorBlock.innerHTML += errors['errors'][error] + '<br>';
                }*/
            }
        };
        http.send(params);
        return false;
    };
};
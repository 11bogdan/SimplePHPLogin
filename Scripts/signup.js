function getVal(name) {
    var el = document.getElementsByName(name);
    return el.value;
}

function getErrField(id) {
    return document.getElementById(id);
}

function onSubmit() {

    document.getElementById("please_wait").innerHTML = "Please, wait...";

    var regexes = {
        /*"login":"^[0-9a-zA-Z]{6,12}$",
        "password":"^[A-Z][0-9a-zA-Z]{6,12}$",
        "email":'^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$'
    */
    };
    
    var isvalid = true;
    
    for (var field in regexes) {
        var val = getVal(field);
        var reg = new RegExp(regexes[field]);
        var isOk = reg.test(val) && val !== undefined;
      
        if (!isOk) {
            getErrField(field).innerHTML = "Field incorrect. Should match "+regexes[field];
        }
        
        isvalid &= isOk;
    }
    
    //do ajax post
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        
        document.getElementById("please_wait").innerHTML = "";
        
        if (this.status === 200) {
            
        } else {
            isvalid = false;
            var resp = JSON.parse(xhttp.responseText);
            for(var key in resp) {
                getErrField(key).innerHTML = resp[key];
            }
        }
    };
    xhttp.open("POST", "signup_post", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhttp.send(["password", "login"]
            .map(name => name + "=" + getVal(name))
            .join("&"));
    
    if (!isvalid) {
        document.getElementsByName("password").value = "";
    }

    return isvalid;
}


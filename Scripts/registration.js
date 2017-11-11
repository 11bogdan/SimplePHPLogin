debug = true;


granted = false;


function getField(name) {
    var el = document.getElementsByName(name)[0];
    return el;
}

function getErrField(id) {
    return document.getElementById(id);
}

function onSubmit() {

    document.getElementById("please_wait").innerHTML = "Please, wait...";

    if (granted) {
        return true;
    }

    var regexes = {
        "agree":"true",
        "birth_date":"([0-9]{4})-([0-9]{2})-([0-9]{2})",
        "login":"^[0-9a-zA-Z]{6,12}$",
        "real_name":"^[0-9a-zA-Z]{2,20}$",
        "password":"^[A-Z][0-9a-zA-Z]{6,12}$",
        "email":'^([^<>().,;:s@"]+(.[^<>()\[\]\.,;:s@"]+)*)@(([[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}])|(([a-zA-Z-0-9]+.)+[a-zA-Z]{2,}))$'
    };
    
    var isvalid = true;

    //check passwords
    if (getField("password").value !== getField("password_repeat").value) {
        getErrField("password_repeat").innerHTML = "The passwords should be the same";
    }
    
    for (var field in regexes) {
        var val = getField(field).value;
        
        if (field === "agree") {
            val = getField(field).checked;
        }
        
        var reg = new RegExp(regexes[field], "g");
        var isOk = reg.test(val) && val !== undefined;
        
 
        if (!isOk) {
            getErrField(field).innerHTML = " Field incorrect. Should match "+regexes[field];
        } else {
            getErrField(field).innerHTML = "";
        }
        
        isvalid &= isOk;
    }
    
    alert("Pre-ajax res: "+isvalid);
    
    //do ajax post
    var xhttp = new XMLHttpRequest();
    var responseCame = false;
    
    xhttp.onreadystatechange = function() {
        
        alert("rd state:" + this.readyState);
        if (this.readyState === 4) {
            alert("the status is "+this.status);
            document.getElementById("please_wait").innerHTML = "";
            
            if (this.status === 200) {
                granted = true;
                document.forms[0].submit();
            } else {
                alert("parsing "+ xhttp.responseText);
                isvalid = false;
                var resp = JSON.parse(xhttp.responseText);

                alert("Err field count: "+resp.length);
                for(var key in resp) {
                    alert("Err field: "+key);
                    getErrField(key).innerHTML = resp[key];
                }
            }

            responseCame = true;
        }
    };

    xhttp.open("POST", "register_validate", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    (xhttp.send(["email", "country", "login"]
            .map(name => name + "=" + getField(name).value)
            .join("&")));
    
    
    
    if (!isvalid) {
        getField("password").value = "";
        getField("password_repeat").value = "";
    }
    
    if (debug) alert("About to return "+isvalid);
    return false;
}


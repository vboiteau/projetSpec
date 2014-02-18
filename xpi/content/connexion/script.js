/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};

/**
 * Controls the browser overlay for the projetSpec extension.
 */
projetSpec.connexion = {
  
  connecter : function(e) {
    var data={};
    data["username"]=document.getElementById("username").value;
    data["password"]=document.getElementById("password").value;
    let strJSON="connexion="+JSON.stringify(data);
    let url = "http://vboirob.com/projetSpec/extension/index.php";
      let x = Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"]
                    .createInstance(Components.interfaces.nsIXMLHttpRequest);
      x.open("POST", url, true);
      x.setRequestHeader('Content-type','application/x-www-form-urlencoded');
      x.onreadystatechange=function(){
        if(x.readyState!=4) return;
        if(x.status!=200&&x.status!=304){
          alert('HTTP error'+req.status);
        }
        var dataOut=JSON.parse(x.responseText);
        console.log(dataOut);
        if(typeof dataOut.success!="undefined"){
          self.close();
        }else{
          if(typeof dataOut.username_error!="undefined"){
            document.getElementById("username_error").value=dataOut.username_error;
          }else{
            document.getElementById("username_error").value="";
          }
          if(typeof dataOut.password_error!="undefined"){
            document.getElementById("password_error").value=dataOut.password_error;
          }else{
            document.getElementById("password_error").value="";
          }
        } 
      }
      x.send(strJSON);
  },
  inscrire: function(e){
  	window.open("chrome://projetSpec/content/inscription/inscription.xul",
    "projetSpec-inscription",
    "chrome,centerscreen");
    self.close();
  },
};
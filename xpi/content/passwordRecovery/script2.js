/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};

/**
 * Controls the browser overlay for the projetSpec extension.
 */
projetSpec.passwordRecovery = {
  init:function(e){
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
      document.getElementById("question").value=x.responseText;
    }
    x.send("ask=question");
  },
  answer : function(e) {
  	let data={};
  	data['in']={};
  	data['in']['username']=document.getElementById('username').value;
   	let strPost="pRUsername="+data['in']['username'];
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
	        let dataOut=JSON.parse(x.responseText);
	        if(typeof dataOut.success!="undefined"){
	        	console.log("win");
	        }else if(typeof dataOut.erreur!="undefined"){
	        	document.getElementById("username_error").value=dataOut.erreur;
	        }
      	}
      	x.send(strPost);
  },
};
window.addEventListener("load", function(){projetSpec.passwordRecovery.init();},false);
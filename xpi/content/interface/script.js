/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};

/**
 * Controls the browser overlay for the projetSpec extension.
 */
projetSpec.interface = {
	init: function(e){
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
	        let text=x.responseText;
	        if(text=="false"){
	        	self.close();
	        	window.open("chrome://projetSpec/content/connexion/connexion.xul","projetSpec-interface","chrome,centerscreen");
	        }
      	}
      	x.send("check=connecter");
	},
	deconnexion: function(e){
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
	        self.close();
	        window.open("chrome://projetSpec/content/connexion/connexion.xul","projetSpec-interface","chrome,centerscreen");
      	}
      	x.send("deconnecter=yolo");
	},
};
window.addEventListener("load", function(){projetSpec.interface.init();},false);
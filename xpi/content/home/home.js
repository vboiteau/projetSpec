projetSpec.home={
	init:function(e){
		action='look_for_connection'
		dataIn={};
		serverRequest(this,action,dataIn);
	},
	signout:function(e){
		alert(url);
	},
	deleteAccount:function(e){
		alert(url);
	},
	serverReturn:function(dataOut){
		if(dataOut.erreur){
			switch(dataOut.erreur){
				case "aucune connexion":
					console.log('no_connection');
					break;
			}
		}
	},
};
window.addEventListener(
  "load", function(){projetSpec.home.init();}, false);
projetSpec.home={
	init:function(e){
		action='look_for_connection'
		dataIn={};
		serverRequest(this,action,dataIn);
	},
	signout:function(e){
		action="sign_out";
		dataIn={};
		serverRequest(this,action,dataIn);
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
				case "signOut":
					self.close();
					openNewWindow('signin/signin.xul','SignIn');
					break;
			}
		}
	},
};
window.addEventListener(
  "load", function(){projetSpec.home.init();}, false);
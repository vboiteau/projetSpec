projetSpec.signin={
	singin:function(e){
		let dataIn={};
		let formFilled=true;
		if(document.getElementById('username').value.length>0){
			dataIn['username']=document.getElementById('username').value;
			document.getElementById('username_error').value="";
		}else{
			formFilled=false;
			document.getElementById('username_error').value="Entrer un nom d'utilistateur";
		}
		if(document.getElementById('password').value.length>0){
			dataIn['password']=document.getElementById('password').value;
			document.getElementById('password_error').value="";
		}else{
			formFilled=false;
			document.getElementById('password_error').value="Entrer un mot de passe";
		}
		if(formFilled==true){
			let action='check_user';
			serverRequest(this,action,dataIn);
		}
	},
	signup:function(e){
		self.close();
		openNewWindow("signup/signup.xul","Sign Up");
	},
	recoverPassword:function(e){

	},
	serverReturn:function(dataOut){
		if(dataOut.success){
			self.close();
			openNewWindow("home/home.xul","Home");
		}
		if(dataOut.erreur){
			document.getElementById("signin_error").value="You have no accout :( , but you can create one. :)"
		}
	}
};
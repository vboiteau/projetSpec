projetSpec.signin={
	singin:function(e){
		let dataIn={};
		let formFilled=true;
		if(i('username').length>0){
			dataIn['username']=i('username');
			si("username_error","");
		}else{
			formFilled=false;
			si('username_error',"Entrer un nom d'utilistateur");
		}
		if(i('password').length>0){
			dataIn['password']=i('password');
			si('password_error',"");
		}else{
			formFilled=false;
			si('password_error',"Entrer un mot de passe");
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
		self.close();
		openNewWindow("recover/askUsername.xul",'Ask for Username');
	},
	serverReturn:function(dataOut){
    console.log(dataOut.erreur);
		if(dataOut.success){
      openNewWindow("spec.loc","spec");
			self.close();
			openNewWindow("home/home.xul","Home");
		}
		if(dataOut.erreur){
			si("signin_error","You have no accout :( , but you can create one. :)");
		}
	}
};
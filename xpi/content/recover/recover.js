projetSpec.recover={
	init:function(e){
		console.log('init');
		action='return_question';
		dataIn={};
		serverRequest(this,action,dataIn);
	},
	continue:function(e){
		let dataIn={};
		let formFilled=true;
		if(i('username').length>0){
			dataIn['username']=i('username');
			si("username_error","");
		}else{
			formFilled=false;
			si('username_error',"Entrer un nom d'utilistateur");
		}
		if(formFilled==true){
			let action='check_username';
			serverRequest(this,action,dataIn);
		}
	},
	recoverPassword:function(e){
		action='return_password';
		dataIn={'answer':i('answer')};
		serverRequest(this,action,dataIn);
	},
	serverReturn:function(dataOut){
		if(dataOut.unexist){
			self.close();
			openNewWindow("recover/askQuestion.xul","askQuestion");
		}
		if(dataOut.errUnexist){
			si("username_error","You have no accout :( , but you can create one. :)");
		}
		if(dataOut.question){
			si("question",dataOut.question);
		}
		if(dataOut.password){
			window.alert("Votre mot de passe est : "+dataOut.password);
			self.close();
			openNewWindow("signin/signin.xul","Signin");
		}if(dataOut.errPassword){
			si("answer_error","La réponse n'est pas la bonne");
		}
	}
};
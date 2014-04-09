projetSpec.signup={
	signup:function(e){
		let formFilled=true;
		let dataIn={};
		console.log(/[0-9a-zA-Z]{8,32}/.test(i('username')));
		if(i('username').length==0){
			formFilled=false;
			si("username_error","Entrer un nom d'utilisateur");
		}else if(!/[0-9a-zA-Z]{8,32}/.test(i('username'))){
			formFilled=false;
			si('username_error',"Le nom d'utilisateur doit contenir 8 à 32 caractères et aucun caractères spéciaux.");
		}else{
			dataIn['username']=i('username');
			si('username_error',"");
		}if(i('password').length==0){
			formFilled=false;
			si('password_error',"Entrer un mot de passe");
		}else if(!/[0-9a-zA-Z]{8,32}/.test(i('password'))){
			formFilled=false;
			si('password_error',"Le mot de passe doit contenir 8 à 32 caractères et aucun caractères spéciaux.");
		}else{
			dataIn['password']=i('password');
			si('password_error',"");
		}if(i('passwordConfirmation')!=i('password')){
			formFilled=false;
			si('passwordConfirmation_error',"Les deux mots de passe doivent être identiques.");
		}else{
			dataIn['passwordConfirmation']=i('passwordConfirmation');
			i('passwordConfirmation_error',"");
		}if(i('question')==""){
			formFilled=false;
			si('question_error',"Entrer une question de sécurité.");
		}else if(!/.{5,128}/.test(i('question'))){
			formFilled=false;
			si('question_error',"La question doit contenir 5 à 128 caractères.");
		}else{
			dataIn['question']=i('question');
			si('question_error',"");
		}if(i('answer')==""){
			formFilled=false;
			si('answer_error',"Entrer une réponse.");
		}else if(!/.{2,32}/.test(i('answer'))){
			formFilled=false;
			si('answer_error',"La réponse doit contenir 2 à 32 caractères.");
		}else{
			dataIn['answer']=i('answer');
			si('answer_error',"");
		}
		if(formFilled==true){
			let action='create_account';
			serverRequest(this,action,dataIn);
		}
	},
	serverReturn:function(dataOut){
		console.log(dataOut);
		if(dataOut.success){
			self.close();
      openNewWindow("spec.loc");
			openNewWindow("home/home.xul","Home");
		}
		if(dataOut.erreur){
			si("signin_error","You have no accout :( , but you can create one. :)");
		}
	}
};
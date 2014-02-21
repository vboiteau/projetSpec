projetSpec.signup={
	signup:function(e){
		let formFilled=true;
		let dataIn={};
		if(document.getElementById('username').value.length>0){
			dataIn['username']=document.getElementById('username').value;
			document.getElementById('username_error').value="";
		}else{
			formFilled=false;
			document.getElementById('username_error').value="Entrer un nom d'utilistateur";
		}if(document.getElementById('password').value.length>0){
			dataIn['password']=document.getElementById('password').value;
			document.getElementById('password_error').value="";
		}else{
			formFilled=false;
			document.getElementById('password_error').value="Entrer un mot de passe";
		}if(document.getElementById('passwordConfirmation').value==document.getElementById('password').value){
			dataIn['passwordConfirmation']=document.getElementById('passwordConfirmation').value;
			document.getElementById('passwordConfirmation_error').value="";
		}else{
			formFilled=false;
			document.getElementById('passwordConfirmation_error').value="Les deux mots de passe doivent être identiques.";
		}if(document.getElementById('question').value!=""){
			dataIn['question']=document.getElementById('question').value;
			document.getElementById('question_error').value="";
		}else{
			formFilled=false;
			document.getElementById('question_error').value="Entrer une question de sécurité.";
		}if(document.getElementById('answer').value!=""){
			dataIn['answer']=document.getElementById('answer').value;
			document.getElementById('answer_error').value="";
		}else{
			formFilled=false;
			document.getElementById('answer_error').value="Les deux mots de passe doivent être identiques.";
		}
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
projetSpec.home={
	init:function(e){
		let action='look_for_connection';
		let dataIn={};
		serverRequest(this,action,dataIn);
		let action='return_types';
		let dataIn={};
		serverRequest(this,action,dataIn);
	},
	signout:function(e){
		action="sign_out";
		dataIn={};
		serverRequest(this,action,dataIn);
	},
	deleteAccount:function(e){
		action="delete_account";
		dataIn={};
		serverRequest(this,action,dataIn);
	},
	loadEntry:function(e){
		console.log('Entry '+e.currentTarget.id);
	},
	addEntry:function(e){
		console.log('add');
	},
	modifyEntry:function(e){
		console.log('modify');
	},
	removeEntry:function(e){
		console.log('remove');
	},
	serverReturn:function(dataOut){
		if(dataOut.erreur){
			switch(dataOut.erreur){
				case "aucune connexion":
					console.log('no_connection');
					openNewWindow('signin/signin.xul','SignIn');
					break;
				case "signOut":
					self.close();
					openNewWindow('signin/signin.xul','SignIn');
					break;
			}
		}else if(dataOut.username){
			// console.log(dataOut.username);
			let action='return_entries';
			let dataIn={};
			dataIn['username']=dataOut.username;
			serverRequest(this,action,dataIn);
		}else if(dataOut.entries){
			for(var key in dataOut.entries){
				document.getElementById('liste_liens').innerHTML+='<hbox><button label="'+dataOut.entries[key].title+'" oncommand="projetSpec.home.loadEntry(event);" id="'+dataOut.entries[key].id_entry+'" class="entry"/><button label="supprimer" oncommand="projetSpec.home.removeEntry(event);" id="remove_'+dataOut.entries[key].id_entry+'" class="entry"/></hbox>';

			}
		}else if(dataOut.types){
			for(var key in dataOut.types){
				console.log(key);
				document.getElementById('type').innerHTML+='<html:option value="'+key+'">'+dataOut.types[key]+'</html:option>';
			}
		}
	},
};
window.addEventListener(
  "load", function(){projetSpec.home.init();}, false);
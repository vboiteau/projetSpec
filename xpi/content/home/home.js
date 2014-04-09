projetSpec.home={
	username:'',
	active:0,
	init:function(e){
		let action='look_for_connection';
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
		this.active=e.currentTarget.id;
		let action='load_entry';
		let dataIn={username:this.username,id:this.active};
		serverRequest(this,action,dataIn);
	},
	addEntry:function(e){
		var e = document.getElementById("type");
		var strUser = e.options[e.selectedIndex].value;
		let formValide=true;
		if(strUser=='1'){
			if(!document.getElementById('title').value){
				document.getElementById('title_error').value='Vous devez entrer un titre.';
				formValide=false;
			}else{
				document.getElementById('title_error').value='';
			}
			if(!document.getElementById('text').value){
				document.getElementById('text_error').value='Vous devez entrer un texte.';
				formValide=false;
			}
			else{
				document.getElementById('text_error').value='';
			}
			if(formValide){
				let action='add_entry';
				let dataIn={
					username:this.username,
					title:document.getElementById('title').value,
					text:document.getElementById('text').value,
					type:1
				};
				serverRequest(this,action,dataIn);
			}
		}
	},
	modifyEntry:function(e){
		var e = document.getElementById("type");
		var strUser = e.options[e.selectedIndex].value;
		let formValide=true;
		if(strUser=='1'){
			if(!document.getElementById('title').value){
				document.getElementById('title_error').value='Vous devez entrer un titre.';
				formValide=false;
			}else{
				document.getElementById('title_error').value='';
			}
			if(!document.getElementById('text').value){
				document.getElementById('text_error').value='Vous devez entrer un texte.';
				formValide=false;
			}
			else{
				document.getElementById('text_error').value='';
			}
			if(formValide){
				let action='modify_entry';
				let dataIn={
					id:this.active,
					username:this.username,
					title:document.getElementById('title').value,
					text:document.getElementById('text').value,
					type:1
				};
				serverRequest(this,action,dataIn);
			}
		}
	},
	removeEntry:function(e){
		var unId=e.currentTarget.id.substring(7);
		console.log(unId);
		let action='remove_entry';
		let dataIn={username:this.username,id:unId};
		serverRequest(this,action,dataIn);
	},
  returnEntries:function(){
    let action='return_entries';
    let dataIn={};
    serverRequest(this,action,dataIn);
  },
	serverReturn:function(dataOut){
    console.log(dataOut);
		if(dataOut.erreur){
			switch(dataOut.erreur){
				case "aucune connexion":
					openNewWindow('signin/signin.xul','SignIn');
					self.close();
					break;
				case "signOut":
					self.close();
					openNewWindow('signin/signin.xul','SignIn');
					break;
			}
		}
    if(dataOut.username){
			// console.log(dataOut.username);
			this.username=dataOut.username;
		}
    if(dataOut.entries){
			document.getElementById('liste_liens').innerHTML='';
			for(var key in dataOut.entries){
				document.getElementById('liste_liens').innerHTML+='<hbox><button label="'+dataOut.entries[key].title+'" oncommand="projetSpec.home.loadEntry(event);" id="'+dataOut.entries[key].id_entry+'" class="entry"/><button label="supprimer" oncommand="projetSpec.home.removeEntry(event);" id="remove_'+dataOut.entries[key].id_entry+'" class="entry"/></hbox>';
			}
		}
    if(dataOut.types){
			for(var key in dataOut.types){
				document.getElementById('type').innerHTML+='<html:option value="'+key+'">'+dataOut.types[key]+'</html:option>';
			}
      this.returnEntries();
		}
    if(dataOut.load){
			document.getElementById('title').value=dataOut.load.title;
			document.getElementById('text').value=dataOut.load.text;
			document.getElementById('type').options[dataOut.load.type];
		}
	},
};
window.addEventListener(
  "load", function(){projetSpec.home.init();}, false);
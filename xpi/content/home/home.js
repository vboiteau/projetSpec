projetSpec.home={
	username:'',
	active:0,
	init:function(e){
		let action='look_for_connection';
		let dataIn={};
		serverRequest(this,action,dataIn);
    document.getElementById('title').focus();
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
				document.getElementById('title').value='default value';
			}
			if(formValide){
				let action='add_entry';
				let dataIn={
					username:this.username,
					title:document.getElementById('title').value,
					text:document.getElementById('text').value,
					type:1,
          id_categorie:document.getElementById('idCat').value
				};
				serverRequest(this,action,dataIn);
			}
		}
	},
  chooseCat:function(e){
    let action='choose_cat';
    let dataIn={cat:document.getElementById('cat').value};
    serverRequest(this,action,dataIn);
  },
  chargeCat:function(e){
    if(document.getElementById('cat').value!=""){
      console.log('string');
      let action='search_cat';
      let dataIn={cat:document.getElementById('cat').value};
      serverRequest(this,action,dataIn);
    }else{
      console.log('no string');
      document.getElementById('search_result_cat').innerHTML='';
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
			if(formValide){
				let action='modify_entry';
				let dataIn={
					id:this.active,
					username:this.username,
					title:document.getElementById('title').value,
					text:document.getElementById('text').value,
					type:1,
          id_categorie:document.getElementById('idCat').value
				};
				serverRequest(this,action,dataIn);
			}
		}
	},
	removeEntry:function(e){
		var unId=e.currentTarget.id.substring(7);
		let action='remove_entry';
		let dataIn={username:this.username,id:unId};
		serverRequest(this,action,dataIn);
	},
  returnEntries:function(){
    let action='return_entries';
    let dataIn={};
    serverRequest(this,action,dataIn);
  },
  selectCat:function(e){
    document.getElementById('cat').value=e.currentTarget.label;
    document.getElementById('idCat').value=e.currentTarget.id;
    document.getElementById('search_result_cat').innerHTML="";
  },
  createLab:function(e){
    var entryId;
    if(document.getElementById('idEntry').value!=''){
      entryId=document.getElementById('idEntry').value;
    }
    let action='create_lab';
    let dataIn={labelName:document.getElementById('lab').value,entryId:entryId};
    document.getElementById('lab').value='';
    document.getElementById('lab_search').innerHTML='';
    serverRequest(this,action,dataIn);
  },
  supprimerLab:function(e,id){
    var entryId;
    if(document.getElementById('idEntry').value!=''){
      entryId=document.getElementById('idEntry').value;
    }
    let action='supprimer_lab';
    let dataIn={label:id,entryId:entryId};
    serverRequest(this,action,dataIn);
  },
  searchLab:function(e){
    document.getElementById('lab_search').innerHTML='';
    console.log(document.getElementById('lab_search').innerHTML);
    if(document.getElementById('lab').value!=""){
      document.getElementById('lab_search').innerHTML='<listitem id="new" onclick="projetSpec.home.createLab(event);">Create new label : «'+document.getElementById('lab').value+'»</listitem>';
      let action='search_lab';
      let dataIn={needle:document.getElementById('lab').value};
      serverRequest(this,action,dataIn);
    }else{
      document.getElementById('lab_search').innerHTML='';
    }
  },
	serverReturn:function(dataOut){
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
        case "noCat":
          document.getElementById('search_result_cat').innerHTML="";
          break;
			}
		}
    if(dataOut.username){
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
      if(!dataOut.load.id_categorie){
        document.getElementById('cat').value='';
        document.getElementById('idCat').value='';
      }else{
        document.getElementById('idCat').value=dataOut.load.id_categorie;
        let action='return_cat';
        let dataIn={id:dataOut.load.id_categorie};
        serverRequest(this,action,dataIn);
      }
      document.getElementById('search_result_cat').innerHTML="";
		}
    if(dataOut.categorie_name){
      document.getElementById('cat').value=dataOut.categorie_name;
    }
    if(dataOut.newCatId||dataOut.newCatId!=0){
      document.getElementById('idCat').value=dataOut.newCatId;
    }
    if(dataOut.id_entry){
      document.getElementById('idEntry').value=dataOut.id_entry;
    }else if(dataOut.load && dataOut.load.id_entry){
      document.getElementById('idEntry').value=dataOut.load.id_entry;
    }
    if(dataOut.search_result_cat){
      document.getElementById('search_result_cat').innerHTML="";
      var cpt=0;
      for(var key in dataOut.search_result_cat){
        if(cpt<5){
          console.log(cpt);
          console.log(dataOut.search_result_cat[key].id);
          console.log(dataOut.search_result_cat[key].name);
          document.getElementById('search_result_cat').innerHTML+='<button id="'+dataOut.search_result_cat[key].id+'" label="'+dataOut.search_result_cat[key].name+'" onclick="projetSpec.home.selectCat(event);"/>';
          cpt++;
        }
      }
    }
    if(dataOut.labels){
      document.getElementById('lab_list').innerHTML='';
      for(var key in dataOut.labels){
        document.getElementById('lab_list').innerHTML+='<html:span>   '+dataOut.labels[key]+' <html:span onclick="projetSpec.home.supprimerLab(event,'+key+');">X</html:span></html:span>';
      }
    }
	},
};
window.addEventListener(
  "load", function(){projetSpec.home.init();}, false);
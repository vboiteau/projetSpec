projetSpec.toolbar = {
  open:function(e){
    let action='look_for_connection'
    let dataIn={};
    serverRequest(this,action,dataIn);
  },
  serverReturn:function(dataOut){
        if(dataOut.erreur){
            openNewWindow("signin/signin.xul","Signin");
        }if(dataOut.username){
            openNewWindow("home/home.xul","Home");
        }
    },
};
function installButton()
{
	var id = "projetSpec-button";
	var toolbarId = "nav-bar";
	var toolbar = document.getElementById(toolbarId);
	//add the button at the end of the navigation toolbar	
	var newset=toolbar.currentSet+","+id;
    toolbar.setAttribute("currentset", newset);
    document.persist(toolbar.id, "currentset");
}
function firstRun(extensions) 
{
    var extension = extensions.get("projetSpec@vboirob.com"); 
    if (extension.firstRun){
    	installButton();
    }
}
if (Application.extensions){
    firstRun(Application.extensions);
}else{
    Application.getExtensions(firstRun);
}
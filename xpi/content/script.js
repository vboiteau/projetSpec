/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};

/**
 * Controls the browser overlay for the projetSpec extension.
 */
projetSpec.BrowserOverlay = {
  
  afficher : function(aEvent) {
    window.open("chrome://projetSpec/content/connexion/connexion.xul",
    "projetSpec-connexion",
    "chrome,centerscreen");
  }
};
function installButton()
{
  console.log('jeminstall');
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
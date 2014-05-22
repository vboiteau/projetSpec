/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};
var url='http://vboirob.com/projetSpec/extension/';
var content="chrome://projetSpec/content/";
function importScriptJs(addon,path){
  Services.scriptloader.loadSubScript(addon.getResourceURI(path).spec,self);
}
function serverRequest(caller,action,dataIn){
	let dataEnJSON=JSON.stringify(dataIn);
	let strPost=action+'='+dataEnJSON;
    let xmlRequest = Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"]
                  .createInstance(Components.interfaces.nsIXMLHttpRequest);
    var dataOut={};
    xmlRequest.open("POST", url, true);
    xmlRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    xmlRequest.onreadystatechange=function(){
     	if(xmlRequest.readyState!=4) return;
     	if(xmlRequest.status!=200&&xmlRequest.status!=304){
       		alert('HTTP error'+req.status);
     	}
        //console.log(xmlRequest.responseText);
     	  dataOut=JSON.parse(xmlRequest.responseText);
        caller.serverReturn(dataOut);
    }
    xmlRequest.send(strPost);
}
function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}
function openNewWindow(url,title){
    window.open(content+url,"projetSpec-"+title,"chrome,centerscreen");
}
function i(strInput){
    return document.getElementById(strInput).value;
}
function si(strInput,valeur){
    document.getElementById(strInput).value=valeur;
}
function $id(id){
  return document.getElementById(id);
}
function $attr(id,attr){
  return $(id).getAttribute(attr)
}
/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};
var url='http://vboirob.com/projetSpec/extension/index.php';
//var url='http://localhost/projetSpec/http/extension/index.php';
var content="chrome://projetSpec/content/";
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
        console.log(xmlRequest.responseText);
     	dataOut=JSON.parse(xmlRequest.responseText);
        caller.serverReturn(dataOut);
    }
    xmlRequest.send(strPost);
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
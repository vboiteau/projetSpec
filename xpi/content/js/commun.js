/**
 * projetSpec namespace.
 */
if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};
var server='http://vboirob.com/projetSpec/extension/index.php';
function serverRequest(action,dataIn){
	let dataEnJSON=JSON.stringify(dataIn);
	let strPost=action+'='+dataEnJSON;
	let url = server;
    let xmlRequest = Components.classes["@mozilla.org/xmlextras/xmlhttprequest;1"]
                  .createInstance(Components.interfaces.nsIXMLHttpRequest);
    var dataOut={};
    xmlRequest.open("POST", url, true);
    xmlRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    fn=this;
    xmlRequest.onreadystatechange=function(){
     	if(xmlRequest.readyState!=4) return;
     	if(xmlRequest.status!=200&&xmlRequest.status!=304){
       		alert('HTTP error'+req.status);
     	}
     	fn.dataOut=JSON.parse(xmlRequest.response);
    }
    xmlRequest.send(strPost);
    return dataOut; 
}
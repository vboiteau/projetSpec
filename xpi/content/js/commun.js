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
    let dataOut={};
    xmlRequest.open("POST", url, true);
    xmlRequest.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    xmlRequest.onreadystatechange=function(){
      if(xmlRequest.readyState!=4) return;
      if(xmlRequest.status!=200&&xmlRequest.status!=304){
        alert('HTTP error'+req.status);
      }
      alert(xmlRequest.response);
      //dataOut=JSON.parse(xmlRequest.response,true);
    }
    xmlRequest.send(strPost);
    //return dataOut;
}
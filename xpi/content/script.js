if ("undefined" == typeof(projetSpec)) {
  var projetSpec = {};
};
var url='http://vboirob.com/projetSpec/extension/';
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
        //console.log(xmlRequest.responseText);
        dataOut=JSON.parse(xmlRequest.responseText);
        projetSpec.toolbar.serverReturn(dataOut);
    }
    xmlRequest.send(strPost);
}
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
function openNewWindow(url,title){
    var ww=Components.classes["@mozilla.org/embedcomp/window-watcher;1"].getService(Components.interfaces.nsIWindowWatcher);
    var win=ww.openWindow(null,content+url,"projetSpec-"+title,"chrome,centerscreen",null);
}
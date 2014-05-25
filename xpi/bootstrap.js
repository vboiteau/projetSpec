const BUTTON_ID="projetSpec-button";
const{classes:Cc,interfaces:Ci,utils:Cu}=Components;
Cu.import("resource://gre/modules/Services.jsm");
Cu.import("resource://gre/modules/AddonManager.jsm");
var self = this,icon;
function include(addon,path){
  Services.scriptloader.loadSubScript(addon.getResourceURI(path).spec,self);
}
function $(node, childId){
  if(node.getElementByid){
    return node.getElementById(childId);
  }else{
    return node.querySelector('#'+childId);
  }
}
function loadIntoWindow(window){
  if(!window) return;
  let doc=window.document;
  let toolbox=$(doc,"navigator-toolbox");
  if(toolbox){
    let button=doc.createElement("toolbarbutton");
    button.setAttribute("id",BUTTON_ID);
    button.setAttribute("label", "EverNote clone");
    button.setAttribute("class", "toolbarbutton-projetSpec toolbarbutton-1 chromeclass-toolbar-additional");
    button.setAttribute("tooltiptext", "EverNote clone");
    button.style.listStyleImage="url("+icon+")";
    button.addEventListener("command",projetSpec.toolbar.open,false);
    toolbox.palette.appendChild(button);
    let {toolbarId,nextItemId}={toolbarId:"nav-bar",nextItemId:"home-button"},toolbar=toolbarId && $(doc,toolbarId);
    if(toolbar){
      let nextItem=$(doc,nextItemId);
      toolbar.insertItem(BUTTON_ID,nextItem &&
        nextItem.parentNode.id==toolbarId &&
        nextItem);
    }
  }
}
function unloadFromWindow(window){
  if(!window)return;
  let doc= window.document;
  let button= $(doc,BUTTON_ID)||$($(doc,"navigator-toolbox").palette,BUTTON_ID);
  button && button.parentNode.removeChild(button);
}
function eachWindow(callback){
  let enumerator=Services.wm.getEnumerator("navigator:browser");
  while(enumerator.hasMoreElements()){
    let win=enumerator.getNext();
    if(win.document.readyState=="complete"){
      callback(win);
    }else{
      runOnLoad(win,callback);
    }
  }
}
function runOnLoad(window,callback){
  window.addEventListener("load",function(){
    window.removeEventListener("load",arguments.callee,false);
    callback(window);
  },false);
}
function windowWatcher(subject,topic){
  if(topic==="downwindowopened"){
    runOnLoad(subject,loadIntoWindow);
  }
}
function startup(data,reason){
  //if(Services.vc.compare(Services.appinfo.platformVersion,"10.0")<0){
    Components.manager.addBootstrappedManifestLocation(data.installPath);
  //}
  AddonManager.getAddonByID(data.id,function(addon){
    include(addon,"content/script.js");
    icon=addon.getResourceURI("skin/icon.png").spec;
    eachWindow(loadIntoWindow);
    Services.ww.registerNotification(windowWatcher);
  });
}
function shutdown(data,reason){
  //if(Services.vc.compare(Services.appinfo.platformVersion,"10.0")<0){
    Components.manager.removeBootstrappedManifestLocation(data.installPath);
  //}
  Services.ww.registerNotification(windowWatcher);
  eachWindow(unloadFromWindow);
}
function install(data,reason){

}
function uninstall(data,reason){

}
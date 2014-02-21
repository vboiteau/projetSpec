projetSpec.home={
	init:function(e){
		let action='look_for_connection';
		let dataIn={};
		dataIn['chat']='bungee';
		serverRequest(action,dataIn);
	},
	signout:function(e){
		alert(server);
	},
	deleteAccount:function(e){
		alert(server);
	},
};
window.addEventListener(
  "load", function(){projetSpec.home.init();}, false);
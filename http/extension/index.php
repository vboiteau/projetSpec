<?php 
	session_start();
	require_once('../lib/inc/connexion.inc.php');
	$arrIn=array();
	$arrOut=array();
	if(isset($_POST)){
		foreach($_POST as $strAction=>$strData){
			$arrIn=json_decode($strData,true);
			if($strAction=='look_for_connection'){
				lookForConnection();
			}
		}
	}
	function lookForConnection(){
		if(isset($_SESSION['projSpec']['user'])){
			$GLOBALS['arrOut']=array("id_user"=>$_SESSION['projSpec']['user']['id'],"username"=>$_SESSION['projSpec']['user']['username']);
		}else{
			$GLOBALS['arrOut']['erreur']='aucune connexion';
		}
		encode();
	}
	function encode(){
		$strJson=json_encode($GLOBALS['arrOut']);
		echo $strJson;
	}
?>
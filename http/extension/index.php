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
			if($strAction=='check_user'){
				//echo($strData);
				checkUser();
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
	function checkUser(){
		$username=$GLOBALS['arrIn']['username'];
		$password=$GLOBALS['arrIn']['password'];
		//echo "{'username':$username,'password':$password}";
		$strQuery="SELECT id_user FROM t_user WHERE username=? AND password=?";
		$result=$GLOBALS['objConnMySQLi']->prepare($strQuery);
		$result->bind_param('ss',$username,$password);
		$result->execute();
		$result->store_result();
		$num_rows=$result->num_rows();
		$result->close();
		//echo "{\"num_rows\":$num_rows}";
		if($num_rows==1){
			$_SESSION['projetSpec']['user']['username']=$username;
			$GLOBALS['arrOut']['success']='success';
			$GLOBALS['arrOut']['user']= $username;
		}else{
			$GLOBALS['arrOut']['erreur']='error';
		}
		encode();
	}
	function encode(){
		$strJson=json_encode($GLOBALS['arrOut']);
		echo $strJson;
	}
	$objConnMySQLi->close();
?>
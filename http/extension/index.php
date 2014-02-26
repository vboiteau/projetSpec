<?php 
	session_start();
	require_once('../lib/inc/connexion.inc.php');
	$arrIn=array();
	$arrOut=array();
	if(isset($_POST)){
		foreach($_POST as $strAction=>$strData){
			$arrIn=json_decode($strData,true);
			switch($strAction){
				case 'look_for_connection':
					lookForConnection();
					break;
				case 'check_user':
					checkUser();
					break;
				case 'sign_out':
					signOut();
					break;
				case 'create_account':
					createAccount();
					break;
			}
		}
	}
	function lookForConnection(){
		if(isset($_SESSION['projetSpec']['user'])){
			$GLOBALS['arrOut']=array("username"=>$_SESSION['projetSpec']['user']['username']);
		}else{
			$GLOBALS['arrOut']['erreur']='aucune connexion';
		}
		encode();
	}
	function createAccount(){
		$username=$GLOBALS['arrIn']['username'];
		$password=$GLOBALS['arrIn']['password'];
		$question=$GLOBALS['arrIn']['question'];
		$answer=$GLOBALS['arrIn']['answer'];
		$date=date('Y-m-d H:i:s');
		$query='INSERT INTO t_user (username, password, question, answer,creation_date) VALUES (?,?,?,?,?)';
		$result=$GLOBALS['objConnMySQLi']->prepare($query);
		$result->bind_param('sssss',$username,$password,$question,$answer,$date);
		$result->execute();
		$result->close();
		$_SESSION['projetSpec']['user']=array('username'=>$username);
		$GLOBALS["arrOut"]['success']='success';
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
			$_SESSION['projetSpec']['user']=array('username'=>$username);
			$GLOBALS['arrOut']['success']='success';
			$GLOBALS['arrOut']['user']= $username;
		}else{
			$GLOBALS['arrOut']['erreur']='error';
		}
		encode();
	}
	function signOut(){
		unset($_SESSION['projetSpec']);
		$GLOBALS['arrOut']['erreur']='signOut';
		encode();
	}
	function encode(){
		$strJson=json_encode($GLOBALS['arrOut']);
		echo $strJson;
	}
	$objConnMySQLi->close();
?>
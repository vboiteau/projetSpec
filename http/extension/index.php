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
				case 'delete_account':
					deleteAccount();
					break;
				case 'check_username':
					checkUsername();
					break;
				case 'return_question':
					returnQuestion();
					break;
				case 'return_password';
					returnPassword();
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
	function returnPassword(){
		$username=$_SESSION['projetSpec']['recover']['username_exist'];
		$question=$_SESSION['projetSpec']['recover']['question'];
		$answer=$GLOBALS['arrIn']['answer'];
		$query='SELECT password FROM t_user WHERE username=? AND question=? AND answer=?';
		$result=$GLOBALS['objConnMySQLi']->prepare($query);
		$result->bind_param('sss',$username,$question,$answer);
		$result->execute();
		$result->bind_result($password);
		$arrPass=array();
		while($result->fetch()){
			 $arrPass[]=$password;
		}
		if(count($arrPass)==1){
			$GLOBALS['arrOut']['password']=$arrPass[0];
			unset($_SESSION['projetSpec']);
		}else{
			$GLOBALS['arrOut']['errPassword']='erreur';
		}
		encode();
		$result->close();
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
	function deleteAccount(){
		$username=$_SESSION['projetSpec']['user']['username'];
		$query='DELETE FROM t_user WHERE username=?';
		$result=$GLOBALS['objConnMySQLi']->prepare($query);
		$result->bind_param('s',$username);
		$result->execute();
		$result->close();
		signOut();
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
	function checkUsername(){
		$username=$GLOBALS['arrIn']['username'];
		//echo "{'username':$username,'password':$password}";
		$strQuery="SELECT question FROM t_user WHERE username=?";
		$result=$GLOBALS['objConnMySQLi']->prepare($strQuery);
		$result->bind_param('s',$username);
		$result->execute();
		$result->bind_result($question);
		$arrQuestion=array();
		while($result->fetch()){
			$arrQuestion[]=$question;
		}
		
		$result->close();
		//echo "{\"num_rows\":$num_rows}";
		if(count($arrQuestion)==1){
			$_SESSION['projetSpec']['recover']['username_exist']=$username;
			$_SESSION['projetSpec']['recover']['question']=$question;
			$GLOBALS['arrOut']['unexist']=true;
		}else{
			$GLOBALS['arrOut']['errUnexist']='error';
		}
		encode();
	}
	function returnQuestion(){
		$GLOBALS['arrOut']['question']=$_SESSION['projetSpec']['recover']['question'];
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
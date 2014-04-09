<?php
	session_start();
	require_once('../lib/conf.php');
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
				case 'return_entries';
					returnEntries();
					break;
				case 'return_types':
					returnTypes();
					break;
				case 'remove_entry':
					removeEntry();
					break;
				case 'add_entry':
					addEntry();
					break;
				case 'load_entry':
					loadEntry();
					break;
				case 'modify_entry':
					modifyEntry();
					break;
			}
		}
	}
	function removeEntry(){
		$id=$GLOBALS['arrIn']['id'];
		$query="DELETE FROM t_entry WHERE id_entry=$id";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->execute();
		$result->close();
		returnEntries();
	}
	function lookForConnection(){
		if(isset($_SESSION['projetSpec']['user']['username'])){
      $GLOBALS['arrOut']['username']=$_SESSION['projetSpec']['user']['username'];
			//$GLOBALS['arrIn']['username']=$_SESSION['projetSpec']['user']['username']);
      returnTypes();
      //returnEntries();
		}else{
			$GLOBALS['arrOut']['erreur']='aucune connexion';
      encode();
		}
	}
	function returnPassword(){
		$username=$_SESSION['projetSpec']['recover']['username_exist'];
		$question=$_SESSION['projetSpec']['recover']['question'];
		$answer=$GLOBALS['arrIn']['answer'];
		$query='SELECT password FROM t_user WHERE username=? AND question=? AND answer=?';
		$result=$GLOBALS['mysqli']->prepare($query);
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
		$date=returnDate();
		$query='INSERT INTO t_user (username, password, question, answer,creation_date) VALUES (?,?,?,?,?)';
		$result=$GLOBALS['mysqli']->prepare($query);
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
		$result=$GLOBALS['mysqli']->prepare($query);
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
		$result=$GLOBALS['mysqli']->prepare($strQuery);
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
	function checkUsername(){
		$username=$GLOBALS['arrIn']['username'];
		//echo "{'username':$username,'password':$password}";
		$strQuery="SELECT question FROM t_user WHERE username=?";
		$result=$GLOBALS['mysqli']->prepare($strQuery);
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
	function returnTypes(){
		$query='SELECT id_type, type_name FROM t_type ORDER BY type_name ASC';
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->execute();
		$result->bind_result($id,$name);
		while($result->fetch()){
			$GLOBALS['arrOut']['types'][$id]=$name;
		}
		$result->close();
    encode();
	}
	function signOut(){
		unset($_SESSION['projetSpec']);
		$GLOBALS['arrOut']['erreur']='signOut';
		encode();
	}
	function returnEntries(){
		$username=$_SESSION['projetSpec']['user']['username'];
		$query='SELECT t_entry.id_entry, t_entry.title, t_entry.creation_date, t_entry.last_modification_date, t_type.type_name FROM t_entry INNER JOIN t_type ON t_type.id_type=t_entry.id_type INNER JOIN t_user ON t_user.id_user=t_entry.id_user WHERE t_user.username=? ORDER BY t_entry.last_modification_date DESC';
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->bind_param('s',$username);
		$result->execute();
		$result->bind_result($idEntry,$title,$creationDate,$lastModificationDate,$typeName);
		while($result->fetch()){
			$GLOBALS['arrOut']['entries'][]=array('id_entry'=>$idEntry,'title'=>$title,'creation_date'=>$creationDate,'last_modification_date'=>$lastModificationDate,'type_name'=>$typeName);
		}
		$result->close();
		encode();
	}
	function addEntry(){
		$id=returnId();
		$title=$GLOBALS['arrIn']['title'];
		$text=$GLOBALS['arrIn']['text'];
		$type=$GLOBALS['arrIn']['type'];
    $date=returnDate();
		$query="INSERT INTO t_entry (title,creation_date,last_modification_date,entry_text,id_type,id_user)VALUES(?,?,?,?,?,$id)";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->bind_param('ssssi',$title,$date,$date,$text,$type);
		$result->execute();
		$result->close();
		returnEntries();
	}
	function loadEntry(){
		$id_user=returnId();
		$id_entry=(int)$GLOBALS['arrIn']['id'];
		$query="SELECT title,entry_text,id_type FROM t_entry WHERE id_entry=? AND id_user=?";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->bind_param('ii',$id_entry,$id_user);
		$result->execute();
		$result->bind_result($title,$text,$type);
		$arrLoad=array();
		while($result->fetch()){
			$arrLoad['title']=$title;
			$arrLoad['text']=$text;
			$arrLoad['type']=$type;
		}
		$GLOBALS['arrOut']['load']=$arrLoad;
		$result->close();
		encode();
	}
	function modifyEntry(){
		$id_user=returnId();
		$id_entry=(int)$GLOBALS['arrIn']['id'];
		$title=$GLOBALS['arrIn']['title'];
		$text=$GLOBALS['arrIn']['text'];
		$type=$GLOBALS['arrIn']['type'];
    $date=returnDate();
		$query="UPDATE t_entry SET title=?,last_modification_date=?,entry_text=?,id_type=? WHERE id_user=? AND id_entry=?";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->bind_param('ssssii',$title,$date,$text,$type,$id_user,$id_entry);
		$result->execute();
		$result->close();
		returnEntries();
	}
	function returnId(){
		$username=$GLOBALS['arrIn']['username'];
		$query="SELECT id_user FROM t_user WHERE username='$username'";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->execute();
		$result->bind_result($unId);
		while($result->fetch()){
			$id=$unId;
		}
		$result->close();
		return $id;
	}
	function returnDate(){
		return date('Y-m-d H:i:s');
	}
	function encode(){
		$strJson=json_encode($GLOBALS['arrOut']);
		echo $strJson;
	}
	$mysqli->close();
?>
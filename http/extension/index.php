<?php 
	session_start();
	require_once('../lib/inc/connexion.inc.php');
	$arrIn=array();
	$arrOut=array();
	if(isset($_SESSION["projetSpec"])){
		
	}
	if(isset($_POST['check'])){
		if(isset($_SESSION["projetSpec"]["username"])){
			echo "true";
		}else{
			echo "false";
		}
	}
	if(isset($_POST['deconnecter'])){
		unset($_SESSION['projetSpec']['username']);
	}
	if(isset($_POST['inscription'])){
		$arrIn=json_decode($_POST['inscription'],true);
		foreach($arrIn as $strKey=>$strValue){
			if(strlen($strValue)==0){
				$arrOut[$strKey."_error"]="Le champs est obligatoires. / The field most be filled.";
			}
		}
		if(!preg_match('#^[-a-z-A-Z0-9]{8,32}$#',$arrIn['username'])){
			$arrOut['username_error'].="Le champs doit être d'au moins 8 caractères et de 32 caractères maximum.";
		}
		if(!preg_match('#^[-a-z-A-Z0-9]{8,32}$#',$arrIn['password'])){
			$arrOut['password_error'].="Le champs doit être d'au moins 8 caractères et de 32 caractères maximum.";
		}
		if($arrIn['password']!=$arrIn['passwordConfirm']){
			$arrOut['passwordConfirm_error']="La confirmation doit être identique au l'entrée précédente.";
		}
		if(!preg_match("#^[-a-z-A-Z0-9'Ç-Üá-Ñ\s?]{2,128}$#",$arrIn['question'])){
			$arrOut['question_error'].="La question ne doit pas dépasser 128 caractères";
		}
		if(!preg_match("#^[-a-z-A-Z0-9'Ç-Üá-Ñ\s?]{2,32}$#",$arrIn['answer'])){
			$arrOut['answer_error'].="Le champs doit être de 32 caractères maximum.";
		}
		if(count($arrOut)==0){
			$date=new DateTime();
			$date=$date->format('Y-m-d H:i:s');
			$strQuery="INSERT INTO t_user(username, password, question, answer, creation_date) VALUES (?,?,?,?,'$date')";
			$result=$objConnMySQLi->prepare($strQuery);
			$result->bind_param('ssss',$arrIn['username'],$arrIn['password'],$arrIn['question'],$arrIn['answer']);
			$result->execute();
			$result->close();
			$_SESSION["projetSpec"]["username"]=$arrIn["username"];
			$arrOut['success']="L'entrée a été ajoutée dans la base de données.";
		}
		$strOut=json_encode($arrOut);
		echo $strOut;
	}
	if(isset($_POST['connexion'])){
		$arrIn=json_decode($_POST['connexion'],true);
		foreach($arrIn as $strKey=>$strValue){
			if(strlen($strValue)==0){
				$arrOut[$strKey."_error"]="Le champs est obligatoires. / The field most be filled.";
			}
		}
		if(!preg_match('#^[-a-z-A-Z0-9]{8,32}$#',$arrIn['username'])){
			$arrOut['username_error'].="Le champs doit être d'au moins 8 caractères et de 32 caractères maximum.";
		}
		if(!preg_match('#^[-a-z-A-Z0-9]{8,32}$#',$arrIn['password'])){
			$arrOut['password_error'].="Le champs doit être d'au moins 8 caractères et de 32 caractères maximum.";
		}
		if(count($arrOut)==0){
			$_SESSION["projetSpec"]["username"]=$arrIn["username"];
			$strQuery="SELECT password FROM t_user WHERE username=?";
			$result=$objConnMySQLi->prepare($strQuery);
			$result->bind_param('s',$arrIn["username"]);
			$result->execute();
			$result->bind_result($password);
			while($result->fetch()){
				if($password==$arrIn['password']){
					$arrOut['success']="L'entrée a été ajoutée dans la base de données.";
				}
			}	
		}
		$strOut=json_encode($arrOut);
		echo $strOut;
	}
?>
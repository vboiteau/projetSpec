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
        case 'choose_cat':
          chooseCat();
          break;
        case 'return_cat';
          returnCat();
          break;
        case 'search_cat':
          searchCat();
          break;
        case 'create_lab':
          createLab();
          break;
        case 'supprimer_lab':
          supprimerLab();
          break;
        case 'assign_cat':
          assignCat($arrIn['id_cat']);
          break;
        case 'search_lab':
          searchLab($arrIn['needle'],$arrIn['id_entry']);
          break;
        case 'select_lab':
          selectLab($arrIn['id_label'],$arrIn['id_entry']);
          break;
			}
		}
	}
  function selectLab($id_label,$id_entry){
    var_dump($id_label);
    var_dump($id_entry);
    $query="INSERT INTO t_entry_label(id_entry,id_label) VALUES(?,?)";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('ii',$id_entry,$id_label);
    $result->execute();
    $result->close();
    returnLabel();
    encode();
  }
  function createIdBlacklist($id_entry){
    $query="SELECT id_label FROM t_entry_label WHERE id_entry=?";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('i',$id_entry);
    $result->execute();
    $result->bind_result($id_label);
    $blacklist=array();
    while ($result->fetch()) {
      array_push($blacklist, $id_label);
    }
    $result->close();
    return $blacklist;
  }
  function searchLab($needle,$id_entry){
    $blacklist=createIdBlacklist($id_entry);
    $needleExist=false;
    $needleSQL="%$needle%";
    $query="SELECT id_label, label_name FROM t_label WHERE label_name LIKE ? ";
    foreach($blacklist as $key=>$id){
      $query.="AND id_label<>$id ";
    }
    $query.=" LIMIT 0,9";
    $result=$GLOBALS["mysqli"]->prepare($query);
    $result->bind_param('s',$needleSQL);
    $result->execute();
    $result->bind_result($id_label,$label_name);
    while($result->fetch()){
      if($label_name==$needle){
        $needleExist=true;
      }
      $GLOBALS['arrOut']['label_search_results'][$id_label]=$label_name;
    }
    if(!$needleExist/*&&!$needleInBlackList*/){
      $GLOBALS['arrOut']['label_search_results']['new']=$needle;
    }
    $result->close();
    encode();
  }
  function createLab(){
    $label_name=$GLOBALS['arrIn']['labelName'];
    if(isset($GLOBALS['arrIn']['entryId'])){
      $entry_id=$GLOBALS['arrIn']['entryId'];
    }
    $query="INSERT INTO t_label(label_name) VALUES(?)";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('s',$label_name);
    $result->execute();
    $id_label=$result->insert_id;
    $result->close();
    if(isset($entry_id)){
      $query="INSERT INTO t_entry_label(id_entry,id_label) VALUES(?,?)";
      $result=$GLOBALS['mysqli']->prepare($query);
      $result->bind_param('ii',$entry_id,$id_label);
      $result->execute();
      $result->close();
    }else{
      $_SESSION['projetSpec']['labs'][$id_label]=$label_name;
    }
    returnLabel();
    encode();
  }
  function assignCat($id_cat){
    $id_entry=$GLOBALS['arrIn']['id_entry'];
    $query="UPDATE t_entry SET id_categorie=? WHERE id_entry=?";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('ii',$id_cat,$id_entry);
    $result->execute();
    $result->close();
    $GLOBALS["arrOut"]["assignCatSuccess"]=true;
  }
  function supprimerLab(){
    $label=$GLOBALS['arrIn']['label'];
    if(isset($GLOBALS['arrIn']['entryId'])){
      $entry=$GLOBALS['arrIn']['entryId'];
      $query='DELETE FROM t_entry_label WHERE id_entry=? AND id_label=?';
      $result=$GLOBALS['mysqli']->prepare($query);
      $result->bind_param('ii',$entry,$label);
      $result->execute();
      $result->close();
    }else{
      unset($_SESSION['projetSpec']['labs'][$label]);
    }
    returnLabel();
    encode();
  }
  function returnCat($id=false){
    if(!$id){
      $idCat=$GLOBALS['arrIn']['id'];
    }else{
      $idCat=$id;
    }
    $query="SELECT categorie_name FROM t_categorie WHERE id_categorie=?";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('i',$idCat);
    $result->execute();
    $result->bind_result($catName);
    while($result->fetch()){
      $GLOBALS['arrOut']['categorie_name']=$catName;
    }
    $result->close();
    if(!$id){
      encode();
    }
  }
  function chooseCat(){
    $cat=$GLOBALS['arrIn']['cat'];
    $query="INSERT INTO t_categorie(categorie_name) VALUES (?)";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('s',$cat);
    $result->execute();
    $GLOBALS['arrOut']['newCatId']=$result->insert_id;
    $result->close();
    if($GLOBALS['arrOut']['newCatId']){
      assignCat($GLOBALS['arrOut']['newCatId']);
      encode();
    }else{
      returnIdCat();
    }
  }
  function returnIdCat(){
    $cat=$GLOBALS['arrIn']['cat'];
    $query="SELECT id_categorie FROM t_categorie WHERE categorie_name=?";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('s',$cat);
    $result->execute();
    $result->bind_result($id);
    while($result->fetch()){
      $leId=$id;
    }
    $result->close();
    assignCat($leId);
    encode();
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
		$idCat=$GLOBALS['arrIn']['id_categorie'];
    $idCat=$idCat=="undefined"?null:$idCat;
    $date=returnDate();
		$query="INSERT INTO t_entry (title,creation_date,last_modification_date,entry_text,id_type,id_user,id_categorie)VALUES(?,?,?,?,?,?,?)";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->bind_param('ssssiii',$title,$date,$date,$text,$type,$id,$idCat);
		$result->execute();
    $GLOBALS['arrOut']['id_entry']=$result->insert_id;
    $result->close();
		returnEntries();
	}
	function loadEntry($calld=false){
    if(isset($_SESSION['projetSpec']['labs'])){
      unset($_SESSION['projetSpec']['labs']);
    }
		$id_user=returnId();
		$id_entry=(int)$GLOBALS['arrIn']['id'];
		$query="SELECT id_entry,title,entry_text,id_type,id_categorie FROM t_entry WHERE id_entry=? AND id_user=?";
		$result=$GLOBALS['mysqli']->prepare($query);
		$result->bind_param('ii',$id_entry,$id_user);
		$result->execute();
		$result->bind_result($id,$title,$text,$type, $idCat);
		$arrLoad=array();
		while($result->fetch()){
      $arrLoad['id_entry']=$id;
			$arrLoad['title']=$title;
			$arrLoad['text']=$text;
			$arrLoad['type']=$type;
      $arrLoad['id_categorie']=$idCat;
		}
		$GLOBALS['arrOut']['load']=$arrLoad;
		$result->close();
    //returnCat($arrLoad['id_categorie']);
    returnLabel();
    //echo 'return label end';
    encode();
	}
  function returnLabel(){
    if(isset($GLOBALS['arrIn']['id'])||isset($GLOBALS['arrIn']['entryId'])||isset($GLOBALS['arrIn']['id_entry'])){
      if(isset($GLOBALS['arrIn']['id'])){
        $id_entry=(int)$GLOBALS['arrIn']['id'];
      }else if(isset($GLOBALS['arrIn']['entryId'])){
        $id_entry=$GLOBALS['arrIn']['entryId'];
      }else{
        $id_entry=$GLOBALS['arrIn']['id_entry'];
      }
      $query="SELECT t_label.id_label, label_name FROM t_label INNER JOIN t_entry_label ON t_entry_label.id_label=t_label.id_label WHERE t_entry_label.id_entry=?";
      $result=$GLOBALS['mysqli']->prepare($query);
      $result->bind_param('i',$id_entry);
      $result->execute();
      $result->bind_result($id_label,$label_name);
      $arrLabels=array();
      while($result->fetch()){
        $arrLabels[$id_label]=$label_name;
      }
      $result->close();
      $GLOBALS['arrOut']['labels']=$arrLabels;
    }else{
      foreach($_SESSION['projetSpec']['labs'] as $id_label=>$label_name){
        $GLOBALS['arrOut']['labels']=$_SESSION['projetSpec']['labs'];
      }
    }
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
  function searchCat(){
    $str='%'.$GLOBALS['arrIn']['cat'].'%';
    $query="SELECT id_categorie,categorie_name FROM t_categorie WHERE categorie_name LIKE ?";
    $result=$GLOBALS['mysqli']->prepare($query);
    $result->bind_param('s',$str);
    $result->execute();
    $result->bind_result($id,$name);
    while($result->fetch()){
      $GLOBALS['arrOut']['search_result_cat'][$id]=array('id'=>$id,'name'=>$name);
    }
    $result->close();
    if(!isset($GLOBALS['arrOut']['search_result_cat'])){
      $GLOBALS['arrOut']['erreur']='noCat';
    }
    encode();
  }
	function encode(){
		$strJson=json_encode($GLOBALS['arrOut']);
		echo $strJson;
	}
	$mysqli->close();
?>
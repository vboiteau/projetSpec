<?php
  $serverName='localhost';
  $username='vboiteau';
  $password='t[wh6qKhcuQ3';
  $database='vboiteau_dbspec';
  $mysqli=new mysqli($serverName,$username,$password,$database);
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }

  // les lignes suivantes forcent mysql à servir les données en utf8 (pour afficher les accents correctement)
  $mysqli->query("SET CHARACTER SET utf8");
  $mysqli->query("SET NAMES utf8");

  error_reporting(E_ALL);
  ini_set('display_errors', true);

  //Set fuseau horaire
  date_default_timezone_set('America/Montreal');

  // FIN paramètres du site

  function escape_data ($data) {
      // Besoin de la connexion:
      $GLOBALS["mysqli"];
      // Verifier Magic Quotes.
      if (ini_get('magic_quotes_gpc')) {
          $data = stripslashes($data);
      }
      // Trim et escape:
      return mysqli_real_escape_string($GLOBALS["mysqli"], trim($data));
  }
?>
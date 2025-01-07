<?php

function connectDB()
{
  $host = 'HOSTNAME';
  $user = 'USER';
  $password = 'PASSWORD';
  $database = 'DATABASE';
  $conn = new mysqli($host, $user, $password, $database);

  if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
  } else {
    return $conn;
  }
}

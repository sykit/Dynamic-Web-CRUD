<?php
include 'dbcon.php';

if(isset($_POST['login']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";
  $sql = $DB_con->prepare($query);
  $sql->execute();

  $runquery = $sql->fetch(PDO::FETCH_ASSOC);

  if($sql->rowCount() > 0){
   session_start();
   $_SESSION['username'] = $username;
   header("Location: index.php");
  } else {
   echo '<h1>
  Username atau Kata Sandi Salah!</h1>
  ';
  }
}


?>

<!DOCTYPE html>
<html>
<head>
 <title>Login</title>
 <style>
 .form {
  margin: 10% 0 0 25%;
  float: left;
  width: 60%;
 }
 .input {
  padding: 1%;
  color: #777777;
  font-size: 14pt;
  float: left;
  width: 80%;
  margin-bottom: 1%;
 }
 .submit {
  padding: 1%;
  color: #fff;
  background-color: #11b022;
  font-size: 14pt;
  font-family: Times New Roman;
  float: left;
  width: 25%;
 }
 .welcome {
  color: #11bb22;
  font-size: 20pt;
  font-weight: 900;
  font-family: Centaur;
 }


 </style>
</head>
<body>

<div class="form">
<span class="welcome">Harap Login Terlebih Dahulu !</span>
 <form method="post">
  <input class="input" type="username" name="username" placeholder="Username">
  <input class="input" type="password" name="password" placeholder="Password">
  <input class="submit" type="submit" name="login">
 </form>
</div>
</body>
</html>

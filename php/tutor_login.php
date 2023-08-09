<?php

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login tutor</title>
    <link rel="stylesheet" type="text/css" href="../css/in_pages.css">
</head>
<body>
    <div class="loginbox-teacher">
        <h1>Login Docenti</h1>
        <div id='in'>
        <form action="tutor_login.php" method="post">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="submit" value="Login">
            <div id='lnk'>
                <a href="teacher_registration.php"><br>Non hai ancora un account?</a>
            </div>
            <div id='back'>
            <a href="../index.php"><br>Torna alla pagina principale!</a>
            
        </div>
        </form>
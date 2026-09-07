<!DOCTYPE html>

<?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            setcookie('userID', 1);
        }
?>

<html lang="de">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>

<body>
    <h1 class="titel">Steam - Streaming Series Memory Steam</h1><br>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $url = "localhost:5000/user";
            $client = curl_init($url);
	        curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
	        $response = curl_exec($client);
	        $result = json_decode($response, true);

            $result = json_decode($response, true);
            foreach ($result as $user) {
                if ($user[1] == $username && $user[2] == $password) {
                    setcookie('userID', $user[0]);
                    header("Location: list.php");
                }
            }
            echo '<div class="meldung">Username oder Passwort falsch</div>';
        }
        
    ?>

    <form action="login.php" method="POST">

        <h1 class="gerahmt">Benutzername: <input type="text" name="username"></h1><br>
        <h1 class="gerahmt">Passwort: <input type="password" name="password"></h1><br>
        <br>
        <input type="submit" value="Login">
        <br>
        <br>
        <input type="reset" value="Clear">
    </form>
</body>
</html>


<!DOCTYPE html>

<?php
    setcookie('userID', $_COOKIE['userID']);
?>
    
<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="list.css">
    </head>

    <body>

        <form class="filter" action="list.php" method="POST">
            <input type="hidden" name="action" value="filter">
            <b class="filterTitel" >Titel</b>
            <input type="text" name="title">
            <b class="filterGenre" >Genre</b>
            <input type="text" name="genre">
            <b class="filterPlattfom" >Plattform</b>
            <input type="text" name="plattform">
            <button class="filtern">filter</button>
        </form>

        <table class="liste">
            <tr>
                <th class="listeTitel">Titel</th>
                <th class="listeStaffeln">Anzahl der Staffeln</th>
                <th class="listeGenre">Genre</th>
                <th class="listePlattform">Plattform</th>
                <th class="listeBewertung">Bewertung</th>
                <th class="neuerEintrag">
                    <a href="create.php" method="GET">
                        <button>+</button>
                    </a>
                </th>
            </tr>
            <?php
                $title = "";
                $genre = "";
                $plattform = "";
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if ($_POST['action'] == 'filter') {
                        $title = $_POST['title'];
                        $genre = $_POST['genre'];
                        $plattform = $_POST['plattform'];
                    } elseif ($_POST['action'] == 'delete') {
                        $url = "http://localhost:5000/userseries/delete/".$_POST['deleteSeriesID']."/".$_COOKIE['userID'];
                        $client = curl_init($url);
                        curl_setopt($client, CURLOPT_CUSTOMREQUEST, "DELETE");
                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($client);
                        curl_close($client);

                        $url = "localhost:5000/userseries/".$_POST['deleteSeriesID'];
                        $client = curl_init($url);
                        curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                        $response = curl_exec($client);
                        $result = json_decode($response, true);

                        if (empty($result)) {
                            $url = "localhost:5000/series/delete/".$_POST['deleteSeriesID'];
                            $client = curl_init($url);
                            curl_setopt($client, CURLOPT_CUSTOMREQUEST, "DELETE");
                            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                            $response = curl_exec($client);
                            curl_close($client);
                        }
                    }
                }
                $url = "localhost:5000/series/".$_COOKIE['userID'];
                $client = curl_init($url);
                curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($client);
                $result = json_decode($response, true);

                foreach ($result as $show) {
                    if (strpos(strtolower($show[1]), strtolower($title)) !== false &&
                        strpos(strtolower($show[3]), strtolower($genre)) !== false &&
                        strpos(strtolower($show[4]), strtolower($plattform)) !== false) {
                        echo "<tr><td>".$show[1]."</td>";
                        echo "<td>".$show[2]."</td>";
                        echo "<td>".$show[3]."</td>";
                        echo "<td>".$show[4]."</td>";
                        echo "<td>".$show[7]."</td>";
                        echo '<td class="löschen">
                        <form action="list.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="deleteSeriesID" value="'.$show[0].'">
                            <button type="submit">-</button>
                        </form>
                    </td></tr>';
                    }
                }
            ?>
        </table>
        <a href="login.php" method="GET">
                <button class="logOut">Log out</button>
        </a>

    </body>
</html>

<!DOCTYPE html>

<?php
    setcookie('userID', $_COOKIE['userID']);
?>

<html lang="de">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="create.css">
        <title>Create Series</title>
    </head>

    <body>
        <?php
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                $userID = $_COOKIE['userID'];
            }
            elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

                $title = $_POST['title'];
                $seasons = $_POST['seasons'];
                $genre = $_POST['genre'];
                $plattform = $_POST['plattform'];
                $rating = $_POST['rating'];
                if ($rating > 0 && $rating < 6) {

                    $userID = $_COOKIE['userID'];

                    $seriesID = -1;

                    $url = "localhost:5000/series";
                    $client = curl_init($url);
                    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
                    $response = curl_exec($client);
                    $result = json_decode($response, true);

                    foreach ($result as $show) {
                        if (strcmp($show[1], $title) === 0 &&
                        strcmp($show[2], $seasons) === 0 &&
                        strcmp($show[3], $genre) === 0 &&
                        strcmp($show[4], $plattform) === 0) {
                            $seriesID = $show[0];
                            break;
                        }
                    }

                    if ($seriesID === -1) {

                        $url = "localhost:5000/series/add";
                        $client = curl_init($url);
                        curl_setopt($client, CURLOPT_POST, true);
                        $post = [
                            'title' => $title,
                            'seasons' => $seasons,
                            'genre' => $genre,
                            'plattform' => $plattform
                        ];
                        curl_setopt($client, CURLOPT_POSTFIELDS, http_build_query($post));
                        curl_exec($client);

                        $url = "localhost:5000/series";
                        $client = curl_init($url);
                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($client);
                        $result = json_decode($response, true);

                        foreach ($result as $show) {

                            if (strcmp($show[1], $title) === 0 &&
                                strcmp($show[2], $seasons) === 0 &&
                                strcmp($show[3], $genre) === 0 &&
                                strcmp($show[4], $plattform) === 0) {

                                $seriesID = $show[0];
                                break;
                            }
                        }
                    }          

                    $url = "localhost:5000/userseries/add/" . $seriesID . "/" . $userID . "/" . $rating;
                    $client = curl_init($url);
                    curl_setopt($client, CURLOPT_POST, true);
                    curl_exec($client);

                    header("Location: list.php");
                } else {
                    echo "Rating muss eine Zahl von 1-5 betragen";
                }
            }
        ?>

<h1 class="titel">Neue Serie anlegen</h1>

<form action="create.php" method="POST">
    <div class="form-row">
        <label for="title">Titel:</label>
        <input type="text" name="title" id="title" required>
    </div>
    <div class="form-row">
        <label for="seasons">Anzahl der Staffeln:</label>
        <input type="number" min="0" name="seasons" id="seasons" required>
    </div>
    <div class="form-row">
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre" required>
    </div>
    <div class="form-row">
        <label for="plattform">Streaming-Plattform:</label>
        <input type="text" name="plattform" id="plattform" required>
    </div>
    <div class="form-row">
        <label for="rating">Bewertung:</label>
        <input type="number" name="rating" id="rating" required>
    </div>
    <div class="form-row">
        <input type="submit" value="Submit">
    </div>
    <div class="form-row">
        <input type="reset" value="Clear">
    </div>
</form>

<a href="list.php" method="GET">
<button class="exit">Exit</button>
        </a>
</html>

<?php
session_start();
include 'connect.php';
include 'logOut.php';



if (isset($_SESSION["EMAIL"]) and isset($_POST["tananyagNev"]) and isset($_POST["kKod"]) and isset($_POST["tananyagAzonosito"])) {
    $DATE = date('Y-m-d h:i:s');
    $TANANYAG_NEV = $_POST["tananyagNev"];
    $TANANYAG_AZONOSITO = $_POST["tananyagAzonosito"];
    $K_KOD = $_POST["kKod"];
    $EMAIL = $_SESSION["EMAIL"];
    if (isset($_POST["end"])) {
        $sql = "INSERT INTO napló (
                email_felhasznalonev,
                tananyagAzonosito,
                mikor,
                miMuvelet,
                kurzusKod
              )
            VALUES (
                '$EMAIL',
                $TANANYAG_AZONOSITO,
                '$DATE',
                '$EMAIL felhasználó bezárta a követekező tananyagot : $TANANYAG_NEV',
                $K_KOD
              );";
        $result = mysqli_query($conn, $sql) or die("");
        header("Location: home.php");
        exit();
    }
    $sql = "INSERT INTO napló (
            email_felhasznalonev,
            tananyagAzonosito,
            mikor,
            miMuvelet,
            kurzusKod
          )
        VALUES (
            '$EMAIL',
            $TANANYAG_AZONOSITO,
            '$DATE',
            '$EMAIL felhasználó megnyitotta a követekező tananyagot : $TANANYAG_NEV',
            $K_KOD
          );";
    $result = mysqli_query($conn, $sql) or die("");
} else {
    logOut();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;justify-content: space-around;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            padding: 20px;
            color: #4CAF50;
            font-size: 24px;
        }

        iframe {
            display: block;
            margin: 0 auto;
            border: 2px solid #ddd;
            border-radius: 8px;
        }

        input[type="submit"] {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 0.5rem;
        }

        input[type="submit"]:hover {
            background-color: darkgreen;
        }

        a:hover {
            background-color: #45a049;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            iframe {
                width: 100%;
                height: 400px;
            }
        }
    </style>
    <title>Funky Lecke</title>
</head>

<body>
    <h1>Mivel még nem dolgoztam ki leckéket így itt hagyok neked egy Iframet Wikipédiához, hogy tudj mit nézni miközben tanulást stimulálsz, ez nekem fontos a naplózáshoz. hf, I guess?</h1>
    <iframe src="https://hu.wikipedia.org/wiki/Kezd%C5%91lap" frameborder="0" width=" 1280px" height="720px"></iframe>
    <form action="seeLesson.php" method="post">
        <input type="hidden" name="tananyagNev" value="<?php echo $_POST["tananyagNev"] ?>">
        <input type="hidden" name="tananyagAzonosito" value="<?php echo $_POST["tananyagAzonosito"] ?>">
        <input type="hidden" name="kKod" value="<?php echo $_POST["kKod"] ?>">

        <input type="submit" name="end" value="End Lesson">
    </form>
</body>

</html>
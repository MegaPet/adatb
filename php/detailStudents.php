<?php
include 'connect.php';
include 'logOut.php';
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Reset and basic styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb;
            margin: 10px;
            padding: 0;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
            margin: 0.5rem;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }
        a:visited{
            color: #ffffff;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        ul {
            max-width: 800px;
            margin: 40px auto;
            padding-left: 20px;
            list-style-type: none;
        }

        li {
            background-color: #ffffff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        li span {
            color: #777;
            font-size: 14px;
            margin-left: 10px;
        }

        .status {
            font-weight: bold;
            color: #28a745;
        }

        .warning {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <a href="home.php"><- Vissza lépés</a>
            <ul>
                <?php
                $sql = 'Select Felhasznalok.nev, Felhasznalok.email_felhasznalonev FROM felhasznalok
                RIGHT JOIN KURZUSTANULOK ON felhasznalok.email_felhasznalonev = KURZUSTANULOK.email_felhasznalonev
                WHERE KURZUSTANULOK.kKod = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_POST["kKod"]);
                $stmt->execute();
                $invoices_result = $stmt->get_result();
                while ($row = $invoices_result->fetch_assoc()) {
                ?>
                    <li><?php echo $row["nev"];
                        $sql = 'select email_felhasznalonev FROM Felhasznalok
                    where isBejelentkezve and nev = ?';
                        $stmt2 = $conn->prepare($sql);
                        $stmt2->bind_param('s', $row['nev']);
                        $stmt2->execute();
                        $invoices_result2 = $stmt2->get_result();
                        if ($row3 = $invoices_result2->fetch_assoc()) {
                        ?>
                            <?php
                            echo " ✔️";
                            ?><?php
                            }
                                ?>
                            <?php
                            if (isset($_SESSION["EMAIL"]) and isset($_POST["tananyagNev"]) and isset($_POST["kKod"]) and isset($_POST["tananyagAzonosito"])) {
                                $TANANYAG_NEV = $_POST["tananyagNev"];
                                $TANANYAG_AZONOSITO = $_POST["tananyagAzonosito"];
                                $K_KOD = $_POST["kKod"];
                                $EMAIL = $row["email_felhasznalonev"];

                                $sql = "Select mikor from napló
                                            WHERE
                                            napló.miMuvelet = '$EMAIL felhasználó bezárta a követekező tananyagot : $TANANYAG_NEV'
                                            ORDER BY logID DESC
                                            LIMIT 1";
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    $valami = mysqli_fetch_assoc($result);
                                    $bezarDate = new DateTime($valami["mikor"]);
                                    $sql = "Select mikor from napló
                                          WHERE
                                          napló.miMuvelet = '$EMAIL felhasználó megnyitotta a követekező tananyagot : $TANANYAG_NEV'
                                          ORDER BY logID DESC
                                          LIMIT 1";
                                    $result = mysqli_query($conn, $sql);
                                    $valami = mysqli_fetch_assoc($result);
                                    $megnyitDate = new DateTime($valami["mikor"]);
                                    $kulonbseg = $bezarDate->diff($megnyitDate);
                            ?>
                                    <span class="status"><?php echo "\tUtolsó megnyit idejének hossza :\t" . $kulonbseg->format("%h:%i:%s"); ?></span>
                                <?php
                                } else {
                                ?>
                                    <span class="warning">Még nem nyitotta meg a tananyagot.</span>
                            <?php
                                }
                            } else {
                                logOut();
                            }
                            ?>
                    </li>
                <?php
                }
                if ($row === false) {
                    echo "Nem vettél fel még diákot a kurzusra.";
                } ?>
            </ul>


</body>

</html>
<?php
session_start();
include 'connect.php';
include 'logOut.php';

if (
    $_SESSION === null or
    !isset($_SESSION["EMAIL"])  or
    isset($_POST["log_out"])
) {
    logOut();
}
$EMAIL = $_SESSION["EMAIL"];

$res;
if (mysqli_select_db($conn, "tankezelo_rendszer")) {
    $sql = "SELECT email_felhasznalonev, nev, szerepkör FROM felhasznalok WHERE email_felhasznalonev = '$EMAIL'";
    try {
        $res = mysqli_query($conn, $sql);
    } catch (mysqli_sql_exception $e) {
        echo "" . mysqli_error($conn);
    }
}
$result = mysqli_fetch_assoc($res);
$NAME = $result["nev"];
$_SESSION["AUTHORITY"] = $AUTHORITY = $result["szerepkör"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            box-sizing: border-box;
        }

        .left {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .all {
            width: 100%;
            display: flex;
            margin: 0;
            padding: 0;
            gap: 0.5 rem;
        }

        header {
            background-color: #0078D4;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
        }

        .user-panel {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .user-panel h3 {
            margin-top: 0;
        }



        .form-container,
        .courses,
        .course {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .courses {
            flex-basis: 80%;
            background-color: transparent;
            outline: none;
            border: none;
            box-shadow: none;
            gap: 0.5rem;
        }
        .courses ul{
            list-style-type: none;
        }
        .lessons li,
        .lessons form {
            padding-left: 0.5rem;
        }
        .diakok{
            list-style-type: none;
            /* Remove default bullets */
            padding: 0;
            max-width: 600px;
            /* Center and constrain width */
            background-color: #f9f9f9;
            /* Light background for the list */
            border: 1px solid #e0e0e0;
            /* Subtle border around the list */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            overflow: hidden;
        }

        .diakok li {
            list-style-type: none;
            padding: 15px 20px;
            /* Inner padding for items */
            font-size: 16px;
            /* Readable font size */
            color: #333;
            /* Dark text color */
            background-color: #ffffff;
            /* White background for each item */
            border-bottom: 1px solid #e0e0e0;
            /* Dividing line between items */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Smooth hover effect */
        }

        .diakok li:last-child {
            border-bottom: none;
            /* Remove border for the last item */
        }

        /* Hover Effect */
        .diakok li:hover {
            background-color: #f0f8ff;
            /* Light blue hover background */
            color: #007bff;
            /* Change text color on hover */
            cursor: pointer;
        }

        .lesson {
            border-left: #0056A4 solid 0.3rem;
            padding-left: 0.5rem;
        }

        .form-container h3 {
            margin-top: 0;
        }

        .form-container input[type="text"],
        #tananyagAzonosito {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container input[type="number"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container input[type="submit"],
        .user-panel input[type="submit"] {
            background-color: #0078D4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056A4;
        }

        .error {
            position: fixed;
            width: 100%;
            color: red;
            background-color: #f4f4f9;
            border: 1px solid red;
            text-align: center;
            bottom: 0px;
            cursor: pointer;
        }

        .success {
            position: fixed;
            width: 100%;
            color: green;
            background-color: #f4f4f9;
            border: 1px solid green;
            text-align: center;
            bottom: 0px;
            cursor: pointer;
        }


        .error::after,
        .success::after {
            content: "[ clikk hogy eltüntesd ]";
        }

        .lesson input[type=submit] {
            display: inline-block;
            padding: 0.5rem;
            font-size: 0.75rem;
            font-weight: bold;
            text-decoration: none;
            color: white;
            background-color: #0056A4;
            border-radius: 5px;
            border: 1px solid #0056A4;
            transition: background-color 0.3s ease, color 0.3s ease;
            cursor: pointer;
        }

        .lesson input[type=submit]:hover {
            background-color: #0056b3;
            color: #ffffff;
        }

        .lesson input[type=submit]:active {
            background-color: #003f7f;
        }

        button:focus {
            outline: 2px solid #0056b3;
            outline-offset: 2px;
        }

        @media only screen and (max-width: 1028px) {
            .all {
                flex-direction: column;
                align-items: center;
            }

            .courses {
                flex-basis: 0;
                width: 85%;
            }
            

        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $NAME ?>!</h1>
    </header>
    <div class="all">
        <div class="container">
            <!-- User Information Panel -->
            <?php if ($AUTHORITY === "teacher" or $AUTHORITY === "mindenható") { ?>
                <div class="left">
                <?php } ?>
                <div class="user-panel">
                    <h3>User Information</h3>
                    <p><strong>Name:</strong> <?php echo $NAME ?></p>
                    <p><strong>Email:</strong> <?php echo $EMAIL ?></p>
                    <p><strong>Role:</strong> <?php echo $AUTHORITY ?></p>
                    <form action="home.php" method="post">
                        <input type="submit" name="log_out" value="logout">
                    </form>
                </div>

                <?php
                if ($AUTHORITY === "teacher" or $AUTHORITY === "mindenható") { ?>
                    <div class="form-container">
                        <h3>Tananyag Kreátor 2000</h3>
                        <form action="lessonCreator.php" method="post">
                            <input type="text" placeholder="Tananyag neve" name="tananyagNev" required>
                            <input type="number" placeholder="Kurzus Kód" name="kKod" min="0" required>
                            <input type="submit" value="Kreál" name="submit">
                        </form>
                    </div>
                    <div class="form-container">
                        <h3>Kurzus Kreátor 200</h3>
                        <form action="courseCreator.php" method="post">
                            <input type="text" placeholder="Kurzus Nev" name="kurzusNev" id="kurzusNev" required>
                            <input type="number" placeholder="Kredit" name="kredit" id="kredit" max="4" min="0" required>
                            <input type="text" placeholder="Fél év" name="felEv" id="kredit" required>
                            <input type="submit" value="submit">
                        </form>
                    </div>
                </div>
        </div>
        <div class="courses">
            <?php
                    $sql = "
            Select Kurzusok.letrehozoFelhasznalo, KurzusNevek.kNevek, Kurzusok.kKod, Kurzusok.felEv FROM Kurzusok INNER JOIN KurzusNevek
            ON kurzusok.idNev = KurzusNevek.idNev
            WHERE Kurzusok.letrehozoFelhasznalo = ?
                ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $EMAIL);
                    $stmt->execute();
                    $invoices_result = $stmt->get_result();
            ?><?php
                    while (($row = $invoices_result->fetch_assoc()) != null) { ?>
            <div class="course">
                <h3>
                    <?php echo $row["kNevek"] . " (" . $row["kKod"] . ")" ?>
                </h3>
                <h4><?php echo $row["felEv"] ?></h4>
                <ul class="lessons">
                    <?php
                        $sql = 'Select tananyagNev, tananyagAzonosito FROM Tananyagok WHERE kKod = ?';
                        $stmt2 = $conn->prepare($sql);
                        $stmt2->bind_param("i", $row["kKod"]);
                        $stmt2->execute();
                        $invoices_result2 = $stmt2->get_result();
                        while ($row2 = $invoices_result2->fetch_assoc()) { ?>
                        <div class="lesson">
                            <li>
                                <p><strong><?php echo $row2["tananyagNev"]; ?></strong></p>
                            </li>
                            <form action="detailStudents.php" method="post">
                                <input type="hidden" name="tananyagNev" value="<?php echo $row2["tananyagNev"] ?>">
                                <input type="hidden" name="tananyagAzonosito" value="<?php echo $row2["tananyagAzonosito"] ?>">
                                <input type="hidden" name="kKod" value="<?php echo $row["kKod"] ?>">

                                <input type="submit" value="Tanulok Lecke látogatási ideje">
                            </form>
                        </div>
                    <?php
                        }
                    ?>
                </ul>
                <h4>
                    diákok:
                </h4>
                <ul class="diakok">
                    <?php
                        $sql = 'Select Felhasznalok.nev, Felhasznalok.email_felhasznalonev FROM felhasznalok
                RIGHT JOIN KURZUSTANULOK ON felhasznalok.email_felhasznalonev = KURZUSTANULOK.email_felhasznalonev
                WHERE KURZUSTANULOK.kKod = ?';
                        $stmt2 = $conn->prepare($sql);
                        $stmt2->bind_param("i", $row["kKod"]);
                        $stmt2->execute();
                        $invoices_result2 = $stmt2->get_result();
                        while ($row2 = $invoices_result2->fetch_assoc()) {
                    ?>
                        <li><?php echo $row2["nev"];
                            $sql = 'select email_felhasznalonev FROM Felhasznalok
                                where isBejelentkezve and nev = ?';
                            $stmt3 = $conn->prepare($sql);
                            $stmt3->bind_param('s', $row2['nev']);
                            $stmt3->execute();
                            $invoices_result3 = $stmt3->get_result();
                            if ($row3 = $invoices_result3->fetch_assoc()) {
                            ?>
                                <?php
                                echo " ✔️";
                                ?><?php
                                }
                                    ?></li>
                    <?php
                        }
                        if ($row2 === false) {
                            echo "Nem vettél fel még diákot a kurzusra.";
                        }
                    ?>
                </ul>
            </div>
        <?php
                    }
        ?>

        </div>
    <?php
                }
    ?>
    <?php
    if ($AUTHORITY === "student") {;
    ?>
    </div>
    <div class="courses">
        <?php
        $sql = "SELECT Kurzusok.kKod, felEv, kNevek, kredit FROM Kurzusok 
                          INNER JOIN KurzusNevek ON KurzusNevek.idNev = kurzusok.idNev
                          INNER JOIN KURZUSTANULOK ON KURZUSTANULOK.kKod = kurzusok.kKod
                          WHERE KURZUSTANULOK.email_felhasznalonev = '$EMAIL'";
        $res = mysqli_query($conn, $sql);
        while (($row = mysqli_fetch_assoc($res)) != null) { ?>

            <div class="course">
                <h3><?php echo $row["kNevek"] . " (" . $row["kKod"] . ")"; ?></h3>
                <h4><?php echo $row["felEv"] ?></h4>
                <ul>
                    <?php
                    $sql = "Select tananyagNev, tananyagAzonosito FROM Tananyagok
                                      WHERE kKod = " . $row["kKod"];
                    $res2 = mysqli_query($conn, $sql);
                    while (($row2 = mysqli_fetch_assoc($res2)) != null) { ?>
                        <div class="lesson">
                            <li>
                                <p><strong><?php echo $row2["tananyagNev"]; ?></strong></p>
                            </li>
                            <form action="seeLesson.php" method="post">
                                <input type="hidden" name="tananyagNev" value="<?php echo $row2["tananyagNev"] ?>">
                                <input type="hidden" name="tananyagAzonosito" value="<?php echo $row2["tananyagAzonosito"] ?>">
                                <input type="hidden" name="kKod" value="<?php echo $row["kKod"] ?>">

                                <input type="submit" value="Lecke megtekintése">
                            </form>
                        </div>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        <?php
        }
        ?>
    </div>
<?php
    }
?>
</div>
</div> <!--all -->

<script>
    function eraseError() {
        document.getElementById("error").style.display = 'none';
    }

    function eraseSuccess() {
        document.getElementById("success").style.display = 'none';
    }

    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = '';
    });
</script>
</body>
<?php
//! ERROR SECTION
if (isset($_SESSION["error"])) {
    echo '<div class="error" id="error" onClick="eraseError()"><p >' . $_SESSION["error"] . "</p></div>";
}
if (isset($_SESSION["success"])) {
    echo '<div class="success" id="success" onClick="eraseSuccess()"><p >' . $_SESSION["success"] . "</p></div>";
}
$_SESSION["error"] = null;
$_SESSION["success"] = null;
?>
<?php mysqli_close($conn); ?>

</html>
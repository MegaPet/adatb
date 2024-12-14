<?php
session_start();
include 'connect.php';

if (
    $_SESSION === null or
    !isset($_SESSION["EMAIL"])  or
    isset($_POST["log_out"])
) {
    logOut();
}
$EMAIL = $_SESSION["EMAIL"];
function logOut()
{
    $EMAIL = $_SESSION["EMAIL"];
    global $conn;
    if (!empty($EMAIL)) {
        $sql = "UPDATE felhasznalok set isBejelentkezve = false WHERE email_felhasznalonev = '$EMAIL'";
        $result = mysqli_query($conn, $sql) or die("");
        $DATE = date('Y-m-d h:i:s');
        $sql = "INSERT INTO napló (
            email_felhasznalonev,
            tananyagAzonosito,
            mikor,
            miMuvelet,
            kurzusKod
          )
        VALUES (
            '$EMAIL',
            Null,
            '$DATE',
            'Kijelentkezés',
            Null
          );";
        $result = mysqli_query($conn, $sql) or die("");
    }
    session_destroy();
    mysqli_close($conn);
    header("Location: Index.php");
    exit();
}

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
        }

        header {
            background-color: #0078D4;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .user-panel {
            width: 30%;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .user-panel h3 {
            margin-top: 0;
        }

        .user-panel,
        .form-container {
            height: 30%;
        }

        .form-container,
        .courses,
        .course {
            width: 65%;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .course ul li {
            list-style-type: none;
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

        @media only screen and (max-width: 1028px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .user-panel {
                width: 65%;
            }

            .courses {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome, <?php echo $NAME ?>!</h1>
    </header>
    <div class="container">
        <!-- User Information Panel -->
        <div class="user-panel">
            <h3>User Information</h3>
            <p><strong>Name:</strong> <?php echo $NAME ?></p>
            <p><strong>Email:</strong> <?php echo $EMAIL ?></p>
            <p><strong>Role:</strong> <?php echo $AUTHORITY ?></p>
            <form action="home.php" method="post">
                <input type="submit" name="log_out" value="logout">
            </form>
        </div>



        <!-- Form Section -->
        <?php
        $ultimateText = '
            <div class="form-container">
                <h3>Tananyag Kreátor 2000</h3>
                <form action="lessonCreator.php" method="post">
                    <input type="text" placeholder="Tananyag neve" name="tananyagNev" required>
                    <input type="number" placeholder="Kurzus Kód" name="kKod" min="0" required>

                    <h4>
                        További munkálatok alatt . . .
                    </h4>
                    <input type="submit" value="Kreál" name="submit">
                </form>
            </div>
            <div class="form-container">
                <h3>Kurzus Kreátor 200</h3>
                <form action="courseCreator.php" method="post">
                    <input type="text" placeholder="Kurzus Nev" name="kurzusNev" id="kurzusNev" required>
                    <input type="number" placeholder="Kredit" name="kredit" id="kredit" max="4" min="0" required>
                    <input type="text" placeholder="Fél év" name="felEv" id="kredit" required>
                    <!--<input type="number" placeholder="Tananyag Hozzáadása Azonosító álltal" name="tananyagAzonosito" id="tananyagAzonosito" required>-->

                    <h4>További munkálatok alatt . . .</h4>
                    <input type="submit" value="submit">

                </form>
            </div>
            <div class="form-container">
            <h2>Diák hozzáadása kurzushoz</h2>
            <form action="addStudent.php" method="post">
                <input type="text" placeholder="Kurzus Kód" name="kKod" required>
                <input type="text" placeholder="Diák emal cím" name="email-felhasznalonev" required>
                <input type="submit" value="Hozzá ad" name="add">
            </form>
            </div>
            <div class="courses">
            <h2>Kurzusaid :</h2>
            
            ';

        if ($AUTHORITY === "teacher" or $AUTHORITY === "mindenható") {


            echo $ultimateText;

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
            <ul>
                <?php
                $sql = 'Select tananyagNev FROM Tananyagok WHERE kKod = ?';
                $stmt2 = $conn->prepare($sql);
                $stmt2->bind_param("i", $row["kKod"]);
                $stmt2->execute();
                $invoices_result2 = $stmt2->get_result();
                while ($row2 = $invoices_result2->fetch_assoc()) { ?>
                    <div class="lesson">
                        <li>
                            <p><strong><?php echo $row2["tananyagNev"]; ?></strong></p>
                        </li>
                        <button>valami</button>
                    </div>
                <?php
                }
                ?>
            </ul>
            <h4>
                diákok:
            </h4>
            <ul>
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
        ?><?php
        }
            ?>

        <?php
        if ($AUTHORITY === "student") {;
        ?>
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
                            $sql = "Select tananyagNev FROM Tananyagok
                                      WHERE kKod = " . $row["kKod"];
                            $res2 = mysqli_query($conn, $sql);
                            while (($row2 = mysqli_fetch_assoc($res2)) != null) { ?>
                                <div class="lesson">
                                    <li>
                                        <p><strong><?php echo $row2["tananyagNev"]; ?></strong></p>
                                    </li>
                                    <button>valami</button>
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

        <script>
            function eraseError() {
                document.getElementById("error").style.display = 'none';
            }

            function eraseSuccess() {
                document.getElementById("success").style.display = 'none';
            }
        </script>

    </div>
</body>
<?php mysqli_close($conn); ?>

</html>
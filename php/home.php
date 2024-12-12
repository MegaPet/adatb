<?php
session_start();
include 'connect.php';

if (isset($_SESSION['']) or isset($_POST["log_out"])) {
    logOut();
}

function logOut()
{
    global $EMAIL;
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
    header("Location: main.php");
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
$AUTHORITY = $result["szerepkör"];







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
            position: absolute;
            width: 100%;
            color: red;
            background-color: #f4f4f9;
            border: 1px solid red;
            text-align: center;
            bottom: 2px;
            cursor: pointer;
        }

        .success {
            position: absolute;
            width: 100%;
            color: green;
            background-color: #f4f4f9;
            border: 1px solid green;
            text-align: center;
            bottom: 2px;
            cursor: pointer;
        }

        .error::after {
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
        <div class="form-container">
            <h2>Diák hozzáadása kurzushoz</h2>
            <form action="addStudent.php" method="post">
                <input type="text" placeholder="Kurzus Kód" name="kKod" required>
                <input type="text" placeholder="Diák emal cím" name="email-felhasznalonev" required>
                <input type="submit" value="Hozzá ad" name="add">
            </form>
        </div>


        <!-- Form Section -->
        <?php
        $ultimateText = '
            <div class="form-container">
                <h3>Tananyag Kreátor 2000</h3>
                <form action="lessonCreator.php" method="post">
                    <input type="text" placeholder="Tananyag azonosítója" name="tananyagAzonosito" required>
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
            <div class="courses">
            <h2>Kurzusaid :</h2>
           
            
            ';

        if ($AUTHORITY === "teacher" or $AUTHORITY === "mindenható") {


            echo $ultimateText;

            $sql = "
            Select Kurzusok.letrehozoFelhasznalo, KurzusNevek.kNevek, Kurzusok.kKod FROM Kurzusok INNER JOIN KurzusNevek
            ON kurzusok.idNev = KurzusNevek.idNev
                WHERE Kurzusok.letrehozoFelhasznalo = ?
                ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $EMAIL);
            $stmt->execute();
            $invoices_result = $stmt->get_result();
        ?>
            <div class="containers"><?php
                                    while (($row = $invoices_result->fetch_assoc()) != null) { ?>
                    <div class="containers">
                        <h3>
                            <?php echo $row["kNevek"] . " (" . $row["kKod"] . ")" ?>
                        </h3>
                        <ul>
                            <?php
                                        $sql = 'Select tananyagNev FROM Tananyagok WHERE kKod = ?';
                                        $stmt2 = $conn->prepare($sql);
                                        $stmt2->bind_param("i", $row["kKod"]);
                                        $stmt2->execute();
                                        $invoices_result2 = $stmt2->get_result();
                                        while ($row2 = $invoices_result2->fetch_assoc()) {?>
                                <li>
                                    <p><strong><?php echo $row2["tananyagNev"]; ?></strong></p>
                                </li>
                            <?php
                                        }
                            ?>
                        </ul>
                        <button>valami</button>
                    </div>
                <?php
                                    }
                ?>
            </div><?php
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


        <?php
        if ($AUTHORITY === "tanulo") {
            echo "HAIIII";
        }
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
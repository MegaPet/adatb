<?php
session_start();
echo var_dump($_SESSION);
include 'connect.php';
$PASSWORD = "";
$EMAIL = "";
if (isset($_POST)) {
    if (isset($_POST["login-email"]) && !empty($_POST["login-email"])) {
        $EMAIL = $_POST["login-email"];
        $PASSWORD = "";
        if (isset($_POST["login-password"]) && !empty($_POST["login-password"])) {
            $PASSWORD = $_POST["login-password"];
            // $PASSWORD = password_hash($_POST["login-password"], PASSWORD_DEFAULT);
        } else {
            header("Location: main.php");
        }
    }
}
$_SESSION["EMAIL"] = $EMAIL;
$_SESSION["PASSWORD"] = $PASSWORD;
$DATE = date('Y-m-d h:i:s');

if (mysqli_select_db($conn, "tankezelo_rendszer")) {
    $sql = "SELECT email_felhasznalonev, jelszó FROM felhasznalok WHERE email_felhasznalonev = '$EMAIL' AND SHA1('$PASSWORD') = jelszó";


    // echo "<br>". var_dump($sql);
    $result = mysqli_query($conn, $sql) or die('Hibás utasítás');
    echo var_dump(mysqli_query($conn, $sql));
    echo mysqli_num_rows($result);
    if (mysqli_num_rows($result) > 0) {
        $sql = "UPDATE felhasznalok set isBejelentkezve = true WHERE email_felhasznalonev = '$EMAIL'";
        $result = mysqli_query($conn, $sql) or die("");

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
            'Bejelentkezés',
            Null
          );";
        $result = mysqli_query($conn, $sql) or die("");
        header("Location: home.php");
    } else {
        $_SESSION["passError"] = "Hibás Jelszó";
        header("Location: Index.php");
    }
}
mysqli_close($conn);

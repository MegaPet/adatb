<?php
session_start();

include 'connect.php';

// echo var_dump($_POST);

$NAME = $_POST["register-name"];
$EMAIL = $_POST["register-email"];
$PASSWORD = $_POST["register-password"];
$PASSWORD_C = $_POST["register-confirm-password"];
$ROLE = $_POST["role"];
if ($PASSWORD === $PASSWORD_C) {
    if (mysqli_select_db($conn, 'tankezelo_rendszer')) {
        $SQL = "SELECT email_felhasznalonev, nev, szerepkör FROM felhasznalok WHERE email_felhasznalonev = '$EMAIL';";
        $res = mysqli_query($conn, $SQL);;
        if (mysqli_num_rows($res) <= 0) {
            $SQL = "
            INSERT INTO 
            felhasznalok (email_felhasznalonev, nev, jelszó, isBejelentkezve, szerepkör)
            VALUES
            ('$EMAIL', '$NAME', SHA1('$PASSWORD'), false,'$ROLE')
            ";
            mysqli_query($conn, $SQL);
            $_SESSION["EMAIL"] = $EMAIL;
            $_SESSION["nev"] = $NAME;
            $_SESSION["szerepkör"] = $ROLE;

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
            '$NAME ($EMAIL) Regisztrált.',
            Null
          );";
            $result = mysqli_query($conn, $sql) or die("");

            header("Location: home.php");
            exit();
        } else {
            $_SESSION["passError"] = "Foglalt e-mail cím!";
        }
    }
} else {
    $_SESSION["passError"] = "Nem megegyező Jelszó";
}
header("Location: Index.php");
mysqli_close($conn);

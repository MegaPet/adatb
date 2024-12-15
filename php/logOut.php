<?php
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

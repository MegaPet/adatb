<?php
session_start();
include 'connect.php';
$_SESSION["error"] = null;
$_SESSION["success"] = null;

$KURZUS_NEV = $_POST["kurzusNev"];
$KREDIT = (int)$_POST["kredit"]; // Cast to ensure it's an integer
$FEL_EV = (string)$_POST["felEv"]; // Escape string for safety
$EMAIL = (string)$_SESSION["EMAIL"]; // Escape string for safety

function does_courseName_exist($course_name) // bool
{
    global $conn;
    $sql = "Select kNevek FROM kurzusnevek Where kNevek = '$course_name'";
    $res = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($res);

    return $result === null;
}

$valami;
if (
    mysqli_select_db($conn, 'tankezelo_rendszer') &&
    isset($_SESSION["EMAIL"]) &&
    does_courseName_exist($KURZUS_NEV)
) {
    $sql = "INSERT into KurzusNevek(kNevek) VALUES (?);";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $KURZUS_NEV,);
    $stmt->execute();
    $valami = $conn->insert_id;
}
if (mysqli_select_db($conn, "tankezelo_rendszer")) {
    $sql = "Select idNev From KurzusNevek Where kNevek = '$KURZUS_NEV'";
    $res = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($res);
    $idNev = $result["idNev"];

    $sql = "Select idNev, felEv From Kurzusok Where idNev = '$idNev' and felEv='$FEL_EV'";
    $res = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($res);

    if ($result === null) {
        $sql = "INSERT into Kurzusok(kredit, felEv, letrehozoFelhasznalo, idNev) VALUES (?,?,?,?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $KREDIT, $FEL_EV, $EMAIL, $idNev,);
        $stmt->execute();
        $_SESSION["success"] = "Kurzus létrehozva : " . $KURZUS_NEV;
        
        $K_KOD = $stmt->insert_id;
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
            '$KURZUS_NEV Letrehozva',
            $K_KOD
          );";
        $result = mysqli_query($conn, $sql) or die("");
        $stmt->close();
    } else {
        $_SESSION["error"] = "Kurzus már létezik :" . $KURZUS_NEV . "(" . $FEL_EV . ")";
    }
}
header("Location: home.php");

<?php
session_start();
include 'connect.php';
$_SESSION["error"] = null;
$_SESSION["success"] = null;
$LETREHOZO_EMAIL = (isset($_SESSION["EMAIL"])) ? $_SESSION["EMAIL"] : null;

$K_KOD = $_POST["kKod"];
$EMAIL = $_POST["email-felhasznalonev"];

$sql = "Select KurzusNevek.kNevek FROM Kurzusok
    INNER join KurzusNevek on Kurzusok.idNev = KurzusNevek.idNev
    Where Kurzusok.kKod = ? ";
if ($_SESSION["AUTHORITY"] === "teacher") {
  $sql .= "and Kurzusok.letrehozoFelhasznalo = '$LETREHOZO_EMAIL'";
}
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $K_KOD);
$stmt->execute();
$KURZUS_NEV;
$row = $stmt->get_result()->fetch_assoc();
if ($row) {
  global $row;
  global $KURZUS_NEV;
  $KURZUS_NEV = $row['kNevek']; 
}


if (
  mysqli_select_db($conn, "tankezelo_rendszer") &&
  $LETREHOZO_EMAIL !== null &&
  $KURZUS_NEV !== NULL
) {
  $sql = "INSERT INTO kurzustanulok (
        email_felhasznalonev,
        kKod
      )
    VALUES (
        ?,
        ?
      );";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $EMAIL, $K_KOD);
  try {
    $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    $_SESSION["error"] = $e->getMessage();
  }
  if (!isset($_SESSION["error"])) {
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
            '$EMAIL hozzaadasa $KURZUS_NEV kurzushoz.',
            $K_KOD
          );";
    $result = mysqli_query($conn, $sql) or die("");

    $_SESSION["success"] = "Sikeresen hozzáadtad a diákot!";
  } else {
    $_SESSION["error"] = "Sikertelen diák hozzáadás";
  }
  header("Location: home.php");
}

<?php
session_start();
include 'connect.php';
$_SESSION["error"] = null;
$_SESSION["success"] = null;
// echo var_dump($_POST);
// echo var_dump($_SESSION);

$TANANYAG_AZONOSITO = $_POST["tananyagAzonosito"];
$TANANYAG_NEV = $_POST["tananyagNev"];
$EMAIL = $_SESSION["EMAIL"];
$DATE = date('Y-m-d');
$K_KOD = $_POST['kKod'];
if (mysqli_select_db($conn, 'tankezelo_rendszer') and isset($_SESSION)) {
  $sql = "INSERT INTO Tananyagok (tananyagAzonosito, letrehozoFelhasznalo, tananyagNev, letrehozasDatuma, kKod)
            VALUES (?,?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isssi", $TANANYAG_AZONOSITO, $EMAIL, $TANANYAG_NEV, $DATE, $K_KOD);
  try {
    $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    $_SESSION["error"] = $e->getMessage();
  }
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
  header("Location: home.php");
}
mysqli_close($conn);

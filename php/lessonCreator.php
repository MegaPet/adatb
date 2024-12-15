<?php
session_start();
include 'connect.php';
$_SESSION["error"] = null;
$_SESSION["success"] = null;

$TANANYAG_NEV = $_POST["tananyagNev"];
$EMAIL = $_SESSION["EMAIL"];
$DATE = date('Y-m-d');
$K_KOD = $_POST['kKod'];
$AUTHORITY = $_POST["szerepkör"];


if (mysqli_select_db($conn, 'tankezelo_rendszer') and isset($_SESSION)) {
  $sql = 'Select tananyagNev from tananyagok
  WHERE tananyagNev = "' . $TANANYAG_NEV . '" and kKod = ' . $K_KOD;
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) === 0) {
    $isChangable = true;
    if ($AUTHORITY === "teacher") {
      $sql = 'Select kNevek From KurzusNevek
              WHERE EXISTS(
              Select letrehozoFelhasznalo from kurzusok
              WHERE
              Kurzusok.idNev = KurzusNevek.idNev AND
              letrehozoFelhasznalo = "' . $EMAIL . '"
              AND Kurzusok.kKod = ' . $K_KOD . ')';
      $result = mysqli_query($conn, $sql);
      $res = $result->fetch_assoc();
      if (mysqli_num_rows($result) === 0) {
        $isChangable = false;
      }
    }
    if ($isChangable) {
      $sql = "INSERT INTO Tananyagok (letrehozoFelhasznalo, tananyagNev, letrehozasDatuma, kKod)
    VALUES (?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssi", $EMAIL, $TANANYAG_NEV, $DATE, $K_KOD);
      try {
        $stmt->execute();
      } catch (mysqli_sql_exception $e) {
        $_SESSION["error"] = $e->getMessage();
      }
      if ($_SESSION["error"] === null) {
        $TANANYAG_AZONOSITO = $stmt->insert_id;
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
            $TANANYAG_AZONOSITO,
            '$DATE',
            '$TANANYAG_NEV Letrehozva',
            $K_KOD
          );";
        $result = mysqli_query($conn, $sql) or die("");
        $_SESSION["success"] = "Sikeres Tananyag hozzáadás.";
      }
    } else {
      $_SESSION["error"] = "Nincs ilyen kurzusod.";
    }
  } else {
    $_SESSION["error"] = "Már létezik ilyen névvel tananyag ebben kurzusban.";
  }

  header("Location: home.php");
}
mysqli_close($conn);

<?php
session_start();
include 'connect.php';
$_SESSION["error"] = null;
$_SESSION["success"] = null;

$K_KOD = $_POST["kKod"];
$EMAIL = $_POST["email-felhasznalonev"];

$sql = "Select KurzusNevek.kNevek, Kurzusok.kKod FROM
          Kurzusok INNER join KurzusNevek on Kurzusok.idNev = KurzusNevek.idNev
          Where ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $K_KOD);
$stmt->execute();
$KURZUS_NEV = $stmt->get_result();


if (mysqli_select_db($conn, "tankezelo_rendszer")) {
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
  if ($stmt->execute()) {

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
            '$KURZUS_NEV',
            $K_KOD
          );";
    $result = mysqli_query($conn, $sql) or die("");

    $_SESSION["success"] = "Sikeresen hozzáadtad a diákot!";
  } else {
    $_SESSION["error"] = "Sikertelen diák hozzáadás";
  }
  header("Location: home.php");
}

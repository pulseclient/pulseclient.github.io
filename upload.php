<?php


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
  // Validierung des Textes
  $text = trim($_POST["text"]); // Entferne unnötige Leerzeichen am Anfang und Ende des Textes
  if (empty($text)) {
    echo "Fehler: Textfeld ist leer.";
    exit; // Beende die Ausführung des Skripts
  }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Überprüfe, ob die Datei ein Bild ist
  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if($check !== false) {
    echo "Datei ist ein Bild - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "Datei ist kein Bild.";
    $uploadOk = 0;
  }

  // Überprüfe, ob die Datei bereits existiert
  if (file_exists($target_file)) {
    echo "Sorry, das Bild existiert bereits.";
    $uploadOk = 0;
  }

  // Überprüfe die Dateigröße
  if ($_FILES["image"]["size"] > 500000) {
    echo "Sorry, das Bild ist zu groß.";
    $uploadOk = 0;
  }

  // Erlaube nur bestimmte Dateiformate
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, nur JPG, JPEG, PNG & GIF Dateien sind erlaubt.";
    $uploadOk = 0;
  }

  // Überprüfe, ob $uploadOk auf 0 gesetzt wurde
  if ($uploadOk == 0) {
    echo "Sorry, das Bild wurde nicht hochgeladen.";
  } else {
    // Versuche das Bild hochzuladen
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      echo "Das Bild ". htmlspecialchars( basename( $_FILES["image"]["name"])). " wurde hochgeladen.";
    } else {
      echo "Es gab einen Fehler beim Hochladen des Bildes.";
    }
  }
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
  $text = $_POST["text"];
  // Hier könntest du den Dateipfad und den Text in die Datenbank speichern
  echo "Bild wurde hochgeladen: " . $target_file;
}
?>

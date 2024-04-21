<?php
// Konfigurationsdaten für die Datenbankverbindung
$servername = "localhost";
$username = "root"; // Dein MySQL-Benutzername
$password = "password"; // Dein MySQL-Passwort
$dbname = "meine_datenbank"; // Der Name deiner Datenbank

// Verbindung zur Datenbank herstellen und Fehler abfangen
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Benutzerdaten aus dem Formular sicher abrufen und säubern
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Überprüfen, ob Benutzername und Passwort den Mindestanforderungen entsprechen
if (strlen($username) < 6 || strlen($password) < 8) {
    die("Benutzername muss mindestens 6 Zeichen lang sein und das Passwort muss mindestens 8 Zeichen lang sein.");
}

// Profilbild verarbeiten
$profile_picture = ''; // Hier wird der Pfad zum Profilbild gespeichert

if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/'; // Verzeichnis zum Speichern der Bilder
    $upload_file = $upload_dir . basename($_FILES['profile_picture']['name']);

    // Überprüfen, ob das hochgeladene Bild gültig ist
    $image_info = getimagesize($_FILES['profile_picture']['tmp_name']);
    if ($image_info === false) {
        die("Das hochgeladene Bild ist kein gültiges Bild.");
    }

    // Überprüfen, ob das hochgeladene Bild die richtige Dateigröße hat (z.B. maximal 5 MB)
    if ($_FILES['profile_picture']['size'] > 5 * 1024 * 1024) {
        die("Das hochgeladene Bild überschreitet die maximale Dateigröße von 5 MB.");
    }

    // Überprüfen, ob das hochgeladene Bild den richtigen Dateityp hat (z.B. nur jpg, png erlauben)
    $allowed_types = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
    if (!in_array($image_info[2], $allowed_types)) {
        die("Nur JPG- und PNG-Bilder sind erlaubt.");
    }

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_file)) {
        $profile_picture = $upload_file;
    } else {
        die("Fehler beim Hochladen des Profilbilds.");
    }
}

// Passwort hashen
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SQL-Anweisung vorbereiten und Fehler abfangen
$stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Vorbereitung fehlgeschlagen: " . $conn->error);
}

// Parameter binden und Fehler abfangen
$stmt->bind_param("ssss", $username, $email, $hashed_password, $profile_picture);
if (!$stmt->execute()) {
    die("Fehler beim Ausführen der Anweisung: " . $stmt->error);
}

// Erfolgsmeldung ausgeben
echo "Benutzer erfolgreich erstellt";

// Verbindung schließen
$stmt->close();
$conn->close();
?>

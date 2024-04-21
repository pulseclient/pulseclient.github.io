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

// Benutzername und Passwort aus dem Formular abrufen
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// SQL-Anweisung vorbereiten und Fehler abfangen
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
if (!$stmt) {
    die("Vorbereitung fehlgeschlagen: " . $conn->error);
}

// Parameter binden und Fehler abfangen
$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    die("Fehler beim Ausführen der Anweisung: " . $stmt->error);
}

// Ergebnisse der Abfrage abrufen
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Benutzer gefunden, Passwort überprüfen
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        // Passwort ist korrekt, Benutzer erfolgreich angemeldet
        echo "Herzlich willkommen, " . $row['username'] . "!";
    } else {
        // Passwort ist falsch
        echo "Falsches Passwort. Bitte versuchen Sie es erneut.";
    }
} else {
    // Benutzer nicht gefunden
    echo "Benutzer nicht gefunden. Bitte überprüfen Sie Ihren Benutzernamen.";
}

// Verbindung schließen
$stmt->close();
$conn->close();
?>

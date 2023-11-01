<?php
// Laden der Kundenliste aus der JSON-Datei
$customers = json_decode(file_get_contents('customers.json'), true);

// Das JSON-Objekt aus dem Frontend abrufen
$data = json_decode(file_get_contents('php://input'), true);

// Index des zu löschenden Kunden
$index = $data['index'];

if (isset($customers[$index])) {
    unset($customers[$index]); // Kunden löschen
    $customers = array_values($customers); // Indizes aktualisieren
    file_put_contents('customers.json', json_encode($customers)); // Aktualisierte Kundenliste speichern
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
error_log("Index: $index");
error_log("Kunden vor dem Löschen: " . json_encode($customers));
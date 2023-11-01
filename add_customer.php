<?php
// �berpr�fen, ob die Anfrage ein POST-Request ist
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Daten aus dem POST-Request lesen
    $data = file_get_contents('php://input');
    $newCustomer = json_decode($data, true);

    if ($newCustomer) {
        // JSON-Datei �ffnen und bestehende Kunden laden
        $customers = json_decode(file_get_contents('customers.json'), true);

        // Neuen Kunden zum Array hinzuf�gen
        $customers[] = $newCustomer;

        // Kundenliste in die JSON-Datei schreiben
        file_put_contents('customers.json', json_encode($customers));

        // Erfolgreiche Antwort senden
        echo json_encode(['status' => 'success']);
    } else {
        // Fehlerhafte Anfrage
        echo json_encode(['status' => 'error']);
    }
} else {
    // Ung�ltige Anfrage-Methode
    echo json_encode(['status' => 'error']);
}
?>

<?php
// Laden der Kundenliste aus der JSON-Datei
$customers = json_decode(file_get_contents('./customers.json'), true);

// Das JSON-Objekt aus dem Frontend abrufen
$data = json_decode(file_get_contents('php://input'), true);

// Index des zu bearbeitenden Kunden
$index = $data['index'];

if (isset($customers[$index])) {
    // Aktualisierte Kundeninformationen
    $customers[$index]['name'] = $data['customer']['name'];
    $customers[$index]['order_number'] = $data['customer']['order_number'];
    $originalDate = $data['customer']['delivery_date'];
$formattedDate = str_replace('.', '-', $originalDate);
$customers[$index]['delivery_date'] = $formattedDate;

    file_put_contents('customers.json', json_encode($customers)); // Aktualisierte Kundenliste speichern
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => $data]);
}

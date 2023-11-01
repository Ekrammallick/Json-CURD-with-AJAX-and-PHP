<?php
// JSON-Datei öffnen und Kundenliste lesen
$customers = json_decode(file_get_contents('customers.json'), true);

// Die Kundenliste als JSON-Daten ausgeben
echo json_encode($customers);
?>

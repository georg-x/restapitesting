<?php

function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Fehler: " . curl_error($ch);
        curl_close($ch);
        return null;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// API-URL für das erste Gerät
$apiUrl1 = "http://10.0.0.76/solar_api/v1/GetPowerFlowRealtimeData.fcgi";
$data1 = fetchData($apiUrl1);

$pAkku = $pGrid = $pLoad = $pPV = $pBalkon = $soc = 'N/A';

if ($data1) {
    $pAkku = isset($data1['Body']['Data']['Site']['P_Akku']) ? floor($data1['Body']['Data']['Site']['P_Akku']) : 'N/A';
    $pGrid = isset($data1['Body']['Data']['Site']['P_Grid']) ? floor($data1['Body']['Data']['Site']['P_Grid']) : 'N/A';
    $pLoad = isset($data1['Body']['Data']['Site']['P_Load']) ? floor($data1['Body']['Data']['Site']['P_Load']) : 'N/A';
    $pPV   = isset($data1['Body']['Data']['Site']['P_PV']) ? floor($data1['Body']['Data']['Site']['P_PV']) : 'N/A';
}

// API-URL für das zweite Gerät
$apiUrl2 = "http://10.0.0.66/api/inverter/id/0";
$data2 = fetchData($apiUrl2);

if ($data2) {
    $pBalkon = isset($data2['ch'][0][2]) ? floor($data2['ch'][0][2]) : 'N/A';
}

// API-URL für das dritte Gerät (Speicher)
$apiUrl3 = "http://10.0.0.76/solar_api/v1/GetStorageRealtimeData.cgi";
$data3 = fetchData($apiUrl3);

if ($data3) {
    $soc = isset($data3['Body']['Data'][0]['Controller']['StateOfCharge_Relative']) ? $data3['Body']['Data'][0]['Controller']['StateOfCharge_Relative'] : 'N/A';
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solar API Werte</title>
    <style>
        table { width: 50%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Solar API Messwerte</h2>
    <table>
        <tr>
            <th>Parameter</th>
            <th>Wert (abgerundet)</th>
        </tr>
        <tr><td>P_Akku</td><td><?= $pAkku ?></td></tr>
        <tr><td>P_Grid</td><td><?= $pGrid ?></td></tr>
        <tr><td>P_Load</td><td><?= $pLoad ?></td></tr>
        <tr><td>P_PV</td><td><?= $pPV ?></td></tr>
        <tr><td>P_Balkon</td><td><?= $pBalkon ?></td></tr>
        <tr><td>SoC</td><td><?= $soc ?>%</td></tr>
    </table>
</body>
</html>

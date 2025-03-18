#!/bin/bash

# API-URL festlegen
API_URL="http://10.0.0.76/solar_api/v1/GetPowerFlowRealtimeData.fcgi"

# API-Aufruf durchführen und die Antwort speichern
RESPONSE=$(curl -s -X GET "$API_URL" -H "Content-Type: application/json")

# Überprüfen, ob die Antwort gültig ist
if [ -z "$RESPONSE" ]; then
    echo "Fehler: Keine Antwort von der API $API_URL erhalten."
    exit 1
fi

# Einen spezifischen Wert aus der JSON-Antwort extrahieren (z.B. 'value')
P_Grid=$(echo "$RESPONSE" | jq -r '.Body.Data.Site.P_Grid')

I_P_Grid=${P_Akku%%.*}

if [[ $I_P_Grid -lt -100 ]]; then
    curl 'http://10.0.0.113/cm?user=admin&password=admin&cmnd=Power%20On'
    echo eingeschaltet
else
    curl 'http://10.0.0.113/cm?user=admin&password=admin&cmnd=Power%20Off'
    echo ausgeschaltet
fi

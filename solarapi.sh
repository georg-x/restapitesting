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
P_Akku=$(echo "$RESPONSE" | jq -r '.Body.Data.Site.P_Akku')
P_Grid=$(echo "$RESPONSE" | jq -r '.Body.Data.Site.P_Grid')
P_Load=$(echo "$RESPONSE" | jq -r '.Body.Data.Site.P_Load')
P_PV=$(echo "$RESPONSE" | jq -r '.Body.Data.Site.P_PV')
echo "${P_Akku%%.*} P_Akku"
echo "${P_Grid%%.*} P_Grid"
echo "${P_Load%%.*} P_Load"
echo "${P_PV%%.*} P_PV"

# Überprüfen, ob der Wert erfolgreich extrahiert wurde
#if [ "$VALUE" == "null" ] || [ -z "$VALUE" ]; then
#    echo "Fehler: Wert konnte nicht extrahiert werden."
#    exit 1
#fi

# API-URL festlegen

API_URL="http://10.0.0.66/api/inverter/id/0"


# API-Aufruf durchführen und die Antwort speichern
RESPONSE=$(curl -s -X GET "$API_URL" -H "Content-Type: application/json")

# Überprüfen, ob die Antwort gültig ist
if [ -z "$RESPONSE" ]; then
    echo "Fehler: Keine Antwort von der API $API_URL erhalten."
    exit 1
fi

P_Balkon=$(echo "$RESPONSE" | jq -r '.ch[0][2]')
echo "${P_Balkon%%.*} P_Balkon"


# Den extrahierten Wert ausgeben
#echo "Der zurückgegebene Wert ist: $VALUE"


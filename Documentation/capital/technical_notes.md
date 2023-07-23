# Funktionsweise
Die Berechnung von statistischen Daten ist ein recht aufwendiger Prozess. Je mehr Daten verfügbar sind, um so länger 
dauert in der Regel die Aufbereitung der gewünschten Informationen. Damit die Daten im Frontend trotzdem schnell 
verfügbar sind, wird in *t3sportstats* ein zweistufiger Prozess für die Berechnung verwendet.

Im ersten Schritt werden die Rohdaten aus **T3sports** verdichtet und so in der Datenbank abgelegt, daß die notwendige 
Berechnung von Summen und Durchschnittswerten im zweiten Schitt durch die Datenbank optimal durchgeführt werden kann.

Das bedeutet aber auch, daß bei Änderung von Daten zum Spiel, die Statistiktabellen von *t3sportstats* aktualisiert 
werden müssen, damit die Werte wieder stimmen.

## Datentabellen
Informationen zu Personen werden in folgenden drei Tabellen abgelegt:

* tx_t3sportstats_players
* tx_t3sportstats_coachs
* tx_t3sportstats_referees

Dabei wird pro Person und Spiel ein Datensatz erstellt. Wenn man weitere Informationen sammeln will, dann können diese 
Tabellen wie üblich in TYPO3 erweitert werden. Damit die neuen Spalten dann mit Daten befüllt werden, kann man je nach
Typ der Person (Spieler, Trainer oder Schiedsrichter) einen zusätzlichen Indexierung-Service registrieren.

Für diese Registrierung muss lediglich eines der folgenden Interfaces implementiert: 

* `System25\T3sports\StatsIndexer\PlayerStatsInterface`
* `System25\T3sports\StatsIndexer\CoachStatsInterface`
* `System25\T3sports\StatsIndexer\RefereeStatsInterface`

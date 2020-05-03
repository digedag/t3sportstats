# Erweiterte Möglichkeiten zum Plugin
## Eigene Report-Profile erstellen

Für alle Personenstatistiken gibt es einen vorbereiteten Report, der einen Gesamtstatistik
vorbereitet. Bei den Spielern gibt es zusätzliche Reports für Torschützen und Torvorbereiter.

Weitere Reports kann man leicht selber integrieren. Dazu muss man sich zunächst eine eigene 
Extension erstellen und in TYPO3 registrieren.

Der eigene Report benötigt zunächst einen eindeutigen eigenen Identifier. Für die 
Torschützenliste wird die ID `scorerlist` verwendet. In der Datei ext_localconf.php wird diese
ID registriert:

```php
System25\T3sports\Utility\StatsConfig::registerPlayerStatsReport('scorerlist');
```

Nach Löschen des System-Caches sollte diese ID direkt im Plugin von **T3sportstats** mit angezeigt 
werden.

Die eigentliche Konfiguration des Reports erfolgt nun per Typoscript. Für die Torschützenliste 
wurde folgendes Typoscript erstellt:

```
plugin.tx_t3sportstats {
  playerstats {
    # Den Standardfilter für die Spieler übernehmen
    scorerlist =< lib.t3sports.statsPlayerFilter
    scorerlist {
      # Die Ergebnisliste soll absteigend nach der Anzahl Tore sortiert werden
      options.orderby.CUSTOM = goals desc
      # Nur Treffer mit wenigstens einem Tor anzeigen
    	options.having = sum(goals) > 0
    	# die Konfiguration für die Formatierung der Daten im Frontend übernehmen
      data =< lib.t3sports.statsData
    }
  }
}
```

Jetzt muss nur noch ein Subpart für die Ausgabe der Daten angelegt werden. Der Hauptsubpart für die 
Spielerstatistiken heißt `###PLAYERSTATS###`. Innerhalb dieses Templates werden vom Plugin weitere 
Subparts für die Reports erwartet. Diese müssen genauso genannt werden, wie die Report-ID, nur in 
Großschreibweise. Für die Torschützen also `###SCORERLIST###`. So kann ein einfaches Beispiel aussehen:

```
###SCORERLIST###

###DATAS###
<ul>
###DATA###
<li>###DATA_PLAYER_DCNAMEREV### (###DATA_GOALS###/###DATA_GOALS_PER_MATCH###)</li>
###DATA###
<ul>
###DATAS###

###SCORERLIST###
```

## Anzeige von zusätzlichen Daten in der Statistikliste

Je nach Anwendungsfall ist es möglich, auch einige Basisdaten in den Statistiktabellen anzuzeigen.

### Wettbewerb
Wenn man eine Statistik erstellt, in der die Personen nur in genau einem Wettbewerb vorkommen, dann kann 
man diesen Wettbewerb in der Ergebnisliste mit anzeigen.

Zunächst muss dafür der Wettbwerb mit in das Ergebnis-Set der Datenbank-Abfrage aufgenommen werden. Das
geschieht per Typoscript. Hier beispielhaft für die Torschützen-Statistik:

```
plugin.tx_t3sportstats {
  playerstats {
    scorerlist {
      options.what = player,competition
      options.groupby = PLAYERSTAT.player,PLAYERSTAT.competition
    }
  }
}
```

Die Spalte `competition` wird also in die Ergebnisliste und die `GROUP BY` Anweisung aufgenommen.
Sollte ein Spieler in dieser Statistik seine Tore doch in unterschiedlichen Wettbewerben erzielt
haben, dann wird er einmal pro gefundenem Wettbewerb in der Ergebnisliste erscheinen.

Im HTML-Template kann man den Namen des Wettbewerb über folgenden Marker anzeigen: `######DATA_COMPETITION_NAME######`.
Es können natürlich auch alle anderen Marker des Wettbewerbs genutzt werden.

### Verein
Auch den Verein kann man in der Ergebnisliste anzeigen, wenn die Daten entsprechend abgerufen werden. Die Datenbankabfrage
muss dazu wie folgt per Typoscript erweitert werden:

```
plugin.tx_t3sportstats {
  playerstats {
    scorerlist {
      options.what = player,club
      options.groupby = PLAYERSTAT.player,PLAYERSTAT.club
    }
  }
}
```

Im HTML-Template kann man den Namen des Vereins über folgenden Marker anzeigen: `######DATA_CLUB_NAME######`.
Und auch hier können natürlich auch alle anderen Marker des Wettbewerbs genutzt werden.

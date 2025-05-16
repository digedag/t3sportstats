# Serien

Es sollen Serien ermittelt werden. Also zum Beispiel wollen wir die längste Sieges- oder Niederlagenserie eines Vereins finden. Wir wollen also wissen, welche aufeinanderfolgenden Spiele ein oder mehrere gemeinsame Kriterien erfüllen.

Im ersten Schritt legt man einen Datensatz vom Typ **Serie** an. Hier legt man zunächst fest, welche Spiele für die Serie ausgewertet werden sollen. Felder, die leer gelassen werden, schränken die Suche nicht ein. Wenn man also bspw. keine Saisondatensätze auswählt, dann wird über alle Spieljahre gesucht.

Man muss nun eine Altersklasse aufwählen und einen oder mehrere Vereine auswählen. Die Serien werden dann für die Teams in der gewählten Alterklasse ermittelt. Will man für mehrere Altersklassen Serien ermitteln, dann muss man auch mehrere Serien erstellen. 

Im Tab **Regeln** kann man eine oder mehrere der verfügbaren Regeln konfigurieren.

Ist die Serie gespeichert, kann man mit der UID die Suche starten. Dafür muss das Command **t3sports:stats:calculate-series** ausgeführt werden:

```bash
$ ./bin/typo3 t3sports:stats:calculate-series --uid=4
```

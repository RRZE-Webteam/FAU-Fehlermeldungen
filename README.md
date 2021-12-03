# FAU Fehlermeldungen

## Download

GitHub-Repo: https://github.com/RRZE-Webteam/FAU-Fehlermeldungen

## Autor

RRZE-Webteam , http://www.rrze.fau.de

## Copryright

GNU General Public License (GPL) Version 3


## Beschreibung

Ausgabe von zusätzlichen wissenschaftlich basierten Nebeninformationen zu den 
Standard Fehlermeldungen 401, 403, 404 und 500 in WordPress-Themes.

Das Plugin FAU Fehlermeldungen generiert Fehlermeldungen abhängig vom Errorcode. 
Diese Fehlermeldungen zeigen einen Inhalt, der einen wissenschaftlichen Bezug 
zur Fehleraussage nimmt.
Es ist eine rein optionale Anreicherung der Meldung mit wissenschaftlichen 
Background anstelle eines lustiges Bildchen, wie es auf anderen Fehlerseiten 
im Internet üblich ist.

Die Fehlermeldung wird mittels eines Shortcodes erzeugt. Dieser Shortcode wird 
optional durch das Theme selbst in den Fehlerseiten eingebaut oder aber normal 
von Hand in Seiten eingebaut. Es werden pro Fehlercode mehrere Ausgaben vorgehalten. 
Die Auswahl erfolgt in der jeweiligen Gruppe (Fehlercode) per Zufall. 


## Aufruf via Shortcode

Der Shortcode `[fau_fehlermeldungen]` erhält aus Übergabe das Attribut `type`.

type kann folgende Werte enthalten:
- 403: Ausgabe eines der Fehlermeldungen im Template-Verzeichnis templates/403
- 404: Ausgabe eines der Fehlermeldungen im Template-Verzeichnis templates/403
- Andere Nummern: Ausgabe eines der Fehlermeldungen im Template-Verzeichnis templates/other

Beispiel:
```
[fau_fehlermeldungen type="404"]
```

## Einbau in Themes

In der jeweiligen Fehlerdatei (z.B. der für 404-Fehler) kann folgender Code eingebaut werden:

```php
if ( is_plugin_active( 'fau-fehlermeldungen/fau-fehlermeldungen.php' ) ) { 
	 <?php echo do_shortcode('[fau_fehlermeldungen type="404"]'); ?>
} ?>

```





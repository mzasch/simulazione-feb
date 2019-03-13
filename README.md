# Simulazione febbraio
Soluzione della **prima simulazione di seconda prova** del **28 febbraio 2019**.

---

#Struttura del repository
Il repository è composto da:

* `content`
* `images`
* `includes`
* `template`
* `.htaccess`
* `README.md`
* `index.php`
* `simulazione-db.sql`


## `content`
È la cartella contenente i file `php` della soluzione, ovvero:

* `404.php`
* `home.php`
* `report-utente.php`
* `scelta-report.php`
* `scelta-stazione.php`
* `stato-bici.php`
* `stato-stazione.php`

ognuno di essi è commentato in modo da spiegare i passaggi effettuati.

## `images`
Contiene due immagini di prova rappresentanti l'icona delle due stazioni di test
e il modello ER descritto anche nel documento di analisi.

## `includes`
Contiene il file `config.php` che definisce alcune impostazioni del sito e la
mappa di navigazione (cioè l'elenco delle pagine e la mappatura di ogni URL) e
il file `functions.php`, che contiene le funzioni richiamate nel template per 
la generazione della pagina.

## `template`
Contiene il foglio di stile `style.css`, unico per tutto il sito, insieme al
file `template.php`, che richiama le funzioni per la generazione della pagina.

## `.htaccess`
Contiene le direttive per il modulo `mod_rewrite` di Apache, che permette di
interpretare URL nella forma `sito/pagina` come `index.php?page=pagina`.

## `README.md`
Questo file.

## `index.php`
La pagina iniziale del sito: richiama le funzioni di inizializzazione del sito,
definite in `includes/functions.php`.

## `simulazione-db.sql`
Contiene il dump del database `simulazione` di prova, con la definizione delle tabelle e
l'inserimento dei dati di test.

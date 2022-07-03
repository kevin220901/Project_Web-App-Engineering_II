# Production Probleme beheben!
Falls `APP_ENV=prod` gesetzt ist kann es sein, dass die Seite 500ter Fehlermeldung erzeugt.

**Das liegt nicht ander Website!** Sondern daran, dass `WebAppProject/var/cache/prod` und alles in diesem Verzeichnis nicht auf `drwxrwxrwx` gesetzt ist!
Das passiert, wenn man `cache:clear` im prod ausführt! FIX: `chmod a+rwx var/cache/prod` & `chmod a+rwx var/cache/prod/*` im `WebAppProject` Verzeichnis (Root der Datenstruktur wo auch .env liegt!)

Falls man keine Datein hochladen kann, müssen die Dateiberechtigungen für den /uploads Ordner angepasst werden!



# Project_Web-App-Engineering_II

`git clone ...` im `/var/www/html` Verzeichnis

## Projektidee
Wir erstellen ein Web-Wiki, auf dem Communitys ihre eigenen Wikis erstellen und bearbeiten können.
Jeder User kann dabei Owner seines eigenen Wikis werden und andere User in das Wiki einladen. Genauso können außenstehende User Anfragen an ein Wiki stellen. Eingeladene User und User die Anfragen stellen werden zu Collaboratoren, welche Einträge erstellen und bearbeiten können.

Die Grundlegende Idee ist es, verschiedensten Communities einen Platz zum Austausch von Informationen, Tipps, Geschichten, etc. zu geben. 
Unsere Plattform soll durch seine Funktionsweise ein Platz für kollaboratives Schreiben, Spiele / Film / Musik / etc. Wikis und ähnliches sein.

## Vorgaben aus dem Referenz Dokument
*Einträge == Beiträge*

- [ ] Für jedes Wiki soll es eine Userverwaltung mit folgenden Rollen geben:
    - [ ] Owner     (Read/Write/Delete/Edit/Manage Users/Grant Moderator Role)
    - [ ] Moderator     (Read/Write/Delete/Edit/Manage Users)
    - [ ] Collaborator    (Read/Write/Edit)

- ✔ Wikis sollen eine Upvote-Funktion haben.

- ❌ Registrierung nur mit gültiger E-Mail. Account wird durch Bestätigungsmail verifiziert.
- ❌ Nicht bestätigte Accounts werden nach Ablauf einer Frist gelöscht.

- ✔ Admin-Seite für die gesamte Plattform
    - ❌ einzelne Wikis können deaktiviert (gesperrt werden)
    - ✔ User können gebannt werden
    - ✔ Admin Rechte können vergeben werden

- ✔ Admin-Seite pro Wiki
    - ✔ Platform Admins, Owner und Admins des Wikis können drauf zugreifen
    - ✔ Wiki Einträge können gelöscht / bearbeitet werden
    - ✔ Usern kann der Collaborator Status gegeben bzw. entfernt werden (Status kann nur bei Anfragen vergeben werden)
    - ✔ Wiki kann für nicht eingeladen bzw. nicht eingeloggte User unsichtbar gemacht werden
    - ✔ Das Wiki kann für alle zugänglich gemacht werden (Jeder kann Einträge erstellen und Editieren, solange man eingeloggt ist)
    - ✔ Einstellen, ob Einträge im Wiki bewerten werden können (Upvote / Downvote)
    - ❌ Wiki Designs können gewählt werden (bzw. konfiguriert werden)

- ✔ Einträge in einem Wiki können von Collaboratoren erstellt und verändert werden
- ❌ Die letzten 5 Versionen des Eintrags werden gespeichert und können von Admins wieder hergestellt werden

- [ ] User können:
    - ~ Wikis als Favoriten markieren und danach Filtern
    - ❌ Ihr Passwort / Ihre E-Mail ändern
        - ❌ Passwort wird mit Salt und gehashed gespeichert (Krypto Vorlesung)
    - ✔ Wikis als “Nicht Interessiert” markieren, um es auszublenden
    - ❌ Benachrichtigung's Einstellungen ändern
    - ❌ Account löschen
    - ❌ 2FA aktivieren (per Mail oder per 2FA App z.B. Google Authenticator) (TOTP)

- ❌ Benachrichtigung's Einstellungen umfassen:
    - ❌ Ob User über Einladungen in ein Wiki eine Mail erhalten sollen
    - ❌ Benachrichtigung über neue Beiträge in favorisierten Wikis
    - ❌ Ob Antrag an ein WIki abgelehnt / angenommen wurde
    - ❌ Ob die Benachrichtigung nur über die Website oder auch per Mail gemacht werden sollen

    - [ ] Einträge haben folgende Funktionen:
    - ❌ Neue Einträge können als Entwurf gespeichert werden
    - ✔ Man kann eine Preview von neuen Einträgen sehen
    - ✔ Text kann verschiedene Schriftfarben haben, dick, kursiv, etc, sein, Bilder können eingebunden werden, Tabellen und ähnliche Formationen sollen möglich sein
    - ❌ Einträge können gemeldet werden, je nach Grund geht die Meldung an die Moderatoren / Owner des Wikis oder direkt an die Plattform Admins
   
 - [ ] Pages:
    - [ ] Main-Page ist über Plattform-Dashboard bearbeitbar
    - [ ] Wiki-Admins können Pages über ihr Dashboard bearbeiten

### How to push :)
- `git checkout BRANCH`
- `git add --all`
- `git commit -m "MESSAGE"`
- `git push origin BRANCH`




### Funktion / User

| Funktion (Sichtbarkeit) / User | Plattform Admin | Wiki Owner | Wiki Admin | Collaborator | Normaler User | Unregistriert |
|---|---|---|---|---|---|---|
| User Einstellungen | ✔ | ✔ | ✔ | ✔ | ✔ | ❌ |
| Plattform Einstellungen | ✔ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Neues Wiki | ✔ | ✔ | ✔ | ✔ | ✔ | ❌ |
| Wiki Einstellungen | ✔ | ✔ | ✔ | ❌ | ❌ | ❌ | ❌ |
| Wikis Favorisieren / Verstecken / Bewerten | ✔ | ✔ | ✔ | ✔ | 〰 | ❌ |
| Beiträge einsehen | ✔ | ✔ | ✔ | ✔ | 〰 | 〰 |
| Beiträge erstellen | ✔ | ✔ | ✔ | ✔ | 〰 | ❌ |
| Beiträge bearbeiten | ✔ | ✔ | ✔ | ✔ | 〰 | ❌ |
| Beiträge löschen | ✔ | ✔ | ✔ | 💥 | 💥 | ❌ |


`Wikis Bewerten kann man nur, wenn man auch Beiträge einsehen kann!`

`〰` = Je nach Wiki Einstellung (Bei Bewerten/Favorisieren/Verstecken hängt es davon ab, ob man Beiträge überhaupt sehen kann)

`💥` = Nur wenn du den Beitrag selbst erstellt hast

# Wiki Arten
`Beispiel Wiki Einstellungen`
## Privates Wiki
- Das Wiki wird nicht im allgemeinen Wiki Browser angezeigt 
- Normale User können **keine** Beitrittsanfragen an das Wiki senden, um Collaborator zu werden
- Der Wiki Owner und die Wiki Admins können nur selbst Anfragen an Nutzer schicken

## View-only Wikis
- Jeder Besucher des Wikis (oder nur jeder Nutzer der eingeloggt ist) kann Beiträge einsehen und das Wiki bewerten
- Nur User >= Collaborator können Beiträge erstellen und bearbeiten
- Jeder kann Anfragen an das Wiki senden

## Öffentliche Wikis
- Jeder eingeloggte User kann Beiträge erstellen, bearbeiten und einsehen
- Wie Wikipedia nur dass man eingeloggt sein muss

## Wiki Einstellungen
`Beispiel wie die Einstellungen für ein Wiki aussehen können`

| Rechte / User | Normaler User | Unregistriert |
|---|---|---|
| Beiträge einsehen | ✔ | ❌ |
| Beiträge erstellen | ✔ | 🚫 |
| Beiträge bearbeiten | ❌ | 🚫 |
| Beitrittsanfragen erlauben | ✔ | 🚫 |
| Wiki ist sichtbar | ✔ | ✔ |





# Full Setup
## Einrichten der WSL Umgebung
### Installation von WSL
1. Ubuntu 20.04.4 LTS aus dem Windows Store herunterladen
2. WSL ausführen und wenn nötig das Kombatiblitätslayer über die Windows Einstellungen aktivieren

### Apache 2 installieren
1. `sudo apt-get update`
2. `sudo apt-get upgrade`
3. `sudo apt-get install apache2`
4. `sudo apache2ctl configtest`
5. `sudo a2enmod rewrite`
6. `sudo a2enmod headers`
7. `sudo service apache2 start`
    - `sudo service apache2 restart`

### MySql installieren
1. `sudo apt-get install mysql-server`
2. `sudo service mysql start`
3. `sudo mysql_secure_installation`
    - `y`, `1`, `YourMYSQLPassword`, `y`, `y`, `y`, `y`, `y`
4. `sudo mysql`
5. `SELECT user, authentication_string, plugin, host FROM mysql.user;`
6. `ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'YourMYSQLPassword';`
7. `FLUSH PRIVILEGES`
8. `exit`
9. `mysql -u root -p` + Dein Password
10. `CREATE USER 'WikiDB'@'localhost' IDENTIFIED BY 'B4db)%aHm84';`
11. `GRANT ALL PRIVILEGES ON *.* TO 'WikiDB'@'localhost' WITH GRANT OPTION;`

### PHP installieren
1.
```
sudo apt-get install php7.4 php7.4-cli php7.4-common php7.4-curl php7.4-gd php7.4-mbstring php7.4-mysql php7.4-xml libapache2-mod-php
```
2. `php -v` (Ist das Ergebnis 7.4.3 ?)
3. `sudo service apache2 restart`
4. `sudo chown YOUR_WSL_USER /var/www/html`
5. `sudo chmod 755 -R /var/www/html`

### phpMyAdmin
1. `sudo apt-get install phpmyadmin`
    - Es wird sich ein neues "Fenster" öffnen
2. `apache 2`, `yes` , `Neues_Password`
    - Es sollte eine Fehlermedlung kommen
3. `abort`
4. Als Root in mysql gehen
    - `mysql -u root -p` + Dein Password
5. `UNINSTALL COMPONENT "file://component_validate_password";`
6. `exit`
7. `sudo apt-get install phpmyadmin`
    - Hier das von oben wiederholen, bzw. fortsetzen
8. `mysql -u root -p` + Dein Password
9. `INSTALL COMPONENT "file://component_validate_password";`
10. `exit`
11. `sudo phpenmod mbstring` Merk dir `/etc/apache2/conf-enabled/`
12. `sudo service apache2 restart`
13. `sudo -H nano /etc/apache2/apache2.conf`
    - In der geöffneten Datei jetzt `Include /etc/phpmyadmin/apache.conf` einfügen und die Datei speichern!
14. `sudo service apache2 restart`
15. Jetzt solltet ihr euch unter `localhost/phpmyadmin` mit diesen Nutzerdaten einloggen können:
    - Username: `WikiDB`
    - Passwort: `B4db)%aHm84`

### Composer, Zip & Unzip
1. `sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"`
2. `sudo php composer-setup.php`
3. `sudo php -r "unlink('composer-setup.php');"`
4. `sudo mv composer.phar /usr/local/bin/composer`
5. `sudo apt-get install -y zip`

# Symfony Projekt erstellen!
1. `cd /var/www/html`
2. `composer create-project symfony/website-skeleton WebAppProject`, `y`
3. `cd /var/www/html/WebAppProject`
4. `php bin/console about`
5. `sudo nano /etc/apache2/sites-available/000-default.conf`
6. Folgenden Code in die Datei einfügen
```cmd
   ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/WebAppProject/public
    
    DirectoryIndex index.php
    <Directory "/var/www/html/WebAppProject/public">
        AllowOverride All
        Allow from All
        
        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ index.php [QSA,L]
        </IfModule>
    </Directory>
```
7. `sudo a2dissite 000-default`
8. `sudo a2ensite 000-default`
9. `sudo service apache2 restart`
10. Jetzt sollte man unter `localhost` eine Symfony Seite sehen!

# Repo klonen
Nachdem ihr das Symfony Projekt erstellt hab klont ihr das Verzeichnis irgendwo auf eure Festplatte.
Danach kopiert ihr den gesamten Inhalt des geklonten Verzeichnis und kopiert ihn in den Ordner des Symfony Projekts

(Die Datein im geklonten Verzeichnis sollen kopiert werden (also z.B. diese README.md, etc.))

Danach könnt ihr normal pushen und pullen (GitHub Desktop macht das dann nochmal viel leichter)
=======


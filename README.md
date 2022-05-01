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

- [ ] Wikis sollen eine Upvote-Funktion haben.

- [ ] Registrierung nur mit gültiger E-Mail. Account wird durch Bestätigungsmail verifiziert.
- [ ] Nicht bestätigte Accounts werden nach Ablauf einer Frist gelöscht.

- [ ] Admin-Seite für die gesamte Plattform
    - [ ] einzelne Wikis können deaktiviert (gesperrt werden)
    - [ ] User können gebannt werden
    - [ ] Admin Rechte können vergeben werden

- [ ] Admin-Seite pro Wiki
    - [ ] Platform Admins, Owner und Admins des Wikis können drauf zugreifen
    - [ ] Wiki Einträge können gelöscht / bearbeitet werden
    - [ ] Usern kann der Collaborator Status gegeben bzw. entfernt werden (Status kann nur bei Anfragen vergeben werden)
    - [ ] Wiki kann für nicht eingeladen bzw. nicht eingeloggte User unsichtbar gemacht werden
    - [ ] Das Wiki kann für alle zugänglich gemacht werden (Jeder kann Einträge erstellen und Editieren, solange man eingeloggt ist)
    - [ ] Einstellen, ob Einträge im Wiki bewerten werden können (Upvote / Downvote)
    - [ ] Wiki Designs können gewählt werden (bzw. konfiguriert werden)

- [ ] Einträge in einem Wiki können von Collaboratoren erstellt und verändert werden
- [ ] Die letzten 5 Versionen des Eintrags werden gespeichert und können von Admins wieder hergestellt werden

- [ ] User können:
    - [ ] Wikis als Favoriten markieren und danach Filtern
    - [ ] Ihr Passwort / Ihre E-Mail ändern
        - [ ] Passwort wird mit Salt und gehashed gespeichert (Krypto Vorlesung)
    - [ ] Wikis als “Nicht Interessiert” markieren, um es auszublenden
    - [ ] Benachrichtigung's Einstellungen ändern
    - [ ] Account löschen
    - [ ] 2FA aktivieren (per Mail oder per 2FA App z.B. Google Authenticator) (TOTP)

- [ ] Benachrichtigung's Einstellungen umfassen:
    - [ ] Ob User über Einladungen in ein Wiki eine Mail erhalten sollen
    - [ ] Benachrichtigung über neue Beiträge in favorisierten Wikis
    - [ ] Ob Antrag an ein WIki abgelehnt / angenommen wurde
    - [ ] Ob die Benachrichtigung nur über die Website oder auch per Mail gemacht werden sollen

    - [ ] Einträge haben folgende Funktionen:
    - [ ] Neue Einträge können als Entwurf gespeichert werden
    - [ ] Man kann eine Preview von neuen Einträgen sehen
    - [ ] Text kann verschiedene Schriftfarben haben, dick, kursiv, etc, sein, Bilder können eingebunden werden, Tabellen und ähnliche Formationen sollen möglich sein
    - [ ] Einträge können gemeldet werden, je nach Grund geht die Meldung an die Moderatoren / Owner des Wikis oder direkt an die Plattform Admins

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
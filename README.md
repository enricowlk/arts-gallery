# arts-gallery

# Übersicht
Diese Webanwendung für eine Kunstgalerie ermöglicht es Nutzern das Durchstöbern von Kunstwerken, Künstlern, Genres und Motiven. Die Anwendung bietet Funktionen für Benutzerregistrierung, Login, Favoritenverwaltung, Bewertungen und administratives Benutzermanagement.


# Hauptfunktionen:

# Nutzerfunktionen:
- Kunstwerke durchsuchen: Sammlung von Kunstwerken mit Sortieroptionen
- Künstler entdecken: Künstlerprofile und deren Werke
- Genres/Motive erkunden: Kunst nach Genre oder Motiv entdecken
- Benutzerkonten: Registrierung, Login und Profilverwaltung
- Favoriten: Künstler und Kunstwerke speichern
- Bewertungen: Bewertungen und Kommentare abgeben (für eingeloggte Nutzer)

# Admin-Funktionen:
- Benutzerverwaltung: Konten einsehen, bearbeiten und verwalten
- Rollenverwaltung: Nutzer zwischen Admin- und Standardrollen wechseln
- Kontostatus: Benutzerkonten aktivieren/deaktivieren
- Bewertungsmoderation: Unangemessene Bewertungen löschen

# Technische Details:
# Dateistruktur
# Hauptdateien:
- index.php - Startseite
- error.php - Fehlerseite
- styles.css - Haupt-Stylesheet

# Templates:
- navigation.php - Navigationsleiste
- footer.php - Fußzeile

# Seiten:
- browse_artists.php - Künstlerübersicht
- browse_artworks.php - Kunstwerkübersicht
- browse_genres.php - Genreübersicht
- browse_subjects.php - Motivübersicht
- site_artist.php - Künstlerdetailseite
- site_artwork.php - Kunstwerkdetailseite
- site_genre.php - Genredetailseite
- site_subject.php - Motivdetailseite
- site_favorites.php - Favoriten
- site_login.php - Login
- site_register.php - Registrierung
- site_myAccount.php - Mein Konto
- site_aboutUs.php - Über uns
- site_search_results.php - Suchergebnisse
- site_manage_users.php - Benutzerverwaltung (Admin)
- site_user_edit.php - Benutzer bearbeiten (Admin)

# Prozessdateien:
- login_process.php - Login-Verarbeitung
- logout_process.php - Logout
- register_process.php - Registrierung
- myAccount_process.php - Kontodaten aktualisieren
- favorites_process.php - Favoriten verwalten
- add_review.php - Bewertung hinzufügen
- delete_review.php - Bewertung löschen (Admin)
- user_edit_process.php - Benutzer bearbeiten (Admin)
- user_role_process.php - Rollen ändern (Admin)
- user_status_process.php - Kontostatus ändern (Admin)

# Klassen:
- database.php - Datenbankverbindung
- dbconfig.php - Datenbankkonfiguration
- logging.php - Logging
- global_exception_handler.php - Fehlerbehandlung

# Entitäten:
- artist.php - Künstler
- artwork.php - Kunstwerk
- genre.php - Genre
- subject.php - Motiv
- gallerie.php - Galerie
- customer.php - Kunde
- customerlogon.php - Login-Daten
- reviews.php - Bewertung

# Repositories:
- artistRepository.php - Künstler-DB-Zugriff
- artworkRepository.php - Kunstwerk-DB-Zugriff
- genreRepository.php - Genre-DB-Zugriff
- subjectRepository.php - Motiv-DB-Zugriff
- gallerieRepository.php - Galerie-DB-Zugriff
- customerRepository.php - Kunden-DB-Zugriff
- reviewRepository.php - Bewertungs-DB-Zugriff

# Hilfsdateien:
- carousel.php - Kunstwerk-Karussell
- top_artist.php - Top-Künstler
- top_artworks.php - Top-Kunstwerke
- recent_reviews.php - Aktuelle Bewertungen

# Datenbankschema
# Die Anwendung nutzt eine MySQL-Datenbank mit Tabellen für:
- Künstler
- Kunstwerke
- Genres
- Motive
- Galerien
- Kunden
- Logins
- Bewertungen
- Verknüpfungstabellen für viele-zu-viele-Beziehungen

# Verwendete Technologien
- XAMPP
- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3
- PDO für Datenbankzugriff


# Installation
1. Repository klonen

2. Datenbankschema aus der SQL-Datei importieren

3. Datenbankzugang in dbconfig.php konfigurieren

4. Schreibrechte für Log-Verzeichnis sicherstellen

5. Auf PHP-fähigen Webserver deployen

# Nutzung
# Für normale Nutzer
- Kunstwerke, Künstler, Genres und Motive durchstöbern
- Konto erstellen für Favoriten und Bewertungen
- Kontodaten im Bereich "Mein Konto" verwalten

# Für Administratoren
- Benutzerverwaltung über das Menü aufrufen
- Nutzerrollen anpassen
- Konten aktivieren/deaktivieren
- Unangemessene Bewertungen löschen

# Sicherheitsfeatures
- Passwort-Hashing
- Eingabevalidierung
- CSRF-Schutz
- Sitzungsverwaltung
- Rollenbasierte Zugriffskontrolle
- Fehlerprotokollierung

# Anpassung
- styles.css für Designänderungen anpassen
- Datenbankkonfiguration in dbconfig.php anpassen

# Lizenz
Dieses Projekt dient Lehrzwecken im Rahmen der Vorlesung "Web Development/Web-Technologies" an der TH Wildau. Nicht für kommerzielle Nutzung vorgesehen.


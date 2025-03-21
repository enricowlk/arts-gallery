<?php
/**
 * Klasse CustomerLogon
 * 
 * Repräsentiert die Anmeldedaten eines Kunden mit Eigenschaften wie Benutzername, Passwort und Benutzertyp.
 * Enthält Getter- und Setter-Methoden für den Zugriff auf die Eigenschaften.
 */
class CustomerLogon {
    // Eigenschaften der Klasse
    private $LogonID;    // Eindeutige ID der Anmeldung
    private $CustomerID; // ID des zugehörigen Kunden
    private $UserName;   // Benutzername des Kunden
    private $Password;   // Passwort des Kunden
    private $Type;       // Benutzertyp (Standard: 'user')

    /**
     * Konstruktor
     * 
     * Initialisiert die Anmeldedaten des Kunden.
     * 
     * Eingabe: LogonID, CustomerID, UserName, Password und optional der Benutzertyp (Standard: 'user').
     */
    public function __construct($LogonID, $CustomerID, $UserName, $Password, $Type = 'user') {
        $this->LogonID = $LogonID;
        $this->CustomerID = $CustomerID;
        $this->UserName = $UserName;
        $this->Password = $Password;
        $this->Type = $Type;
    }

    // Getter und Setter für LogonID
    public function getLogonID() {
        return $this->LogonID;
    }

    public function setLogonID($LogonID) {
        $this->LogonID = $LogonID;
    }

    // Getter und Setter für CustomerID
    public function getCustomerID() {
        return $this->CustomerID;
    }

    public function setCustomerID($CustomerID) {
        $this->CustomerID = $CustomerID;
    }

    // Getter und Setter für UserName
    public function getUserName() {
        return $this->UserName;
    }

    public function setUserName($UserName) {
        $this->UserName = $UserName;
    }

    // Getter und Setter für Password
    public function getPassword() {
        return $this->Password;
    }

    public function setPassword($Password) {
        $this->Password = $Password;
    }

    // Getter und Setter für Type
    public function getType() {
        return $this->Type;
    }

    public function setType($Type) {
        $this->Type = $Type;
    }
}
?>
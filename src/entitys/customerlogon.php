<?php
/**
 * Klasse zur Verwaltung von Kunden-Login-Daten
 * Enthält Authentifizierungsinformationen und Benutzerrechte
 */
class CustomerLogon {
    // Private Eigenschaften
    private $LogonID;     
    private $CustomerID; 
    private $UserName;   
    private $Password;   
    private $Type;       

    //Konstruktor für Login-Daten
    public function __construct($LogonID, $CustomerID, $UserName, $Password, $Type = 'user') { //Type standardmäßig auf user
        $this->LogonID = $LogonID;
        $this->CustomerID = $CustomerID;
        $this->UserName = $UserName;
        $this->Password = $Password;
        $this->Type = $Type;
    }

    // --- Getter-Methoden ---
    public function getLogonID() {
        return $this->LogonID;
    }

    public function getCustomerID() {
        return $this->CustomerID;
    }

    public function getUserName() {
        return $this->UserName;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function getType() {
        return $this->Type;
    }
}
?>
<?php
class CustomerLogon {
    private $LogonID;
    private $CustomerID;
    private $UserName;
    private $Password;
    private $Type;

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
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
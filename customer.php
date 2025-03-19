<?php
class Customer {
    private $CustomerID;
    private $FirstName;
    private $LastName;
    private $Address;
    private $City;
    private $Country;
    private $Postal;
    private $Phone;
    private $Email;

    public function __construct($CustomerID, $FirstName, $LastName, $Address, $City, $Country, $Postal, $Phone, $Email) {
        $this->CustomerID = $CustomerID;
        $this->FirstName = $FirstName;
        $this->LastName = $LastName;
        $this->Address = $Address;
        $this->City = $City;
        $this->Country = $Country;
        $this->Postal = $Postal;
        $this->Phone = $Phone;
        $this->Email = $Email;
    }

    // Getter und Setter für CustomerID
    public function getCustomerID() {
        return $this->CustomerID;
    }

    public function setCustomerID($CustomerID) {
        $this->CustomerID = $CustomerID;
    }

    // Getter und Setter für FirstName
    public function getFirstName() {
        return $this->FirstName;
    }

    public function setFirstName($FirstName) {
        $this->FirstName = $FirstName;
    }

    // Getter und Setter für LastName
    public function getLastName() {
        return $this->LastName;
    }

    public function setLastName($LastName) {
        $this->LastName = $LastName;
    }

    // Getter und Setter für Address
    public function getAddress() {
        return $this->Address;
    }

    public function setAddress($Address) {
        $this->Address = $Address;
    }

    // Getter und Setter für City
    public function getCity() {
        return $this->City;
    }

    public function setCity($City) {
        $this->City = $City;
    }

    // Getter und Setter für Country
    public function getCountry() {
        return $this->Country;
    }

    public function setCountry($Country) {
        $this->Country = $Country;
    }

    // Getter und Setter für Postal
    public function getPostal() {
        return $this->Postal;
    }

    public function setPostal($Postal) {
        $this->Postal = $Postal;
    }

    // Getter und Setter für Phone
    public function getPhone() {
        return $this->Phone;
    }

    public function setPhone($Phone) {
        $this->Phone = $Phone;
    }

    // Getter und Setter für Email
    public function getEmail() {
        return $this->Email;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }
}
?>
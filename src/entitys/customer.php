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

    public function getCustomerID() {
        return $this->CustomerID;
    }

    public function getFirstName() {
        return $this->FirstName;
    }

    public function getLastName() {
        return $this->LastName;
    }

    public function getAddress() {
        return $this->Address;
    }

    public function getCity() {
        return $this->City;
    }

    public function getCountry() {
        return $this->Country;
    }

    public function getPostal() {
        return $this->Postal;
    }

    public function getPhone() {
        return $this->Phone;
    }

    public function getEmail() {
        return $this->Email;
    }
}
?>
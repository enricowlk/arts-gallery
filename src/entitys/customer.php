<?php
/**
 * Class Customer
 *
 * Represents a customer entity.
 */
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

    /**
     * Customer constructor.
     *
     * @param int $CustomerID Customer ID
     * @param string $FirstName First name
     * @param string $LastName Last name
     * @param string $Address Street address
     * @param string $City City
     * @param string $Country Country
     * @param string $Postal Postal code
     * @param string $Phone Phone number
     * @param string $Email Email address
     */
    public function __construct(
        $CustomerID,
        $FirstName,
        $LastName,
        $Address,
        $City,
        $Country,
        $Postal,
        $Phone,
        $Email
    ) {
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

    /**
     * @return int Customer ID
     */
    public function getCustomerID() {
        return $this->CustomerID;
    }

    /**
     * @return string First name
     */
    public function getFirstName() {
        return $this->FirstName;
    }

    /**
     * @return string Last name
     */
    public function getLastName() {
        return $this->LastName;
    }

    /**
     * @return string Address
     */
    public function getAddress() {
        return $this->Address;
    }

    /**
     * @return string City
     */
    public function getCity() {
        return $this->City;
    }

    /**
     * @return string Country
     */
    public function getCountry() {
        return $this->Country;
    }

    /**
     * @return string Postal code
     */
    public function getPostal() {
        return $this->Postal;
    }

    /**
     * @return string Phone number
     */
    public function getPhone() {
        return $this->Phone;
    }

    /**
     * @return string Email address
     */
    public function getEmail() {
        return $this->Email;
    }
}

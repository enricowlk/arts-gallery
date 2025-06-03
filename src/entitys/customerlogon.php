<?php
/**
 * Class CustomerLogon
 *
 * Represents login credentials and access rights for a customer.
 */
class CustomerLogon {
    private $LogonID;
    private $CustomerID;
    private $UserName;
    private $Password;
    private $Type;

    /**
     * CustomerLogon constructor.
     *
     * @param int $LogonID Logon ID
     * @param int $CustomerID Customer ID
     * @param string $UserName Username (email)
     * @param string $Password Hashed password
     * @param string $Type User role (default: 'user')
     */
    public function __construct($LogonID, $CustomerID, $UserName, $Password, $Type = 'user') {
        $this->LogonID = $LogonID;
        $this->CustomerID = $CustomerID;
        $this->UserName = $UserName;
        $this->Password = $Password;
        $this->Type = $Type;
    }

    /**
     * @return int Logon ID
     */
    public function getLogonID() {
        return $this->LogonID;
    }

    /**
     * @return int Customer ID
     */
    public function getCustomerID() {
        return $this->CustomerID;
    }

    /**
     * @return string Username (email)
     */
    public function getUserName() {
        return $this->UserName;
    }

    /**
     * @return string Hashed password
     */
    public function getPassword() {
        return $this->Password;
    }

    /**
     * @return string User role (e.g., 'user' or 'admin')
     */
    public function getType() {
        return $this->Type;
    }
}
?>

<?php
// Include required classes
require_once __DIR__ . '/../entitys/customer.php';
require_once __DIR__ . '/../entitys/customerLogon.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Repository class for all customer-related database operations.
 */
class CustomerRepository {
    /**
     * @var Database $db Database connection instance
     */
    private $db;

    /**
     * Constructor with dependency injection.
     *
     * @param Database $db An instance of the database class
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves all customers with associated login data.
     *
     * @return array List of customers with login information
     * @throws Exception If a database error occurs
     */
    public function getAllCustomers() {
        try {
            $this->db->connect();
            $sql = 'SELECT customers.CustomerID, customers.FirstName, customers.LastName, customers.Email, customerlogon.Type, customerlogon.Pass
                    FROM customers 
                    JOIN customerlogon ON customers.CustomerID = customerlogon.CustomerID
                    ORDER BY customers.LastName, customers.FirstName';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching customers");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves a single customer by ID.
     *
     * @param int $customerID The customer ID
     * @return Customer|null A Customer object or null if not found
     * @throws Exception If a database error occurs
     */
    public function getCustomerByID($customerID) {
        try {
            $this->db->connect();
            $sql = 'SELECT * FROM customers WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            return new Customer(
                $row['CustomerID'],
                $row['FirstName'],
                $row['LastName'],
                $row['Address'],
                $row['City'],
                $row['Country'],
                $row['Postal'],
                $row['Phone'],
                $row['Email']
            );
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching customer");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves the full name of a customer by ID.
     *
     * @param int $customerId The customer ID
     * @return string Full name or 'Unknown'
     * @throws Exception If a database error occurs
     */
    public function getCustomerNameById($customerId) {
        try {
            $this->db->connect();
            $sql = 'SELECT FirstName, LastName FROM customers WHERE CustomerID = ?';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$customerId]);
            $row = $stmt->fetch();

            return $row ? $row['FirstName'] . ' ' . $row['LastName'] : 'Unknown';
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching customer name");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves customer data by email (username).
     *
     * @param Database $db Not used – ignored
     * @param string $email The email address (username)
     * @return array|null Customer data or null
     * @throws Exception If a database error occurs
     */
    public function getCustomerByEmail($db, $email) {
        try {
            $this->db->connect();
            $sql = 'SELECT customerlogon.*, customers.FirstName, customers.LastName
                    FROM customerlogon
                    INNER JOIN customers ON customerlogon.CustomerID = customers.CustomerID
                    WHERE customerlogon.UserName = :email';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching customer by email");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Checks whether an email address already exists.
     *
     * @param string $email Email address
     * @return bool True if email exists
     * @throws Exception If a database error occurs
     */
    public function emailExists($email) {
        try {
            $this->db->connect();
            $sql = 'SELECT * FROM customerlogon WHERE UserName = :email';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while checking email existence");
        } finally {
            $this->db->close();
        }
    }


    /**
     * Counts the number of administrators.
     *
     * @return int Number of administrators
     * @throws Exception If a database error occurs
     */
    public function countAdministrators() {
        try {
            $this->db->connect();
            $sql = 'SELECT COUNT(*) FROM customerlogon WHERE Type = 1';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while counting administrators");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Returns the user's role.
     *
     * @param int $customerID Customer ID
     * @return int 0 = User, 1 = Administrator
     * @throws Exception If a database error occurs
     */
    public function getUserRole($customerID) {
        try {
            $this->db->connect();
            $sql = 'SELECT Type FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while getting user role");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Checks if an email is already used by another user.
     *
     * @param string $email The email address to check
     * @param int $customerID The customer ID to exclude
     * @return bool True if the email exists for a different user
     * @throws Exception If a database error occurs
     */
    public function emailExistsForOtherUser($email, $customerID) {
        try {
            $this->db->connect();
            $sql = 'SELECT CustomerID FROM customerlogon WHERE UserName = :email AND CustomerID != :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $email, 'customerID' => $customerID]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while checking email existence");
        } finally {
            $this->db->close();
        }
    }
    
}

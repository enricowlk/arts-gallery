<?php
// Include required classes
require_once __DIR__ . '/../entitys/customer.php';
require_once __DIR__ . '/../entitys/customerLogon.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Repository class for all customer-related database operations.
 */
class CustomerServiceRepository {
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
     * Adds a new customer with password.
     *
     * @param Customer $customer Customer object
     * @param string $password Plain text password
     * @return int New customer ID
     * @throws Exception If a database error occurs
     */
    public function addCustomer($customer, $password) {
        try {
            $this->db->connect();
            $this->db->beginTransaction();

            $sql = 'INSERT INTO customerlogon (UserName, Pass, Type) VALUES (?, ?, 0)';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$customer->getEmail(), password_hash($password, PASSWORD_DEFAULT)]);
            $customerID = (int) $this->db->lastInsertId();

            $sql = 'INSERT INTO customers (CustomerID, FirstName, LastName, Address, City, Country, Postal, Phone, Email) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                $customerID,
                $customer->getFirstName(),
                $customer->getLastName(),
                $customer->getAddress(),
                $customer->getCity(),
                $customer->getCountry(),
                $customer->getPostal(),
                $customer->getPhone(),
                $customer->getEmail()
            ]);

            $this->db->commit();
            return $customerID;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Database error occurred while adding customer");
        } finally {
            $this->db->close();
        }
    }


        /**
     * Updates a customer's full profile including address and optionally password.
     *
     * @param int $customerID Customer ID
     * @param array $data Associative array of profile data
     * @param string|null $password Optional new password
     * @return bool True on success
     * @throws Exception If a database error occurs
     */
    public function updateUserProfile($customerID, $data, $password = null) {
        try {
            $this->db->connect();
            $this->db->beginTransaction();

            $sql = "UPDATE customers SET 
                    FirstName = :firstName, LastName = :lastName, Email = :email, 
                    Address = :address, City = :city, Country = :country, 
                    Postal = :postal, Phone = :phone 
                    WHERE CustomerID = :customerID";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(array_merge($data, ['customerID' => $customerID]));

            $sql = "UPDATE customerlogon SET UserName = :email WHERE CustomerID = :customerID";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $data['email'], 'customerID' => $customerID]);

            if ($password) {
                $sql = "UPDATE customerlogon SET Pass = :password WHERE CustomerID = :customerID";
                $stmt = $this->db->prepareStatement($sql);
                $stmt->execute([
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'customerID' => $customerID
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Database error occurred while updating user profile");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Sets the user role.
     *
     * @param int $customerID Customer ID
     * @param int $type 0 = user, 1 = admin
     * @return bool True on success
     * @throws Exception If a database error occurs
     */
    public function setUserRole($customerID, $type) {
        try {
            $this->db->connect();
            $sql = 'UPDATE customerlogon SET Type = :type WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(compact('type', 'customerID'));
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while setting user role");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Marks a user as inactive.
     *
     * @param int $customerID Customer ID
     * @return bool True on success
     * @throws Exception If a database error occurs
     */
    public function deactivateUser($customerID) {
        try {
            $this->db->connect();
            $this->db->beginTransaction();

            $sql = 'SELECT UserName FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $username = $stmt->fetchColumn();

            if (strpos($username, 'INACTIVE_') === 0) {
                $this->db->commit();
                return true;
            }

            $inactiveUsername = 'INACTIVE_' . $username;
            $sql = 'UPDATE customerlogon SET UserName = :username WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['username' => $inactiveUsername, 'customerID' => $customerID]);

            $sql = 'UPDATE customers SET Email = :email WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $inactiveUsername, 'customerID' => $customerID]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Database error occurred while deactivating user");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Reactivates a previously deactivated user.
     *
     * @param int $customerID Customer ID
     * @return bool True on success
     * @throws Exception If a database error occurs
     */
    public function reactivateUser($customerID) {
        try {
            $this->db->connect();
            $this->db->beginTransaction();

            $sql = 'SELECT UserName FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $username = $stmt->fetchColumn();

            if (strpos($username, 'INACTIVE_') !== 0) {
                $this->db->commit();
                return true;
            }

            $activeUsername = substr($username, 9);
            $sql = 'UPDATE customerlogon SET UserName = :username WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['username' => $activeUsername, 'customerID' => $customerID]);

            $sql = 'UPDATE customers SET Email = :email WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $activeUsername, 'customerID' => $customerID]);

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Database error occurred while reactivating user");
        } finally {
            $this->db->close();
        }
    }
}
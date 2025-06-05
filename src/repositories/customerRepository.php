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
     * Updates basic customer data (optionally the password).
     *
     * @param int $customerID Customer ID
     * @param string $firstName First name
     * @param string $lastName Last name
     * @param string $email Email address
     * @param string|null $password Optional new password
     * @throws Exception If a database error occurs
     */
    public function updateCustomer($customerID, $firstName, $lastName, $email, $password = null) {
        try {
            $this->db->connect();
            $this->db->beginTransaction();

            $sql = "UPDATE customers SET FirstName = :firstName, LastName = :lastName, Email = :email WHERE CustomerID = :customerID";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(compact('firstName', 'lastName', 'email', 'customerID'));

            $sql = $password
                ? "UPDATE customerlogon SET UserName = :email, Pass = :password WHERE CustomerID = :customerID"
                : "UPDATE customerlogon SET UserName = :email WHERE CustomerID = :customerID";

            $params = ['email' => $email, 'customerID' => $customerID];
            if ($password) {
                $params['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute($params);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Database error occurred while updating customer");
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
     * Resets the password of a customer.
     *
     * @param int $customerID Customer ID
     * @param string $newPassword New plain text password
     * @throws Exception If a database error occurs
     */
    public function resetPassword($customerID, $newPassword) {
        try {
            $this->db->connect();
            $sql = 'UPDATE customerlogon SET Pass = :password WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                'customerID' => $customerID
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while resetting password");
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

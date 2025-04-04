<?php
require_once 'customer.php';
require_once 'customerLogon.php';
require_once 'database.php';
require_once 'logging.php';

class CustomerRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

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
            Logging::LogError("Error in getAllCustomers: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching customers");
        } finally {
            $this->db->close();
        }
    }

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
            Logging::LogError("Error in getCustomerByID: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching customer");
        } finally {
            $this->db->close();
        }
    }

    public function getCustomerNameById($customerId) {
        try {
            $this->db->connect();
            $sql = 'SELECT FirstName, LastName FROM customers WHERE CustomerID = ?';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$customerId]);
            $row = $stmt->fetch();
            
            if ($row) {
                return $row['FirstName'] . ' ' . $row['LastName'];
            } else {
                return 'Unknown';
            }
        } catch (PDOException $e) {
            Logging::LogError("Error in getCustomerNameById: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching customer name");
        } finally {
            $this->db->close();
        }
    }

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
            Logging::LogError("Error in getCustomerByEmail: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching customer by email");
        } finally {
            $this->db->close();
        }
    }

    public function addCustomer($customer, $password) {
        try {
            $this->db->connect();
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // 1. Insert in customerlogon
            $sql = 'INSERT INTO customerlogon (UserName, Pass, Type) VALUES (?, ?, 0)';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$customer->getEmail(), password_hash($password, PASSWORD_DEFAULT)]);
    
            // 2. Insert in customers
            $customerID = $this->db->lastInsertId();
            
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
            
            // Commit transaction
            $this->db->commit();
            
            return $customerID;
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();
            Logging::LogError("Error in addCustomer: " . $e->getMessage());
            throw new Exception("Database error occurred while adding customer");
        } finally {
            $this->db->close();
        }
    }

    public function updateCustomer($customerID, $firstName, $lastName, $email, $password = null) {
        try {
            $this->db->connect();
            
            // Begin transaction
            $this->db->beginTransaction();
            
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // Update customers table
                $sql = "UPDATE customers SET FirstName = :firstName, LastName = :lastName, Email = :email WHERE CustomerID = :customerID";
                $stmt = $this->db->prepareStatement($sql);
                $stmt->execute([
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'customerID' => $customerID
                ]);
                
                // Update customerlogon table
                $sql = "UPDATE customerlogon SET UserName = :email, Pass = :password WHERE CustomerID = :customerID";
                $stmt = $this->db->prepareStatement($sql);
                $stmt->execute([
                    'email' => $email,
                    'password' => $hashedPassword,
                    'customerID' => $customerID
                ]);
            } else {
                // Update customers table
                $sql = "UPDATE customers SET FirstName = :firstName, LastName = :lastName, Email = :email WHERE CustomerID = :customerID";
                $stmt = $this->db->prepareStatement($sql);
                $stmt->execute([
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'email' => $email,
                    'customerID' => $customerID
                ]);
                
                // Update customerlogon table
                $sql = "UPDATE customerlogon SET UserName = :email WHERE CustomerID = :customerID";
                $stmt = $this->db->prepareStatement($sql);
                $stmt->execute([
                    'email' => $email,
                    'customerID' => $customerID
                ]);
            }
            
            // Commit transaction
            $this->db->commit();
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();
            Logging::LogError("Error in updateCustomer: " . $e->getMessage());
            throw new Exception("Database error occurred while updating customer");
        } finally {
            $this->db->close();
        }
    }
    
    public function updateUserProfile($customerID, $data, $password = null) {
        try {
            $this->db->connect();
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // Update customer table
            $sql = "UPDATE customers SET 
                    FirstName = :firstName, 
                    LastName = :lastName, 
                    Email = :email, 
                    Address = :address, 
                    City = :city, 
                    Country = :country, 
                    Postal = :postal, 
                    Phone = :phone 
                    WHERE CustomerID = :customerID";
                    
            $params = [
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'address' => $data['address'],
                'city' => $data['city'],
                'country' => $data['country'],
                'postal' => $data['postal'],
                'phone' => $data['phone'],
                'customerID' => $customerID
            ];
            
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute($params);
            
            // Update email in customerlogon table
            $sql = "UPDATE customerlogon SET UserName = :email WHERE CustomerID = :customerID";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $data['email'], 'customerID' => $customerID]);
            
            // If password provided, update it
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE customerlogon SET Pass = :password WHERE CustomerID = :customerID";
                $stmt = $this->db->prepareStatement($sql);
                $stmt->execute(['password' => $hashedPassword, 'customerID' => $customerID]);
            }
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();
            Logging::LogError("Error in updateUserProfile: " . $e->getMessage());
            throw new Exception("Database error occurred while updating user profile");
        } finally {
            $this->db->close();
        }
    }

    public function emailExists($email) {
        try {
            $this->db->connect();
            $sql = 'SELECT * FROM customerlogon WHERE UserName = :email';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            Logging::LogError("Error in emailExists: " . $e->getMessage());
            throw new Exception("Database error occurred while checking email existence");
        } finally {
            $this->db->close();
        }
    }

    public function resetPassword($customerID, $newPassword) {
        try {
            $this->db->connect();
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = 'UPDATE customerlogon SET Pass = :password WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'password' => $hashedPassword,
                'customerID' => $customerID
            ]);
        } catch (PDOException $e) {
            Logging::LogError("Error in resetPassword: " . $e->getMessage());
            throw new Exception("Database error occurred while resetting password");
        } finally {
            $this->db->close();
        }
    }
    
    public function setUserRole($customerID, $type) {
        try {
            $this->db->connect();
            $sql = 'UPDATE customerlogon SET Type = :type WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'type' => $type,
                'customerID' => $customerID
            ]);
            return true;
        } catch (PDOException $e) {
            Logging::LogError("Error in setUserRole: " . $e->getMessage());
            throw new Exception("Database error occurred while setting user role");
        } finally {
            $this->db->close();
        }
    }
    
    public function countAdministrators() {
        try {
            $this->db->connect();
            $sql = 'SELECT COUNT(*) FROM customerlogon WHERE Type = 1';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            Logging::LogError("Error in countAdministrators: " . $e->getMessage());
            throw new Exception("Database error occurred while counting administrators");
        } finally {
            $this->db->close();
        }
    }
    
    public function deactivateUser($customerID) {
        try {
            $this->db->connect();
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // Get current username
            $sql = 'SELECT UserName FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $username = $stmt->fetchColumn();
            
            // Check if already inactive
            if (strpos($username, 'INACTIVE_') === 0) {
                $this->db->commit();
                return true;
            }
            
            // Update username to mark as inactive
            $inactiveUsername = 'INACTIVE_' . $username;
            $sql = 'UPDATE customerlogon SET UserName = :username WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'username' => $inactiveUsername,
                'customerID' => $customerID
            ]);
            
            // Update email in customers table
            $sql = 'UPDATE customers SET Email = :email WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'email' => $inactiveUsername,
                'customerID' => $customerID
            ]);
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();
            Logging::LogError("Error in deactivateUser: " . $e->getMessage());
            throw new Exception("Database error occurred while deactivating user");
        } finally {
            $this->db->close();
        }
    }
    
    public function reactivateUser($customerID) {
        try {
            $this->db->connect();
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // Get current username
            $sql = 'SELECT UserName FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $username = $stmt->fetchColumn();
            
            // Check if actually inactive
            if (strpos($username, 'INACTIVE_') !== 0) {
                $this->db->commit();
                return true;
            }
            
            // Update username to reactivate (remove INACTIVE_ prefix)
            $activeUsername = substr($username, 9);
            $sql = 'UPDATE customerlogon SET UserName = :username WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'username' => $activeUsername,
                'customerID' => $customerID
            ]);
            
            // Update email in customers table
            $sql = 'UPDATE customers SET Email = :email WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'email' => $activeUsername,
                'customerID' => $customerID
            ]);
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();
            Logging::LogError("Error in reactivateUser: " . $e->getMessage());
            throw new Exception("Database error occurred while reactivating user");
        } finally {
            $this->db->close();
        }
    }
    
    public function getUserRole($customerID) {
        try {
            $this->db->connect();
            $sql = 'SELECT Type FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            Logging::LogError("Error in getUserRole: " . $e->getMessage());
            throw new Exception("Database error occurred while getting user role");
        } finally {
            $this->db->close();
        }
    }
    
    public function emailExistsForOtherUser($email, $customerID) {
        try {
            $this->db->connect();
            $sql = 'SELECT CustomerID FROM customerlogon WHERE UserName = :email AND CustomerID != :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['email' => $email, 'customerID' => $customerID]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            Logging::LogError("Error in emailExistsForOtherUser: " . $e->getMessage());
            throw new Exception("Database error occurred while checking email existence");
        } finally {
            $this->db->close();
        }
    }
}
?>
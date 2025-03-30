<?php
require_once 'customer.php'; // Bindet die Customer-Klasse ein
require_once 'customerLogon.php'; // Bindet die CustomerLogon-Klasse ein
require_once 'database.php'; // Bindet die Database-Klasse ein

/**
 * Klasse CustomerRepository
 * 
 * Verwaltet den Zugriff auf die Kundendaten in der Datenbank.
 * Enthält Methoden zum Hinzufügen, Aktualisieren und Abrufen von Kunden sowie zur Passwortverwaltung.
 */
class CustomerRepository {
    private $db; // Datenbankverbindung

    /**
     * Konstruktor
     * 
     * Eingabe: Die Datenbankverbindung.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Get all users with their details
     */
    public function getAllCustomers() {
        $this->db->connect();
        $sql = 'SELECT customers.CustomerID, customers.FirstName, customers.LastName, customers.Email, customerlogon.Type, customerlogon.Pass
                FROM customers 
                JOIN customerlogon ON customers.CustomerID = customerlogon.CustomerID
                ORDER BY customers.LastName, customers.FirstName';
    $stmt = $this->db->prepareStatement($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $this->db->close();
    return $result;
    }

    /**
     * Holt die Kundendaten anhand der CustomerID.
     * 
     * Eingabe: Die CustomerID.
     * Ausgabe: Ein Customer-Objekt oder `null`, wenn kein Kunde gefunden wurde.
     */
    public function getCustomerByID($customerID) {
        $this->db->connect();
        $sql = 'SELECT * FROM customers WHERE CustomerID = :customerID';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['customerID' => $customerID]);
        $row = $stmt->fetch();

        $this->db->close();

        if ($row) {
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
        } else {
            return null; // Kein Kunde gefunden
        }
    }

    /**
     * Holt den Namen eines Kunden anhand der CustomerID.
     * 
     * Eingabe: Die CustomerID.
     * Ausgabe: Der vollständige Name des Kunden oder 'Unknown', wenn kein Kunde gefunden wurde.
     */
    public function getCustomerNameById($customerId) {
        $this->db->connect();
        $sql = 'SELECT FirstName, LastName FROM customers WHERE CustomerID = ?';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$customerId]);
        $row = $stmt->fetch();

        $this->db->close();
        
        if ($row) {
            return $row['FirstName'] . ' ' . $row['LastName'];
        } else {
            return 'Unknown'; // Kein Kunde gefunden
        }
    }

    /**
     * Holt die Kundendaten anhand der E-Mail-Adresse.
     * 
     * Eingabe: Die E-Mail-Adresse.
     * Ausgabe: Ein Array mit den Kundendaten oder `null`, wenn kein Kunde gefunden wurde.
     */
    public function getCustomerByEmail($db, $email) {
        $this->db->connect();
        $sql = 'SELECT customerlogon.*, customers.FirstName, customers.LastName
                FROM customerlogon
                INNER JOIN customers ON customerlogon.CustomerID = customers.CustomerID
                 WHERE customerlogon.UserName = :email';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $this->db->close();
        return $user;
    }

    /**
     * Fügt einen neuen Kunden in die Datenbank ein.
     * 
     * Eingabe: Ein Customer-Objekt und das Passwort des Kunden.
     * Aktion: Fügt den Kunden in die `customers`-Tabelle und die Anmeldedaten in die `customerlogon`-Tabelle ein.
     */
    public function addCustomer($customer, $password) {
        $this->db->connect();
        
        try {
            // 1. Insert in customerlogon (Type immer 0 für neue User)
            $sql='INSERT INTO customerlogon (UserName, Pass, Type) VALUES (?, ?, 0)';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$customer->getEmail(),password_hash($password, PASSWORD_DEFAULT)]);
    
            // 2. Insert in customers
            $customerID = $this->db->lastInsertId();
            
            $sql= 'INSERT INTO customers (CustomerID, FirstName, LastName, Address, City, Country, Postal, Phone, Email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
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
    
            return $customerID; // Rückgabe der neuen ID bei Erfolg
            
        } catch (PDOException $e) {
            // Fehler automatisch geworfen
            return false;
        } finally {
            $this->db->close();
        }
    }

    /**
     * Aktualisiert die Kontodaten eines Kunden.
     * 
     * Eingabe: CustomerID, Vorname, Nachname, E-Mail und optional ein neues Passwort.
     * Aktion: Aktualisiert die Kundendaten in der `customers`-Tabelle und die Anmeldedaten in der `customerlogon`-Tabelle.
     */
    public function updateCustomer($customerID, $firstName, $lastName, $email, $password = null) {
        $this->db->connect();
        if ($password) {
            // Passwort aktualisieren
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE customers SET FirstName = :firstName, LastName = :lastName, Email = :email WHERE CustomerID = :customerID;
                    UPDATE customerlogon SET UserName = :email, Pass = :password WHERE CustomerID = :customerID";
        } else {
            // Ohne Passwort aktualisieren
            $sql = "UPDATE customers SET FirstName = :firstName, LastName = :lastName, Email = :email WHERE CustomerID = :customerID;
                    UPDATE customerlogon SET UserName = :email WHERE CustomerID = :customerID";
        }

        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => isset($hashedPassword) ? $hashedPassword : null,
            'customerID' => $customerID
        ]);
        $this->db->close();
    }
    
    /**
     * Updates a user's complete profile information including address details
     * 
     * @param int $customerID The ID of the customer to update
     * @param array $data Array of all customer information to update
     * @param string|null $password Optional new password
     * @return bool Success status
     */
    public function updateUserProfile($customerID, $data, $password = null) {
        $this->db->connect();
        
        try {
            // Update customer table with all profile information
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
            
            return true;
        } catch (PDOException $e) {
            return false;
        } finally {
            $this->db->close();
        }
    }

    /**
     * Überprüft, ob eine E-Mail bereits existiert.
     * 
     * Eingabe: Die E-Mail-Adresse.
     * Ausgabe: `true`, wenn die E-Mail existiert, sonst `false`.
     */
    public function emailExists($email) {
        $this->db->connect();
        $sql= 'SELECT * FROM customerlogon WHERE UserName = :email';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['email' => $email]);

        $this->db->close();
        return $stmt->fetch() !== false;
    }

    /**
     * Setzt das Passwort eines Kunden zurück.
     * 
     * Eingabe: CustomerID und das neue Passwort.
     * Aktion: Aktualisiert das Passwort in der `customerlogon`-Tabelle.
     */
    public function resetPassword($customerID, $newPassword) {
        $this->db->connect();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = 'UPDATE customerlogon SET Pass = :password WHERE CustomerID = :customerID';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([
            'password' => $hashedPassword,
            'customerID' => $customerID
        ]);
        $this->db->close();
    }
    
    /**
     * Set a user's role (administrator or regular user)
     *
     * @param int $customerID The ID of the customer
     * @param int $type The type (0 = regular user, 1 = administrator)
     * @return bool Success status
     */
    public function setUserRole($customerID, $type) {
        $this->db->connect();
        
        try {
            $sql = 'UPDATE customerlogon SET Type = :type WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([
                'type' => $type,
                'customerID' => $customerID
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        } finally {
            $this->db->close();
        }
    }
    
    /**
     * Count the number of administrators in the system
     *
     * @return int The number of administrators
     */
    public function countAdministrators() {
        $this->db->connect();
        $sql = 'SELECT COUNT(*) FROM customerlogon WHERE Type = 1';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $this->db->close();
        return $count;
    }
    
    /**
     * Deactivate a user (set their status to inactive without deleting)
     * This is done by adding "INACTIVE_" prefix to their email/username
     * 
     * @param int $customerID The ID of the customer to deactivate
     * @return bool Success status
     */
    public function deactivateUser($customerID) {
        $this->db->connect();
        
        try {
            // Get current username
            $sql = 'SELECT UserName FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $username = $stmt->fetchColumn();
            
            // Check if already inactive
            if (strpos($username, 'INACTIVE_') === 0) {
                return true; // Already inactive
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
            
            return true;
        } catch (PDOException $e) {
            return false;
        } finally {
            $this->db->close();
        }
    }
    
    /**
     * Reactivate a user who has been previously deactivated
     * Removes the "INACTIVE_" prefix from their email/username
     * 
     * @param int $customerID The ID of the customer to reactivate
     * @return bool Success status
     */
    public function reactivateUser($customerID) {
        $this->db->connect();
        
        try {
            // Get current username
            $sql = 'SELECT UserName FROM customerlogon WHERE CustomerID = :customerID';
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['customerID' => $customerID]);
            $username = $stmt->fetchColumn();
            
            // Check if actually inactive
            if (strpos($username, 'INACTIVE_') !== 0) {
                return true; // Already active
            }
            
            // Update username to reactivate (remove INACTIVE_ prefix)
            $activeUsername = substr($username, 9); // Remove first 9 chars (INACTIVE_)
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
            
            return true;
        } catch (PDOException $e) {
            return false;
        } finally {
            $this->db->close();
        }
    }
    
    /**
     * Get the current user role (type) from the customerlogon table
     *
     * @param int $customerID The ID of the customer
     * @return int The user type (0 = regular user, 1 = administrator)
     */
    public function getUserRole($customerID) {
        $this->db->connect();
        $sql = 'SELECT Type FROM customerlogon WHERE CustomerID = :customerID';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['customerID' => $customerID]);
        $type = (int)$stmt->fetchColumn();
        $this->db->close();
        return $type;
    }
    
    /**
     * Check if an email exists for any user other than the specified customer
     *
     * @param string $email The email to check
     * @param int $customerID The ID of the customer to exclude from the check
     * @return bool True if the email exists for another user, false otherwise
     */
    public function emailExistsForOtherUser($email, $customerID) {
        $this->db->connect();
        $sql = 'SELECT CustomerID FROM customerlogon WHERE UserName = :email AND CustomerID != :customerID';
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute(['email' => $email, 'customerID' => $customerID]);
        $exists = $stmt->fetch() !== false;
        $this->db->close();
        return $exists;
    }
}
?>
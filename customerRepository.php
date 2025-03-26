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
            'password' => $hashedPassword,
            'customerID' => $customerID
        ]);
        $this->db->close();
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
}
?>
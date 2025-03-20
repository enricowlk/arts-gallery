<?php
require_once 'Customer.php';
require_once 'CustomerLogon.php';
require_once 'database.php';

class CustomerRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Fügt einen neuen Kunden in die customer-Tabelle ein.
     */
    public function addCustomer($customer, $password) {
        
        $this->db->connect();
        // Transaktion starten, um sicherzustellen, dass beide Einfügeoperationen erfolgreich sind
        $this->db->beginTransaction();
    
        try {
            // Zuerst den Datensatz in customerlogon einfügen
            $stmt = $this->db->prepareStatement("
                INSERT INTO customerlogon (UserName, Pass, Type)
                VALUES (:email, :password, :type)
            ");
            $stmt->execute([
                'email' => $customer->getEmail(), // E-Mail als username
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'type' => 'user'
            ]);
    
            // CustomerID des neu eingefügten Datensatzes abrufen
            $customerID = $this->db->lastInsertId();
    
            // Dann den Datensatz in customers einfügen
            $stmt = $this->db->prepareStatement("
                INSERT INTO customers (CustomerID, FirstName, LastName, Address, City, Country, Postal, Phone, Email)
                VALUES (:customer_id, :first_name, :last_name, :address, :city, :country, :postal, :phone, :email)
            ");
            $stmt->execute([
                'customer_id' => $customerID,
                'first_name' => $customer->getFirstName(),
                'last_name' => $customer->getLastName(),
                'address' => $customer->getAddress(),
                'city' => $customer->getCity(),
                'country' => $customer->getCountry(),
                'postal' => $customer->getPostal(),
                'phone' => $customer->getPhone(),
                'email' => $customer->getEmail()
            ]);
    
            // Transaktion bestätigen
            $this->db->commit();
        } catch (PDOException $ex) {
            // Bei einem Fehler die Transaktion rückgängig machen
            $this->db->rollBack();
            throw $ex;
        }

        $this->db->close();
    }

    /**
     * Überprüft, ob eine E-Mail bereits existiert.
     */
    public function emailExists($email) {
        $this->db->connect();
        $stmt = $this->db->prepareStatement("SELECT * FROM customerlogon WHERE UserName = :email");
        $stmt->execute(['email' => $email]);

        $this->db->close();

        return $stmt->fetch() !== false;
    }

    /**
     * Aktualisiert die Kontodaten eines Kunden.
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
            'password' => $hashedPassword ?? null,
            'customerID' => $customerID
        ]);
        $this->db->close();
    }

    /**
     * Setzt das Passwort eines Kunden zurück.
     */
    public function resetPassword($customerID, $newPassword) {
        $this->db->connect();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepareStatement("UPDATE customerlogon SET Pass = :password WHERE CustomerID = :customerID");
        $stmt->execute([
            'password' => $hashedPassword,
            'customerID' => $customerID
        ]);
        $this->db->close();
    }

    /**
     * Holt die Kundendaten anhand der CustomerID.
     */
    public function getCustomerByID($customerID) {
        $this->db->connect();
        $stmt = $this->db->prepareStatement("SELECT * FROM customers WHERE CustomerID = :customerID");
        $stmt->execute(['customerID' => $customerID]);

        $this->db->close();
        return $stmt->fetch();
    }


    public function getCustomerNameById($customerId) {
        $this->db->connect();
        $stmt = $this->db->prepareStatement("SELECT FirstName, LastName FROM customers WHERE CustomerID = ?");
        $stmt->execute([$customerId]);
        $row = $stmt->fetch();

        $this->db->close();
        
        if ($row) {
            return $row['FirstName'] . ' ' . $row['LastName'];
        } else {
            return 'Unknown';
        }
    }

    public function getCustomerByEmail($db, $email) {
        $this->db->connect();
        $stmt = $this->db->prepareStatement("SELECT customerlogon.*, customers.FirstName, customers.LastName
        FROM customerlogon
        INNER JOIN customers ON customerlogon.CustomerID = customers.CustomerID
        WHERE customerlogon.UserName = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        $this->db->close();
        return $user;
    }
}
?>
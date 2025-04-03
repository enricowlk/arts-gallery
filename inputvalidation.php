<?php
class InputValidation {
    // Validate email format
    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }
        return null;
    }

    // Validate password strength
    public static function validatePassword($password) {
        if (strlen($password) < 8) {
            return "Password must be at least 8 characters long";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return "Password must contain at least one uppercase letter";
        }
        if (!preg_match('/[a-z]/', $password)) {
            return "Password must contain at least one lowercase letter";
        }
        if (!preg_match('/[0-9]/', $password)) {
            return "Password must contain at least one number";
        }
        return null;
    }

    // Validate name fields (first name, last name) - Updated for a-z, A-Z, öüäß, spaces, hyphens
    public static function validateName($name, $fieldName) {
        if (empty($name)) {
            return "$fieldName is required";
        }
        if (!preg_match('/^[a-zA-ZäöüÄÖÜß\s]+$/', $name)) {
            return "$fieldName can only contain letters (a-z, A-Z, äöüÄÖÜß), spaces, and hyphens";
        }
        if (strlen($name) > 50) {
            return "$fieldName must be less than 50 characters";
        }
        return null;
    }

    // Validate address fields
    public static function validateAddress($address) {
        if (empty($address)) {
            return "Address is required";
        }
        if (strlen($address) > 100) {
            return "Address must be less than 100 characters";
        }
        return null;
    }

    // Validate city - Updated for Umlaute
    public static function validateCity($city) {
        if (empty($city)) {
            return "City is required";
        }
        if (!preg_match('/^[a-zA-ZäöüÄÖÜß\s\-]+$/', $city)) {
            return "City can only contain letters (a-z, A-Z, äöüÄÖÜß), spaces, and hyphens";
        }
        if (strlen($city) > 50) {
            return "City must be less than 50 characters";
        }
        return null;
    }

    // Validate country - Updated for Umlaute
    public static function validateCountry($country) {
        if (empty($country)) {
            return "Country is required";
        }
        if (!preg_match('/^[a-zA-ZäöüÄÖÜß\s\-]+$/', $country)) {
            return "Country can only contain letters (a-z, A-Z, äöüÄÖÜß), spaces, and hyphens";
        }
        if (strlen($country) > 50) {
            return "Country must be less than 50 characters";
        }
        return null;
    }

    // Validate postal code 
    public static function validatePostal($postal) {
        if (empty($postal)) {
            return "Postal code is required";
        }
        if (!preg_match('/^[a-zA-Z0-9\s\-]+$/', $postal)) {
            return "Postal code can only contain letters, numbers, spaces, and hyphens";
        }
        if (strlen($postal) > 20) {
            return "Postal code must be less than 20 characters";
        }
        return null;
    }

    // Validate phone number 
    public static function validatePhone($phone) {
        if (!empty($phone) && !preg_match('/^[\d\s\-\+\(\)]{6,20}$/', $phone)) {
            return "Phone number can only contain digits, spaces, and +-() characters (6-20 chars)";
        }
        return null;
    }

    // Validate password confirmation 
    public static function validatePasswordConfirmation($password, $confirmPassword) {
        if (!empty($password) && $password !== $confirmPassword) {
            return "Passwords do not match";
        }
        return null;
    }

    // Validate user type (admin/regular) 
    public static function validateUserType($type) {
        if (!in_array($type, [0, 1])) {
            return "Invalid user type";
        }
        return null;
    }

    // --- Ab hier bleiben die Methoden unverändert ---
    // Validate all registration fields
    public static function validateRegistration($data) {
        $errors = [];

        // Validate each field
        if ($error = self::validateEmail($data['email'])) {
            $errors['email'] = $error;
        }

        if ($error = self::validatePassword($data['password'])) {
            $errors['password'] = $error;
        }

        if ($error = self::validateName($data['first_name'], "First name")) {
            $errors['first_name'] = $error;
        }

        if ($error = self::validateName($data['last_name'], "Last name")) {
            $errors['last_name'] = $error;
        }

        if ($error = self::validateCity($data['city'])) {
            $errors['city'] = $error;
        }

        if ($error = self::validateCountry($data['country'])) {
            $errors['country'] = $error;
        }

        if ($error = self::validatePostal($data['postal'])) {
            $errors['postal'] = $error;
        }

        if ($error = self::validateAddress($data['address'])) {
            $errors['address'] = $error;
        }

        if ($error = self::validatePhone($data['phone'])) {
            $errors['phone'] = $error;
        }

        return $errors;
    }

    // Validate all account update fields
    public static function validateAccountUpdate($data) {
        $errors = [];

        // Validate each field
        if ($error = self::validateEmail($data['email'])) {
            $errors['email'] = $error;
        }

        if (!empty($data['password'])) {
            if ($error = self::validatePassword($data['password'])) {
                $errors['password'] = $error;
            }
            
            if ($error = self::validatePasswordConfirmation($data['password'], $data['confirmPassword'])) {
                $errors['confirmPassword'] = $error;
            }
        }

        if ($error = self::validateName($data['firstName'], "First name")) {
            $errors['firstName'] = $error;
        }

        if ($error = self::validateName($data['lastName'], "Last name")) {
            $errors['lastName'] = $error;
        }

        return $errors;
    }

    // Validate all user edit fields (admin)
    public static function validateUserEdit($data) {
        $errors = [];

        // Validate each field
        if ($error = self::validateEmail($data['email'])) {
            $errors['email'] = $error;
        }

        if (!empty($data['password'])) {
            if ($error = self::validatePassword($data['password'])) {
                $errors['password'] = $error;
            }
            
            if ($error = self::validatePasswordConfirmation($data['password'], $data['confirmPassword'])) {
                $errors['confirmPassword'] = $error;
            }
        }

        if ($error = self::validateName($data['firstName'], "First name")) {
            $errors['firstName'] = $error;
        }

        if ($error = self::validateName($data['lastName'], "Last name")) {
            $errors['lastName'] = $error;
        }

        if ($error = self::validateCity($data['city'])) {
            $errors['city'] = $error;
        }

        if ($error = self::validateCountry($data['country'])) {
            $errors['country'] = $error;
        }

        if ($error = self::validatePostal($data['postal'])) {
            $errors['postal'] = $error;
        }

        if ($error = self::validateAddress($data['address'])) {
            $errors['address'] = $error;
        }

        if ($error = self::validatePhone($data['phone'])) {
            $errors['phone'] = $error;
        }

        if ($error = self::validateUserType($data['userType'])) {
            $errors['userType'] = $error;
        }

        return $errors;
    }
}
?>
<?php
// Set header
header("Content-Type: text/xml; charset=utf-8");

// Konfigurasi database
$db_host = 'localhost';
$db_name = 'soap_api';
$db_user = 'root';
$db_pass = '';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Class untuk Contact dan Address Service
class ContactAddressService {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getContact($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM contacts WHERE id = ?");
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new SoapFault('Server', $e->getMessage());
        }
    }

    public function createContact($firstName, $lastName, $email, $phone, $userId) {
        try {
            // Verifikasi user_id exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $stmt->execute(array($userId));
            if (!$stmt->fetch()) {
                throw new SoapFault('Client', 'User ID tidak ditemukan');
            }

            $stmt = $this->db->prepare("INSERT INTO contacts (first_name, last_name, email, phone, user_id) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute(array(
                $firstName,
                $lastName,
                $email,
                $phone,
                $userId
            ));
        } catch (PDOException $e) {
            throw new SoapFault('Server', $e->getMessage());
        }
    }

    public function getAddress($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM addresses WHERE id = ?");
            $stmt->execute(array($id));
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new SoapFault('Server', $e->getMessage());
        }
    }

    public function createAddress($street, $city, $province, $country, $postalCode, $contactId) {
        try {
            // Verifikasi contact_id exists
            $stmt = $this->db->prepare("SELECT id FROM contacts WHERE id = ?");
            $stmt->execute(array($contactId));
            if (!$stmt->fetch()) {
                throw new SoapFault('Client', 'Contact ID tidak ditemukan');
            }

            $stmt = $this->db->prepare("INSERT INTO addresses (street, city, province, country, postal_code, contact_id) VALUES (?, ?, ?, ?, ?, ?)");
            return $stmt->execute(array(
                $street,
                $city,
                $province,
                $country,
                $postalCode,
                $contactId
            ));
        } catch (PDOException $e) {
            throw new SoapFault('Server', $e->getMessage());
        }
    }
}

// Inisialisasi SOAP Server dengan WSDL
$server = new SoapServer("http://localhost/soap_api/service.wsdl", array(
    'cache_wsdl' => WSDL_CACHE_NONE
));
$service = new ContactAddressService($db);
$server->setObject($service);

// Handle request
$server->handle(); 
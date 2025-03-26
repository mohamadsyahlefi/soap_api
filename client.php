<?php
try {
    // Inisialisasi SOAP Client
    $client = new SoapClient("http://localhost/soap_api/service.wsdl", array(
        'trace' => 1,
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_NONE
    ));

    // Test Contact Service
    echo "Testing Contact Service:\n";
    
    // Create Contact
    try {
        $result = $client->createContact(
            'John',          // firstName
            'Doe',           // lastName
            'john@example.com', // email
            '1234567890',    // phone
            1               // userId
        );
        echo "Create Contact: " . ($result ? "Success" : "Failed") . "\n";
    } catch (SoapFault $e) {
        echo "Error creating contact: " . $e->getMessage() . "\n";
    }

    // Get Contact
    try {
        $contact = $client->getContact(1);  // id
        echo "Get Contact: ";
        print_r($contact);
        echo "\n";
    } catch (SoapFault $e) {
        echo "Error getting contact: " . $e->getMessage() . "\n";
    }

    // Test Address Service
    echo "\nTesting Address Service:\n";
    
    // Create Address
    try {
        $result = $client->createAddress(
            '123 Main St',   // street
            'City',          // city
            'Province',      // province
            'Country',       // country
            '12345',        // postalCode
            1               // contactId
        );
        echo "Create Address: " . ($result ? "Success" : "Failed") . "\n";
    } catch (SoapFault $e) {
        echo "Error creating address: " . $e->getMessage() . "\n";
    }

    // Get Address
    try {
        $address = $client->getAddress(1);  // id
        echo "Get Address: ";
        print_r($address);
        echo "\n";
    } catch (SoapFault $e) {
        echo "Error getting address: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (isset($client)) {
        echo "\nRequest Headers:\n" . $client->__getLastRequestHeaders() . "\n";
        echo "Request:\n" . $client->__getLastRequest() . "\n";
        echo "Response Headers:\n" . $client->__getLastResponseHeaders() . "\n";
        echo "Response:\n" . $client->__getLastResponse() . "\n";
    }
}
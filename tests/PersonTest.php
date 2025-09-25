<?php

require_once 'entities/Person.php';

class PersonTest {
    public function testPersonCreation() {
        echo "Testing Person entity creation...\n";

        // Create the person table
        Person::createTable();
        echo "✓ Person table created\n";

        // Create a new person
        $person = new Person();
        $person->fill([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1-555-0123',
            'date_of_birth' => '1990-05-15'
        ]);

        $savedPerson = $person->save();
        echo "✓ Person created with ID: " . $savedPerson->id . "\n";

        // Test full name method
        echo "✓ Full name: " . $person->getFullName() . "\n";

        // Test age calculation
        echo "✓ Age: " . $person->getAge() . " years\n";

        // Test status methods
        echo "✓ Is active: " . ($person->isActive() ? 'Yes' : 'No') . "\n";

        // Test finding by email
        $foundPerson = Person::findByEmail('john.doe@example.com');
        if (!empty($foundPerson)) {
            echo "✓ Found person by email: " . $foundPerson[0]->getFullName() . "\n";
        }

        // Test status update
        $person->deactivate();
        echo "✓ Person deactivated. Status: " . $person->status . "\n";

        $person->activate();
        echo "✓ Person activated. Status: " . $person->status . "\n";

        echo "\nAll Person entity tests passed!\n";
        return true;
    }

    public function runTests() {
        try {
            $this->testPersonCreation();
            echo "\n✅ All tests completed successfully!\n";
        } catch (Exception $e) {
            echo "\n❌ Test failed: " . $e->getMessage() . "\n";
            return false;
        }
    }
}

// Run tests if this file is executed directly
if (basename(__FILE__) == basename($_SERVER["SCRIPT_NAME"])) {
    $test = new PersonTest();
    $test->runTests();
}
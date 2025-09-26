<?php

require_once 'entities/PersonCredential.php';
require_once 'entities/Person.php';

class PersonCredentialTest {
    private $testPersonId;
    private $testCredential;
    private $testUsername;

    public function setUp() {
        Person::createTable();
        PersonCredential::createTable();

        $person = new Person();
        $person->fill([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test' . time() . '@example.com',
            'phone' => '+1-555-0123'
        ]);
        $person->save();
        $this->testPersonId = $person->toArray()['id'];

        echo "✓ Test Person ID: " . $this->testPersonId . "\n";
    }

    public function testCredentialCreation() {
        echo "Testing PersonCredential creation...\n";

        $this->testUsername = 'testuser' . time();

        $credential = new PersonCredential();
        $credential->fill([
            'person_id' => $this->testPersonId,
            'username' => $this->testUsername
        ]);
        $credential->setPassword('SecurePass123!');

        $savedCredential = $credential->save();
        $this->testCredential = $savedCredential;

        echo "✓ PersonCredential created with ID: " . $savedCredential->id . "\n";
        echo "✓ Username: " . $credential->username . "\n";
        echo "✓ Password hash stored securely\n";

        return true;
    }

    public function testPasswordVerification() {
        echo "\nTesting password verification...\n";

        $isValid = $this->testCredential->verifyPassword('SecurePass123!');
        echo "✓ Correct password verification: " . ($isValid ? 'Pass' : 'Fail') . "\n";

        $isInvalid = $this->testCredential->verifyPassword('WrongPassword');
        echo "✓ Incorrect password verification: " . (!$isInvalid ? 'Pass' : 'Fail') . "\n";

        return $isValid && !$isInvalid;
    }

    public function testSecurityQuestions() {
        echo "\nTesting security questions...\n";

        $this->testCredential->setSecurityQuestion(1, "What is your mother's maiden name?", "Johnson");
        $this->testCredential->setSecurityQuestion(2, "What was your first pet's name?", "Fluffy");
        $this->testCredential->setSecurityQuestion(3, "What city were you born in?", "New York");
        $this->testCredential->save();

        echo "✓ Security questions set\n";

        $answer1Valid = $this->testCredential->verifySecurityAnswer(1, "Johnson");
        $answer2Valid = $this->testCredential->verifySecurityAnswer(2, "fluffy");
        $answer3Valid = $this->testCredential->verifySecurityAnswer(3, "new york");
        $answerInvalid = $this->testCredential->verifySecurityAnswer(1, "Smith");

        echo "✓ Security answer 1 verification: " . ($answer1Valid ? 'Pass' : 'Fail') . "\n";
        echo "✓ Security answer 2 verification (case insensitive): " . ($answer2Valid ? 'Pass' : 'Fail') . "\n";
        echo "✓ Security answer 3 verification (case insensitive): " . ($answer3Valid ? 'Pass' : 'Fail') . "\n";
        echo "✓ Invalid security answer rejection: " . (!$answerInvalid ? 'Pass' : 'Fail') . "\n";

        return $answer1Valid && $answer2Valid && $answer3Valid && !$answerInvalid;
    }

    public function testLoginAttempts() {
        echo "\nTesting login attempts and account locking...\n";

        $result1 = $this->testCredential->login($this->testUsername, 'WrongPassword');
        echo "✓ Failed login attempt: " . $result1['message'] . "\n";

        $result2 = $this->testCredential->login($this->testUsername, 'SecurePass123!');
        echo "✓ Successful login: " . $result2['message'] . "\n";

        for ($i = 0; $i < 5; $i++) {
            $this->testCredential->login($this->testUsername, 'WrongPassword');
        }

        $lockedResult = $this->testCredential->login($this->testUsername, 'SecurePass123!');
        echo "✓ Account locked after failed attempts: " . $lockedResult['message'] . "\n";

        $this->testCredential->resetLoginAttempts();
        $unlockedResult = $this->testCredential->login($this->testUsername, 'SecurePass123!');
        echo "✓ Account unlocked after reset: " . $unlockedResult['message'] . "\n";

        return !$result1['success'] && $result2['success'] && !$lockedResult['success'] && $unlockedResult['success'];
    }

    public function testPasswordReset() {
        echo "\nTesting password reset functionality...\n";

        $securityAnswers = [
            1 => 'Johnson',
            2 => 'Fluffy',
            3 => 'New York'
        ];

        $resetResult = $this->testCredential->resetPassword($securityAnswers, 'NewSecurePass456!');
        echo "✓ Password reset with correct security answers: " . $resetResult['message'] . "\n";

        $loginWithNewPassword = $this->testCredential->login($this->testUsername, 'NewSecurePass456!');
        echo "✓ Login with new password: " . $loginWithNewPassword['message'] . "\n";

        $loginWithOldPassword = $this->testCredential->login($this->testUsername, 'SecurePass123!');
        echo "✓ Login with old password rejected: " . $loginWithOldPassword['message'] . "\n";

        $wrongAnswers = [
            1 => 'Smith',
            2 => 'Buddy',
            3 => 'Boston'
        ];
        $failedReset = $this->testCredential->resetPassword($wrongAnswers, 'AnotherPassword');
        echo "✓ Password reset with wrong answers: " . $failedReset['message'] . "\n";

        return $resetResult['success'] && $loginWithNewPassword['success'] && !$loginWithOldPassword['success'] && !$failedReset['success'];
    }

    public function testPasswordResetToken() {
        echo "\nTesting password reset token functionality...\n";

        $token = $this->testCredential->generatePasswordResetToken();
        echo "✓ Password reset token generated\n";

        $isTokenValid = $this->testCredential->isPasswordResetTokenValid($token);
        echo "✓ Token validation: " . ($isTokenValid ? 'Valid' : 'Invalid') . "\n";

        $isWrongTokenValid = $this->testCredential->isPasswordResetTokenValid('wrong_token');
        echo "✓ Wrong token rejection: " . (!$isWrongTokenValid ? 'Rejected' : 'Accepted') . "\n";

        $this->testCredential->clearPasswordResetToken();
        $isTokenValidAfterClear = $this->testCredential->isPasswordResetTokenValid($token);
        echo "✓ Token invalid after clear: " . (!$isTokenValidAfterClear ? 'Invalid' : 'Valid') . "\n";

        return $isTokenValid && !$isWrongTokenValid && !$isTokenValidAfterClear;
    }

    public function testStaticMethods() {
        echo "\nTesting static methods...\n";

        $foundByUsername = PersonCredential::findByUsername($this->testUsername);
        echo "✓ Find by username: " . ($foundByUsername ? 'Found' : 'Not found') . "\n";

        $foundByPersonId = PersonCredential::findByPersonId($this->testPersonId);
        echo "✓ Find by person ID: " . ($foundByPersonId ? 'Found' : 'Not found') . "\n";

        $authResult = PersonCredential::authenticate($this->testUsername, 'NewSecurePass456!');
        echo "✓ Static authenticate method: " . $authResult['message'] . "\n";

        return $foundByUsername && $foundByPersonId && $authResult['success'];
    }

    public function testActivationDeactivation() {
        echo "\nTesting account activation/deactivation...\n";

        $this->testCredential->deactivate();
        $deactivatedLogin = $this->testCredential->login($this->testUsername, 'NewSecurePass456!');
        echo "✓ Login on deactivated account: " . $deactivatedLogin['message'] . "\n";

        $this->testCredential->activate();
        $activatedLogin = $this->testCredential->login($this->testUsername, 'NewSecurePass456!');
        echo "✓ Login on activated account: " . $activatedLogin['message'] . "\n";

        return !$deactivatedLogin['success'] && $activatedLogin['success'];
    }

    public function runTests() {
        try {
            echo "🔧 Setting up test environment...\n";
            $this->setUp();

            echo "\n=== PersonCredential Entity Tests ===\n";

            $test1 = $this->testCredentialCreation();
            $test2 = $this->testPasswordVerification();
            $test3 = $this->testSecurityQuestions();
            $test4 = $this->testLoginAttempts();
            $test5 = $this->testPasswordReset();
            $test6 = $this->testPasswordResetToken();
            $test7 = $this->testStaticMethods();
            $test8 = $this->testActivationDeactivation();

            if ($test1 && $test2 && $test3 && $test4 && $test5 && $test6 && $test7 && $test8) {
                echo "\n✅ All PersonCredential tests completed successfully!\n";
                return true;
            } else {
                echo "\n❌ Some tests failed!\n";
                return false;
            }

        } catch (Exception $e) {
            echo "\n❌ Test failed with exception: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
            return false;
        }
    }
}

if (basename(__FILE__) == basename($_SERVER["SCRIPT_NAME"])) {
    $test = new PersonCredentialTest();
    $test->runTests();
}
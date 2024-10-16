<?php
require 'vendor/autoload.php';

use OpenAPI\OpenAPI\Discussion;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class DiscussionTester {
    private $sdk;
    private $logger;

    public function __construct($securityToken, LoggerInterface $logger) {
        $this->sdk = Discussion::builder()->setSecurity($securityToken)->build();
        $this->logger = $logger;
    }

    public function testListDiscussionsForRepo($owner, $repo) {
        try {
            $response = $this->sdk->listDiscussionsForRepo($owner, $repo);

            if ($response->responseBodies) {
                $this->logger->info("Test Passed: Discussions retrieved successfully.", ['discussions' => $response->responseBodies]);
                echo "Test Passed: Discussions retrieved successfully.\n";
            } else {
                $this->logger->warning("Test Failed: No discussions retrieved.");
                echo "Test Failed: No discussions retrieved.\n";
            }
        } catch (Exception $e) {
            $this->logger->error("Error occurred while retrieving discussions: " . $e->getMessage());
            echo "Error occurred while retrieving discussions: " . $e->getMessage() . "\n";
        }
    }
}

// Set up logging
$logger = new Logger('discussion_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));

// Security token
$token = getenv('GITHUB_PERSONAL_ACCESS_TOKEN_2');
$securityToken = $token;

// Create tester instance
$tester = new DiscussionTester($securityToken, $logger);

// Run the test
$tester->testListDiscussionsForRepo('himeshp', 'discussion-php-2');
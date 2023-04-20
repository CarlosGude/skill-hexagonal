<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class RequestContext implements Context
{
    private ?Response $response = null;

    /** @var array<string|int, string> */
    private array $responseContent = [];

    public function __construct(
        private readonly KernelInterface $kernel
    ) {
    }

    /**
     * @When the demo scenario sends a request to :path
     *
     * @throws \Exception
     */
    public function theDemoScenarioSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then /^the response code must be (\d+)$/
     */
    public function theResponseCodeMustBe(int $responseCode): void
    {
        if (!$this->response) {
            throw new \RuntimeException('Unable to access the response before visiting a page');
        }

        if ($responseCode !== $this->response->getStatusCode()) {
            throw new \RuntimeException('The response code expected is '.$responseCode.' but the code is '.$this->response->getStatusCode());
        }
    }

    /**
     * @Then the response should be received a JSON
     */
    public function theResponseShouldBeReceivedAJson(): void
    {
        if (null === $this->response) {
            throw new \RuntimeException('No response received');
        }

        $content = $this->response->getContent();
        if (!$content) {
            throw new \RuntimeException('The response is empty.');
        }

        /** @var array<int|string, string> $response */
        $response = json_decode($content, true);
        $this->responseContent = $response;
        if (empty($this->responseContent)) {
            throw new \RuntimeException('The response is not a valid Json');
        }
    }

    /**
     * @Then /^the response must contain a key called "([^"]*)"$/
     */
    public function theResponseMustContainAKeyCalled(string $keyName): void
    {
        if (!array_key_exists($keyName, $this->responseContent)) {
            throw new \RuntimeException('The response not content the key '.$keyName);
        }
    }

    /**
     * @Then the response :keyName must be equals to :value
     */
    public function theResponseKeyNameMustBeEqualsTo(string $keyName, string|int|float $value): void
    {
        $this->theResponseMustContainAKeyCalled($keyName);

        if ($this->responseContent[$keyName] !== $value) {
            throw new \RuntimeException('The response with the key '.$keyName.' is not equals to '.$value.' The value is '.$this->responseContent[$keyName]);
        }
    }

    /**
     * @Then the response :keyName must be a :type
     */
    public function theResponseKeyNameMustBeAType(string $keyName, string $type): void
    {
        $this->theResponseMustContainAKeyCalled($keyName);

        $functionType = 'is_'.$type;

        if (!function_exists($functionType)) {
            throw new \RuntimeException('The type sent not exist');
        }

        if (!$functionType($this->responseContent[$keyName])) {
            throw new \RuntimeException('The value type is not the expected.');
        }
    }

    /**
     * @Then /^the response contains (\d+) or more elements$/
     */
    public function theResponseContainsOrMoreElements(int $count): void
    {
        if ($count < count($this->responseContent)) {
            throw new \RuntimeException('The array not have '.$count.' or more elements');
        }
    }

    /**
     * @Then /^the element (\d+) of response must contains the key "([^"]*)"$/
     */
    public function theElementOfResponseMustContainsTheKey(int $pos, string $keyName): void
    {
        if (!array_key_exists($keyName, (array) $this->responseContent[$pos])) {
            throw new \RuntimeException('The response not content the key '.$keyName);
        }
    }

    /**
     * @Then /^the element (\d+) of response with the key "([^"]*)" must be an "([^"]*)"$/
     */
    public function theElementOfResponseWithTheKeyMustBeAn(int $pos, string $keyName, string $type): void
    {
        if (!array_key_exists($pos, $this->responseContent)) {
            throw new \RuntimeException('The element not exist');
        }

        $element = (array) $this->responseContent[$pos];
        if (!array_key_exists($keyName, $element)) {
            throw new \RuntimeException('The element not exist');
        }

        $value = $element[$keyName];

        if ('email' === $type) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('The value is not an email.');
            }

            return;
        }

        $functionType = 'is_'.$type;

        if (!function_exists($functionType)) {
            throw new \RuntimeException('The type sent not exist');
        }

        if (!$functionType($value)) {
            throw new \RuntimeException('The value type is not the expected.');
        }
    }
}

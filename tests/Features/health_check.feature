Feature: Health check

  Scenario: Checking the health check end point
    When the demo scenario sends a request to "/"
    Then the response should be received a JSON
    Then the response must contain a key called "status"
    Then the response must contain a key called "seed"
    Then the response "status" must be equals to "ok"
    Then the response "seed" must be a "string"




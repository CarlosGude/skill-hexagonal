Feature: Get Authors

  Scenario: Get all authors
    Given the following authors exist:
      | name    | email           |
      | John Doe | john@example.com |
    When the demo scenario sends a request to "/api/authors"
    Then the response should be received a JSON
    Then the response code must be 200
    Then the response contains 1 or more elements
    Then the element 0 of response must contains the key "name"
    Then the element 0 of response must contains the key "email"
    Then the element 0 of response must contains the key "articles"
    Then the element 0 of response with the key "name" must be an "string"
    Then the element 0 of response with the key "email" must be an "email"
    Then the element 0 of response with the key "articles" must be an "array"

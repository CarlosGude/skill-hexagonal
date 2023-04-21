Feature: Get Authors

  Scenario: Get all authors
    Given the following articles exist:
      | name     | email            | title      | body     |
      | John Doe | john@example.com | Title test | Body test|
    When the demo scenario sends a request to "/api/authors"
    Then the response should be received a JSON
    Then the response code must be 200
    Then the response contains 1 or more elements
    Then the element 0 of response must contains the key "name"
    Then the element 0 of response must contains the key "email"
    Then the element 0 of response must contains the key "articles"
    Then the element 0 of response with the key "uuid" must be an "string"
    Then the element 0 of response with the key "name" must be an "string"
    Then the element 0 of response with the key "email" must be an "email"
    Then the element 0 of response with the key "articles" must be an "array"

  Scenario: Get an author
    Given the following articles exist:
      | name     | email            | title      | body     |
      | John Doe | john@example.com | Title test | Body test|
    When the demo scenario sends a request to "/api/authors" with the uuid of "john@example.com"
    Then the response should be received a JSON
    Then the response code must be 200
    Then the response must contain a key called "name"
    Then the response must contain a key called "email"
    Then the response must contain a key called "articles"
    Then the response "uuid" must be a "string"
    Then the response "name" must be a "string"
    Then the response "email" must be a "email"
    Then the first element of "articles" of response with the key "uuid" must be an "string"
    Then the first element of "articles" of response with the key "title" must be an "string"
    Then the first element of "articles" of response with the key "body" must be an "string"
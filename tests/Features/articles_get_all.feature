Feature: Get Articles

  Scenario: Get all articles
    Given the following articles exist:
      | name     | email            | title      | body     |
      | John Doe | john@example.com | Title test | Body test|
    When the demo scenario sends a request to "/api/articles"
    Then the response code must be 200
    Then the response should be received a JSON
    Then the response contains 1 or more elements
    Then the element 0 of response must contains the key "title"
    Then the element 0 of response must contains the key "body"
    Then the element 0 of response must contains the key "author"
    Then the element 0 of response with the key "uuid" must be an "string"
    Then the element 0 of response with the key "title" must be an "string"
    Then the element 0 of response with the key "body" must be an "string"
    Then the element 0 of response with the key "author" must be an "array"

  Scenario: Get an author
    Given the following articles exist:
      | name     | email            | title      | body     |
      | John Doe | john@example.com | Title test | Body test|
    When the demo scenario sends a request to "/api/articles" with the uuid of the article "Title test"
    Then the response should be received a JSON
    Then the response code must be 200
    Then the response must contain a key called "uuid"
    Then the response must contain a key called "title"
    Then the response must contain a key called "body"
    Then the response must contain a key called "author"
    Then the response "uuid" must be a "string"
    Then the response "title" must be a "string"
    Then the response "body" must be a "string"
    Then the response "author" must be a "array"
    Then the element "author" of response with the key "uuid" must be an "string"
    Then the element "author" of response with the key "name" must be an "string"
    Then the element "author" of response with the key "email" must be an "email"
Feature: Post Articles

  Scenario: Post an article
    Given the following user exist:
      | name     | email           |
      | John Doe | john@example.com|
    Given the user "john@example.com" wants create an article with this body:
      | title      | body     |
      | Title test | Body test|
    When the demo scenario sends a POST request to "/api/articles" with the given body
    Then the response should be received a JSON
    Then the response code must be 201
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

  Scenario: Post an article without title
    Given the following user exist:
      | name     | email           |
      | John Doe | john@example.com|
    Given the user "john@example.com" wants create an article with this body:
      | title      | body     |
      |  | Body test|
    When the demo scenario sends a POST request to "/api/articles" with the given body
    Then the response should be received a JSON
    Then the response code must be 400
    Then the response must contain a key called "title"
    Then the response "title" must be equals to "This value can not be null or empty."


  Scenario: Post an article without user
    Given the user "not_exist@example.com" wants create an article with this body:
      | title      | body      |
      | Title test | Body test |
    When the demo scenario sends a POST request to "/api/articles" with the given body
    Then the response should be received a JSON
    Then the response code must be 400
    Then the response must contain a key called "author"
    Then the response "author" must be equals to "This value can not be null or empty."
Feature: Testing social REST services
  In order to maintain user information through the services
  as a service user
  I want to see if the services work as expected

  Scenario: Creating a New User
    Given that I want to add a new user
    And that name of the user is "James Bond"
    And that age of the user is "27"
    When I request "/users"
    Then the response status code should be 200
    And the response is JSON

  Scenario: Finding an Existing User
    Given that I want to find an user
    When I request "/users/{2}"
    Then the response status code should be 200
    And the response is JSON
    And in the response name of the user is "Gilbert Abbott a Beckett"
    And in the response age of the user is "30"

  Scenario: Deleting Existing and Non-existing User
    Given that I want to delete an user
    And I request "/users/{8}"
    Then the response status code should be 200
    And the response is JSON
    And the response should contain field "id"
    Given that I want to delete an user
    And I request "/users/1000"
    Then the response status code should be 400
    And the response is not JSON
    And the response should be "Unable to delete because the user does not exist."
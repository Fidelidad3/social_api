Feature: Testing user relations in social REST services
  In order to maintain user relation through the services
  as a service user
  I want to see if the services work as expected

  Scenario: Sending friend request
    Given that I want to send a friend request
    And that userId of the user is "{6}"
    When I request "/users/{3}/requests"
    Then the response status code should be 200
    And the response is JSON
    And the response should contain field "id"

  Scenario: Get friend requests list
    Given that I want to find an friends request
    When I request "/users/{5}/requests"
    Then the response status code should be 200
    And the response is JSON
    And the response should be:
      | name                   |
      | Lascelles Abercrombie  |
      | J. R. Ackerley         |


  Scenario: Rejecting friend request
    Given that I want to reject a friend
    When I request "/users/{5}/requests/{6}"
    Then the response status code should be 204

  Scenario: Approving friend request
    Given that I want to approve a new friend
    And that userId of the user is "{4}"
    When I request "/users/{5}/requests"
    Then the response status code should be 200

  Scenario: Get user friend list
    Given that I want to find an friends request
    When I request "/users/{4}/friends"
    Then the response status code should be 200
    And the response is JSON
    And the response should be:
      | name                     |
      | George Abbot             |
      | J. R. Ackerley           |
      | Gilbert Abbott a Beckett |
      | Paul Ableman             |

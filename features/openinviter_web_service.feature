Feature: Open Inviter Web Service

    From a Rails application,
    I want to send a provider, username, password and get back JSON list of contacts
    So that we can send health club passes to each member

Background:

	Given I have a gmail account clubleads@gmail.com with password "baseball"
	And a contact "sue@test.com"
	And a contact "eric@test.com"

	Given I have a gmail account clubleads.nocontacts@gmail.com with password "baseball"
	And no contacts

Scenario: Successfully fetch the contact list from each provider

	  When I POST to /get_contacts?service=gmail&username=clubleads@gmail.com&password=baseball
	  Then I should receive a SUCCESS response with the JSON array [{"email":"sue@test.com"},{"email":"eric@test.com"}]

Scenario: Bad Username

	When I POST to /get_contacts?service=gmail&username=bademail_address&password=baseball
	Then I should receive a FAIL response with the JSON {message:"Bad Username or Password"}
	
Scenario: Bad Password

	When I POST to /get_contacts?service=gmail&username=joe@gmail.com&password=badpassword
	Then I should receive a FAIL response with the JSON {message:"Bad Username or Password"}

Scenario: Successful Login But No Contacts Exist

	When I POST to /get_contacts?service=gmail&username=clubleads.nocontacts@gmail.com&password=badpassword
	Then I should receive a FAIL response with the JSON {message:"Bad Username or Password"}

Scenario: Username has the email extension on it (e.g. "clubleads@gmail.com" instead of "clubleads")
    ## TODO: Need to look at the current index.php file to see how that works
    ## For some providers, we need to trim off the gmail.com part

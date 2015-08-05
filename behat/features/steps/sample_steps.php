<?php
/**
 * @author   Demin Yin <deminy@deminy.net>
 * @license  MIT license
 */
use Behat\Behat\Context\Step;

/**
 * Convert string "user ID" to "id".
 *
 * @param   string  $fieldName  Field name.
 *
 * @return  string
 */
function getVarName($fieldName)
{
    return lcfirst(
        implode(
            '',
            array_map(
                function($val) {
                    return ucfirst($val);
                },
                explode(' ', strtolower($fieldName))
            )
        )
    );
}

/**
 * @var Behat\Behat\Definition\Loader\ClosuredDefinitionLoader $steps
 */
$steps->Given(
    '/^that (.*) of the user is "([^"]*)"$/',
    function(FeatureContext $world, $fieldName, $fieldValue) {
        $fieldValue = strtr($fieldValue, $world->getNodeReplaceList());
        $world->setData(getVarName($fieldName), $fieldValue);
    }
);

/**
 * @var Behat\Behat\Definition\Loader\ClosuredDefinitionLoader $steps
 */
$steps->Given(
    '/^that (.*) of the request is "([^"]*)"$/',
    function(FeatureContext $world, $fieldName, $fieldValue) {
        $fieldValue = strtr($fieldValue, $world->getNodeReplaceList());
        $world->setData(getVarName($fieldName), $fieldValue);
    }
);

$steps->Given(
    '/^in the response (.*) of the user is "([^"]*)"$/',
    function(FeatureContext $world, $fieldName, $fieldValue) {
        return array(
            new Step\Then(sprintf('field "%s" in the response should be "%s"', getVarName($fieldName), $fieldValue)),
        );
    }
);

/**
 * A demostration showing how to access sub-context and how to make assertions from under closured context.
 */
$steps->Given(
    '/^in the response there should be no field called "([^"]*)"$/',
    function(FeatureContext $world, $fieldName) {
        $responseData = json_decode($world->getSubcontext('RestContext')->getResponse()->getBody(true));
        assertObjectNotHasAttribute($fieldName, $responseData);
    }
);

$steps->Then(
    '/^I\'m changing (.*) of the user to "([^"]*)"$/',
    function(FeatureContext $world, $fieldName, $fieldValue) {
        $world->setData(getVarName($fieldName), $fieldValue);
    }
);

$steps->Given('/^the response should be:$/',
    function($world, $table) {
        $responseData = json_decode($world->getSubcontext('RestContext')->getResponse()->getBody(true), true);
        $assertData = [];
        $hash = $table->getHash();
        foreach ($hash as $row) {
            $assertData[] = $row['name'];
        }
        if (array_diff($assertData, $responseData)) {
            throw new \Exception('Content is not equals');
        }
});



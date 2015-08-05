<?php
/**
 * @author   Demin Yin <deminy@deminy.net>
 * @license  MIT license
 */
use App\Http\Controllers\Controller;
use App\Services\DatabaseCreator;
use Behat\Behat\Event\FeatureEvent;
use Behat\Behat\Context\Context;
use Behat\Behat\Event\SuiteEvent;

/**
 * Before entering the feature, we want to make sure data file is empty.
 *
 * @var Behat\Behat\Hook\Loader\ClosuredHookLoader $hooks
 */
$hooks->beforeSuite(
    function(SuiteEvent $scope) {
        $file = dirname(__FILE__) . '/../users.txt';
        $dataDump = new DatabaseCreator();
        $dataDump->init();
        $data = serialize([
            'nodeReplaceList' => $dataDump->getNodeReplaceList(),
        ]);
        file_put_contents($file, $data);
    }
);
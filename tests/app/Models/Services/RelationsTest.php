<?php

use App\Models\Services\Relations;

class RelationsTest extends PHPUnit_Framework_TestCase
{
    public function testRejectFriendRequest()
    {
        $endNode = $this->getMock('Everyman\Neo4j\Node', array(), array(), '', false);
        $rejectRelationship = $this->getMock('Everyman\Neo4j\Relationship', array(), array(), '', false);
        $rejectRelationship->expects($this->once())
            ->method('save');

        $startNode = $this->getMock('Everyman\Neo4j\Node', array('relateTo'), array(), '', false);
        $startNode->expects($this->once())
            ->method('relateTo')
            ->with($endNode, 'REJECTED')
            ->will($this->returnValue($rejectRelationship));

        $relationship = $this->getMock('Everyman\Neo4j\Relationship', array('getStartNode', 'getEndNode', 'delete'), array(), '', false);
        $relationship->expects($this->once())
            ->method('getStartNode')
            ->will($this->returnValue($startNode));

        $relationship->expects($this->once())
            ->method('getEndNode')
            ->will($this->returnValue($endNode));

        $relationship->expects($this->once())
            ->method('delete');

        $relationService = new Relations();

        $relationService->rejectFriendRequest($relationship);
    }
}
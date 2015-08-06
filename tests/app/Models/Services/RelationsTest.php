<?php

use App\Models\Services\Relations;
use Mockery as m;

class RelationsTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testRejectFriendRequest()
    {
        $endNode = m::mock('Everyman\Neo4j\Node');

        $rejectRelationship = m::mock('Everyman\Neo4j\Relationship');
        $rejectRelationship->shouldReceive('save')
            ->once();

        $startNode = m::mock('Everyman\Neo4j\Node');
        $startNode->shouldReceive('relateTo')
            ->once()
            ->with($endNode, 'REJECTED')
            ->andReturn($rejectRelationship);

        $relationship = m::mock('Everyman\Neo4j\Relationship');
        $relationship->shouldReceive('getStartNode')
            ->once()
            ->andReturn($startNode);
        $relationship->shouldReceive('getEndNode')
            ->once()
            ->andReturn($endNode);
        $relationship->shouldReceive('delete')
            ->once();

        $relationService = new Relations();
        $relationService->rejectFriendRequest($relationship);
    }

    public function testApproveFriendRequest()
    {
        $startNode = m::mock('Everyman\Neo4j\Node');
        $endNode = m::mock('Everyman\Neo4j\Node');

        $directFriendRelationship = m::mock('Everyman\Neo4j\Relationship');
        $directFriendRelationship->shouldReceive('save')
            ->once();

        $indirectFriendRelationship = m::mock('Everyman\Neo4j\Relationship');
        $indirectFriendRelationship->shouldReceive('save')
            ->once();

        $startNode->shouldReceive('relateTo')
            ->once()
            ->with($endNode, 'FRIENDS')
            ->andReturn($directFriendRelationship);

        $endNode->shouldReceive('relateTo')
            ->once()
            ->with($startNode, 'FRIENDS')
            ->andReturn($indirectFriendRelationship);

        $relationship = m::mock('Everyman\Neo4j\Relationship');
        $relationship->shouldReceive('getStartNode')
            ->once()
            ->andReturn($startNode);
        $relationship->shouldReceive('getEndNode')
            ->once()
            ->andReturn($endNode);
        $relationship->shouldReceive('delete')
            ->once();

        $relationService = new Relations();
        $relationService->approveFriendRequest($relationship);
    }

}
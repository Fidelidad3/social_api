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
}
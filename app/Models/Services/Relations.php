<?php

namespace App\Models\Services;

use Everyman\Neo4j\Relationship;

class Relations
{
    /**
     * Reject friend request.
     *
     * @param Relationship $relationship
     */
    public function rejectFriendRequest(Relationship $relationship)
    {
        $startNode = $relationship->getStartNode();
        $endNode = $relationship->getEndNode();
        $endNode->relateTo($startNode, 'REJECTED')->save();
        $relationship->delete();
    }
}

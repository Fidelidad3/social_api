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
        $startNode->relateTo($endNode, 'REJECTED')->save();
        $relationship->delete();
    }
}

<?php

namespace App\Models\Repositories;

use App\Services\Neo4j;

class Relations
{
    /**
     * @var Neo4j
     */
    private $_neo4j;

    /**
     * @param Neo4j $neo4j
     */
    public function __construct(Neo4j $neo4j = null)
    {
        $this->_neo4j = is_null($neo4j) ? new Neo4j() : $neo4j;
    }

    /**
     * @param integer $id
     * @return \Everyman\Neo4j\Relationship
     */
    public function getRelationById($id)
    {
        return $this->_neo4j->getClient()->getRelationship($id);
    }

    /**
     * @param $fromUserId
     * @param $toUserId
     * @return \Everyman\Neo4j\Relationship
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    public function getPendingRelation($fromUserId, $toUserId)
    {
        $queryTemplate = "START to=node({$fromUserId}), from=node({$toUserId}) MATCH from-[relation:PENDING]->to RETURN relation";
        $resultSet = $this->_neo4j->query($queryTemplate);
        $currentRow = $resultSet->current();
        $relationId = $currentRow['relation']->getId();
        return $this->getRelationById($relationId);
    }
}
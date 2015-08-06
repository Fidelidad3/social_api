<?php

namespace App\Models\Repositories;

use App\Services\Neo4j;

/**
 *  Node Repository
 */
class Node
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
     * Get node by id.
     *
     * @param $id
     * @return \Everyman\Neo4j\Node
     * @throws \Everyman\Neo4j\Exception
     */
    public function getNodeById($id)
    {
        return $this->_neo4j->getClient()->getNode($id);
    }

    /**
     * Get list with request ti friend.
     *
     * @param string $id
     * @return array
     */
    public function getFriendRequestUserList($id)
    {
        $queryTemplate = "START current=node({$id}) MATCH user-[:PENDING]->current RETURN user";
        $resultSet = $this->_neo4j->query($queryTemplate);

        $result = array();
        foreach ($resultSet as $row) {
            $result[$row['user']->getId()] = $row['user']->getProperty('name');
        }
        return $result;
    }
}
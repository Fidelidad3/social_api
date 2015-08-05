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
     * @param $id
     * @return \Everyman\Neo4j\Node
     * @throws Exception
     * @throws \Everyman\Neo4j\Exception
     */
    public function getNodeById($id)
    {
        return $this->_neo4j->getClient()->getNode($id);
    }
}
<?php
namespace App\Services;

use Everyman\Neo4j\Cypher\Query;

/**
 * Class for creating database structure.
 *
 * @package App\Services
 */
class DatabaseCreator
{
    /**
     * List of aliases for node ids.
     *
     * @var array
     */
    private $_nodeReplaceList;

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

    public function init()
    {
        $userData = array(
            1 => ['name' => 'Edwin Abbott Abbott', 'age' => 21],
            2 => ['name' => 'Gilbert Abbott a Beckett', 'age' => 30],
            3 => ['name' => 'George Abbot', 'age' => 50],
            4 => ['name' => 'Lascelles Abercrombie', 'age' => 45],
            5 => ['name' => 'Paul Ableman', 'age' => 35],
            6 => ['name' => 'J. R. Ackerley', 'age' => 70],
            7 => ['name' => 'Rodney Ackland', 'age' => 15],
            8 => ['name' => 'Michael Caine', 'age' => 43],
        );

        $userRelationShip = array(
            [1, 2, 'FRIENDS'],
            [2, 1, 'FRIENDS'],
            [1, 3, 'REJECTED'],
            [4, 2, 'FRIENDS'],
            [2, 4, 'FRIENDS'],
            [4, 3, 'FRIENDS'],
            [3, 4, 'FRIENDS'],
            [7, 3, 'FRIENDS'],
            [3, 7, 'FRIENDS'],
            [4, 6, 'FRIENDS'],
            [6, 4, 'FRIENDS'],
            [7, 6, 'FRIENDS'],
            [6, 7, 'FRIENDS'],
            [6, 5, 'PENDING'],
            [4, 5, 'PENDING'],
        );

        $this->_flushDatabase();

        $nodes = [];
        foreach ($userData as $key => $data) {
            $node= $this->_neo4j->getClient()->makeNode();
            $node->setProperty('name', $data['name'])
                ->setProperty('age', $data['age'])
                ->save();
            $nodes[$key] = $node;
        }

        foreach ($userRelationShip as $data) {
            $start = $nodes[$data[0]];
            $end = $nodes[$data[1]];
            $start->relateTo($end, $data[2])->save();
        }

        foreach ($nodes as $key => $node) {
            $this->_nodeReplaceList['{' . $key . '}'] = $node->getId();
        }
    }

    /**
     * Get node is aliases list.
     *
     * @return array
     */
    public function getNodeReplaceList()
    {
        return $this->_nodeReplaceList;
    }

    /**
     * Remove all relations and nodes.
     *
     * @return \Everyman\Neo4j\Node
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    private function _flushDatabase()
    {
        $queryTemplate = "MATCH ()-[relation]->() RETURN relation";
        $rows = $this->_neo4j->query($queryTemplate);
        foreach ($rows as $row) {
            $item = $this->_neo4j->getClient()->getRelationship($row['relation']->getId());
            $this->_neo4j->getClient()->deleteRelationship($item);
        }

        $queryTemplate = "MATCH (node) RETURN node";
        $rows = $this->_neo4j->query($queryTemplate);
        foreach ($rows as $row) {
            $node = $this->_neo4j->getClient()->getNode($row['node']->getId());
            $this->_neo4j->getClient()->deleteNode($node);
        }
        return $node;
    }
}
<?php

namespace App\Services;

use Everyman\Neo4j\Client;
use Everyman\Neo4j\Cypher\Query;

class Neo4j
{
    //TODO Move configuration to .env file
    const NEO4J_HOST = 'localhost';
    const NEO4J_PORT = 7474;
    const NEO4J_USER = 'neo4j';
    const NEO4J_PASS = '123';

    /**
     * @var Client
     */
    private $_client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        $this->_client = (is_null($client)) ? new Client(self::NEO4J_HOST, self::NEO4J_PORT) : $client;
        $this->_client->getTransport()
            ->setAuth(self::NEO4J_USER, self::NEO4J_PASS);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @param string $queryTemplate
     * @return \Everyman\Neo4j\Query\ResultSet
     */
    public function query($queryTemplate)
    {
        $query = new Query($this->_client, $queryTemplate);
        return $query->getResultSet();
    }
}
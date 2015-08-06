;<?php

use App\Models\Repositories\Node;
use Mockery as m;

class NodeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testGetNodeById()
    {
        $nodeId = 15;

        $node = m::mock('Everyman\Neo4j\Node');

        $client = m::mock('Everyman\Neo4j\Client');
        $client->shouldReceive('getNode')
            ->once()
            ->with($nodeId)
            ->andReturn($node);

        $neo4j = m::mock('App\Services\Neo4j');
        $neo4j->shouldReceive('getClient')
            ->once()
            ->andReturn($client);

        $nodeRepository = new Node($neo4j);
        $this->assertEquals($node, $nodeRepository->getNodeById($nodeId));
    }

    public function testGetFriendRequestUserList()
    {
        $userId = 16;
        $queryTemplate = strtr(
            Node::QUERY_TEMPLATE_FRIEND_REQUESTS,
            array(
                '{id}' => $userId
            )
        );
        $userList = [1 => 'Name 1', 2 => 'Name 2'];

        $neo4j = $this->_getNeo4jMock($queryTemplate, $userList);

        $nodeRepository = new Node($neo4j);
        $this->assertEquals($userList, $nodeRepository->getFriendRequestUserList($userId));
    }

    public function testGetUserFriendList()
    {
        $userId = 16;
        $queryTemplate = strtr(
            Node::QUERY_TEMPLATE_GET_USER_FRIENDS,
            array(
                '{userId}' => $userId
            )
        );
        $userList = [3 => 'Name 3', 4 => 'Name 4'];

        $neo4j = $this->_getNeo4jMock($queryTemplate, $userList);

        $nodeRepository = new Node($neo4j);
        $this->assertEquals($userList, $nodeRepository->getUserFriendList($userId));
    }

    public function testGetUserListFromCircle()
    {
        $id = 10;
        $depth = 3;
        $queryTemplate = strtr(
            Node::QUERY_TEMPLATE_GET_USERS_BY_CIRCLE,
            array(
                '{id}' => $id,
                '{depth}' => $depth,
            )
        );
        $userList = [3 => 'Name 3', 5 => 'Name 5'];

        $neo4j = $this->_getNeo4jMock($queryTemplate, $userList);

        $nodeRepository = new Node($neo4j);
        $this->assertEquals($userList, $nodeRepository->getUserListFromCircle($id, $depth));
    }

    public function testGetAllUsersList()
    {
        $userId = 16;
        $queryTemplate = Node::QUERY_TEMPLATE_GET_ALL_USERS;
        $userList = [6 => 'Name 6', 2 => 'Name 2'];

        $neo4j = $this->_getNeo4jMock($queryTemplate, $userList);

        $nodeRepository = new Node($neo4j);
        $this->assertEquals($userList, $nodeRepository->getAllUsersList($userId));
    }

    /**
     * @param $queryTemplate
     * @return m\MockInterface
     */
    private function _getNeo4jMock($queryTemplate, $userList)
    {
        $resultSet = [];
        foreach ($userList as $id => $name) {
            $resultSet[] = ['user' => $this->_getQueryRowMock($id, $name)];
        }

        $neo4j = m::mock('App\Services\Neo4j');
        $neo4j->shouldReceive('query')
            ->once()
            ->with($queryTemplate)
            ->andReturn($resultSet);
        return $neo4j;
    }

    /**
     * @return m\MockInterface
     */
    private function _getQueryRowMock($id, $name)
    {
        $row = m::mock('Everyman\Neo4j\Query\Row');
        $row->shouldReceive('getId')
            ->once()
            ->andReturn($id);
        $row->shouldReceive('getProperty')
            ->once()
            ->with('name')
            ->andReturn($name);
        return $row;
    }
}
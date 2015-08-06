<?php

namespace App\Http\Controllers;

use App\Services\Neo4j;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Repositories\Node;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends BaseController
{
    /**
     * @var Neo4j
     */
    private $_neo4j;

    /**
     * @var Node
     */
    private $_nodeRepository;

    /**
     * @param Neo4j $neo4j
     * @param Node  $nodeRepository
     */
    public function __construct(Neo4j $neo4j, Node $nodeRepository)
    {
        $this->_neo4j = $neo4j;
        $this->_nodeRepository = $nodeRepository;
    }

    /**
     * Get user info.
     *
     * @param $id
     * @return Response
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    public function getUser($id)
    {
        $user = $this->_neo4j->getClient()->getNode($id);
        if (is_null($user)) {
            return new Response('User with id (%s) does not exist.', Response::HTTP_BAD_REQUEST);
        }
        return new Response($user->getProperties(), Response::HTTP_OK);
    }

    /**
     * Get all user.
     *
     * @return Response
     */
    public function listUsers()
    {
        $queryTemplate = "MATCH (user) RETURN user";
        $resultSet = $this->_neo4j->query($queryTemplate);
        $result = array();
        foreach ($resultSet as $row) {
            $result[$row['user']->getId()] = $row['user']->getProperty('name');
        }
        return new Response($result, Response::HTTP_OK);
    }

    /**
     * Create user.
     *
     * @param Request $request
     * @return Response
     * @throws \Everyman\Neo4j\Exception
     */
    public function createUser(Request $request)
    {
        $user = $this->_neo4j->getClient()->makeNode();
        $user->setProperty('name', $request->get('name'))
            ->setProperty('age', $request->get('age'))
            ->save();
        return new Response([$user->getId()], Response::HTTP_OK);
    }

    /**
     * Remove user.
     *
     * @param string $id
     * @return Response
     * @throws \Everyman\Neo4j\Exception
     * @throws \Exception
     */
    public function removeUser($id)
    {
        $user = $this->_neo4j->getClient()->getNode($id);
        if (is_null($user)) {
            return new Response("Unable to delete because the user does not exist.", Response::HTTP_BAD_REQUEST);
        }
        try {
            $user->delete();
            return new Response(['id' => $id], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}

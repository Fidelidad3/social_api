<?php

namespace App\Http\Controllers;

use App\Services\Neo4j;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class RelationController
 * @package App\Http\Controllers
 */
class RelationController extends BaseController
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
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function sendFriendRequest(Request $request, $id)
    {
        $fromUser = $this->_neo4j->getClient()->getNode($id);
        $toUser = $this->_neo4j->getClient()->getNode($request->get('userid'));

        $relationship = $fromUser->relateTo($toUser, 'PENDING')->save();

        return new Response(['id' => $relationship->getId()], Response::HTTP_OK);
    }
}

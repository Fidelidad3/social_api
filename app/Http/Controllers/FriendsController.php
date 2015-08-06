<?php

namespace App\Http\Controllers;

use App\Models\Repositories;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class FriendsController
 * @package App\Http\Controllers
 */
class FriendsController extends BaseController
{
    /**
     * @var Repositories\Node
     */
    private $_nodeRepository;

    /**
     * @param Repositories\Node $nodeRepository
     */
    public function __construct(Repositories\Node $nodeRepository)
    {
        $this->_nodeRepository = $nodeRepository;
    }

    /**
     * Get list of user friends.
     *
     * @param string $id
     * @return Response
     */
    public function getFriendList($id)
    {
        return new Response($this->_nodeRepository->getUserFriendList($id), Response::HTTP_OK);
    }

    /**
     * @param string $id          Root user Id.
     * @param string $circleDepth Depth of friends circle.
     * @return Response
     */
    public function getFriendCircle($id, $circleDepth)
    {
        return new Response($this->_nodeRepository->getUserListFromCircle($id, $circleDepth), Response::HTTP_OK);
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\DatabaseCreator;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    /**
     * @var DatabaseCreator
     */
    private $_databaseCreator;

    /**
     * @param DatabaseCreator $creator
     */
    public function __construct(DatabaseCreator $creator)
    {
        $this->_databaseCreator = $creator;
    }

    /**
     * Init database
     */
    public function init()
    {
        $this->_databaseCreator->init();
        return new Response('Database initialization completed.', Response::HTTP_OK);
    }
}
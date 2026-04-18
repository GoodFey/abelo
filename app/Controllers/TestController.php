<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Logger;
use App\Models\Post;
use App\Models\Category;

/**
 * TestController - Database testing and debugging
 */
class TestController extends Controller
{
    /**
     * Test database connection and basic queries
     */
    public function index(Request $request, Response $response, array $params = []): ?string
    {
        return null;
    }
}


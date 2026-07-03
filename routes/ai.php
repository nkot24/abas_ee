<?php

use App\Mcp\Servers\ProductServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::oauthRoutes();

Mcp::web('/mcp/products', ProductServer::class)
    ->middleware(['auth:api', 'scope:mcp:use']);

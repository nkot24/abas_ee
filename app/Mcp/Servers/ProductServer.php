<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\CreateProductTool;
use App\Mcp\Tools\DeleteProductTool;
use App\Mcp\Tools\GetProductTool;
use App\Mcp\Tools\ListProductsTool;
use App\Mcp\Tools\UpdateProductTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Product Server')]
#[Version('0.0.1')]
#[Instructions('Manage abas.lt store products: list/search, fetch details, create, update, and delete. Prices are in EUR. Categories are one or more slugs from a fixed set. Deleting a product is permanent and also removes its images.')]
class ProductServer extends Server
{
    protected array $tools = [
        ListProductsTool::class,
        GetProductTool::class,
        CreateProductTool::class,
        UpdateProductTool::class,
        DeleteProductTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}

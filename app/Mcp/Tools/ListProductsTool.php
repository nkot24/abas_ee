<?php

namespace App\Mcp\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('List products, optionally filtered by name search, category, or active status. Supports pagination.')]
class ListProductsTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'search' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string', 'in:aksesuarai,sodo-baldai,grilis,rukyklos,grilio-anglys,virykles,nesiojaimos,profesionalios,lauzavietes'],
            'active' => ['sometimes', 'boolean'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ]);

        $query = Product::query();

        if (isset($validated['search'])) {
            $query->where('name', 'like', '%'.$validated['search'].'%');
        }

        if (isset($validated['category'])) {
            $query->whereJsonContains('category', $validated['category']);
        }

        if (isset($validated['active'])) {
            $query->where('active', $validated['active']);
        }

        $perPage = $validated['per_page'] ?? 20;
        $page = $validated['page'] ?? 1;

        $paginator = $query->orderBy('id')->paginate($perPage, ['*'], 'page', $page);

        if ($paginator->isEmpty()) {
            return Response::text('No products found matching the given filters.');
        }

        $lines = $paginator->getCollection()->map(function (Product $product) {
            $price = $product->on_sale && $product->sale_price
                ? "{$product->sale_price} EUR (was {$product->price} EUR)"
                : "{$product->price} EUR";

            return "#{$product->id} {$product->name} — {$price}".
                ($product->active ? '' : ' [inactive]');
        })->implode("\n");

        return Response::text(
            "Page {$paginator->currentPage()} of {$paginator->lastPage()} ({$paginator->total()} total products):\n{$lines}"
        );
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'search' => $schema->string()
                ->description('Filter products whose name contains this text.'),
            'category' => $schema->string()
                ->enum(['aksesuarai', 'sodo-baldai', 'grilis', 'rukyklos', 'grilio-anglys', 'virykles', 'nesiojaimos', 'profesionalios', 'lauzavietes'])
                ->description('Filter products belonging to this category slug.'),
            'active' => $schema->boolean()
                ->description('Filter by active/visible status.'),
            'per_page' => $schema->integer()
                ->min(1)->max(100)
                ->description('Number of products per page (default 20, max 100).'),
            'page' => $schema->integer()
                ->min(1)
                ->description('Page number to fetch (default 1).'),
        ];
    }
}

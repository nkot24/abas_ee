<?php

namespace App\Mcp\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Description('Permanently delete a product by id. This also deletes its images. This action cannot be undone.')]
#[IsDestructive]
class DeleteProductTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::find($validated['id']);

        if (! $product) {
            return Response::error("No product found with id {$validated['id']}.");
        }

        $name = $product->name;
        $id = $product->id;

        $product->delete();

        return Response::text("Deleted product #{$id} \"{$name}\".");
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('The numeric id of the product to permanently delete.')
                ->required(),
        ];
    }
}

<?php

namespace App\Mcp\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Fetch full details of a single product by its numeric id.')]
class GetProductTool extends Tool
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

        $categories = implode(', ', $product->category ?? []);
        $price = $product->on_sale && $product->sale_price
            ? "{$product->sale_price} EUR (regular {$product->price} EUR, on sale)"
            : "{$product->price} EUR";

        $details = [
            "#{$product->id} {$product->name}".($product->name_en ? " ({$product->name_en})" : ''),
            "Price: {$price}",
            "Categories: ".($categories ?: '—'),
            'Active: '.($product->active ? 'yes' : 'no'),
            'Locker eligible: '.($product->locker_eligible ? 'yes' : 'no'),
            'Badge: '.($product->badge ?? '—'),
            'Material: '.($product->material ?? '—'),
            'Dimensions (h/l/w, cm): '.implode('/', [$product->height ?? '—', $product->length ?? '—', $product->width ?? '—']),
            'Thickness (mm): '.($product->thickness ?? '—'),
            'Weight (kg): '.($product->weight ?? '—'),
            'Description: '.($product->description ?? '—'),
        ];

        return Response::text(implode("\n", $details));
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
                ->description('The numeric id of the product to fetch.')
                ->required(),
        ];
    }
}

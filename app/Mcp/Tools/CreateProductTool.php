<?php

namespace App\Mcp\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a new product. Name and price are required; all other fields are optional.')]
class CreateProductTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['sometimes', 'array'],
            'category.*' => ['string', 'in:aksesuarai,sodo-baldai,grilis,rukyklos,grilio-anglys,virykles,nesiojaimos,profesionalios,lauzavietes'],
            'badge' => ['sometimes', 'nullable', 'string', 'max:50'],
            'material' => ['sometimes', 'nullable', 'string', 'in:nerūdijantis,paprastas,corten'],
            'height' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'length' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'width' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'thickness' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'weight' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'active' => ['sometimes', 'boolean'],
            'locker_eligible' => ['sometimes', 'boolean'],
            'on_sale' => ['sometimes', 'boolean'],
            'sale_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'sale_percent' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::create($validated);

        return Response::text("Created product #{$product->id} \"{$product->name}\" at {$product->price} EUR.");
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('Product name (Estonian). Max 255 characters.')
                ->required(),
            'description' => $schema->string()
                ->description('Optional long-form product description.'),
            'price' => $schema->number()
                ->min(0)
                ->description('Regular price in EUR.')
                ->required(),
            'category' => $schema->array()
                ->items($schema->string()->enum(['aksesuarai', 'sodo-baldai', 'grilis', 'rukyklos', 'grilio-anglys', 'virykles', 'nesiojaimos', 'profesionalios', 'lauzavietes']))
                ->description('List of category slugs this product belongs to.'),
            'badge' => $schema->string()
                ->description('Optional display badge text, e.g. "New" or "Popular". Max 50 characters.'),
            'material' => $schema->string()
                ->enum(['nerūdijantis', 'paprastas', 'corten'])
                ->description('Optional material type.'),
            'height' => $schema->integer()->min(0)->description('Height in cm.'),
            'length' => $schema->integer()->min(0)->description('Length in cm.'),
            'width' => $schema->integer()->min(0)->description('Width in cm.'),
            'thickness' => $schema->number()->min(0)->description('Material thickness in mm.'),
            'weight' => $schema->number()->min(0)->description('Weight in kg.'),
            'active' => $schema->boolean()->description('Whether the product is visible on the storefront. Defaults to true.'),
            'locker_eligible' => $schema->boolean()->description('Whether the product can ship via parcel locker. Defaults to true.'),
            'on_sale' => $schema->boolean()->description('Whether the product is currently on sale. Defaults to false.'),
            'sale_price' => $schema->number()->min(0)->description('Sale price in EUR, used when on_sale is true.'),
            'sale_percent' => $schema->integer()->min(1)->max(99)->description('Sale discount percentage (1-99), used when on_sale is true.'),
        ];
    }
}

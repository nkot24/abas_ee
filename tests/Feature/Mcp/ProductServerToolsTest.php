<?php

use App\Mcp\Servers\ProductServer;
use App\Mcp\Tools\CreateProductTool;
use App\Mcp\Tools\DeleteProductTool;
use App\Mcp\Tools\UpdateProductTool;
use App\Models\Product;

it('creates a product with valid data', function () {
    ProductServer::tool(CreateProductTool::class, [
        'name' => 'Roostevaba grill',
        'price' => 249.90,
        'category' => ['grilis'],
        'material' => 'nerūdijantis',
    ])
        ->assertOk()
        ->assertSee('Created product');

    $product = Product::firstWhere('name', 'Roostevaba grill');

    expect($product)->not->toBeNull();
    expect((float) $product->price)->toBe(249.90);
    expect($product->category)->toBe(['grilis']);
    expect($product->active)->toBeTrue();
});

it('fails to create a product without required fields', function () {
    ProductServer::tool(CreateProductTool::class, [
        'price' => 10,
    ])
        ->assertHasErrors(['name']);

    expect(Product::count())->toBe(0);
});

it('fails to create a product with an invalid category', function () {
    ProductServer::tool(CreateProductTool::class, [
        'name' => 'Test toode',
        'price' => 10,
        'category' => ['not-a-real-category'],
    ])
        ->assertHasErrors();

    expect(Product::count())->toBe(0);
});

it('updates a product by id', function () {
    $product = Product::create([
        'name' => 'Vana nimi',
        'price' => 100,
        'active' => true,
    ]);

    ProductServer::tool(UpdateProductTool::class, [
        'id' => $product->id,
        'name' => 'Uus nimi',
        'price' => 150,
    ])
        ->assertOk()
        ->assertSee('Updated product');

    $product->refresh();

    expect($product->name)->toBe('Uus nimi');
    expect((float) $product->price)->toBe(150.0);
});

it('fails to update a product with invalid data', function () {
    $product = Product::create([
        'name' => 'Toode',
        'price' => 100,
    ]);

    ProductServer::tool(UpdateProductTool::class, [
        'id' => $product->id,
        'price' => -5,
    ])
        ->assertHasErrors();

    expect((float) $product->refresh()->price)->toBe(100.0);
});

it('returns an error when updating a non-existent product', function () {
    ProductServer::tool(UpdateProductTool::class, [
        'id' => 999999,
        'name' => 'Does not matter',
    ])
        ->assertSee('No product found');
});

it('deletes a product by id', function () {
    $product = Product::create([
        'name' => 'Kustutatav toode',
        'price' => 50,
    ]);

    ProductServer::tool(DeleteProductTool::class, [
        'id' => $product->id,
    ])
        ->assertOk()
        ->assertSee('Deleted product');

    expect(Product::find($product->id))->toBeNull();
});

it('returns an error when deleting a non-existent product', function () {
    ProductServer::tool(DeleteProductTool::class, [
        'id' => 999999,
    ])
        ->assertSee('No product found');
});

it('fails to delete a product without an id', function () {
    ProductServer::tool(DeleteProductTool::class, [])
        ->assertHasErrors(['id']);
});

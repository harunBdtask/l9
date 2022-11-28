<?php

use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Dyeing\Models\TextileModels\TextileOrders\TextileOrder;

uses(Tests\TestCase::class);

beforeEach(function () {
    $this->actingAs(User::query()->first());
});

it('can check factory api response', function () {
    $this->getJson('/fetch-factories')->assertStatus(200);
});

it('can check order payment basis', function () {
    $response = [
        "message" => "Fetch textile order payment basis successfully",
        "data" => [],
        "status" => 200
    ];

    $this->getJson('/dyeing/api/v1/get-payment-basis')
        ->assertStatus(200)->assertJson($response);
});

it('can check fabric-sales-order api response', function () {
    $buyer = Buyer::query()->first();

    $response = [
        "message" => "Fetch fabric sales orders no successfully",
        "data" => [],
        "status" => 200
    ];

    $this->getJson("/dyeing/api/v1/get-fabric-sales-orders-no/{$buyer->id}")
        ->assertStatus(200)->assertJson($response);
});

it('can check validation for textile orders', function () {
    $this->postJson('/dyeing/textile-orders', [])->assertStatus(422);
});

it('can check store for textile order', function () {
    $attributes = TextileOrder::modelFactory()->raw();
    $attributes['textile_order_details'] = [];

    $this->postJson('/dyeing/textile-orders', $attributes)->assertStatus(201);
});

it('can check textile-order edit api', function () {
    $textileOrderId = TextileOrder::query()->first();

    $response = [
        'message' => 'Textile order fetch successfully',
        'data' => [],
        'status' => 200,
    ];

    $this->getJson("/dyeing/textile-orders/{$textileOrderId->id}/edit")
        ->assertStatus(200)->assertJson($response);
});

it('can check update for textile orders', function () {
    $attributes = TextileOrder::query()
        ->with('textileOrderDetails')
        ->first();

    $this->putJson("/dyeing/textile-orders/{$attributes->id}", $attributes->toArray())
        ->assertStatus(201);
});

it('can check delete for textile order', function () {
    $textileOrder = TextileOrder::query()->latest()->first();

    $this->delete("/dyeing/textile-orders/{$textileOrder->id}")
        ->assertStatus(302);
});

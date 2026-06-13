<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_order_and_kitchen_workflow(): void
    {
        // 1. Create a cashier user (role_id 2) and kitchen user (role_id 4)
        $cashier = User::factory()->create(['role_id' => 2]);
        $kitchenStaff = User::factory()->create(['role_id' => 3]);

        // 2. Setup master data
        $table = Table::create([
            'table_number' => '5',
            'qr_token' => \Illuminate\Support\Str::random(64),
            'is_active' => true
        ]);
        $category = Category::create(['name' => 'Food', 'type' => 'Makanan']);
        $menu = Menu::create([
            'category_id' => $category->id,
            'name' => 'Burger',
            'price' => 50000,
            'stock' => 10,
            'is_active' => true,
        ]);

        // 3. Create an unpaid order
        $orderId = 'TRX-' . date('Ymd') . '-0001';
        $order = Order::create([
            'id' => $orderId,
            'table_id' => $table->id,
            'customer_name' => 'John Doe',
            'user_id' => $cashier->id,
            'status' => 'unpaid',
        ]);

        $orderItem = OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'quantity' => 2,
            'price_at_order' => $menu->price,
            'status' => 0, // Pending
        ]);

        // 4. Kitchen display should NOT show unpaid orders
        $response = $this->actingAs($kitchenStaff)->get('/kitchen/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee('Meja 5');
        $response->assertDontSee('Burger');

        // 5. Checkout the order (pay Cash)
        $response = $this->actingAs($cashier)->postJson("/cashier/orders/{$order->id}/checkout", [
            'payment_method' => 'cash',
            'cash_amount' => 100000,
        ]);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Order status should now be 'proses'
        $order->refresh();
        $this->assertEquals('proses', $order->status);
        $this->assertEquals('cash', $order->payment_method);
        $this->assertEquals(100000, $order->cash_amount);
        $this->assertEquals(0, $order->change_amount);

        // 6. Kitchen display should NOW show the order in 'proses'
        $response = $this->actingAs($kitchenStaff)->get('/kitchen/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Meja 5');
        $response->assertSee('Burger');

        // 7. Progress the item to "Sedang Dimasak" (status 1)
        $response = $this->actingAs($kitchenStaff)->post("/kitchen/items/{$orderItem->id}/status", [
            'status' => 1,
        ]);
        $response->assertRedirect();
        
        $orderItem->refresh();
        $this->assertEquals(1, $orderItem->status);
        $this->assertNotNull($orderItem->accepted_at);

        // 8. Progress the item to "Siap Saji" (status 2)
        $response = $this->actingAs($kitchenStaff)->post("/kitchen/items/{$orderItem->id}/status", [
            'status' => 2,
        ]);
        $response->assertRedirect();

        $orderItem->refresh();
        $this->assertEquals(2, $orderItem->status);
        $this->assertNotNull($orderItem->ready_at);

        // Parent order status should automatically be updated to 'ready'
        $order->refresh();
        $this->assertEquals('ready', $order->status);

        // 9. Complete the order
        $response = $this->actingAs($cashier)->post("/cashier/orders/{$order->id}/complete");
        $response->assertRedirect(route('cashier.orders.index'));

        // Parent order status should now be 'completed'
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }
}

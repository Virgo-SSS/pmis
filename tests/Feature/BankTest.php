<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\User;
use Tests\TestCase;

class BankTest extends TestCase
{
    /**
     * Test User can't view bank page if not authenticated
     *
     * @return void
     */
    public function test_user_cant_view_bank_page_if_not_authenticated(): void
    {
        $response = $this->get(route('bank'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't view bank page if not authorized
     *
     * @return void
     */
    public function test_user_cant_view_bank_page_if_not_authorized(): void
    {
        $this->loginUser('HR');

        $response = $this->get(route('bank'));

        $response->assertStatus(403);
    }

    /**
     * Test User can view bank page
     *
     * @return void
     */
    public function test_user_can_view_bank_page(): void
    {
        $this->loginUser('HR', ['view banks']);

        $response = $this->get(route('bank'));

        $response->assertStatus(200);
        $response->assertViewIs('banks.index');
        $response->assertViewHas('banks');
    }

    /**
     * Test User can't create bank if not authorized
     *
     * @return void
     */
    public function test_user_cant_create_bank_if_not_authorized(): void
    {
        $this->loginUser('HR');

        $response = $this->post(route('bank.store'), [
            'name' => 'Bank Name',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test User can't create bank if not authenticated
     *
     * @return void
     */
    public function test_user_cant_create_bank_if_not_authenticated(): void
    {
        $response = $this->post(route('bank.store'), [
            'name' => 'Bank Name',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't create bank if validation fails
     *
     * @return void
     */
    public function test_user_cant_create_bank_if_validation_fails(): void
    {
        $this->loginUser('HR', ['view banks', 'create banks']);

        $response = $this->post(route('bank.store'), []);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test User can create bank
     *
     * @return void
     */
    public function test_user_can_create_bank(): void
    {
        $user = $this->loginUser('HR', ['view banks', 'create banks']);

        $response = $this->post(route('bank.store'), [
            'name' => 'Bank Name',
        ]);

        $response->assertRedirect(route('bank'));
        $response->assertSessionHas('success-swal', 'Bank created successfully.');

        $this->assertDatabaseHas('banks', [
            'name' => 'Bank Name',
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Create new bank with name Bank Name',
            'subject_type' => Bank::class,
            'subject_id' => Bank::query()->where('name', 'Bank Name')->first()->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }

    /**
     * Test User can't update bank if not authorized
     *
     * @return void
     */
    public function test_user_cant_update_bank_if_not_authorized(): void
    {
        $this->loginUser('HR');

        $bank = Bank::factory()->create();

        $response = $this->put(route('bank.update', $bank), [
            'name' => 'Bank Name Updated',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test User can't update bank if not authenticated
     *
     * @return void
     */
    public function test_user_cant_update_bank_if_not_authenticated(): void
    {
        $bank = Bank::factory()->create();

        $response = $this->put(route('bank.update', $bank), [
            'name' => 'Bank Name Updated',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't update bank if validation fails
     *
     * @return void
     */
    public function test_user_cant_update_bank_if_validation_fails(): void
    {
        $user = $this->loginUser('HR', ['view banks', 'edit banks']);

        $bank = Bank::factory()->create();

        Bank::factory()->create(['name' => 'Bank duplicate 1']);
        $response = $this->put(route('bank.update', $bank), []);

        $response->assertSessionHasErrors('name');

        // test name must be unique
        $response2 = $this->put(route('bank.update', $bank), [
            'name' => 'Bank duplicate 1',
        ]);

        $response2->assertSessionHasErrors('name');
    }

    /**
     * Test User can update bank
     *
     * @return void
     */
    public function test_user_can_update_bank(): void
    {
        $user = $this->loginUser('HR', ['view banks', 'edit banks']);

        $bank = Bank::factory()->create();

        $response = $this->put(route('bank.update', $bank), [
            'name' => 'Bank Name Updated',
        ]);

        $response->assertRedirect(route('bank'));
        $response->assertSessionHas('success-swal', 'Bank updated successfully.');

        $this->assertDatabaseHas('banks', [
            'name' => 'Bank Name Updated',
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Update bank from ' . $bank->name . ' to Bank Name Updated',
            'subject_type' => Bank::class,
            'subject_id' => $bank->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }

    /**
     * Test User can't delete bank if not authorized
     *
     * @return void
     */
    public function test_user_cant_delete_bank_if_not_authorized(): void
    {
        $this->loginUser('HR');

        $bank = Bank::factory()->create();

        $response = $this->delete(route('bank.delete', $bank));

        $response->assertStatus(403);
    }

    /**
     * Test User can't delete bank if not authenticated
     *
     * @return void
     */
    public function test_user_cant_delete_bank_if_not_authenticated(): void
    {
        $bank = Bank::factory()->create();

        $response = $this->delete(route('bank.delete', $bank));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can delete bank
     *
     * @return void
     */
    public function test_user_can_delete_bank(): void
    {
        $user = $this->loginUser('HR', ['view banks', 'delete banks']);

        $bank = Bank::factory()->create();

        $response = $this->delete(route('bank.delete', $bank));

        $response->assertRedirect(route('bank'));
        $response->assertSessionHas('success-swal', 'Bank deleted successfully.');

        $this->assertDatabaseMissing('banks', [
            'id' => $bank->id,
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Delete bank with name ' . $bank->name,
            'subject_type' => Bank::class,
            'subject_id' => $bank->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }
}

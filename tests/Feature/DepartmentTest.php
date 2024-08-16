<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    /**
     * Test user can view the department page.
     * 
     * @return void
     */
    public function test_user_can_view_the_department_page(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->get(route('department'));

        $response->assertOk();
        $response->assertViewIs('departments.index');
        $response->assertViewHas('departments');
    }

    /**
     * Test user can't create a department if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_create_a_department_if_not_authenticated(): void
    {
        $response = $this->post(route('department.store'), [
            'name' => 'IT Department',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can create a department.
     * 
     * @return void
     */
    public function test_user_can_create_a_department(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->post(route('department.store'), [
            'name' => 'IT Department',
        ]);

        $response->assertRedirect(route('department'));
        $response->assertSessionHas('success-swal', 'Department created successfully');

        $this->assertDatabaseHas('departments', [
            'name' => 'IT Department',
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Create new department with name IT Department',
            'subject_type' => Department::class,
            'subject_id' => Department::first()->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }

    /**
     * Test department name is required to create a department.
     * 
     * @return void
     */
    public function test_department_name_is_required_to_create_a_department(): void
    {
        $this->actingAs($this->createUser());

        $response = $this->post(route('department.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test user can't update a department if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_update_a_department_if_not_authenticated(): void
    {
        $department = Department::factory()->create();

        $response = $this->put(route('department.update', $department), [
            'name' => 'IT Department',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can update a department.
     * 
     * @return void
     */
    public function test_user_can_update_a_department(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $department = Department::factory()->create();

        $response = $this->put(route('department.update', $department), [
            'name' => 'IT Department',
        ]);

        $response->assertRedirect(route('department'));
        $response->assertSessionHas('success-swal', 'Department updated successfully');

        $this->assertDatabaseHas('departments', [
            'name' => 'IT Department',
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Update department from ' . $department->name . ' to IT Department',
            'subject_type' => Department::class,
            'subject_id' => $department->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }

    /**
     * Test department name is required to update a department.
     * 
     * @return void
     */
    public function test_department_name_is_required_to_update_a_department(): void
    {
        $this->actingAs($this->createUser());

        $department = Department::factory()->create();

        $response = $this->put(route('department.update', $department), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test department name is unique to update a department.
     * 
     * @return void
     */
    public function test_department_name_is_unique_to_update_a_department(): void
    {
        $this->actingAs($this->createUser());

        $department = Department::factory()->create();
        $department2 = Department::factory()->create();

        $response = $this->put(route('department.update', $department), [
            'name' => $department2->name,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test user can't delete a department if not authenticated.
     * 
     * @return void
     */
    public function test_user_cant_delete_a_department_if_not_authenticated(): void
    {
        $department = Department::factory()->create();

        $response = $this->delete(route('department.delete', $department));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can delete a department.
     * 
     * @return void
     */
    public function test_user_can_delete_a_department(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);

        $department = Department::factory()->create();

        $response = $this->delete(route('department.delete', $department));

        $response->assertRedirect(route('department'));
        $response->assertSessionHas('success-swal', 'Department deleted successfully');

        $this->assertDatabaseMissing('departments', [
            'name' => $department->name,
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Delete department with name ' . $department->name,
            'subject_type' => Department::class,
            'subject_id' => $department->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
        ]);
    }
}

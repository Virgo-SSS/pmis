<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    /**
     * Test User can't view the attendance index page if not authenticated
     *
     * @return void
     */
    public function test_user_cant_view_attendance_index_page_if_not_authenticated(): void
    {
        $response = $this->get(route('attendance'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't view attendance index page if not authorized
     *
     * @return void
     */
    public function test_user_cant_view_attendance_index_page_if_not_authorized(): void
    {
        $this->loginUser('User');
        $response = $this->get(route('attendance'));

        $response->assertStatus(403);
    }

    /**
     * Test User can view the attendance index page
     *
     * @return void
     */
    public function test_user_can_view_attendance_index_page(): void
    {
        $this->loginUser('User', ['view attendances']);
        $response = $this->get(route('attendance'));

        $response->assertStatus(200);
        $response->assertViewIs('attendances.index');
        $response->assertViewHas('attendances');
    }

    /**
     * Test user can't view the attendance create page if not authenticated
     *
     * @return void
     */
    public function test_user_cant_view_attendance_create_page_if_not_authenticated(): void
    {
        $response = $this->get(route('attendance.create'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't view the attendance create page if not authorized
     *
     * @return void
     */
    public function test_user_cant_view_attendance_create_page_if_not_authorized(): void
    {
        $this->loginUser('User');
        $response = $this->get(route('attendance.create'));

        $response->assertStatus(403);
    }

    /**
     * Test User can view the attendance create page
     *
     * @return void
     */
    public function test_user_can_view_attendance_create_page(): void
    {
        $this->loginUser('User', ['view attendances', 'create attendances']);
        $response = $this->get(route('attendance.create'));

        $response->assertStatus(200);
        $response->assertViewIs('attendances.create');
        $response->assertViewHas('users');
    }

    /**
     * Test User can't store attendance if not authenticated
     *
     * @return void
     */
    public function test_user_cant_store_attendance_if_not_authenticated(): void
    {
        $response = $this->post(route('attendance.store'), []);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't store attendance if not authorized
     *
     * @return void
     */
    public function test_user_cant_store_attendance_if_not_authorized(): void
    {
        $this->loginUser('User');
        $response = $this->post(route('attendance.store'), []);

        $response->assertStatus(403);
    }

    /**
     * Test User can store attendance
     *
     * @return void
     */
    public function test_user_can_store_attendance(): void
    {
        $authUser = $this->loginUser('User', ['view attendances', 'create attendances']);

        $user = User::factory()->create();

        $response = $this->post(route('attendance.store'), [
            'user_id' => $user->id,
            'clock_in' => '2021-10-01T08:00',
            'clock_out' => '2021-10-01T17:00',
            'is_late' => false,
            'status' => 1,
            'overtime' => '01:00:00',
            'note' => 'Attendance note',
        ]);

        $response->assertRedirect(route('attendance'));
        $response->assertSessionHas('success-swal', 'Attendance created successfully!');

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'clock_in' => '2021-10-01 08:00:00',
            'clock_out' => '2021-10-01 17:00:00',
            'is_late' => false,
            'status' => 1,
            'overtime' => 3600,
            'note' => 'Attendance note',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'default',
            'description' => 'Create new attendance for ' . $user->name,
            'subject_type' => Attendance::class,
            'subject_id' => Attendance::query()->where('user_id', $user->id)->first()->id,
            'causer_type' => User::class,
            'causer_id' => $authUser->id,
        ]);
    }

    /**
     * Test user can't view the attendance edit page if not authenticated
     *
     * @return void
     */
    public function test_user_cant_view_attendance_edit_page_if_not_authenticated(): void
    {
        $attendance = Attendance::factory()->create();

        $response = $this->get(route('attendance.edit', $attendance));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't view the attendance edit page if not authorized
     *
     * @return void
     */
    public function test_user_cant_view_attendance_edit_page_if_not_authorized(): void
    {
        $this->loginUser('User');
        $attendance = Attendance::factory()->create();

        $response = $this->get(route('attendance.edit', $attendance));

        $response->assertStatus(403);
    }

    /**
     * Test User can view the attendance edit page
     *
     * @return void
     */
    public function test_user_can_view_attendance_edit_page(): void
    {
        $this->loginUser('User', ['view attendances', 'edit attendances']);
        $attendance = Attendance::factory()->create();

        $response = $this->get(route('attendance.edit', $attendance));

        $response->assertStatus(200);
        $response->assertViewIs('attendances.edit');
        $response->assertViewHas(['attendance', 'users']);
    }

    /**
     * Test User can't update attendance if not authenticated
     *
     * @return void
     */
    public function test_user_cant_update_attendance_if_not_authenticated(): void
    {
        $attendance = Attendance::factory()->create();

        $response = $this->put(route('attendance.update', $attendance), []);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't update attendance if not authorized
     *
     * @return void
     */
    public function test_user_cant_update_attendance_if_not_authorized(): void
    {
        $this->loginUser('User');
        $attendance = Attendance::factory()->create();

        $response = $this->put(route('attendance.update', $attendance), []);

        $response->assertStatus(403);
    }

    /**
     * Test User can update attendance
     *
     * @return void
     */
    public function test_user_can_update_attendance(): void
    {
        $authUser = $this->loginUser('User', ['view attendances', 'edit attendances']);

        $attendance = Attendance::factory()->create();

        $response = $this->put(route('attendance.update', $attendance), [
            'clock_in' => '2021-10-01T08:00',
            'clock_out' => '2021-10-01T17:00',
            'is_late' => false,
            'status' => 1,
            'overtime' => '01:00:00',
            'note' => 'Attendance note',
        ]);

        $response->assertRedirect(route('attendance'));
        $response->assertSessionHas('success-swal', 'Attendance updated successfully!');

        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'clock_in' => '2021-10-01 08:00:00',
            'clock_out' => '2021-10-01 17:00:00',
            'is_late' => false,
            'status' => 1,
            'overtime' => 3600,
            'note' => 'Attendance note',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'default',
            'description' => 'Update Attendance for ' . $attendance->user->name,
            'subject_type' => Attendance::class,
            'subject_id' => $attendance->id,
            'causer_type' => User::class,
            'causer_id' => $authUser->id,
        ]);
    }

    /**
     * Test User can't delete attendance if not authenticated
     *
     * @return void
     */
    public function test_user_cant_delete_attendance_if_not_authenticated(): void
    {
        $attendance = Attendance::factory()->create();

        $response = $this->delete(route('attendance.delete', $attendance));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test User can't delete attendance if not authorized
     *
     * @return void
     */
    public function test_user_cant_delete_attendance_if_not_authorized(): void
    {
        $this->loginUser('User');
        $attendance = Attendance::factory()->create();

        $response = $this->delete(route('attendance.delete', $attendance));

        $response->assertStatus(403);
    }

    /**
     * Test User can delete attendance
     *
     * @return void
     */
    public function test_user_can_delete_attendance(): void
    {
        $authUser = $this->loginUser('User', ['view attendances', 'delete attendances']);

        $attendance = Attendance::factory()->create();

        $response = $this->delete(route('attendance.delete', $attendance));

        $response->assertRedirect(route('attendance'));
        $response->assertSessionHas('success-swal', 'Attendance deleted successfully!');

        $this->assertDatabaseMissing('attendances', [
            'id' => $attendance->id,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'default',
            'description' => 'Delete attendance for ' . $attendance->user->name,
            'subject_type' => Attendance::class,
            'subject_id' => $attendance->id,
            'causer_type' => User::class,
            'causer_id' => $authUser->id,
        ]);
    }
}

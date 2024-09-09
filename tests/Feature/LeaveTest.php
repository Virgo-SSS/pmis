<?php

namespace Tests\Feature;

use App\Enums\LeaveStatus;
use App\Events\LeaveProcessed;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LeaveTest extends TestCase
{
    /**
     * Test user can't view leave index page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_leave_index_page_if_not_authenticated(): void
    {
        $response = $this->get(route('leave'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't view leave index page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_leave_index_page_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $response = $this->actingAs($user)->get(route('leave'));
        $response->assertForbidden();
    }

    /**
     * Test user can view leave index page if authorized.
     *
     * @return void
     */
    public function test_user_can_view_leave_index_page_if_authorized(): void
    {
        $this->loginUser('HR', ['view leaves']);
        $response = $this->get(route('leave'));
        $response->assertOk();
        $response->assertViewIs('leaves.index');
        $response->assertViewHas('leaves');
    }

    /**
     * Test user can't view leave create page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_leave_create_page_if_not_authenticated(): void
    {
        $response = $this->get(route('leave.create'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't view leave create page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_leave_create_page_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $response = $this->actingAs($user)->get(route('leave.create'));
        $response->assertForbidden();
    }

    /**
     * Test user can view leave create page if authorized.
     *
     * @return void
     */
    public function test_user_can_view_leave_create_page_if_authorized(): void
    {
        $this->loginUser('HR', ['create leaves']);
        $response = $this->get(route('leave.create'));
        $response->assertOk();
        $response->assertViewIs('leaves.create');
    }

    /**
     * Test user can't store leave if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_store_leave_if_not_authenticated(): void
    {
        $response = $this->post(route('leave.store'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't store leave if not authorized.
     *
     * @return void
     */
    public function test_user_cant_store_leave_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $response = $this->actingAs($user)->post(route('leave.store'));
        $response->assertForbidden();
    }

    /**
     * Test user can store leave if authorized.
     *
     * @return void
     */
    public function test_user_can_store_leave_if_authorized(): void
    {
        $user = $this->loginUser('User', ['create leaves']);

        $response = $this->post(route('leave.store'), [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'reason' => 'Leave Reason',
        ]);

        $response->assertRedirect(route('leave'));
        $response->assertSessionHas('success-swal', 'Leave created successfully');

        $this->assertDatabaseHas('leaves', [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'reason' => 'Leave Reason',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Request a leave',
            'subject_type' => Leave::class,
            'subject_id' => Leave::query()->where('user_id', $user->id)->where('start_date', '2021-01-01')->first()->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'created',
            'log_name' => 'default',
        ]);
    }

    /**
     * Test user can't view leave edit page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_leave_edit_page_if_not_authenticated(): void
    {
        $leave = Leave::factory()->create();
        $response = $this->get(route('leave.edit', $leave));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't view leave edit page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_leave_edit_page_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $leave = Leave::factory()->create();
        $response = $this->actingAs($user)->get(route('leave.edit', $leave));
        $response->assertForbidden();
    }

    /**
     * Test user can view leave edit page if authorized.
     *
     * @return void
     */
    public function test_user_can_view_leave_edit_page_if_authorized(): void
    {
        $this->loginUser('HR', ['edit leaves']);
        $leave = Leave::factory()->create();
        $response = $this->get(route('leave.edit', $leave));
        $response->assertOk();
        $response->assertViewIs('leaves.edit');
        $response->assertViewHas('leave');
    }

    /**
     * Test user can't update leave if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_update_leave_if_not_authenticated(): void
    {
        $leave = Leave::factory()->create();
        $response = $this->put(route('leave.update', $leave));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't update leave if not authorized.
     *
     * @return void
     */
    public function test_user_cant_update_leave_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $leave = Leave::factory()->create();
        $response = $this->actingAs($user)->put(route('leave.update', $leave));
        $response->assertForbidden();
    }

    /**
     * Test user can update leave if authorized.
     *
     * @return void
     */
    public function test_user_can_update_leave_if_authorized(): void
    {
        $user = $this->loginUser('User', ['edit leaves']);
        $leave = Leave::factory()->approved()->create(['user_id' => $user->id]);

        Event::fake(); // must after $this->loginUser function

        $response = $this->put(route('leave.update', $leave), [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'reason' => 'Leave Reason',
            'status' => LeaveStatus::REJECTED->value,
        ]);
        $response->assertRedirect(route('leave'));
        $response->assertSessionHas('success-swal', 'Leave updated successfully');

        Event::assertDispatched(LeaveProcessed::class);

        $this->assertDatabaseMissing('leaves', [
            'start_date' => $leave->start_date,
            'end_date' => $leave->end_date,
            'reason' => $leave->reason,
            'status' => $leave->status,
        ]);

        $this->assertDatabaseHas('leaves', [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'reason' => 'Leave Reason',
            'status' => LeaveStatus::REJECTED,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Update User ' . $user->name . ' leave request',
            'subject_type' => Leave::class,
            'subject_id' => $leave->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'updated',
            'log_name' => 'default',
        ]);
    }

    /**
     * Test event leaveProcessed not dispatched if leave status changed to pending.
     *
     * @return void
     */
    public function test_event_leaveProcessed_not_dispatched_if_leave_status_changed_to_pending(): void
    {
        $user = $this->loginUser('User', ['edit leaves']);
        $leave = Leave::factory()->approved()->create(['user_id' => $user->id]);

        Event::fake(); // must after $this->loginUser function

        $response = $this->put(route('leave.update', $leave), [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'reason' => 'Leave Reason',
            'status' => LeaveStatus::PENDING->value,
        ]);
        $response->assertRedirect(route('leave'));
        $response->assertSessionHas('success-swal', 'Leave updated successfully');

        Event::assertNotDispatched(LeaveProcessed::class);

        $this->assertDatabaseMissing('leaves', [
            'start_date' => $leave->start_date,
            'end_date' => $leave->end_date,
            'reason' => $leave->reason,
            'status' => $leave->status,
        ]);

        $this->assertDatabaseHas('leaves', [
            'start_date' => '2021-01-01',
            'end_date' => '2021-01-02',
            'reason' => 'Leave Reason',
            'status' => LeaveStatus::PENDING,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Update User ' . $user->name . ' leave request',
            'subject_type' => Leave::class,
            'subject_id' => $leave->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'updated',
            'log_name' => 'default',
        ]);
    }

    /**
     * Test user can't delete leave if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_delete_leave_if_not_authenticated(): void
    {
        $leave = Leave::factory()->create();
        $response = $this->delete(route('leave.delete', $leave));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't delete leave if not authorized.
     *
     * @return void
     */
    public function test_user_cant_delete_leave_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $leave = Leave::factory()->create();
        $response = $this->actingAs($user)->delete(route('leave.delete', $leave));
        $response->assertForbidden();
    }

    /**
     * Test user can delete leave if authorized.
     *
     * @return void
     */
    public function test_user_can_delete_leave_if_authorized(): void
    {
        $user = $this->loginUser('User', ['delete leaves']);
        $leave = Leave::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('leave.delete', $leave));
        $response->assertRedirect(route('leave'));
        $response->assertSessionHas('success-swal', 'Leave deleted successfully');

        $this->assertDatabaseMissing('leaves', [
            'start_date' => $leave->start_date,
            'end_date' => $leave->end_date,
            'reason' => $leave->reason,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Delete User ' . $user->name . ' leave request',
            'subject_type' => Leave::class,
            'subject_id' => $leave->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'deleted',
            'log_name' => 'default',
        ]);
    }

    /**
     * Test User can't view review leave page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_review_leave_page_if_not_authenticated(): void
    {
        $leave = Leave::factory()->create();
        $response = $this->get(route('leave.review', $leave));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't view review leave page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_review_leave_page_if_not_authorized(): void
    {
        $user = $this->createUser('HR');
        $leave = Leave::factory()->create();
        $response = $this->actingAs($user)->get(route('leave.review', $leave));
        $response->assertForbidden();
    }

    /**
     * Test user can view review leave page if authorized.
     *
     * @return void
     */
    public function test_user_can_view_review_leave_page_if_authorized(): void
    {
        $this->loginUser('HR', ['review leaves']);
        $response = $this->get(route('leave.review'));
        $response->assertOk();
        $response->assertViewIs('leaves.review');
        $response->assertViewHas('leaves');
    }

    /**
     * Test user can't approve leave if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_approve_leave_if_not_authenticated(): void
    {
        $leave = Leave::factory()->create();
        $response = $this->patch(route('leave.approve', $leave));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't approve leave if not authorized.
     *
     * @return void
     */
    public function test_user_cant_approve_leave_if_not_authorized(): void
    {
        $this->loginUser('HR');
        $leave = Leave::factory()->create();
        $response = $this->patch(route('leave.approve', $leave));
        $response->assertForbidden();
    }

    /**
     * Test user can approve leave if authorized.
     *
     * @return void
     */
    public function test_user_can_approve_leave_if_authorized(): void
    {
        $user = $this->loginUser('HR', ['review leaves']);
        $leave = Leave::factory()->create();

        Event::fake(); // must after $this->>loginUser function

        $response = $this->patch(route('leave.approve', $leave));

        $response->assertRedirect(route('leave'));
        $response->assertSessionHas('success-swal', 'Leave approved successfully');

        Event::assertDispatched(LeaveProcessed::class);

        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => LeaveStatus::APPROVED,
            'approved_by' => $user->id,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Approve user ' . $leave->user->name . ' leave request',
            'subject_type' => Leave::class,
            'subject_id' => $leave->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'leave.processed',
            'log_name' => 'default',
        ]);
    }

    /**
     * Test user can't reject leave if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_reject_leave_if_not_authenticated(): void
    {
        $leave = Leave::factory()->create();
        $response = $this->patch(route('leave.reject', $leave));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't reject leave if not authorized.
     *
     * @return void
     */
    public function test_user_cant_reject_leave_if_not_authorized(): void
    {
        $this->loginUser('HR');
        $leave = Leave::factory()->create();
        $response = $this->patch(route('leave.reject', $leave));
        $response->assertForbidden();
    }

    /**
     * Test user can reject leave if authorized.
     *
     * @return void
     */
    public function test_user_can_reject_leave_if_authorized(): void
    {
        $user = $this->loginUser('HR', ['review leaves']);
        $leave = Leave::factory()->create();

        Event::fake(); // must after $this->loginUser function

        $response = $this->patch(route('leave.reject', $leave));

        $response->assertRedirect(route('leave'));
        $response->assertSessionHas('success-swal', 'Leave rejected successfully');

        Event::assertDispatched(LeaveProcessed::class);

        $this->assertDatabaseHas('leaves', [
            'id' => $leave->id,
            'status' => LeaveStatus::REJECTED,
            'approved_by' => $user->id,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Reject user ' . $leave->user->name . ' leave request',
            'subject_type' => Leave::class,
            'subject_id' => $leave->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'leave.processed',
            'log_name' => 'default',
        ]);
    }
}

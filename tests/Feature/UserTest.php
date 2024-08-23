<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\Department;
use App\Models\User;
use App\Models\UserBank;
use App\Models\UserProfile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test user can't view users page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_users_page_if_not_authenticated(): void
    {
        $response = $this->get(route('user'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can't view users page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_users_page_if_not_authorized(): void
    {
        $this->loginUser('user');
        $response = $this->get(route('user'));

        $response->assertStatus(403);
    }

    /**
     * Test user can view users page
     *
     * @return void
     */
    public function test_user_can_view_users_page(): void
    {
        $this->loginUser('user', ['view users']);

        $response = $this->get(route('user'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');

    }

    /**
     * Test user can't view user create page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_user_create_page_if_not_authorized(): void
    {
        $this->loginUser('user');
        $response = $this->get(route('user.create'));

        $response->assertStatus(403);
    }

    /**
     * Test User can't view user create page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_user_create_page_if_not_authenticated(): void
    {
        $response = $this->get(route('user.create'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can view user create page
     *
     * @return void
     */
    public function test_user_can_view_user_create_page(): void
    {
        $this->loginUser('user', ['view users', 'create users']);

        $response = $this->get(route('user.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
        $response->assertViewHasAll(['departments', 'roles', 'banks']);
    }

    /**
     * Test user can't store user if not authorized.
     *
     * @return void
     */
    public function test_user_cant_store_user_if_not_authorized(): void
    {
        $this->loginUser('user');
        $response = $this->post(route('user.store'), []);

        $response->assertStatus(403);
    }

    /**
     * Test user can't store user if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_store_user_if_not_authenticated(): void
    {
        $response = $this->post(route('user.store'), []);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can store user
     *
     * @return void
     */
    public function test_user_can_store_user(): void
    {
        Storage::fake('public');

        $authUser = $this->loginUser('user', ['view users', 'create users']);
        $file = UploadedFile::fake()->image('profile_picture.jpg');

        $department = Department::factory()->create();
        $bank = Bank::factory()->create();

        $userRequest = User::factory()->unverified()->make()->toArray();
        $userProfileRequest = UserProfile::factory()
            ->profilePicture($file)
            ->department($department->id)->make()->toArray();

        $userBankRequest = UserBank::factory(['bank_id' => $bank->id])->make()->toArray();

        $request = array_merge(
            $userRequest,
            $userProfileRequest,
            $userBankRequest,
            ['roles' => ['user']],
            ['password' => 'password'],
        );

        $response = $this->post(route('user.store'), $request);

        $response->assertRedirect(route('user'));
        $response->assertSessionHas('success-swal', 'User created successfully.');

        $this->assertDatabaseHas('users', $userRequest);

        $profilePicture = User::query()->where('username', $userRequest['username'])->first()->profile->profile_picture;

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => User::query()->where('username', $userRequest['username'])->first()->id,
            'department_id' => $userProfileRequest['department_id'],
            'profile_picture' => $profilePicture,
            'phone' => $userProfileRequest['phone'],
            'gender' => $userProfileRequest['gender'],
            'emergency_contact' => $userProfileRequest['emergency_contact'],
            'address' => $userProfileRequest['address'],
            'joined_at' => $userProfileRequest['joined_at'],
        ]);

        $this->assertDatabaseHas('user_banks', [
            'user_id' => User::query()->where('username', $userRequest['username'])->first()->id,
            'bank_id' => $userBankRequest['bank_id'],
            'account_number' => $userBankRequest['account_number'],
            'account_name' => $userBankRequest['account_name'],
        ]);

        $this->assertDatabaseHas(config('permission.table_names.model_has_roles'), [
            'model_id' => User::query()->where('email', $userRequest['email'])->first()->id,
            'model_type' => User::class,
            'role_id' => Role::query()->where('name', 'user')->first()->id,
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Create new user with name ' . $userRequest['name'],
            'subject_type' => User::class,
            'subject_id' => User::query()->where('username', $userRequest['username'])->first()->id,
            'causer_type' => User::class,
            'causer_id' => $authUser->id,
        ]);

        Storage::disk('public')->assertExists('user-profile-pictures/' . $profilePicture);
    }

    /**
     * Test user can't view user edit page if not authorized.
     *
     * @return void
     */
    public function test_user_cant_view_user_edit_page_if_not_authorized(): void
    {
        $this->loginUser('user');
        $user = User::factory()->create();
        $response = $this->get(route('user.edit', $user->id));

        $response->assertStatus(403);
    }

    /**
     * Test user can't view user edit page if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_view_user_edit_page_if_not_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->get(route('user.edit', $user->id));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can view user edit page
     *
     * @return void
     */
    public function test_user_can_view_user_edit_page(): void
    {
        $this->loginUser('user', ['view users', 'edit users']);

        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id]);
        UserBank::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('user.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertViewHasAll(['departments', 'roles', 'user', 'banks']);
    }

    /**
     * Test user can't update user if not authorized.
     *
     * @return void
     */
    public function test_user_cant_update_user_if_not_authorized(): void
    {
        $this->loginUser('user');
        $user = User::factory()->create();
        $response = $this->put(route('user.update', $user->id), []);

        $response->assertStatus(403);
    }

    /**
     * Test user can't update user if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_update_user_if_not_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->put(route('user.update', $user->id), []);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test user can update user
     *
     * @return void
     */
    public function test_user_can_update_user(): void
    {
        Storage::fake('public');
        $authUser = $this->loginUser('user', ['view users', 'edit users']);

        // Prepare necessary data
        $file = UploadedFile::fake()->image('profile_picture.jpg');
        $file2 = UploadedFile::fake()->image('profile_picture2.jpg');

        $department = Department::factory()->create();
        $bank = Bank::factory()->create();
        $user = User::factory()->unverified()->create();
        UserProfile::factory()
            ->profilePicture($file2, true)
            ->department($department->id)
            ->create(['user_id' => $user->id]);

        $user->assignRole(['user']);
        $oldProfilePicture = $user->profile->profile_picture;

        // Prepare Request
        $userRequest = User::factory()->unverified()->make()->toArray();
        $userProfileRequest = UserProfile::factory()
            ->profilePicture($file)
            ->department($department->id)->make()->toArray();
        $userBankRequest = UserBank::factory(['bank_id' => $bank->id])->make()->toArray();

        // Send Request
        $response = $this->put(route('user.update', $user->id), array_merge(
            $userRequest,
            $userProfileRequest,
            $userBankRequest,
            ['roles' => ['user']],
            ['password' => 'password'],
        ));

        // Assert Response
        $response->assertRedirect(route('user'));
        $response->assertSessionHas('success-swal', 'User updated successfully.');

        $this->assertDatabaseHas('users', $userRequest);

        $profilePicture = User::query()->where('username', $userRequest['username'])->first()->profile->profile_picture;
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'department_id' => $userProfileRequest['department_id'],
            'profile_picture' => $profilePicture,
            'phone' => $userProfileRequest['phone'],
            'gender' => $userProfileRequest['gender'],
            'emergency_contact' => $userProfileRequest['emergency_contact'],
            'address' => $userProfileRequest['address'],
            'joined_at' => $userProfileRequest['joined_at'],
        ]);

        $this->assertDatabaseHas('user_banks', [
            'user_id' => $user->id,
            'bank_id' => $userBankRequest['bank_id'],
            'account_number' => $userBankRequest['account_number'],
            'account_name' => $userBankRequest['account_name'],
        ]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Update user with name ' . $userRequest['name'],
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $authUser->id,
        ]);

        $this->assertDatabaseHas(config('permission.table_names.model_has_roles'), [
            'model_id' => $user->id,
            'model_type' => User::class,
            'role_id' => Role::query()->where('name', 'user')->first()->id,
        ]);

        Storage::disk('public')->assertExists('user-profile-pictures/' . $profilePicture);
        Storage::disk('public')->assertMissing('user-profile-pictures/' . $oldProfilePicture);
    }

    /**
     * Test user can't delete user if not authorized.
     *
     * @return void
     */
    public function test_user_cant_delete_user_if_not_authorized(): void
    {
        $this->loginUser('user');
        $user = User::factory()->create();
        $response = $this->delete(route('user.delete', $user->id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Test user can't delete user if not authenticated.
     *
     * @return void
     */
    public function test_user_cant_delete_user_if_not_authenticated(): void
    {
        $user = User::factory()->create();
        $response = $this->delete(route('user.delete', $user->id));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Test user can delete user
     *
     * @return void
     */
    public function test_user_can_delete_user(): void
    {
        $authUser = $this->loginUser('user', ['view users', 'delete users']);

        $user = User::factory()->create();

        $response = $this->delete(route('user.delete', $user->id));

        $response->assertRedirect(route('user'));
        $response->assertSessionHas('success-swal', 'User deleted successfully.');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('user_profiles', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('user_banks', ['user_id' => $user->id]);

        $this->assertDatabaseHas(config('activitylog.table_name'), [
            'log_name' => 'default',
            'description' => 'Delete user with name ' . $user->name,
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $authUser->id,
        ]);
    }
}

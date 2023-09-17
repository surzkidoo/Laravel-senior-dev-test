<?php

namespace Tests\Unit;

use App\Services\UserService;
use PHPUnit\Framework\TestCase;
use Illuminate\Pagination\Paginator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserServiceTest extends TestCase
{

    use DatabaseMigrations, RefreshDatabase, WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {
       
        $user = new UserService();
        $result = $user->all();
        $this->assertInstanceOf(Paginator::class, $result);
    }


    public function it_can_store_a_user_to_database()
{
    $userData = [
        'firstname' => 'John Doe',
        'lastname' => 'John Doe',
        'password' => 'password',
        'usernmae' => 'user1',
        'email' => 'johndoe@example.com',
    ];

    $userService = new UserService();
    $user = $userService->store($userData);

    
    $this->assertInstanceOf(User::class, $user);
    $this->assertDatabaseHas('users', $userData);
}


public function it_can_find_and_return_an_existing_user()
{
    
    $existingUserData = [
        'firstname' => 'John Doe',
        'lastname' => 'John Doe',
        'password' => 'password',
        'usernmae' => 'user1',
        'email' => 'johndoe@example.com'
    ];

    $user = User::factory()->create($existingUserData);

  
    $userService = new UserService();
    $foundUser = $userService->find($user->id);

    $this->assertInstanceOf(User::class, $foundUser);
    $this->assertEquals($user->id, $foundUser->id);
}


public function test_it_can_update_an_existing_user()
{
  
    $existingUserData = [
        'firstname' => 'John Doe',
        'lastname' => 'John Doe',
        'password' => 'password',
        'usernmae' => 'user1',
        'email' => 'johndoe@example.com'
    ];

    $user = User::factory()->create($existingUserData);

    // New data to update the user
    $updatedUserData = [
        'firstname' => 'John Doe',
        'lastname' => 'John Doe',
        'password' => 'password',
        'usernmae' => 'user3',
        'email' => 'johndoe@example.com'
    ];

    // Actions
    $userService = new UserService();
    $updatedUser = $userService->update($user->id, $updatedUserData);

    // Assertions
    $this->assertInstanceOf(User::class, $updatedUser);
    $this->assertEquals('user3', $updatedUser->firstname);
}

public function it_can_soft_delete_an_existing_user()
{
    // Arrangements
    $existingUserData = [
        'firstname' => 'John Doe',
        'lastname' => 'John Doe',
        'password' => 'password',
        'usernmae' => 'user3',
        'email' => 'johndoe@example.com'
    ];

    $user = User::factory()->create($existingUserData);
    $userService = new UserService();
    $userService->destroyed('johndoe@example.com');
    $this->assertSoftDeleted('users', $existingUserData);
}


public function it_can_return_a_paginated_list_of_trashed_users()
{

    $trashedUsers = factory(User::class, 5)->create();
    foreach ($trashedUsers as $user) {
        $user->delete();
    }
    $userService = new UserService();
    $trashedUserList = $userService->trashed();

    $this->assertInstanceOf(LengthAwarePaginator::class, $trashedUserList);
    $this->assertEquals(count($trashedUsers), $trashedUserList->total());
}


public function it_can_restore_a_soft_deleted_user()
{
   
    $user = User::factory()->create();
    $user->delete();

    $userService = new UserService();
    $userService->restore($user->id);

    $restoredUser = User::withTrashed()->find($user->id);
    $this->assertFalse($restoredUser->trashed());
}


public function it_can_permanently_delete_a_soft_deleted_user()
{

    $user = User::factory()->create();
    $user->delete();

    // Actions
    $userService = new UserService();
    $userService->delete($user->id);

    // Assertions
    $deletedUser = User::withTrashed()->find($user->id);
    $this->assertNull($deletedUser);
}

public function it_can_upload_photo()
{
    // Arrangements
    $user = User::factory()->create();
    $userService = new UserService();
    $file = UploadedFile::fake()->image('avatar.jpg');
    $uploadedPhotoPath = $userService->upload($user->id, $file);
    $this->assertNotNull($uploadedPhotoPath);
    $this->assertFileExists(public_path($uploadedPhotoPath)); 
}


}

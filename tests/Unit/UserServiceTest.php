<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
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

    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = app(UserService::class);
    }


    public function test_it_can_return_a_paginated_list_of_users()
    {

        $result = $this->service->list();
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }


    public function test_it_can_store_a_user_to_database()
    {
        $userData = [
            'prefixname' => 'Mr',
            'firstname' => 'fahs',
            'lastname' => 'musa',
            'middlename' => 's',
            'suffixname' => 'jr',
            'password' => 'password',
            'username' => 'user1',
            'email' => 'johnnamusa@example.com',
        ];


        $user = $this->service->store($userData);


        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users',[
            'prefixname' => 'Mr',
            'firstname' => 'fahs',
            'lastname' => 'musa',
            'middlename' => 's',
            'suffixname' => 'jr',
            'username' => 'user1',
            'email' => 'johnnamusa@example.com',
        ]);
    }


    public function test_it_can_find_and_return_an_existing_user()
    {

        $existingUserData = [
            'prefixname' => 'Mr',
            'firstname' => 'fahs',
            'lastname' => 'musa',
            'middlename' => 's',
            'suffixname' => 'jr',
            'password' => 'password',
            'username' => 'user1',
            'email' => 'johnnamusa@example.com',
        ];

        $user = User::factory()->create($existingUserData);



        $foundUser = $this->service->find($user->id);

        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }


    public function test_it_can_update_an_existing_user()
    {

        $existingUserData = [
            'prefixname' => 'Mr',
            'firstname' => 'fahs',
            'lastname' => 'musa',
            'middlename' => 's',
            'suffixname' => 'jr',
            'password' => 'password',
            'username' => 'user1',
            'email' => 'johnnamusa@example.com',
        ];

        $user = User::factory()->create($existingUserData);

        // New data to update the user
        $updatedUserData = [
            'prefixname' => 'Mr',
            'firstname' => 'musah',
            'lastname' => 'musah',
            'middlename' => 's',
            'suffixname' => 'jr',
            'password' => 'password',
            'username' => 'user1',
            'email' => 'johnnamusa@example.com',
        ];

        

        $result = $this->service->update($user->id, $updatedUserData);

        $updatedUser = $this->service->find($user->id);

       
        $this->assertTrue($result);
        $this->assertEquals('musah', $updatedUser->firstname);
    }

    public function test_it_can_soft_delete_an_existing_user()
    {
        
        $existingUserData = [
            'prefixname' => 'Mr',
            'firstname' => 'fahs',
            'lastname' => 'musa',
            'middlename' => 's',
            'suffixname' => 'jr',
            'password' => 'password',
            'username' => 'user1',
            'email' => 'johnnamusa@example.com',
        ];

        $user = User::factory()->create($existingUserData);

        $this->service->destroy($user->id);
        $this->assertSoftDeleted('users', $existingUserData);
    }


    public function test_it_can_return_a_paginated_list_of_trashed_users()
    {

        $trashedUsers = User::factory(5)->create();
        foreach ($trashedUsers as $user) {
            $user->delete();
        }

        $trashedUserList = $this->service->listTrashed();

        $this->assertInstanceOf(LengthAwarePaginator::class, $trashedUserList);
        $this->assertEquals(count($trashedUsers), $trashedUserList->total());
    }


    public function test_it_can_restore_a_soft_deleted_user()
    {

        $user = User::factory()->create();
        $user->delete();


        $this->service->restore($user->id);

        $restoredUser = User::withTrashed()->find($user->id);
        $this->assertFalse($restoredUser->trashed());
    }


    public function test_it_can_permanently_delete_a_soft_deleted_user()
    {

        $user = User::factory()->create();
        $user->delete();

        

        $this->service->delete($user->id);

       
        $deletedUser = User::withTrashed()->find($user->id);
      
        $this->assertNull($deletedUser);
    }

    public function test_it_can_upload_photo()
    {
     
        $file = UploadedFile::fake()->image('avatar.jpg');
        $uploadedPhotoPath = $this->service->upload($file);
        $this->assertNotNull($uploadedPhotoPath);
        $this->assertFileExists(public_path('storage/'.$uploadedPhotoPath));
    }


}
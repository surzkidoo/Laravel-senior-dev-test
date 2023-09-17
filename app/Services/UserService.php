<?php 
namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    /**
     * The model instance.
     *
     * @var App\User
     */
    protected $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  int $id
     * @return array
     */
    public function rules($id = null)
    {

        return [
            
            'firstname' => 'required|min:3',
            'lastname' => 'required|min:3',
            'middlename' => 'nullable|min:3',
            'suffixname' => 'nullable|min:1',
            'prefixname' => 'in:Mr,Mrs,Ms',
            'email' =>['required',Rule::unique('users')->ignore($id)],
            'username' => ['required',Rule::unique('users')->ignore($id)],
            'password' => 'confirmed|min:8',
            'photo' => 'mimes:jpg,png,jpeg',
            
        ];
    }

    
    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        $users = User::paginate();

        return $users;
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
    
        $user = new User();
     $user->prefixname = $attributes['prefixname'];
     $user->firstname = $attributes['firstname']; 
     $user->lastname = $attributes['lastname']; 
     $user->middlename = $attributes['middlename']; 
     $user->suffixname = $attributes['suffixname']; 
     $user->username = $attributes['username'];
     $this->request->hasFile('file') &&  $user->photo = $this->upload($this->request->file('file'));
     $user->email = $attributes['email'];
     $user->password = $this->hash($attributes['password']);
     $user->save();

    return $user;

    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id):? Model
    {
      $user =  $this->model->findOrFail($id);

      return $user;
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {

        $user = $this->model->where('id',$id)->first();
        $user->prefixname = $attributes['prefixname'];
        $user->firstname = $attributes['firstname']; 
        $user->lastname = $attributes['lastname']; 
        $user->middlename = $attributes['middlename']; 
        $user->suffixname = $attributes['suffixname']; 
        $user->username = $attributes['username'];
        $user->photo = $this->upload($this->request->file('file'));
        $user->email = $attributes['email'];
        $user->password = $this->hash($attributes['password']);
        $user->save();
        $result = $user->save();

        return boolval($result);


    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id)
    {
        $this->model->where('id',$id)->delete();
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
       return $this->model->onlyTrashed()->paginate();
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
        $this->model->withTrashed()->where('id',$id)->first()->restore();
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        $this->model->where('id',$id)->forceDelete();
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
       $hashed = Hash::make($key);

       return $hashed;
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        $path = $file->store('/user/images','public');
       return 'http://localhost:8000/storage/'.$path;

    }
}

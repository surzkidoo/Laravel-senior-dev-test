<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  protected $user;

    public function __construct(UserService $user) 
    {
      // $this->middleware('auth');

      $this->user= $user;
    }

    public function index(){
       $alluser = $this->user->list();
        return view('home',['users'=>$alluser]);
    }

    public function show(Request $request,$id){
        $user = $this->user->find($id);
        return view('show',['user'=>$user]);
     }

     public function edit(Request $request,$id){
      
        $user = User::where('id',$id)->first();
         return view('edit',['user'=>$user]);

     }

     public function update(UserRequest $request,int $id){
      $user = User::where('id',$id)->first();

      $user->prefixname = $request->prefixname;
      $user->firstname = $request->firstname; 
      $user->lastname = $request->lastname; 
      $user->middlename = $request->middlename; 
      $user->suffixname = $request->suffixname; 
      $user->username = $request->username;
      $user->email = $request->email;
      $user->password &&  Hash::make($request->password);

      if ($request->hasFile('file'))
      {
          $file  = $request->file('file');
          $path = $file->store('/user/images','public');
          $user->photo = 'storage/'.$path;
         
      }
      $user->save();

      return redirect()->route('user.index');;
   }

     public function create(){
         return view('create');
     }

     public function store(UserRequest $request){
      
      // $user = new User();
      // $user->prefixname = $request->prefixname;
      // $user->firstname = $request->firstname; 
      // $user->lastname = $request->lastname; 
      // $user->middlename = $request->middlename; 
      // $user->suffixname = $request->suffixname; 
      // $user->username = $request->username;
      // $user->email = $request->email;
      // $user->password = Hash::make($request->password);

      // if ($request->hasFile('file'))
      // {
      //     $file  = $request->file('file');
      //     $path = $file->store('/user/images','public');
      //     $user->photo = 'storage/'.$path;
         
      // }
      // $user->save();




      $this->user->store($request->all());

      return redirect()->back()->with('register','Successfull Register!!! You can now Login');
  }
  

     public function trashed(){
      //  $trashedUser = User::onlyTrashed()->get();
      $trashedUser = $this->user->listTrashed();
       return view('trashed',['users'=>$trashedUser]);
     }

     public function restore($id){
        // $user = User::withTrashed()->where('id',$id)->first();
        // $user->restore();

        $this->user->restore($id);
        return redirect()->back();
      }

      
      public function destroy($id){
        // $user = User::where('id',$id)->first();
        // $user->delete();

        $this->user->destroy($id);
        return redirect('/');
      }

      public function delete($id){
        // $user = User::withTrashed()->where('id',$id)->first();
        // $user->forceDelete();

        $this->user->delete($id);
        return redirect('/');
      }
}

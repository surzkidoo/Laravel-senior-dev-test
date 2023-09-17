@extends('layouts.app')

@section('content')

   
            <div class="container">
              <h4 class="h4">trashed</h4>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">Photo</th>
                    <th scope="col">prefix</th>
                    <th scope="col">FirstName</th>
                    <th scope="col">LastName</th>
                    <th scope="col">MiddleName</th>
                    <th scope="col">Email</th>
                    <th scope="col">Username</th>
                    <th scope="col">SuffixName</th>
                    <th scope="col">Action</th>

                  </tr>
                </thead>
                <tbody>
                    @if(! $users->isEmpty())

                
                    @foreach ($users as $user)
                  <tr>
                    <td> <img src="{{$user->avatar}}" class="rounded-circle" alt="" width="55" height="55" srcset=""></td>
                    
                    <td>{{$user->prefixname}}</td>
                    
                    <td>{{$user->firstname}}</td>
                 
                    <td>{{$user->lastname}}</td>
                 
                    <td>{{$user->middlename}}</td>
                 
                    <td>{{$user->email}}</td>

                    <td>{{$user->username}}</td>

                                      
                    <td>{{$user->suffixname}}</td>
                        <td>
                            <a href="{{route('user.restore',$user->id)}}">
                                <button class="btn btn-sm btn-success">
                                    Restore
                                </button>
                            </a>

                            <a href="{{route('user.edit',$user->id)}}">
                                <button class="btn btn-sm btn-success">
                                    Edit
                                </button>
                            </a>

                            <a href="{{route('user.delete',$user->id)}}">
                                <button class="btn btn-sm btn-danger">
                                    Delete Permanently
                                </button>
                            </a>
                        
                        </td>


                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
           
            </div>
           

@endsection


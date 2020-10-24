<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Traits\ApiResponser;
use DB;

Class UserController extends Controller {
    use ApiResponser;
    private $request;

    public function __construct(Request $request){
    $this->request = $request;
    }
    //login
    public function login(){
        return view('login');
    }
    
    //validate user login
    public function validateUser(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = app('db')->select("SELECT * FROM tbluser WHERE username='$username' and password='$password'");
            if(empty($user)){
                return 'Invalid credentials.';
            }else{
                return redirect()->route('dashboard');
           
    }
}

     //dashboard
     public function dashboard(){
        return view('dashboard');
    }
   
       
    //get all data
    public function getUsers(){
        $users = app('db')->select ('SELECT * FROM tbluser');
        return response()->json($users, 200);
    }

    public function index(){
        $users = User::all();
        return  response()->json($users, 200);
    }
    
    //create new user
    public function addUser(Request $request ){
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
        ];

        $this->validate($request,$rules);

        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    //show user data
    public function show($id){
         $user = User::findOrFail($id);
         return response()->json($user);     
    }
    
    //update user data
    public function update(Request $request,$id){
        $rules = [
        'username' => 'max:20',
        'password' => 'max:20',
        ];

        $this->validate($request, $rules);
        $user = User::findOrFail($id);
            
        $user->fill($request->all());

        // if no changes happen
        if ($user->isClean()) {
            return response()->json('No changes at all', 404);
        }
        //save changes
        $user->save();
        return response()->json($user);
    }

    //delete user data
    public function delete($id){
        $user = User::find($id);
            if($user ==null) return response()->json('Doesnt exist in database', 404);
            $user->delete();
            return response()->json('The data has been deleted',200);
    }
}
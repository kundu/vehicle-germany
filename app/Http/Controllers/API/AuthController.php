<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AuthController extends BaseController
{

    /**
     * log in method
     *
     * @param   Request $request
     * @return  token   log in user auth token
     * @return  name    log in user name
     * @return  App\Http\Controllers\API\BaseController method
     */
    public function login(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError('Input validation error', $validator->errors());
            }

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $authUser           = Auth::user();
                $authUser->tokens()->delete();
                $success['token']   =  $authUser->createToken('VehicleAppAuth')->plainTextToken;
                $success['name']    =  $authUser->name;

                return $this->sendResponse($success, 'Login Successfully !!!');
            }
            else{
                return $this->sendError('Wrong email or password.', ['error'=>'Unauthorized']);
            }
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendError('Internal Server Error. Please inform admin to check log file.');
        }
    }

    /**
     * sign up method for new user
     *
     * @param   Request $request
     * @return  App\Http\Controllers\API\BaseController method
     */
    public function signup(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name'             => 'required|max:50',
                'email'            => 'required|max:100|unique:users,email',
                'password'         => ['required', Password::min(6)->mixedCase()->numbers()->symbols()],
                'confirm_password' => 'required|same:password',
            ]);

            if($validator->fails()){
                return $this->sendError('Input validation error', $validator->errors());
            }

            $input              = $request->all();
            $input['password']  = bcrypt($input['password']);
            $user               = User::create($input);
            $success['token']   =  $user->createToken('MyAuthApp')->plainTextToken;
            $success['name']    =  $user->name;

            return $this->sendResponse($success, 'User created successfully.');
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendError('Internal Server Error. Please inform admin to check log file.');
        }
    }

}

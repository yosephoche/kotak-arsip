<?php namespace App\Http\Controllers\Chatbot;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use App\User;
use App\UserVerification;
use GlobalClass;

class AuthController extends Controller
{
    public function authLoginForm()
    {
        $username = !empty($_GET['u']) ? $_GET['u'] : '';
        return view('chatbot.login')->withUsername($username);
    }

    public function authLoginSave(Request $request)
    {        
        $this->validateLogin($request);
        
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        
        //ini untuk yang terlalu banyak login
        /* if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        } */
        
        if ($this->attemptLogin($request)) {
            //find user on users collection
            $redirectUri = 'kotak_arsip_bot';
            $user = User::where('email',$request->email)->first();
            //check if there is no user registered on user verification table
            $user_id = GlobalClass::generateMongoObjectId($user->id);
            $isRegisteredUser = UserVerification::where('user_id',$user_id)->first();
            if($isRegisteredUser){
                //user is registered
                return redirect('https://telegram.me/'.$redirectUri);
            }
            //user is not registered and will be register below
            $newUserVerification = new UserVerification();
            $token = str_random(50); //will be replace to jwt mechanism
            $newUserVerification->token = $token;
            $newUserVerification->user_telegram = $request->get('u'); // get username telegram
            $newUserVerification->user_id = $user_id;
            
            //insert data
            if($newUserVerification->save()){
                //return to telegram username bot
                return redirect('https://telegram.me/'.$redirectUri);
            }
            //failed to save user verification
            dd("failed to save user");
            
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit($this->throttleKey($request));
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed')];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    public function username()
    {
        return 'email';
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    public function isAuthorized()
    {
        $username = @$_GET['u'];
        $isRegisteredUser = UserVerification::where('user_telegram',$username)->first();
        $data = '';
        if($isRegisteredUser){
            $data = [
                'status' => 'success',
                'message'   => 'user is registered'
            ];
            return response()->json($data, 200);
        }
        $data = [
            'status' => 'failed',
            'message'   => 'user is not registered'
        ];
        return response()->json($data, 200);
    }
}

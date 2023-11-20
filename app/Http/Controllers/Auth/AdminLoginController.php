<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Mail\ResetMail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Admin;
use DateTime;
use Mail;
use Auth;

class AdminLoginController extends Controller {

    use AuthenticatesUsers;

    protected $redirectTo = '/admin/home';
    protected $loginPath = '/admin';
    public $maxOportunity = 3; //3 Intentos fallidos bloquea
    public $timeBlocked = 5;


    /* public function __construct() {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    } */

    public function showLoginForm() {
        $activedTimeout = false;
        $timeLeft = '';
        $token = request()->get('token');
        if ($token) {
            session()->put('token', $token);
        }
        if (session()->has('login.timeout')) {
            $activedTimeout = true;
            $timeout = session()->get('login.timeout');
            $timenow = new DateTime();
            $intervalo = $timeout->diff($timenow);
            $minutes = $this->timeBlocked - $intervalo->format('%i');
            $timeLeft = 'Superó el intento máximo de login, Tiempo restante: ' . $minutes . ' Minuto(s)';
            if ($minutes > 0) {
                session()->forget('login.timeout');
                $activedTimeout = false;
            }
        }
        return view('auth.admin_login', compact('activedTimeout', 'timeLeft', 'token'));
    }


    public function login(Request $request) {
        // Validate the form data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $remember = isset($request['remember']) ? true : false;
        // Log in administrador
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            //Datos validos redirigir dashboard
            $user = Auth::guard('admin')->user();
            if(!$user->published) {
                Auth::guard('admin')->logout();
                return redirect()->back()->with('data_invalid', 'El usuario esta inactivo.');
            }
            Admin::find($user->id)->update(['last_login' => date('Y-m-d H:i:s'), 'last_ip' => request()->ip()]);
            setcookie("login_admin", "ok");

            return redirect()->route('home.index');

        }
        //
        $attempts = session()->get('login.attempts', $this->maxOportunity); // obtener intentos, default: 0
        if (!session()->has('login.timeout')) {
            if ($attempts > 1) {
                session()->put('login.attempts', $attempts - 1); // incrementrar intentos
                return redirect()->back()->with('data_invalid', 'Los datos ingresados no son válidos.');
            }else{
                session()->forget('login.attempts');
                session()->put('login.timeout',  new DateTime());
                return redirect()->back();
            }
        }

        return redirect()->back()->with('data_invalid', 'Los datos ingresados no son válidos.');

    }

    public function forgot() {
        if (request()->method() == 'POST') {
            $data = request()->all();
            $admin = Admin::whereEmail($data['email'])->first();
            if (empty($admin)) {
                return redirect()->back()->with('data_invalid', 'El correo ingresado no existe');
            }

            $reset = \DB::table('password_resets')
                ->where('email', $admin->email)
                ->first();
            $token = md5(rand(1, 10) . microtime());

            if (!empty($reset)) {
                \DB::table('password_resets')
                    ->where('email', $admin->email)
                    ->update(['token' => $token, 'created_at' => date('Y-m-d H:i')]);
            } else {
                \DB::table('password_resets')
                    ->insert([
                        'email' => $admin->email,
                        'token' => $token,
                        'created_at' => date('Y-m-d H:i'),
                ]);
            }

            $dataEmail = [
                'token' => $token,
                'name' => $admin->name,
                'email' => $admin->email,
                'url' => route('forgot.admin.reset', [$token]),
            ];
            Mail::send(new ResetMail($dataEmail));

            session()->flash('flash.success', 'Se ha enviado un mensaje al correo para recuperar tu contraseña');
            return redirect()->back();
        }
        return view('auth.admin_forgot');
    }

    public function reset(Request $request, $token = null) {
        if ($request->method() == 'POST') {
            $request->validate([
                    'password' => 'required|min:6|max:70',
                    '_password_confirm' => 'required|min:6|max:70'
                ],
                [
                    'password.required' => 'Este campo es requerido',
                    '_password_confirm.required' => 'Este campo es requerido',
                    'password.min' => 'Las contraseña debe tener mínimo 6 caracteres',
                    'password.max' => 'Las contraseña debe tener máximo 70 caracteres',
                    '_password_confirm.min' => 'Las contraseña debe tener mínimo 6 caracteres',
                    '_password_confirm.max' => 'Las contraseña debe tener máximo 70 caracteres',
                ]
            );
            $data = $request->all();
            $token = $data['token'];
            if ($data['password'] != $data['_password_confirm']) {
                session()->flash('flash.error', 'Las contraseñas no coinciden');
                return redirect()->back();
            }

            $reset = \DB::table('password_resets')
                ->where('token', $token)
                ->first();
            if (empty($reset)) {
                return redirect()->route('home');
            }

            $admin = Admin::whereEmail($reset->email)->firstOrFail();
            $admin->password = $data['password'];
            $admin->save();

            \DB::table('password_resets')
                ->where('token', $token)
                ->delete();
            session()->flash('flash.success', 'Se ha restablecido la contraseña correctamente');
            return redirect()->route('admin.login');
        }
        $reset = \DB::table('password_resets')
            ->where('token', $token)
            ->first();
        if (empty($reset)) {
            return redirect()->route('home');
        }

        $now = Carbon::now();
        $totalDuration = $now->diffInHours($reset->created_at);
        if ($totalDuration > 4) {
            return redirect()->route('home');
        }
        return view('auth.admin_reset', compact('token'));
    }

    public function logout() {
        Auth::guard('admin')->logout();
        if(session()->has('flash.success')){
           session()->flash('flash.success', 'La contraseña ha sido cambiada, ingrese nuevamente.');
        }
        return redirect()->route('admin.login');
    }

    public function admin_errors(){
         return view('auth.admin_errors');
    }
}


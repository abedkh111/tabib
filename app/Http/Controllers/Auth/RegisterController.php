<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $specializations = Specialization::orderBy('name_ar')->get();
        return view('auth.register', compact('specializations'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = $this->create($request->all());

        Auth::login($user);

        return redirect($this->redirectTo)
            ->with('success', 'تم إنشاء حسابك بنجاح! مرحباً بك في منصة طبيب التعليمية');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'university' => ['nullable', 'string', 'max:255'],
            'graduation_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 10)],
            'specialization_id' => ['nullable', 'exists:specializations,id'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'graduation_year.integer' => 'سنة التخرج يجب أن تكون رقم',
            'graduation_year.min' => 'سنة التخرج غير صحيحة',
            'graduation_year.max' => 'سنة التخرج غير صحيحة',
            'specialization_id.exists' => 'التخصص المحدد غير موجود',
            'bio.max' => 'النبذة الشخصية يجب ألا تتجاوز 1000 حرف',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'university' => $data['university'] ?? null,
            'graduation_year' => $data['graduation_year'] ?? null,
            'specialization_id' => $data['specialization_id'] ?? null,
            'bio' => $data['bio'] ?? null,
            'is_active' => true,
            'subscription_type' => 'free',
        ]);

        // Assign student role by default
        $user->assignRole('student');

        return $user;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // التحقق من وجود المستخدم
        if (!$user) {
            return redirect()->route('login');
        }
        
        // السماح للمدراء والمحاضرين بالوصول
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Instructor'])) {
            return $next($request);
        }
        
        // التحقق من الاشتراك للطلاب
        if ($user->hasRole('Student')) {
            // إذا كان لديه اشتراك نشط
            if ($user->subscription_status === 'active' || $user->subscription_status === 'trial') {
                return $next($request);
            }
            
            // إذا لم يكن لديه اشتراك، توجيه لصفحة الاشتراك
            return redirect()->route('subscription.plans')
                ->with('error', 'يجب عليك الاشتراك للوصول إلى هذا المحتوى');
        }
        
        return $next($request);
    }
}

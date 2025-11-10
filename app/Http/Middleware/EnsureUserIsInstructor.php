<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsInstructor
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
        
        // التحقق من أن المستخدم محاضر أو مدير
        if (!$user->hasAnyRole(['Instructor', 'Admin', 'Super Admin'])) {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }
        
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
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
        
        // التحقق من أن المستخدم مدير
        if (!$user->hasAnyRole(['admin', 'super-admin'])) {
            abort(403, 'غير مصرح لك بالوصول إلى لوحة الإدارة');
        }
        
        return $next($request);
    }
}

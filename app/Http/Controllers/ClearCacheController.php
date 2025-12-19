<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ClearCacheController extends Controller
{
    /**
     * Clear all Laravel caches (for production use)
     */
    public function clearAll(Request $request)
    {
        // Security: Only allow if specific token is provided
        $token = $request->query('token');
        $expectedToken = config('app.cache_clear_token'); // Set this in .env

        if ($token !== $expectedToken) {
            abort(403, 'Unauthorized');
        }

        $results = [];

        // Clear application cache
        try {
            Artisan::call('cache:clear');
            $results[] = '✅ Application cache cleared';
        } catch (\Exception $e) {
            $results[] = '❌ Application cache: ' . $e->getMessage();
        }

        // Clear route cache
        try {
            Artisan::call('route:clear');
            $results[] = '✅ Route cache cleared';
        } catch (\Exception $e) {
            $results[] = '❌ Route cache: ' . $e->getMessage();
        }

        // Clear config cache
        try {
            Artisan::call('config:clear');
            $results[] = '✅ Config cache cleared';
        } catch (\Exception $e) {
            $results[] = '❌ Config cache: ' . $e->getMessage();
        }

        // Clear view cache
        try {
            Artisan::call('view:clear');
            $results[] = '✅ View cache cleared';
        } catch (\Exception $e) {
            $results[] = '❌ View cache: ' . $e->getMessage();
        }

        // Clear compiled classes
        try {
            Artisan::call('clear-compiled');
            $results[] = '✅ Compiled classes cleared';
        } catch (\Exception $e) {
            $results[] = '❌ Compiled classes: ' . $e->getMessage();
        }

        // Optimize autoloader
        try {
            Artisan::call('optimize:clear');
            $results[] = '✅ Optimization cache cleared';
        } catch (\Exception $e) {
            $results[] = '❌ Optimization: ' . $e->getMessage();
        }

        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully',
            'details' => $results
        ]);
    }
}

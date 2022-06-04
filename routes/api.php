<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [ApiController::class, 'register']);
Route::post('/login', [ApiController::class, 'login'])->middleware('api.superAdmin');
Route::post('/logout', [ApiController::class, 'logout'])->middleware('auth:api');

Route::apiResource('/products', ProductController::class)->middleware('auth:api', 'api.superAdmin');
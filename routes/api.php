<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BallotController;
use App\Http\Controllers\CasillaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Menu;
use App\Models\ObjResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/', function (Request $request) {
    return "API LARAVEL v1.0.0.0";
});

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/signup', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkLoggedIn', function (Response $response, Request $request) {
        $response->data = ObjResponse::success()->getData(true); // convertir a array;
        $id = Auth::user()->id;
        if ($id < 1 || !$id) {
            throw ValidationException::withMessages([
                'message' => false
            ]);
        }
        if ($request->url) {
            $response->data = ObjResponse::default()->getData(true); // convertir a array
            try {
                $menu = Menu::where('url', $request->url)->where('active', 1)->select("id")->first();
                $response->data = ObjResponse::success()->getData(true); // convertir a array;
                $response->data["message"] = 'Peticion satisfactoria | validar inicio de sesión.';
                $response->data["result"] = $menu;
            } catch (\Exception $ex) {
                $response->data = ObjResponse::error($ex->getMessage());
            }
            return response()->json($response, $response->data["status_code"]);
        }
        return response()->json($response, $response->data["status_code"]);
    });
    Route::get('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/changePasswordAuth', [AuthController::class, 'changePasswordAuth']);

    Route::prefix("logs")->group(function () {
        Route::post("/", [ActivityLogController::class, 'index']);
        Route::get("/dashboard", [ActivityLogController::class, 'dashboard']);
        Route::get("/export", [ActivityLogController::class, 'export']);
    });

    Route::prefix("menus")->group(function () {
        Route::get("/", [MenuController::class, 'index']);
        Route::get("/getMenusByRole/{pages_read}", [MenuController::class, 'getMenusByRole']);
        Route::get("/getHeadersMenusSelect", [MenuController::class, 'getHeadersMenusSelect']);
        Route::get("/selectIndexToRoles", [MenuController::class, 'selectIndexToRoles']);
        Route::post("/createOrUpdate", [MenuController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [MenuController::class, 'show']);
        Route::get("/disEnable/{id}/{active}", [MenuController::class, 'disEnable']);

        Route::post("/getIdByUrl", [MenuController::class, 'getIdByUrl']);
    });

    Route::prefix("roles")->group(function () {
        Route::get("/", [RoleController::class, 'index']);
        Route::get("/selectIndex", [RoleController::class, 'selectIndex']);
        Route::post("/createOrUpdate", [RoleController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [RoleController::class, 'show']);
        Route::delete("/delete/{id}", [RoleController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [RoleController::class, 'disEnable']);
        Route::delete("/deleteMultiple", [RoleController::class, 'deleteMultiple']);

        Route::post("/updatePermissions", [RoleController::class, 'updatePermissions']);
    });


    Route::prefix("casillas")->group(function () {
        Route::get("/", [CasillaController::class, 'index']);
        Route::get("/selectIndex", [CasillaController::class, 'selectIndex']);
        Route::post("/createOrUpdate", [CasillaController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [CasillaController::class, 'show']);
        Route::delete("/delete/{id}", [CasillaController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [CasillaController::class, 'disEnable']);
        Route::delete("/deleteMultiple", [CasillaController::class, 'deleteMultiple']);
    });

    Route::prefix("users")->group(function () {
        Route::get("/", [UserController::class, 'index']);
        Route::get("/selectIndexByRole/{role_id}", [UserController::class, 'selectIndexByRole']);
        Route::get("/selectIndex", [UserController::class, 'selectIndex']);
        Route::post("/createOrUpdate", [UserController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [UserController::class, 'show']);
        Route::delete("/delete/{id}", [UserController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [UserController::class, 'disEnable']);
        Route::delete("/deleteMultiple", [UserController::class, 'deleteMultiple']);
    });

    // Rutas para la gestión de proyectos
    Route::prefix("projects")->group(function () {
        Route::get("/", [ProjectController::class, 'index']);
        Route::get("/selectIndex", [ProjectController::class, 'selectIndex']);
        Route::post("/createOrUpdate", [ProjectController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [ProjectController::class, 'show']);
        Route::delete("/delete/{id}", [ProjectController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [ProjectController::class, 'disEnable']);
        Route::delete("/deleteMultiple", [ProjectController::class, 'deleteMultiple']);
    });


    // Rutas para la gestión de participaciones
    Route::prefix("participations")->group(function () {
        Route::get("/", [ParticipationController::class, 'index']);
        Route::get("/selectIndex", [ParticipationController::class, 'selectIndex']);
        Route::post("/createOrUpdate", [ParticipationController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [ParticipationController::class, 'show']);
        Route::delete("/delete/{id}", [ParticipationController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [ParticipationController::class, 'disEnable']);
        Route::delete("/deleteMultiple", [ParticipationController::class, 'deleteMultiple']);
    });

    // Rutas para la gestión de boletas
    Route::prefix("ballots")->group(function () {
        Route::get("/", [BallotController::class, 'index']);
        Route::get("/selectIndex", [BallotController::class, 'selectIndex']);
        Route::post("/createOrUpdate", [BallotController::class, 'createOrUpdate']);
        Route::get("/id/{id}", [BallotController::class, 'show']);
        Route::delete("/delete/{id}", [BallotController::class, 'delete']);
        Route::get("/disEnable/{id}/{active}", [BallotController::class, 'disEnable']);
        Route::delete("/deleteMultiple", [BallotController::class, 'deleteMultiple']);
    });



    // Dashboard
    // Route::prefix("dashboard")->group(function () {
    //     Route::get('/stats', [DashboardController::class, 'getDashboardStats']);

    //     Route::get('/export', [DashboardController::class, 'exportDashboard']);
    //     Route::get('/ported', [ProductController::class, 'getPortedProducts']);
    //     Route::get('/ported/report-by-seller', [ProductController::class, 'getPortabilityBySellerReport']);

    //     Route::post('/by-seller', [DashboardController::class, 'getSellerDashboard']);
    //     Route::post('/getReporter', [DashboardController::class, 'getReporter']);
    // });

    // ----------------- RUTAS BASICAS -----------------
});

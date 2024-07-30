<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;



/**
 * @OA\Info(
 *    title="Blockchain Mobile API",
 *    version="1.0.0",
 * )
 */

/**
 *@OA\SecurityScheme(
 *   securityScheme="Bearer",
 *   type="http",
 *   scheme="bearer"
 * ),
 **/


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

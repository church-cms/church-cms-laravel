<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Base Controller
 *
 * Root controller class that provides common functionality shared across all application controllers.
 * Includes standard Laravel traits for authorization, request validation, and job dispatching.
 *
 * @package App\Http\Controllers
 * @uses AuthorizesRequests For policy-based authorization checks
 * @uses ValidatesRequests For form request validation in controllers
 * @uses DispatchesJobs For queuing background jobs
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

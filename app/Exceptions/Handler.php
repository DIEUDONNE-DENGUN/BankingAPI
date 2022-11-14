<?php

namespace App\Exceptions;

use App\Customer\Accounts\Exceptions\AccountNotFoundException;
use App\Customer\Profile\Exceptions\InvalidUsernamePasswordException;
use App\Customer\Profile\Exceptions\UserAccountDeactivated;
use App\Customer\Profile\Exceptions\UserEmailAlreadyExistException;
use App\Customer\Transfers\Exceptions\InsufficientBalanceException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        //handle all custom exceptions for the application
        if ($exception instanceof AccountNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => "NOT FOUND",
                'path' => $request->path()
            ], 404);
        }
        if ($exception instanceof UserEmailAlreadyExistException && $request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => "EMAIL EXIST",
                'path' => $request->path()
            ], 409);
        }
        if ($exception instanceof InvalidUsernamePasswordException && $request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => "BAD REQUEST",
                'path' => $request->path()
            ], 401);
        }
        if ($exception instanceof UserAccountDeactivated && $request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => "ACCESS DENIED",
                'path' => $request->path()
            ], 403);
        }
        //InsufficientBalanceException
        if ($exception instanceof InsufficientBalanceException && $request->wantsJson()) {
            return response()->json([
                'message' => $exception->getMessage(),
                'code' => "INSUFFICIENT FUND",
                'path' => $request->path()
            ], 409);
        }
        return parent::render($request, $exception);
    }
}

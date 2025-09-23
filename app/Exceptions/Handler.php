<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Aquí capturamos TODAS las excepciones y decidimos qué vista mostrar.
     */
    public function render($request, Throwable $e)
    {
        // 1) No autenticado → redirigir al login (o 401 JSON)
        if ($e instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'));
        }

        // 2) Autenticado pero sin permiso → 403 Forbidden
        if ($e instanceof AuthorizationException) {
            return response()->view('errors.403', [], 403);
        }

        // 3) Cualquier excepción HTTP (404, 403, 500 si usas abort(500), etc.)
        if ($e instanceof HttpExceptionInterface) {
            $status = $e->getStatusCode();
            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [], $status);
            }
        }

        // 4) Cualquier otro error → 500 Internal Server Error
        if (view()->exists('errors.500')) {
            return response()->view('errors.500', [], 500);
        }

        // Finalmente cae al render por defecto (útil en debug)
        return parent::render($request, $e);
    }
}

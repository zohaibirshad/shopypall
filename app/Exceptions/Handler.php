<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Route;
use App\Models\Generalsetting;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];
    
    

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

      
        if(str_contains(url()->current(), '/admin/')){

            if($exception->getMessage() != "Unauthenticated.")
            {
                if($exception->getStatusCode() == '404')
                {
                    echo "<h1 style='padding: 300px;margin-left: 306px;'>404 | Not Found</h1>";
                    return;
                }
            }
            
        }
        return parent::render($request, $exception);
    }
    
    

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guard = array_get($exception->guards(), 0);
        $storename = Route::current()->parameter('storename');
        
        switch ($guard) {
          case 'admin':
            $login = 'admin.login';
            break;

          default:
            $login = 'user.login';
            break;
        }
        return redirect()->guest(route($login,['storename' => $storename]));
    }
}

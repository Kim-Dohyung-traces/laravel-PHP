<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        if (app()->environment('production')) {
            $statusCode = 400;
            $title = '죄송합니다. :(';
            $description = '에러가발생했습니다.';
            if (
                $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
                or $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
            ) {
                $statusCode = 404;
                $description = $exception->getMessage() ?: '요청하신페이지가없습니다.';
            }
            return response(view('errors.notice', [
                'title' => $title,
                'description' => $description,
            ]), $statusCode);
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

    //로그인 하지 않고 /articles/create URL을 열었을 경우 오류를 예외처리
    //auth 미들웨어가 리다이렉션하는 GET /login 라우트가 없기 때문에.
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest(route('sessions.create'));
    }
}
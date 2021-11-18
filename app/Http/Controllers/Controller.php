<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Retorna uma mensagem de erro em formato padronizado.
     * @param string $message
     * @param int $code
     * @param \Exception|null $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($message = '', $code = 500, \Exception $e = null)
    {
        $response = [ 'error' => true ];
        $response['message'] = [];

        if (is_string($message)) {
            $message = ['error' => [$message ?: 'Ocorreu um erro na sua solicitação.']];
        }

        $response['message'] = [ $message ];

        if ($e) {
            $error =  [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ];

            if (env('APP_ENV') == 'local') {
                $response['trace'] = $error;
            }

            Log::error($error);
        }

        return response()->json($response, $code);
    }
}

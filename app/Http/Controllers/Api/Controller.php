<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    protected function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function error($message = 'Error', $status = 400, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    protected function notFound($message = 'Data tidak ditemukan')
    {
        return $this->error($message, 404);
    }

    protected function unauthorized($message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }

    protected function validationError($errors, $message = 'Validasi gagal')
    {
        return $this->error($message, 422, $errors);
    }
}

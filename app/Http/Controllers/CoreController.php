<?php
/**
 * Created by PhpStorm.
 * User: mahmood
 * Date: 8/1/20
 * Time: 5:23 PM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoreController extends Controller
{
    public function validationErrorResponse($errors)
    {
        $data = [
            'errors' => $errors,
            'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY

        ];
        return $this->response($data);
    }

    public function response($result, $statusCode = Response::HTTP_OK)
    {
        if (isset($result['statusCode'])) {
            $statusCode = $result['statusCode'];
        }
        return response()->json($result, $statusCode);
    }

    public function validateParams(?array $data, array $rules, array $messages = [], $customAttributes = [])
    {
        if (!$data) {
            $data = [];
        }
        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        return $validator->errors()->toArray();
    }

    public function getParamsCollection(array $data)
    {
        return collect($data);
    }
}

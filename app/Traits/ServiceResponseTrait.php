<?php
/**
 * Created by PhpStorm.
 * User: mahmood
 * Date: 8/3/20
 * Time: 2:22 PM
 */

namespace App\Traits;


trait ServiceResponseTrait
{
    public function error($statusCode,$message)
    {

        return [
            "data" =>  [
                'status' => false,
                'message' =>$message,
                'data' => []
            ],
            "statusCode" => $statusCode
        ];
    }


    public function success($data)
    {
        return [
            "data" => $data,
            "statusCode" => 200
            ];
        
    }
}

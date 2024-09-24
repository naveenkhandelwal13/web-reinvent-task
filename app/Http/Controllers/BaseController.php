<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public const DEFAULT_PER_PAGE = 10;

    public function __construct(Request $request)
    {
        App::setLocale($request->header('Accept-Language'));
    }


    

    public function sendSuccess($result = null, $message = 'Success', $code = Response::HTTP_OK)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'code' => $code,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
    

    public function sendDuplicate($result = null, $message = 'Duplicate', $code = Response::HTTP_CONFLICT)
    {
        $response = [
            'success' => false,
            'data' => $result,
            'code' => $code,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }


    
    public function sendCreated($result = null, $message = 'Created', $code = Response::HTTP_CREATED)
    {
        $response = [
            'success' => true,
            'code' => $code,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    public function sendServerError($error, $errorMessages = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->sendError($error, $errorMessages, $code);
    }

    public function sendError($error, $errorMessages = [], $code = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            'success' => false,
            'code' => $code,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }



}

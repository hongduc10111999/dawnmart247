<?php

namespace App\Http\Concerns;

use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Trait HasApiResponse
 *
 * @package App\Http\Concerns
 */
trait HasApiResponse
{
    /**
     * Extra response fields
     *
     * @var array
     */
    protected static $extraResponse = [
        '_block' => null,
    ];

    /**
     * Add message to _block response
     *
     * @param mixed $message Message
     * @param bool  $fresh   Refresh?
     *
     * @return void
     */
    public static function addBlockResponseMessage($message, $fresh = false)
    {
        if ($fresh || self::$extraResponse['_block'] == null) {
            self::$extraResponse['_block'] = [];
        }

        self::$extraResponse['_block'][] = $message;
    }

    /**
     * Add Block Response with $key
     *
     * @param mixed $key   Key
     * @param mixed $value Value
     * @param bool  $fresh Refresh?
     *
     * @return void
     */
    public static function addBlockResponse($key, $value, $fresh = false)
    {
        if ($fresh || empty(self::$extraResponse[$key])) {
            self::$extraResponse[$key] = [];
        }

        self::$extraResponse[$key] = $value;
    }

    /**
     * Response
     *
     * @param bool   $failed       Failed?
     * @param mixed  $data         Data
     * @param string $message      Message
     * @param mixed  $customStatus Custom Http Status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($failed, $data = null, $message = '', $customStatus = null)
    {
        $status = !is_null($customStatus) ? $customStatus : ($failed ? Response::HTTP_BAD_REQUEST : Response::HTTP_OK);

        return response()->json([
            '_status'   => $status,
            '_success'  => !$failed,
            '_messages' => empty($message) ? null : (array) $message,
            '_data'     => $data,
            '_extra'    => self::$extraResponse,
        ], $status);
    }

    /**
     * Response Success
     *
     * @param mixed  $data         Data
     * @param string $message      Message
     * @param mixed  $customStatus Custom Http Status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccess($data = null, $message = '', $customStatus = null)
    {
        return $this->response(false, $data, $message, $customStatus);
    }

    /**
     * Response Error
     *
     * @param string $message      Message
     * @param mixed  $data         Data
     * @param mixed  $customStatus Custom Status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseError($message = '', $data = null, $customStatus = null)
    {
        if ($message instanceof ModelNotFoundException) {
            return $this->response(true, null, __('error.404_not_found'), Response::HTTP_NOT_FOUND);
        } elseif ($message instanceof CustomException) {
            $exception = $message;
            $message   = $exception->getMessage();
            $data      = array_merge((array) $data, [
                'attached'  => $exception->getAttachedData(),
                'exception' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);

            Log::error($exception);

            // show detail error in json format
            if (config('app.env') == 'production' && config('app.debug') == false) {
                return $this->response(true, [], __('error.level_3_failed'), $customStatus);
            } else {
                return $this->response(true, $data, $message, $customStatus);
            }
        } elseif ($message instanceof \Exception) {
            $exception = $message;
            $message   = $exception->getMessage();
            $data      = array_merge((array)$data, [
                'exception' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);

            Log::error($exception);

            // show detail error in json format
            if (config('app.env') == 'production' && config('app.debug') == false) {
                return $this->response(true, [], __('error.level_3_failed'), $customStatus);
            } else {
                return $this->response(true, $data, $message, $customStatus);
            }
        }

        return $this->response(true, $data, $message, $customStatus);
    }
}

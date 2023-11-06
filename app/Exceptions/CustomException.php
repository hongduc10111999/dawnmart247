<?php

namespace App\Exceptions;

/**
 * Class CustomException
 *
 * @package App\Exceptions
 */
class CustomException extends \Exception
{
    const USER_LEVEL      = 0;
    const DATABASE_LEVEL  = 1;
    const APP_LEVEL       = 2;
    const UNHANDLED_LEVEL = 3;
    const NONE_LEVEL      = 4;

    /**
     * Attached Data
     *
     * @var mixed|null
     */
    protected $attachedData;

    /**
     * CustomException constructor.
     *
     * @param string          $message      Message
     * @param int             $level        Level
     * @param mixed           $attachedData Attach Data
     * @param int             $code         Code
     * @param \Exception|null $previous     Previous Exception
     */
    public function __construct($message = '', $level = 2, $attachedData = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(self::formatMessage($message, $level), $code, $previous);

        $this->attachedData = $attachedData;

        if ($previous) {
            $this->line = $previous->getLine();
            $this->file = $previous->getFile();

            if (empty($message)) {
                $this->message = self::formatMessage($previous->getMessage(), $level);
            }
        }
    }

    /**
     * Get Attached Data
     *
     * @return mixed
     */
    public function getAttachedData()
    {
        return $this->attachedData;
    }

    /**
     * Format Message
     *
     * @param string $message Message
     * @param int    $level   Level
     *
     * @return array|null|string
     */
    public static function formatMessage($message = '', $level = 2)
    {
        return empty($message)
            ? __('error.level_' . $level . '_failed')
            : __('error.level_' . $level, ['message' => $message]);
    }
}

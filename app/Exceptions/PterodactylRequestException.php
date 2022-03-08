<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class PterodactylRequestException extends Exception
{
    public ?string $body = null;

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, ?string $body = null)
    {
        parent::__construct($message, $code, $previous);
        $this->body = $body;
    }

    /**
     * Return the error message given by Pterodactyl
     *
     * @return string|null
     */
    public function getPterodactylErrorMessage(): mixed
    {
        if (empty($this->body)) return $this->message;

        //check if response body is json
        if ($this->isJson($this->body)) {

            $errors = json_decode($this->body, true);

            //check if error message is set
            if (isset($errors['errors']) && isset($errors['errors'][0]) && isset($errors['errors'][0]['detail'])) {
                return $errors['errors'][0]['detail'];
            }
        }

        return $this->body;
    }

    protected function isJson(string $string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

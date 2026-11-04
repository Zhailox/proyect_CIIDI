<?php
// core/Database/Exceptions/DatabaseConnectionException.php

class DatabaseConnectionException extends Exception {
    private bool $isAjax;
    private ?string $debugDetails;

    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, bool $isAjax = false, ?string $debugDetails = null) {
        parent::__construct($message, $code, $previous);
        $this->isAjax = $isAjax;
        $this->debugDetails = $debugDetails;
    }

    public function isAjax(): bool {
        return $this->isAjax;
    }

    public function getDebugDetails(): ?string {
        return $this->debugDetails;
    }
}

<?php

namespace Application\Handlers;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

/**
 * Class Session
 * @package Shop\Application\Handlers
 */
class Session extends PdoSessionHandler
{
    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword)
    {
        parent::__construct('mysql:dbname=' . $dbName . ';host=' . $dbHost, ['db_username' => $dbUser, 'db_password' => $dbPassword]);
    }
}

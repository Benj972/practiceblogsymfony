<?php

namespace SnowTricks\HomeBundle\Event;

use Symfony\Component\EventDispatcher\Event;

final class HomeEvents extends Event
{
    const NEW_ACCOUNT_CREATED = 'snow_tricks_homebundle.new_account_created';
    const NEW_PASSWORD_REQUESTED = 'snow_tricks_homebundle.new_password_requested';
} 
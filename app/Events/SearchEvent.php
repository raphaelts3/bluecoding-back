<?php

namespace App\Events;

use App\User;

class SearchEvent extends Event
{

    /**
     * The authenticated user.
     *
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $query;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $query
     */
    public function __construct(User $user, string $query)
    {
        $this->user = $user;
        $this->query = $query;
    }
}

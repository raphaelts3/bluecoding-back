<?php

namespace App\Listeners;

use App\Events\SearchEvent;
use App\History;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SearchListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SearchEvent $event
     * @return void
     */
    public function handle(SearchEvent $event)
    {
        $this->saveHistory($event->user, $event->query);
    }

    /**
     * @param User $user
     * @param string $query
     */
    private function saveHistory(User $user, string $query)
    {
        History::create(['user_id' => $user->id, 'query' => $query]);
    }
}

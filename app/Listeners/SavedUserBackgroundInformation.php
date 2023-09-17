<?php

namespace App\Listeners;

use App\Models\Detail;
use App\Events\UserSaved;
use App\Services\UserService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SavedUserBackgroundInformation
{
    protected $model;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserService $model)
    {
        $this->model =  $model;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserSaved $event)
    {
        $user = $this->model->find($event->user->id);
        // dd($user->toArray());
       
        $detail = new Detail();

        $detail->key = "Full Name";

        $detail->value = $user->fullname;

        $detail->user_id = $event->user->id;

        $detail->type = 'Bio';

        $detail->icon = 'Bio';

        $detail->save();


           
        $detail = new Detail();

        $detail->key = "Middle Initial";

        $detail->value = $user->middleinitial;

        $detail->user_id = $event->user->id;

        $detail->type = 'Bio';

        $detail->icon = 'Bio';

        $detail->save();


        $detail = new Detail();

        $detail->key = "Avatar";

        $detail->value = $user->avatar;

        $detail->user_id = $event->user->id;

        $detail->type = 'Bio';

        $detail->icon = 'Bio';

        $detail->save();


        $detail = new Detail();

        $detail->key = "Gender";

        $detail->value = $user->prefixname!='Mr'? "Female" : "Male";

        $detail->user_id = $event->user->id;

        $detail->type = 'Bio';

        $detail->icon = 'Bio';

        $detail->save();
    }
}

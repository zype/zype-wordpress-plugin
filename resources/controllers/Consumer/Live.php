<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;

class Live extends Base
{
    private $audio_only = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function show()
    {
        $zype_livestream_auth_required = $this->options['livestream_authentication_required'];

        $vm = new \ZypeMedia\Models\Video;
        $by = ['on_air' => 'true'];
        $vm->all_by($by, ['per_page' => 1]);

        if (isset($vm->collection[0])) {
            $livestream = $vm->collection[0];
            $title = $livestream->title;
        } else {
            $livestream = false;
            $title = 'Off Air';
        }

        return view('livestream', [

        ]);
    }
}

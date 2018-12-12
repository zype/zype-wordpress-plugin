<?php

namespace ZypeMedia\Models\V2;

class PassPlan extends Base
{
    public function __construct($object)
    {
        parent::__construct();
        $this->set_attributes($object);
    }

    public static function find($id)
    {
        $single = \Zype::get_pass_plan($id);
        return $single ? self::load_model($single) : false;
    }
}

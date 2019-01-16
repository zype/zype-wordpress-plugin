<?php

namespace Zype\Api;

class Video extends \Api
{
    use \ApiOperations\All;
    use \ApiOperations\Create;
    use \ApiOperations\Retrieve;
    use \ApiOperations\Update;

    const RESOURCE_PATH = 'videos';
}

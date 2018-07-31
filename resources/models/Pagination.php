<?php

namespace ZypeMedia\Models;

class Pagination extends Base
{
    public function __construct($pagination)
    {
        parent::__construct();
        if ($pagination) {
            $this->current = $pagination->current;
            $this->previous = $pagination->previous;
            $this->next = $pagination->next;
            $this->last = $pagination->pages;
            $this->build();
        }
    }

    public function build()
    {
        $links = [
            $this->current,
            $this->previous,
            $this->last,
            $this->last - 1,
            1,
            2,
            $this->current - 1,
            $this->current - 2,
            $this->current + 1,
            $this->current + 2
        ];

        $links = array_unique($links);
        $links = array_filter($links, [$this, 'too_big']);
        $links = array_filter($links, [$this, 'too_small']);
        sort($links);
        $final = [];

        for ($i = 0; $i < sizeof($links); $i++) {
            array_push($final, ['title' => $links[$i], 'url' => $links[$i]]);
            if (isset($links[$i + 1]) && $links[$i] != $links[$i + 1] - 1) {
                array_push($final, ['title' => '...', 'url' => $this->midpoint($links[$i], $links[$i + 1])]);
            }
        }

        $this->links = $final;
    }

    private function midpoint($min, $max)
    {
        return floor((($max - $min) / 2) + $min);
    }

    private function too_big($var)
    {
        return ($var <= $this->last);
    }

    private function too_small($var)
    {
        return ($var > 0);
    }
}

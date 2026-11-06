<?php

namespace App\Channels\Messages;

class WhatsappMessage
{
    public $content;

    public function content($content)
    {
        $this->content = $content;

        return $this;
    }
}

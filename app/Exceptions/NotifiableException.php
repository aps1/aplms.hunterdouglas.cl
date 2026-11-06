<?php

namespace App\Exceptions;

use App\Http\Controllers\Traits\Notifications;
use Exception;

class NotifiableException extends Exception
{
    use Notifications;

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report() {}

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $this->notifyError($this->getMessage());

        return redirect()->back();
    }
}

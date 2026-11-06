<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Session;

trait Notifications
{
    /**
     * Notify success
     *
     * @param  string  $message
     * @param  string  $title
     * @return void
     */
    public function notifySuccess($message = 'Todo bien', $title = null)
    {
        $this->notify('success', $message, $title);
    }

    /**
     * Notiofy error
     *
     * @param  string  $message
     * @param  string  $title
     * @return void
     */
    public function notifyError($message = 'Hubo un error', $title = null)
    {
        $this->notify('error', $message, $title);
    }

    /**
     * Notify
     *
     * @param [type] $type
     * @param [type] $message
     * @param [type] $title
     * @return void
     */
    public function notify($type, $message, $title = null)
    {
        Session::flash('notification', [
            'type' => $type,
            'message' => $message,
            'title' => $title,
        ]);
    }
}

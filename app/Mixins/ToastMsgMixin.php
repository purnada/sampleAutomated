<?php

namespace App\Mixins;

class ToastMsgMixin
{
    /**
     * Get the message and its type.
     *
     * @param  string  $msg
     * @param  string  $type
     * @return array
     */
    public function toastMsg()
    {
        return function ($msg, $type) {
            $notification = [
                'msg' => $msg,
                'type' => $type,
            ];

            return $notification;

        };
    }
}

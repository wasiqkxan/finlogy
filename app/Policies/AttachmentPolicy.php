<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;

class AttachmentPolicy
{
    public function view(User $user, Attachment $attachment)
    {
        return $user->id === $attachment->attachable->account->user_id;
    }

    public function delete(User $user, Attachment $attachment)
    {
        return $user->id === $attachment->attachable->account->user_id;
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Podcast;
use Illuminate\Auth\Access\HandlesAuthorization;

class PodcastPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Podcast');
    }

    public function view(AuthUser $authUser, Podcast $podcast): bool
    {
        return $authUser->can('View:Podcast');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Podcast');
    }

    public function update(AuthUser $authUser, Podcast $podcast): bool
    {
        return $authUser->can('Update:Podcast');
    }

    public function delete(AuthUser $authUser, Podcast $podcast): bool
    {
        return $authUser->can('Delete:Podcast');
    }

    public function restore(AuthUser $authUser, Podcast $podcast): bool
    {
        return $authUser->can('Restore:Podcast');
    }

    public function forceDelete(AuthUser $authUser, Podcast $podcast): bool
    {
        return $authUser->can('ForceDelete:Podcast');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Podcast');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Podcast');
    }

    public function replicate(AuthUser $authUser, Podcast $podcast): bool
    {
        return $authUser->can('Replicate:Podcast');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Podcast');
    }

}
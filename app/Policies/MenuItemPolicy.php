<?php

namespace App\Policies;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isRestaurant();
    }

    public function view(User $user, MenuItem $menuItem)
    {
        return $user->id === $menuItem->restaurant_id;
    }

    public function create(User $user)
    {
        return $user->isRestaurant();
    }

    public function update(User $user, MenuItem $menuItem)
    {
        return $user->id === $menuItem->restaurant_id;
    }

    public function delete(User $user, MenuItem $menuItem)
    {
        return $user->id === $menuItem->restaurant_id;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization;
    use GeneralTrait;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->author('Read Product',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $this->author('Read Product',$user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $this->author('Create Product',$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $this->author('Update Product',$user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $this->author('Delete Product',$user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @return bool
     */
    public function restore(User $user)
    {
        return $this->author('Restore Product',$user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function forceDelete(User $user)
    {
        return $this->author('Delete Product',$user);
    }
}

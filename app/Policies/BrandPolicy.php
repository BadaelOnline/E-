<?php

namespace App\Policies;

use App\Models\Brands\Brand;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BrandPolicy
{
    use HandlesAuthorization;
    use GeneralTrait;

    private $Brand;
    private $user;
    public function __construct(Brand $Brand,User $user)
    {
        $this->Brand=$Brand;
        $this->user=$user;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->author('Read Brand',$user);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $this->author('Read Brand',$user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $this->author('Create Brand',$user);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user)
    {
        return $this->author('Update Brand',$user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return $this->author('Delete Brand',$user);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @return bool
     */
    public function restore(User $user)
    {
        return $this->author('Restore Brand',$user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @return bool
     */
    public function forceDelete(User $user)
    {
        return $this->author('Delete Brand',$user);
    }
}

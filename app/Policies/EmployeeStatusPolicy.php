<?php

namespace App\Policies;

use App\Models\EmployeeStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeStatusPolicy
{
    use HandlesAuthorization;

    public const EMPLOYEE_STATUS_MANAGE = 'employee_status.*';
    public const EMPLOYEE_STATUS_LIST = 'employee_status.list';
    public const EMPLOYEE_STATUS_CREATE = 'employee_status.create';
    public const EMPLOYEE_STATUS_UPDATE = 'employee_status.update';
    public const EMPLOYEE_STATUS_DELETE = 'employee_status.delete';

    public function manage(User $user)
    {
        if ($user->can(self::EMPLOYEE_STATUS_MANAGE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->can(self::EMPLOYEE_STATUS_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, EmployeeStatus $employeeStatus)
    {
        if ($user->can(self::EMPLOYEE_STATUS_LIST)) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($user->can(self::EMPLOYEE_STATUS_CREATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, EmployeeStatus $employeeStatus)
    {
        if ($user->can(self::EMPLOYEE_STATUS_UPDATE)) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EmployeeStatus  $employeeStatus
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, EmployeeStatus $employeeStatus)
    {
        if ($user->can(self::EMPLOYEE_STATUS_DELETE)) {
            return true;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers() {
        $users = User::query()->join('roles', function ($join) {
            $join->on('users.role_id', '=', 'roles.id');
        })->get(['users.name', 'email', 'NID', 'roles.name AS role', 'start_date']);

        return json_encode($users);
    }

    public function getUsersByRole($role) {
        $role = ucwords($role);

        $users = User::query()->join('roles', function ($join) use ($role) {
            $join->on('users.role_id', '=', 'roles.id')
                ->where('roles.name', '=', $role);
        })->get(['users.name', 'email', 'NID', 'roles.name AS role', 'start_date']);

        return json_encode($users);
    }

    public function getUserByNID($nid) {
        $user = User::query()->join('roles', function ($join) {
            $join->on('users.role_id', '=', 'roles.id');
        })
            ->where('NID', '=', strtolower($nid))
            ->first(['users.name', 'email', 'NID', 'roles.name AS role']);

        return json_encode($user);
    }

    public function groupUsersByRoles() {
        $groups = User::query()->join('roles', function ($join) {
            $join->on('users.role_id', '=', 'roles.id');
        })
            ->get(['users.name', 'NID', 'roles.name AS role'])
            ->groupBy('role');

        return json_encode($groups);
    }
}

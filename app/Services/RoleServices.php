<?php

namespace App\Services;

use App\Exceptions\FieldInUseException;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleServices
{
    public function getRoleList(bool $isAll = false, $search = null)
    {
        if ($isAll) {
            $roles = Role::get()->map(function ($role) {
                return [
                    "id" => $role->id,
                    "name" => $role->name,
                    "default" => $role->default,
                ];
            });
        } else {
            $query = Role::query();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%{$search}%")
                      ->orWhere("description", "like", "%{$search}%");
                });
            }

            $roles = new RoleCollection($query->paginate(10));
        }

        return $roles;
    }

    public function getRoleById($id)
    {
        $role = Role::find($id);
        return new RoleResource($role);
    }

    public function createRole($data)
    {
        $role = Role::create([
            "name" => $data["name"],
            "description" => $data["description"],
            "default" => $data["default"],
        ]);

        return new RoleResource($role);
    }

    public function updateRole($data, $id)
    {
        $role = Role::find($id);

        if (!$role) throw new NotFoundHttpException(__('errorMessages.not_found'));

        $role->name = $data["name"];
        $role->description = $data["description"];
        $role->default = $data["default"];
        $role->save();

        return new RoleResource($role);
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if (!$role) throw new NotFoundHttpException(__('errorMessages.not_found'));

        if ($role->profiles()->exists()) throw new FieldInUseException(__('errorMessages.field_in_use'));

        $role->delete();
        return "";
    }
}

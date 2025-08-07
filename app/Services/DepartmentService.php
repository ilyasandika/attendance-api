<?php

namespace App\Services;

use App\Exceptions\FieldInUseException;
use App\Helpers\Helper;
use App\Http\Resources\DepartmentCollection;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentService
{
    public function getDepartmentList(bool $isAll = false, $search = null)
    {
        if ($isAll) {
            $departments = Department::get()->map(function ($department) {
                return [
                    "id" => $department->id,
                    "name" => $department->name,
                    "default" => $department->default,
                ];
            });
        } else {
            $query = Department::query();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%{$search}%")
                      ->orWhere("description", "like", "%{$search}%");
                });
            }

        $departments = new DepartmentCollection($query->paginate(10));
    }

    return $departments;
}

    public function getDepartmentById($id) {
        $department = Department::find($id);
        return new departmentResource($department);
    }

    public function createDepartment($data) {

        $department = Department::create([
            "name" => $data["name"],
            "description" => $data["description"],
            "default" => $data["default"],
        ]);

        return new DepartmentResource($department);
    }

    public function updateDepartment($data, $id) {
        $department = Department::find($id);

        if (!$department)  throw new NotFoundHttpException(__('errorMessages.not_found'));

        $department->name = $data["name"];
        $department->description = $data["description"];
        $department->default = $data["default"];
        $department->save();

        return new DepartmentResource($department);
    }

    public function deleteDepartment($id) {
        $department = Department::find($id);
        if (!$department) throw new NotFoundHttpException(__('errorMessages.not_found'));

        if ($department->profiles()->exists()) throw new FieldInUseException(__('errorMessages.field_in_use'));

        $department->delete();
        return "";
    }
}

<?php

namespace App\Http\Repositories;

use App\Models\Classroom;

class ClassroomRepository extends BaseRepository
{
    public function __construct(private Classroom $model)
    {
        parent::__construct($model);
    }

    public function getWithFilters(array $data)
    {
        $builder = $this->model->query()->with('course')->where(function ($query) use ($data) {
            if (data_get($data, 'name')) {
                $query->where('name', 'like', '%' . $data['name'] . '%');
            }
        });

        return $this->index($builder);
    }

    public function manageUser(string $classroomId, int $userId, string $action)
    {
        $classroom = $this->show($classroomId);

        if ($action === 'attach') {
            $classroom->users()->syncWithoutDetaching([$userId]);
        } else {
            $classroom->users()->detach($userId);
        }

        return $classroom->load('users');
    }
}
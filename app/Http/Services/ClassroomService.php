<?php

namespace App\Http\Services;

use App\Http\Repositories\ClassroomRepository;

class ClassroomService extends BaseService
{
    public function __construct(private ClassroomRepository $classroomRepository)
    {
        parent::__construct($classroomRepository);
    }

    public function getWithFilters(array $data)
    {
        return $this->classroomRepository->getWithFilters($data);
    }
    
    public function manageUser(string $classroomId, int $userId, string $action)
    {
        return $this->classroomRepository->manageUser($classroomId, $userId, $action);
    }
}

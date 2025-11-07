<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'folder_id',
        'name',
        'file_path',
        'file_type',
        'size',
        'created_at',
        'created_by',
        'deleted_at',
    ];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function listInFolder(int $userId, ?int $folderId): array
    {
        return $this->where('user_id', $userId)
            ->where('folder_id', $folderId)
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function listInTrash(int $userId, ?int $folderId): array
    {
        return $this->where('user_id', $userId)
            // ->where('folder_id', $folderId)
            ->where('deleted_at IS NOT NULL')
            ->orderBy('deleted_at', 'DESC')
            ->findAll();
    }

    public function search(int $userId, string $query, string $type = 'file')
    {
        return $this->where('user_id', $userId)
            ->like('name', $query)
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}
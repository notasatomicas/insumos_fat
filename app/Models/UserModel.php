<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_usuario';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'email', 'username', 'password_hash', 'nombre', 'apellido', 
        'dni', 'direccion', 'type', 'active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation - Solo las reglas básicas, sin password_hash required
    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[users.email,id_usuario,{id}]',
        'username'      => 'required|alpha_numeric|min_length[3]|is_unique[users.username,id_usuario,{id}]',
        'nombre'        => 'required|min_length[2]',
        'apellido'      => 'required|min_length[2]',
        'dni'           => 'required',
        'direccion'     => 'required'
    ];
    
    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Encuentra usuario por email o username
     */
    public function findByEmailOrUsername(string $identity)
    {
        return $this->where('email', $identity)
                    ->orWhere('username', $identity)
                    ->first();
    }

    /**
     * Registra un nuevo usuario
     */
    public function registerUser(array $data)
    {
        // Encripta la contraseña antes de guardar
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        
        // Por defecto, los usuarios son compradores (type=0)
        if (!isset($data['type'])) {
            $data['type'] = 0;
        }
        
        // Insertar sin validaciones automáticas del modelo (ya se validó en el controlador)
        return $this->skipValidation(true)->insert($data);
    }

    /**
     * Actualiza un usuario existente
     */
    public function updateUser(int $userId, array $data)
    {
        // Crear reglas de validación dinámicas para actualización
        $updateRules = [
            'email'         => "required|valid_email|is_unique[users.email,id_usuario,{$userId}]",
            'username'      => "required|alpha_numeric|min_length[3]|is_unique[users.username,id_usuario,{$userId}]",
            'nombre'        => 'required|min_length[2]',
            'apellido'      => 'required|min_length[2]',
            'dni'           => 'required',
            'direccion'     => 'required'
        ];

        // Si hay contraseña, agregarla a las validaciones
        if (isset($data['password']) && !empty($data['password'])) {
            $updateRules['password'] = 'required|min_length[6]';
            if (isset($data['password_confirm'])) {
                $updateRules['password_confirm'] = 'required|matches[password]';
            }
        }

        // Preparar datos para validación (solo los campos que se van a actualizar)
        $dataToValidate = [];
        foreach ($updateRules as $field => $rule) {
            if (isset($data[$field])) {
                $dataToValidate[$field] = $data[$field];
            }
        }

        // Validar solo los campos presentes
        $validation = \Config\Services::validation();
        $validation->setRules($updateRules);
        
        if (!$validation->run($dataToValidate)) {
            $this->errors = $validation->getErrors();
            return false;
        }

        // Procesar la contraseña si existe
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        
        // Remover password_confirm si existe
        if (isset($data['password_confirm'])) {
            unset($data['password_confirm']);
        }

        // Actualizar sin validaciones automáticas del modelo
        return $this->skipValidation(true)->update($userId, $data);
    }
}
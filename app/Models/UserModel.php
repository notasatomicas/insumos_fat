<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
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

    // Validation
    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username'      => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required',
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
        
        return $this->insert($data);
    }
}
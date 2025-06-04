<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactoModel extends Model
{
    protected $table = 'contactos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'nombre',
        'apellido', 
        'correo',
        'telefono',
        'mensaje',
        'tipo_contacto',
        'estado',
        'ip_address',
        'user_agent'
    ];

    // Fechas
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validación
    protected $validationRules = [
        'nombre' => 'required|min_length[2]|max_length[100]',
        'apellido' => 'required|min_length[2]|max_length[100]',
        'correo' => 'required|valid_email|max_length[150]',
        'telefono' => 'permit_empty|max_length[20]',
        'mensaje' => 'permit_empty|max_length[2000]',
        'tipo_contacto' => 'required|in_list[mensaje,llamada]',
        'estado' => 'permit_empty|in_list[nuevo,leido,respondido,cerrado]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'apellido' => [
            'required' => 'El apellido es obligatorio',
            'min_length' => 'El apellido debe tener al menos 2 caracteres',
            'max_length' => 'El apellido no puede exceder 100 caracteres'
        ],
        'correo' => [
            'required' => 'El correo es obligatorio',
            'valid_email' => 'Debe proporcionar un correo válido',
            'max_length' => 'El correo no puede exceder 150 caracteres'
        ],
        'telefono' => [
            'max_length' => 'El teléfono no puede exceder 20 caracteres'
        ],
        'mensaje' => [
            'max_length' => 'El mensaje no puede exceder 2000 caracteres'
        ],
        'tipo_contacto' => [
            'required' => 'El tipo de contacto es obligatorio',
            'in_list' => 'Tipo de contacto no válido'
        ]
    ];

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

    /**
     * Obtener contactos por estado
     */
    public function getByEstado(string $estado)
    {
        return $this->where('estado', $estado)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Obtener contactos por tipo
     */
    public function getByTipo(string $tipo)
    {
        return $this->where('tipo_contacto', $tipo)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Obtener estadísticas de contactos
     */
    public function getEstadisticas()
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                estado,
                tipo_contacto,
                COUNT(*) as total,
                DATE(created_at) as fecha
            FROM contactos 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY estado, tipo_contacto, DATE(created_at)
            ORDER BY fecha DESC
        ");

        return $query->getResultArray();
    }

    /**
     * Marcar contacto como leído
     */
    public function marcarComoLeido(int $id)
    {
        return $this->update($id, ['estado' => 'leido']);
    }

    /**
     * Marcar contacto como respondido
     */
    public function marcarComoRespondido(int $id)
    {
        return $this->update($id, ['estado' => 'respondido']);
    }

    /**
     * Buscar contactos por email
     */
    public function buscarPorEmail(string $email)
    {
        return $this->where('correo', $email)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Obtener contactos recientes
     */
    public function getRecientes(int $limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                   ->limit($limit)
                   ->findAll();
    }
}
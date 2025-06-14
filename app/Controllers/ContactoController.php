<?php

namespace App\Controllers;

use App\Models\ContactoModel;
use CodeIgniter\HTTP\ResponseInterface;

class ContactoController extends BaseController
{
    protected $contactoModel;

    public function __construct()
    {
        $this->contactoModel = new ContactoModel();
    }

    /**
     * Mostrar la página de contacto
     */
    public function index()
    {
        $data = [
            'title' => 'Contacto - INSUMOS FAT S.A.',
            'description' => 'Comunícate con nosotros. Estamos aquí para ayudarte.',
            'keywords' => 'contacto, insumos fat, comunicación, atención al cliente'
        ];

        return view('contacto', $data);
    }

    /**
     * Procesar formulario de contacto
     */
    public function enviar()
    {
        // Verificar que sea una petición POST
        if (!$this->request->isAJAX() && $this->request->getMethod() !== 'POST') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Método no permitido'
            ])->setStatusCode(405);
        }

        // Validar datos de entrada
        $rules = [
            'nombre' => 'required|min_length[2]|max_length[100]',
            'apellido' => 'required|min_length[2]|max_length[100]',
            'correo' => 'required|valid_email|max_length[150]',
            'telefono' => 'required|max_length[20]',
            'mensaje' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $this->validator->getErrors()
            ])->setStatusCode(400);
        }

        // Preparar datos para insertar
        $data = [
            'nombre' => trim($this->request->getPost('nombre')),
            'apellido' => trim($this->request->getPost('apellido')),
            'correo' => trim($this->request->getPost('correo')),
            'telefono' => trim($this->request->getPost('telefono')),
            'mensaje' => trim($this->request->getPost('mensaje')),
            'estado' => 'nuevo'
        ];

        try {
            // Insertar en la base de datos
            $contactoId = $this->contactoModel->insert($data);

            if ($contactoId) {
                // Enviar email de notificación (opcional)
                $this->enviarNotificacionEmail($data, $contactoId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tu mensaje ha sido enviado correctamente. Te responderemos a la brevedad.',
                    'contacto_id' => $contactoId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al enviar el mensaje. Por favor, inténtalo nuevamente.'
                ])->setStatusCode(500);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al enviar mensaje de contacto: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor. Por favor, inténtalo más tarde.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Listar contactos para administración
     */
    public function listar()
    {
        // Verificar autenticación de administrador aquí
        // if (!$this->session->get('admin_logged_in')) {
        //     return redirect()->to('/admin/login');
        // }

        $page = $this->request->getGet('page') ?? 1;
        $perPage = 25;
        $estado = $this->request->getGet('estado');

        // Obtener estadísticas
        $stats = $this->obtenerEstadisticas();

        // Construir consulta con filtros
        $query = $this->contactoModel;
        
        if ($estado) {
            $query = $query->where('estado', $estado);
        }

        $contactos = $query->orderBy('created_at', 'DESC')->paginate($perPage);

        $data = [
            'contactos' => $contactos,
            'pager' => $this->contactoModel->pager,
            'estado_filtro' => $estado,
            'stats' => $stats,
            'title' => 'Gestión de Mensajes de Contacto - Admin'
        ];

        return view('admin/contactos/index', $data);
    }

    /**
     * Cambiar estado de un contacto via AJAX
     */
    public function cambiarEstado($id)
    {
        // Verificar que sea una petición POST
        if ($this->request->getMethod() !== 'POST') {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Método no permitido'
                ])->setStatusCode(405);
            } else {
                // Para requests no AJAX, redirect con error
                session()->setFlashdata('error', 'Método no permitido');
                return redirect()->to('/admin/contactos');
            }
        }

        $nuevoEstado = $this->request->getPost('estado');
        
        $estadosValidos = ['nuevo', 'leido', 'respondido', 'cerrado'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Estado no válido'
                ])->setStatusCode(400);
            } else {
                session()->setFlashdata('error', 'Estado no válido');
                return redirect()->to('/admin/contactos');
            }
        }

        try {
            // Verificar que el contacto existe
            $contacto = $this->contactoModel->find($id);
            if (!$contacto) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Contacto no encontrado'
                    ])->setStatusCode(404);
                } else {
                    session()->setFlashdata('error', 'Contacto no encontrado');
                    return redirect()->to('/admin/contactos');
                }
            }

            // Actualizar el estado
            if ($this->contactoModel->update($id, ['estado' => $nuevoEstado])) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Estado actualizado correctamente',
                        'nuevo_estado' => $nuevoEstado
                    ]);
                } else {
                    session()->setFlashdata('success', 'Estado actualizado correctamente');
                    return redirect()->to('/admin/contactos');
                }
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Error al actualizar el estado'
                    ])->setStatusCode(500);
                } else {
                    session()->setFlashdata('error', 'Error al actualizar el estado');
                    return redirect()->to('/admin/contactos');
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado del contacto: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error interno del servidor'
                ])->setStatusCode(500);
            } else {
                session()->setFlashdata('error', 'Error interno del servidor');
                return redirect()->to('/admin/contactos');
            }
        }
    }

    /**
     * Eliminar un contacto
     */
    public function eliminar($id)
    {
        // Verificar autenticación de administrador
        // if (!$this->session->get('admin_logged_in')) {
        //     return redirect()->to('/admin/login');
        // }

        $contacto = $this->contactoModel->find($id);

        if (!$contacto) {
            session()->setFlashdata('error', 'Contacto no encontrado');
            return redirect()->to('/admin/contactos');
        }

        try {
            if ($this->contactoModel->delete($id)) {
                session()->setFlashdata('success', 'Contacto eliminado correctamente');
            } else {
                session()->setFlashdata('error', 'Error al eliminar el contacto');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar contacto: ' . $e->getMessage());
            session()->setFlashdata('error', 'Error al eliminar el contacto');
        }

        return redirect()->to('/admin/contactos');
    }

    /**
     * Ver detalles de un contacto (opcional - para AJAX)
     */
    public function ver($id)
    {
        $contacto = $this->contactoModel->find($id);

        if (!$contacto) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Contacto no encontrado'
            ])->setStatusCode(404);
        }

        // Marcar como leído si está en estado 'nuevo'
        if ($contacto['estado'] === 'nuevo') {
            $this->contactoModel->update($id, ['estado' => 'leido']);
            $contacto['estado'] = 'leido';
        }

        return $this->response->setJSON([
            'success' => true,
            'contacto' => $contacto
        ]);
    }

    /**
     * Obtener estadísticas de contactos
     */
    private function obtenerEstadisticas()
    {
        $stats = [];
        $estados = ['nuevo', 'leido', 'respondido', 'cerrado'];
        
        foreach ($estados as $estado) {
            // Crear una nueva instancia del modelo para cada consulta
            $contactoModel = new ContactoModel();
            $stats[$estado] = $contactoModel->where('estado', $estado)->countAllResults();
        }

        // Agregar estadísticas adicionales
        $contactoModel = new ContactoModel();
        $stats['total'] = $contactoModel->countAllResults();
        
        // Contactos de hoy
        $stats['hoy'] = $contactoModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults();
        
        // Contactos de esta semana
        $stats['semana'] = $contactoModel->where('created_at >=', date('Y-m-d', strtotime('-7 days')))->countAllResults();

        return $stats;
    }

    /**
     * Enviar notificación por email (método privado)
     */
    private function enviarNotificacionEmail($data, $contactoId)
    {
        try {
            $email = \Config\Services::email();
            
            // Configurar email
            $email->setTo('admin@insumosfat.com'); // Cambiar por el email real
            $email->setFrom('noreply@insumosfat.com', 'INSUMOS FAT S.A.'); // Configurar remitente
            $email->setSubject('Nuevo contacto desde el sitio web - #' . $contactoId);
            
            // Preparar mensaje
            $mensaje = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #007bff;'>
                        <h2 style='color: #007bff; margin-top: 0;'>Nuevo mensaje de contacto desde el sitio web</h2>
                        <p style='font-size: 16px; color: #6c757d;'>ID del contacto: <strong>#{$contactoId}</strong></p>
                    </div>
                    
                    <div style='padding: 20px 0;'>
                        <table style='width: 100%; border-collapse: collapse;'>
                            <tr>
                                <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Nombre:</strong></td>
                                <td style='padding: 8px 0; border-bottom: 1px solid #eee;'>{$data['nombre']} {$data['apellido']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Email:</strong></td>
                                <td style='padding: 8px 0; border-bottom: 1px solid #eee;'>
                                    <a href='mailto:{$data['correo']}' style='color: #007bff; text-decoration: none;'>{$data['correo']}</a>
                                </td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; border-bottom: 1px solid #eee;'><strong>Teléfono:</strong></td>
                                <td style='padding: 8px 0; border-bottom: 1px solid #eee;'>
                                    <a href='tel:{$data['telefono']}' style='color: #007bff; text-decoration: none;'>{$data['telefono']}</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div style='margin: 20px 0;'>
                        <h4 style='color: #495057; margin-bottom: 10px;'>Mensaje:</h4>
                        <div style='background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 3px solid #28a745;'>
                            " . nl2br(htmlspecialchars($data['mensaje'])) . "
                        </div>
                    </div>
                    
                    <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                    <div style='font-size: 12px; color: #6c757d;'>
                        <p><strong>Información:</strong></p>
                        <p>Fecha: " . date('d/m/Y H:i:s') . "</p>
                    </div>
                </div>
            ";
            
            $email->setMessage($mensaje);
            
            if (!$email->send()) {
                log_message('error', 'Error al enviar email de notificación: ' . $email->printDebugger(['headers']));
            } else {
                log_message('info', 'Email de notificación enviado correctamente para contacto #' . $contactoId);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error en envío de notificación por email: ' . $e->getMessage());
        }
    }

    /**
     * Método para obtener contactos via AJAX (para DataTables server-side si es necesario)
     */
    public function ajaxListar()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start') ?? 0;
        $length = $this->request->getPost('length') ?? 25;
        $search = $this->request->getPost('search')['value'] ?? '';

        $query = $this->contactoModel;

        // Aplicar búsqueda si existe
        if (!empty($search)) {
            $query = $query->groupStart()
                          ->like('nombre', $search)
                          ->orLike('apellido', $search)
                          ->orLike('correo', $search)
                          ->orLike('mensaje', $search)
                          ->groupEnd();
        }

        // Total de registros filtrados
        $recordsFiltered = $query->countAllResults(false);

        // Obtener datos paginados
        $contactos = $query->orderBy('created_at', 'DESC')
                          ->limit($length, $start)
                          ->find();

        // Total de registros sin filtro
        $recordsTotal = $this->contactoModel->countAllResults();

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $contactos
        ]);
    }
}
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
     * Procesar formulario de mensaje
     */
    public function enviarMensaje()
    {
        // Verificar que sea una petición POST
        if (!$this->request->isAJAX() && !$this->request->getMethod() === 'POST') {
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
            'mensaje' => 'required|min_length[10]|max_length[2000]'
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
            'mensaje' => trim($this->request->getPost('mensaje')),
            'tipo_contacto' => 'mensaje',
            'estado' => 'nuevo',
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
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
     * Procesar formulario de solicitud de llamada
     */
    public function solicitarLlamada()
    {
        // Verificar que sea una petición POST
        if (!$this->request->isAJAX() && !$this->request->getMethod() === 'POST') {
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
            'telefono' => 'required|min_length[8]|max_length[20]'
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
            'tipo_contacto' => 'llamada',
            'estado' => 'nuevo',
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ];

        try {
            // Insertar en la base de datos
            $contactoId = $this->contactoModel->insert($data);

            if ($contactoId) {
                // Enviar email de notificación (opcional)
                $this->enviarNotificacionEmail($data, $contactoId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Tu solicitud de llamada ha sido registrada. Te contactaremos a la brevedad.',
                    'contacto_id' => $contactoId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al registrar la solicitud. Por favor, inténtalo nuevamente.'
                ])->setStatusCode(500);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al solicitar llamada: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error interno del servidor. Por favor, inténtalo más tarde.'
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtener contactos (para admin)
     */
    public function listar()
    {
        // Verificar autenticación de administrador aquí
        // if (!$this->session->get('admin_logged_in')) {
        //     return redirect()->to('/admin/login');
        // }

        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;
        $estado = $this->request->getGet('estado');
        $tipo = $this->request->getGet('tipo');

        $builder = $this->contactoModel;

        if ($estado) {
            $builder = $builder->where('estado', $estado);
        }

        if ($tipo) {
            $builder = $builder->where('tipo_contacto', $tipo);
        }

        $contactos = $builder->orderBy('created_at', 'DESC')
                           ->paginate($perPage);

        $data = [
            'contactos' => $contactos,
            'pager' => $this->contactoModel->pager,
            'estado_filtro' => $estado,
            'tipo_filtro' => $tipo
        ];

        return view('admin/contactos/listar', $data);
    }

    /**
     * Ver detalles de un contacto
     */
    public function ver($id)
    {
        $contacto = $this->contactoModel->find($id);

        if (!$contacto) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Contacto no encontrado');
        }

        // Marcar como leído si es nuevo
        if ($contacto['estado'] === 'nuevo') {
            $this->contactoModel->marcarComoLeido($id);
            $contacto['estado'] = 'leido';
        }

        $data = [
            'contacto' => $contacto
        ];

        return view('admin/contactos/ver', $data);
    }

    /**
     * Cambiar estado de un contacto
     */
    public function cambiarEstado($id)
    {
        $nuevoEstado = $this->request->getPost('estado');
        
        $estadosValidos = ['nuevo', 'leido', 'respondido', 'cerrado'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Estado no válido'
            ])->setStatusCode(400);
        }

        if ($this->contactoModel->update($id, ['estado' => $nuevoEstado])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ])->setStatusCode(500);
        }
    }

    /**
     * Enviar notificación por email (opcional)
     */
    private function enviarNotificacionEmail($data, $contactoId)
    {
        // Aquí puedes implementar el envío de emails
        // usando la librería de Email de CodeIgniter 4
        
        /*
        $email = \Config\Services::email();
        
        $email->setTo('admin@insumosfat.com');
        $email->setSubject('Nuevo contacto desde el sitio web');
        
        $mensaje = view('emails/nuevo_contacto', [
            'data' => $data,
            'contacto_id' => $contactoId
        ]);
        
        $email->setMessage($mensaje);
        
        if (!$email->send()) {
            log_message('error', 'Error al enviar email de notificación: ' . $email->printDebugger());
        }
        */
    }
}
<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    /**
     * Muestra el formulario de login
     */
    public function index()
    {
        // Si ya está logueado, redirigir a inicio
        if (session()->get('isLoggedIn')) {
            //return redirect()->to('./');
            return redirect()->route('inicio');
        }
        $data['activo'] = '';
        $data['titulo'] = 'Insumos_FAT - Iniciar Sesión';
        
        return view('login', $data);
    }
    
    /**
     * Procesa el formulario de login
     */
    public function login(): RedirectResponse
    {
        $session = session();
        $userModel = new UserModel();
        
        // Validar formulario
        $rules = [
            'identity' => 'required',
            'password' => 'required|min_length[6]'
        ];
        
        if (!$this->validate($rules)) {
            $session->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        // Buscar usuario por email o username
        $identity = $this->request->getPost('identity');
        $password = $this->request->getPost('password');
        
        $user = $userModel->findByEmailOrUsername($identity);
        
        // Verificar si el usuario existe
        if (!$user) {
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->back()->withInput();
        }
        
        // Verificar si la cuenta está activa
        if ($user['active'] != 1) {
            $session->setFlashdata('error', 'Tu cuenta aún no ha sido activada');
            return redirect()->back()->withInput();
        }
        
        // Verificar contraseña
        if (!password_verify($password, $user['password_hash'])) {
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->back()->withInput();
        }
        
        // Todo bien, crear sesión
        $sessionData = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'isAdmin' => ($user['type'] == 1),
            'isLoggedIn' => true
        ];
        
        $session->set($sessionData);
        
        // Redirigir según tipo de usuario
        if ($user['type'] == 1) {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/');
        }
    }
    
    /**
     * Muestra el formulario de registro
     */
    public function register()
    {
        // Si ya está logueado, redirigir a inicio
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data['activo'] = '';
        $data['titulo'] = 'Insumos_FAT - Registro';
        
        return view('register', $data);
    }
    
    /**
     * Procesa el formulario de registro
     */
    public function doRegister(): RedirectResponse
    {
        $session = session();
        $userModel = new UserModel();
        
        // Debug: Log de datos recibidos
        log_message('info', 'Datos POST recibidos: ' . json_encode($this->request->getPost()));
        
        // Obtener datos del formulario
        $username = trim($this->request->getPost('username'));
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');
        $password_confirm = $this->request->getPost('password_confirm');
        $nombre = trim($this->request->getPost('nombre'));
        $apellido = trim($this->request->getPost('apellido'));
        $dni = trim($this->request->getPost('dni'));
        $direccion = trim($this->request->getPost('direccion'));
        
        // Validación manual para mejor control
        $errors = [];
        
        // Validar username
        if (empty($username)) {
            $errors['username'] = 'El nombre de usuario es requerido';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'El nombre de usuario debe tener al menos 3 caracteres';
        } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            $errors['username'] = 'El nombre de usuario solo puede contener letras y números';
        } else {
            // Verificar si el username ya existe
            $existingUser = $userModel->where('username', $username)->first();
            if ($existingUser) {
                $errors['username'] = 'Este nombre de usuario ya está en uso';
            }
        }
        
        // Validar email
        if (empty($email)) {
            $errors['email'] = 'El email es requerido';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Ingresa un email válido';
        } else {
            // Verificar si el email ya existe
            $existingEmail = $userModel->where('email', $email)->first();
            if ($existingEmail) {
                $errors['email'] = 'Este email ya está registrado';
            }
        }
        
        // Validar contraseña
        if (empty($password)) {
            $errors['password'] = 'La contraseña es requerida';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        // Validar confirmación de contraseña
        if (empty($password_confirm)) {
            $errors['password_confirm'] = 'Confirma tu contraseña';
        } elseif ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Las contraseñas no coinciden';
        }
        
        // Validar otros campos
        if (empty($nombre) || strlen($nombre) < 2) {
            $errors['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        }
        
        if (empty($apellido) || strlen($apellido) < 2) {
            $errors['apellido'] = 'El apellido debe tener al menos 2 caracteres';
        }
        
        if (empty($dni)) {
            $errors['dni'] = 'El DNI es requerido';
        }
        
        if (empty($direccion) || strlen($direccion) < 5) {
            $errors['direccion'] = 'La dirección debe tener al menos 5 caracteres';
        }
        
        // Si hay errores, regresar
        if (!empty($errors)) {
            log_message('error', 'Errores de validación: ' . json_encode($errors));
            $session->setFlashdata('error', $errors);
            return redirect()->back()->withInput();
        }
        
        // Preparar datos para registro
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dni' => $dni,
            'direccion' => $direccion,
            'active' => 1,  // Activar cuenta automáticamente
            'type' => 0     // Usuario normal (no admin)
        ];
        
        log_message('info', 'Datos preparados para registro: ' . json_encode($userData));
        
        // Intentar registrar
        try {
            // Verificar si el método registerUser existe en el modelo
            if (!method_exists($userModel, 'registerUser')) {
                log_message('error', 'El método registerUser no existe en UserModel');
                $session->setFlashdata('error', 'Error de configuración del sistema.');
                return redirect()->back()->withInput();
            }
            
            $userId = $userModel->registerUser($userData);
            
            log_message('info', 'Resultado del registro - ID: ' . ($userId ?: 'false'));
            
            if (!$userId) {
                // Obtener errores del modelo si están disponibles
                $modelErrors = $userModel->errors();
                if (!empty($modelErrors)) {
                    log_message('error', 'Errores del modelo: ' . json_encode($modelErrors));
                    $session->setFlashdata('error', $modelErrors);
                } else {
                    $session->setFlashdata('error', 'No se pudo completar el registro. Verifica que todos los datos sean correctos.');
                }
                return redirect()->back()->withInput();
            }
            
            // Éxito
            log_message('info', 'Usuario registrado exitosamente con ID: ' . $userId);
            $session->setFlashdata('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
            return redirect()->to('/auth');
            
        } catch (\Exception $e) {
            log_message('error', 'Excepción en registro: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            $session->setFlashdata('error', 'Error del sistema: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
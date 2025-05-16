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
    public function register(): string
    {
        // Si ya está logueado, redirigir a inicio
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }

        $data['activo'] = '';
        $data['titulo'] = 'Insumos_FAT - Registro';
        
        $data['contenido'] = view('register');
        
        return view('register', $data);
    }
    
    /**
     * Procesa el formulario de registro
     */
    public function doRegister(): RedirectResponse
    {
        $session = session();
        $userModel = new UserModel();
        
        // Validar formulario (usará las reglas definidas en el modelo)
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'nombre' => 'required|min_length[2]',
            'apellido' => 'required|min_length[2]',
            'dni' => 'required',
            'direccion' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            $session->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        // Preparar datos para registro
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'dni' => $this->request->getPost('dni'),
            'direccion' => $this->request->getPost('direccion'),
            'active' => 1  // Por ahora activamos la cuenta automáticamente
        ];
        
        // Intentar registrar
        $userId = $userModel->registerUser($data);
        
        if (!$userId) {
            $session->setFlashdata('error', 'Ocurrió un error al registrar tu cuenta. Por favor intenta de nuevo.');
            return redirect()->back()->withInput();
        }
        
        // Éxito
        $session->setFlashdata('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');
        return redirect()->to('/auth');
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
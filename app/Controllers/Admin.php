<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Admin extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    /**
     * Método que verifica si el usuario es administrador
     */
    private function checkAdmin()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth');
        }
        
        if (!session()->get('isAdmin')) {
            return redirect()->to('/');
        }
        
        return true;
    }
    
    /**
     * Muestra el dashboard principal
     */
    public function dashboard()
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $data['titulo'] = 'Panel de Administración';
        $data['activo'] = 'dashboard';
        
        return view('admin/dashboard', $data);
    }
    
    /**
     * Gestión de usuarios
     */
    public function users()
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $data['titulo'] = 'Gestión de Usuarios';
        $data['activo'] = 'users';
        $data['users'] = $this->userModel->findAll();
        
        return view('admin/users', $data);
    }
    
    /**
     * Cambiar estado (activar/desactivar) de un usuario
     */
    public function toggleActive($userId): RedirectResponse
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'Usuario no encontrado');
            return redirect()->to('/admin/users');
        }
        
        // No permitir desactivar el propio usuario
        if ($user['id'] == session()->get('id')) {
            session()->setFlashdata('error', 'No puedes desactivar tu propia cuenta');
            return redirect()->to('/admin/users');
        }
        
        $newStatus = ($user['active'] == 1) ? 0 : 1;
        $statusText = ($newStatus == 1) ? 'activado' : 'desactivado';
        
        $this->userModel->update($userId, ['active' => $newStatus]);
        
        session()->setFlashdata('success', "Usuario {$statusText} correctamente");
        return redirect()->to('/admin/users');
    }
    
    /**
     * Cambiar tipo de usuario (normal/administrador)
     */
    public function toggleType($userId): RedirectResponse
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'Usuario no encontrado');
            return redirect()->to('/admin/users');
        }
        
        $newType = ($user['type'] == 1) ? 0 : 1;
        $typeText = ($newType == 1) ? 'administrador' : 'comprador';
        
        $this->userModel->update($userId, ['type' => $newType]);
        
        session()->setFlashdata('success', "Usuario cambiado a {$typeText} correctamente");
        return redirect()->to('/admin/users');
    }
    
    /**
     * Eliminar usuario (soft delete)
     */
    public function deleteUser($userId): RedirectResponse
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'Usuario no encontrado');
            return redirect()->to('/admin/users');
        }
        
        // No permitir eliminar el propio usuario
        if ($user['id'] == session()->get('id')) {
            session()->setFlashdata('error', 'No puedes eliminar tu propia cuenta');
            return redirect()->to('/admin/users');
        }
        
        $this->userModel->delete($userId);
        
        session()->setFlashdata('success', 'Usuario eliminado correctamente');
        return redirect()->to('/admin/users');
    }
    
    /**
     * Mostrar formulario para editar usuario
     */
    public function editUser($userId)
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'Usuario no encontrado');
            return redirect()->to('/admin/users');
        }
        
        $data['titulo'] = 'Editar Usuario';
        $data['activo'] = 'users';
        $data['user'] = $user;
        
        return view('admin/edit_user', $data);
    }
    
    /**
     * Procesar la edición de usuario
     */
    public function updateUser($userId): RedirectResponse
    {
        $check = $this->checkAdmin();
        if ($check !== true) {
            return $check;
        }
        
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            session()->setFlashdata('error', 'Usuario no encontrado');
            return redirect()->to('/admin/users');
        }
        
        // Validar datos
        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'nombre' => 'required|min_length[2]',
            'apellido' => 'required|min_length[2]',
            'dni' => 'required',
            'direccion' => 'required'
        ];
        
        // Si se ingresó una contraseña, validarla
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'required|min_length[6]';
            $rules['password_confirm'] = 'required|matches[password]';
        }
        
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        // Preparar datos para actualizar
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'dni' => $this->request->getPost('dni'),
            'direccion' => $this->request->getPost('direccion')
        ];
        
        // Actualizar contraseña si se proporcionó
        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        // Actualizar usuario
        $this->userModel->update($userId, $data);
        
        session()->setFlashdata('success', 'Usuario actualizado correctamente');
        return redirect()->to('/admin/users');
    }
}
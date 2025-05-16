<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    /**
     * Verifica si el usuario es administrador
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Debes iniciar sesión para acceder a esta página');
            return redirect()->to('/auth');
        }
        
        if (!session()->get('isAdmin')) {
            session()->setFlashdata('error', 'No tienes permisos para acceder a esta sección');
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacemos nada después
    }
}
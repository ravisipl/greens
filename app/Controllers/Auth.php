<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $phone = $this->request->getPost('phone');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('phone', $phone)->first();

            if ($user && password_verify($password, $user['password'])) {
                // Set session data
                $sessionData = [
                    'user_id' => $user['id'],
                    'user_name' => $user['name'],
                    'role' => $user['role'],
                    'isLoggedIn' => true
                ];

                session()->set($sessionData);

                // Redirect based on role
                if ($user['role'] === 'Admin') {
                    return redirect()->to('admin/dashboard')->withCookies();
                } else {
                    return redirect()->to('user/dashboard')->withCookies();
                }
            }

            return redirect()->back()
                ->with('error', 'Invalid phone number or password');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('auth/login')->with('message', 'Logged out successfully');
    }
} 
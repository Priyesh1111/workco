<?php

namespace App\Controllers;
use App\Models\UsersModel;
use App\Controllers\BaseController;


class AuthController extends BaseController
{
    // Display login page
    public function login()
    {
        return view('auth/login');
    }

    // Process login form
    public function attemptLogin()
    {
        // Get email and password from request
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validate input
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Create model object
        $usersModel = new UsersModel();

        // Find active user by email
        $user = $usersModel
            ->where('email', $email)
            ->where('is_active', 'active')
            ->first();

        // Check user and password
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Regenerate session ID
        session()->regenerate();

        // Store user information in session
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'isLoggedIn' => true,
        ]);

        // Redirect to dashboard
        return redirect()->to('/dashboard');
    }

    // Logout user
    public function logout()
    {
        // Destroy current session
        session()->destroy();

        // Redirect to login page
        return redirect()->to('/login');
    }
}

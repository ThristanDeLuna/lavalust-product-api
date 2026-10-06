<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * JSON authentication endpoints (JWT access token + refresh token).
 *   GET  /                    health check
 *   POST /api/auth/register   {username, email, password}
 *   POST /api/auth/login      {username, password}
 *   POST /api/auth/refresh    {refresh_token}
 *   POST /api/auth/logout     {refresh_token}
 *   GET  /api/auth/me         (Bearer token)
 */
class ApiAuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');   // also sends CORS headers and answers OPTIONS
    }

    private function json_body(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function first_row($result)
    {
        if (is_array($result)) {
            if (isset($result['id'])) return $result;
            if (isset($result[0]))    return is_object($result[0]) ? (array) $result[0] : $result[0];
        } elseif (is_object($result)) {
            return (array) $result;
        }
        return null;
    }

    public function index()
    {
        $this->api->respond(['status' => 'ok', 'service' => 'Product API']);
    }

    public function register()
    {
        $b        = $this->json_body();
        $username = trim($b['username'] ?? '');
        $email    = trim($b['email'] ?? '');
        $password = (string) ($b['password'] ?? '');

        if (strlen($username) < 3 || strlen($username) > 100) {
            $this->api->respond_error('Username must be 3-100 characters', 422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->api->respond_error('A valid email is required', 422);
        }
        if (strlen($password) < 6) {
            $this->api->respond_error('Password must be at least 6 characters', 422);
        }

        $dup = $this->db->raw(
            'SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1',
            [$username, $email]
        )->fetch(PDO::FETCH_ASSOC);
        if ($dup) {
            $this->api->respond_error('Username or email already exists', 409);
        }

        $this->db->table('users')->insert([
            'username'   => $username,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => 'user',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->api->respond(['message' => 'Registered successfully'], 201);
    }

    public function login()
    {
        $b        = $this->json_body();
        $username = trim($b['username'] ?? '');
        $password = (string) ($b['password'] ?? '');

        $user = $this->first_row(
            $this->db->table('users')->where('username', $username)->get()
        );

        if (!$user || !password_verify($password, $user['password'])) {
            $this->api->respond_error('Invalid username or password', 401);
        }
        if (isset($user['is_active']) && (int) $user['is_active'] === 0) {
            $this->api->respond_error('Account is disabled', 403);
        }

        $tokens = $this->api->issue_tokens([
            'id'     => (int) $user['id'],
            'role'   => $user['role'],
            'scopes' => ['read', 'write'],
        ]);

        $this->api->respond([
            'message' => 'Login successful',
            'tokens'  => $tokens,
            'user'    => [
                'id'       => (int) $user['id'],
                'username' => $user['username'],
                'role'     => $user['role'],
            ],
        ]);
    }

    public function refresh()
    {
        $b = $this->json_body();
        if (empty($b['refresh_token'])) {
            $this->api->respond_error('refresh_token is required', 422);
        }
        $this->api->refresh_access_token($b['refresh_token']); // responds itself
    }

    public function logout()
    {
        $b = $this->json_body();
        if (!empty($b['refresh_token'])) {
            $this->api->revoke_refresh_token($b['refresh_token']);
        }
        $this->api->respond(['message' => 'Logged out']);
    }

    public function me()
    {
        $payload = $this->api->require_jwt();
        $this->api->respond(['user' => ['id' => (int) $payload['sub'], 'role' => $payload['role'] ?? 'user']]);
    }
}

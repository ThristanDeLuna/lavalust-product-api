<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Product CRUD API - every endpoint requires a valid Bearer access token.
 *   GET    /api/products
 *   GET    /api/products/{id}
 *   POST   /api/products
 *   PUT    /api/products/{id}   (PATCH also accepted)
 *   DELETE /api/products/{id}
 */
class ApiProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');   // CORS + OPTIONS preflight handled here
        $this->api->require_jwt();     // 401 if token missing/invalid/expired
        $this->call->model('ProductModel');
    }

    private function json_body(): array
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function format($p)
    {
        return [
            'id'           => (int) $p['id'],
            'product_name' => $p['product_name'],
            'description'  => $p['description'],
            'price'        => (float) $p['price'],
            'quantity'     => (int) $p['quantity'],
            'created_at'   => $p['created_at'],
        ];
    }

    /** Validate; $partial = true for PATCH (only validate fields that were sent). */
    private function validated(array $b, bool $partial = false): array
    {
        $out = [];

        if (!$partial || array_key_exists('product_name', $b)) {
            $name = trim((string) ($b['product_name'] ?? ''));
            if ($name === '' || strlen($name) > 100) {
                $this->api->respond_error('product_name is required (max 100 characters)', 422);
            }
            $out['product_name'] = $name;
        }
        if (!$partial || array_key_exists('description', $b)) {
            $out['description'] = trim((string) ($b['description'] ?? ''));
        }
        if (!$partial || array_key_exists('price', $b)) {
            if (!isset($b['price']) || !is_numeric($b['price']) || $b['price'] < 0) {
                $this->api->respond_error('price must be a number >= 0', 422);
            }
            $out['price'] = round((float) $b['price'], 2);
        }
        if (!$partial || array_key_exists('quantity', $b)) {
            if (!isset($b['quantity']) || filter_var($b['quantity'], FILTER_VALIDATE_INT) === false || $b['quantity'] < 0) {
                $this->api->respond_error('quantity must be a whole number >= 0', 422);
            }
            $out['quantity'] = (int) $b['quantity'];
        }
        return $out;
    }

    private function find_or_404($id)
    {
        $p = $this->ProductModel->get_product((int) $id);
        if (!$p) {
            $this->api->respond_error('Product not found', 404);
        }
        return $p;
    }

    public function index()
    {
        $rows = $this->ProductModel->get_all_products() ?: [];
        $this->api->respond(['data' => array_map([$this, 'format'], $rows)]);
    }

    public function show($id)
    {
        $this->api->respond(['data' => $this->format($this->find_or_404($id))]);
    }

    public function store()
    {
        $data = $this->validated($this->json_body());
        $id   = $this->ProductModel->create_product($data);
        $this->api->respond([
            'message' => 'Product created',
            'data'    => $this->format($this->ProductModel->get_product((int) $id)),
        ], 201);
    }

    public function update($id)
    {
        $this->find_or_404($id);
        $partial = ($_SERVER['REQUEST_METHOD'] === 'PATCH');
        $data    = $this->validated($this->json_body(), $partial);
        if (empty($data)) {
            $this->api->respond_error('Nothing to update', 422);
        }
        $this->ProductModel->update_product((int) $id, $data);
        $this->api->respond([
            'message' => 'Product updated',
            'data'    => $this->format($this->ProductModel->get_product((int) $id)),
        ]);
    }

    public function destroy($id)
    {
        $this->find_or_404($id);
        $this->ProductModel->delete_product((int) $id);
        $this->api->respond(['message' => 'Product deleted']);
    }
}

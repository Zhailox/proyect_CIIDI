<?php
// core/Security/EmergencyRescueService.php

class EmergencyRescueService {
    private string $emergFilePath;

    public function __construct(?string $customPath = null) {
        if ($customPath !== null) {
            $this->emergFilePath = $customPath;
        } elseif (defined('STORAGE_PATH')) {
            $this->emergFilePath = STORAGE_PATH . 'emergency_admin.json';
        } else {
            $this->emergFilePath = __DIR__ . '/../../storage/emergency_admin.json';
        }
    }

    /**
     * Obtiene los datos de la cuenta de emergencia guardada.
     */
    public function getEmergencyData(): array {
        if (!file_exists($this->emergFilePath)) {
            return [];
        }
        $content = file_get_contents($this->emergFilePath);
        return json_decode($content, true) ?: [];
    }

    /**
     * Intenta autenticar a un usuario utilizando la cuenta local Break-Glass.
     */
    public function authenticate(string $usuario, string $password): array {
        $emergData = $this->getEmergencyData();

        if (empty($emergData)) {
            return [
                'success' => false,
                'message' => 'No existe el archivo de cuenta de emergencia en storage/emergency_admin.json.'
            ];
        }

        if (empty($emergData['activo'])) {
            return [
                'success' => false,
                'message' => 'La cuenta de emergencia se encuentra desactivada.'
            ];
        }

        if (strtolower($emergData['usuario'] ?? '') !== strtolower(trim($usuario))) {
            return [
                'success' => false,
                'message' => "No existe una cuenta de emergencia con el usuario '{$usuario}'."
            ];
        }

        if (password_verify($password, $emergData['hash_contrasena'] ?? '')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['modo_emergencia_aislado'] = true;
            $_SESSION['emerg_user_nombre'] = $emergData['usuario'];

            return [
                'success' => true,
                'message' => 'Autenticación de emergencia exitosa.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Contraseña de emergencia incorrecta.'
        ];
    }

    /**
     * Actualiza las credenciales de la cuenta de emergencia.
     */
    public function updateCredentials(string $usuario, string $newPassword, string $updatedBy = 'SuperAdmin'): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $data = [
            'usuario' => trim($usuario),
            'hash_contrasena' => $hash,
            'activo' => true,
            'actualizado_por' => $updatedBy,
            'actualizado_el' => date('Y-m-d H:i:s')
        ];

        return file_put_contents(
            $this->emergFilePath,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        ) !== false;
    }
}

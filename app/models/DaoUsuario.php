<?php

class DaoUsuario
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /* =====================================================
       CREAR USUARIO
    ===================================================== */
    public function insertUsuario(array $data): bool
    {
        $sql = "
            INSERT INTO usuarios (
                id_personal,
                username,
                password_hash,
                email,
                estado
            ) VALUES (
                :id_personal,
                :username,
                :password_hash,
                :email,
                :estado
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id_personal'   => $data['id_personal'],
            ':username'      => $data['username'],
            ':password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':email'         => $data['email'] ?? null,
            ':estado'        => $data['estado'] ?? 'Activo'
        ]);
    }

    /* =====================================================
       OBTENER USUARIO POR ID
    ===================================================== */
    public function selectPorId(int $idUsuario): ?array
    {
        $sql = "
            SELECT 
                u.*,
                p.nombres,
                p.apellidos,
                p.cargo
            FROM usuarios u
            INNER JOIN personal_salud p ON p.id_personal = u.id_personal
            WHERE u.id_usuario = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idUsuario]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /* =====================================================
       OBTENER USUARIO POR USERNAME
    ===================================================== */
    public function selectPorUsername(string $username): ?array
    {
        $sql = "
            SELECT * 
            FROM usuarios 
            WHERE username = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

 
    /* =====================================================
       LISTAR TODOS LOS USUARIOS
    ===================================================== */
    public function select(): array
    {
        $sql = "
            SELECT 
                u.id_usuario,
                u.username,
                u.email,
                u.estado,
                u.fecha_creacion,
                u.ultimo_acceso,
                p.nombres,
                p.apellidos,
                p.cargo
            FROM usuarios u
            INNER JOIN personal_salud p ON p.id_personal = u.id_personal
            ORDER BY u.fecha_creacion DESC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       ACTUALIZAR DATOS DEL USUARIO
    ===================================================== */
    public function update(int $idUsuario, array $data): bool
    {
        $sql = "
            UPDATE usuarios SET
                username = :username,
                email    = :email,
                estado   = :estado
            WHERE id_usuario = :id_usuario
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':username'   => $data['username'],
            ':email'      => $data['email'] ?? null,
            ':estado'     => $data['estado'],
            ':id_usuario' => $idUsuario
        ]);
    }

    /* =====================================================
       CAMBIAR CONTRASEÑA
    ===================================================== */
    public function updatePassword(int $idUsuario, string $password): bool
    {
        $sql = "
            UPDATE usuarios 
            SET password_hash = ?
            WHERE id_usuario = ?
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            password_hash($password, PASSWORD_BCRYPT),
            $idUsuario
        ]);
    }

    /* =====================================================
       ACTUALIZAR ÚLTIMO ACCESO
    ===================================================== */
    public function updateUltimoAcceso(int $idUsuario): void
    {
        $sql = "
            UPDATE usuarios 
            SET ultimo_acceso = NOW()
            WHERE id_usuario = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idUsuario]);
    }

    /* =====================================================
       LOGIN / AUTENTICACIÓN
    ===================================================== */
    public function login(string $username, string $password): ?array
    {
        $sql = "
            SELECT 
                u.*,
                p.nombres,
                p.apellidos,
                p.cargo
            FROM usuarios u
            INNER JOIN personal_salud p ON p.id_personal = u.id_personal
            WHERE u.username = ?
              AND u.estado = 'Activo'
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            $this->actualizarUltimoAcceso($usuario['id_usuario']);
            unset($usuario['password_hash']); // seguridad
            return $usuario;
        }

        return null;
    }

    /* =====================================================
       CAMBIAR ESTADO (Activo / Inactivo)
    ===================================================== */
    public function updateEstado(int $idUsuario, string $estado): bool
    {
        $sql = "
            UPDATE usuarios 
            SET estado = ?
            WHERE id_usuario = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$estado, $idUsuario]);
    }

    /* =====================================================
       ELIMINAR USUARIO
    ===================================================== */
    public function delete(int $idUsuario): bool
    {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idUsuario]);
    }

    /* =====================================================
   OBTENER ACCESO A REPORTES
===================================================== */


 public function selectPorEmail(string $correo): ?array
    {
        $sql = "
            SELECT 
                *
                FROM usuarios  WHERE email = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$correo]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    

    /* =====================================================
       ACCESO A REPORTES
    ===================================================== */
    public function obtenerAccesoReportePorUsuario(int $idUsuario): ?array
    {
        $sql = "
            SELECT 
                tau.acceso,
                ur.ruta_reporte
            FROM usuario_reporte ur
            INNER JOIN tipo_acceso_usuario tau 
                ON tau.id_acceso_usuario = ur.id_acceso_usuario
            WHERE ur.id_usuario = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idUsuario]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }




}



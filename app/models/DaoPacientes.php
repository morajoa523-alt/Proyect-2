<?php

class DaoPacientes
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       CÁLCULO DE EDAD (AÑOS / MESES)
    ===================================================== */
    public function calcularEdad(string $fechaNacimiento): array
    {
        $fechaNac = new DateTime($fechaNacimiento);
        $hoy      = new DateTime();
        $diff     = $hoy->diff($fechaNac);

        return [
            'anios' => $diff->y,
            'meses' => ($diff->y * 12) + $diff->m
        ];
    }

    /* =====================================================
       REGISTRAR PACIENTE
    ===================================================== */
    public function crear(array $data): bool
    {
        $sql = "
            INSERT INTO pacientes (
                cedula,
                nombres,
                apellidos,
                sexo,
                fecha_nacimiento,
                id_ciudad,
                id_zona,
                direccion,
                telefono
            ) VALUES (
                :cedula,
                :nombres,
                :apellidos,
                :sexo,
                :fecha_nacimiento,
                :id_ciudad,
                :id_zona,
                :direccion,
                :telefono
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':cedula'            => $data['cedula'],
            ':nombres'           => $data['nombres'],
            ':apellidos'         => $data['apellidos'],
            ':sexo'              => $data['sexo'],
            ':fecha_nacimiento'  => $data['fecha_nacimiento'],
            ':id_ciudad'         => $data['id_ciudad'],
            ':id_zona'           => $data['id_zona'],
            ':direccion'         => $data['direccion'] ?? null,
            ':telefono'          => $data['telefono'] ?? null
        ]);
    }

    /* =====================================================
       OBTENER PACIENTE POR ID
    ===================================================== */
    public function obtenerPorId(int $idPaciente): ?array
    {
        $sql = "
            SELECT 
                p.*,
                c.nombre AS ciudad,
                pr.nombre AS provincia,
                z.tipo AS zona
            FROM pacientes p
            INNER JOIN ciudades c ON c.id_ciudad = p.id_ciudad
            INNER JOIN provincias pr ON pr.id_provincia = c.id_provincia
            INNER JOIN zonas z ON z.id_zona = p.id_zona
            WHERE p.id_paciente = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idPaciente]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /* =====================================================
       OBTENER PACIENTE POR CÉDULA
    ===================================================== */
    public function obtenerPorCedula(string $cedula): ?array
    {
        $sql = "
            SELECT *
            FROM pacientes
            WHERE cedula = ?
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cedula]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /* =====================================================
       LISTAR PACIENTES
    ===================================================== */
    public function listar(): array
    {
        $sql = "
            SELECT 
                p.id_paciente,
                p.cedula,
                p.nombres,
                p.apellidos,
                p.sexo,
                p.fecha_nacimiento,
                c.nombre AS ciudad,
                pr.nombre AS provincia,
                z.tipo AS zona,
                p.telefono,
                p.fecha_registro
            FROM pacientes p
            INNER JOIN ciudades c ON c.id_ciudad = p.id_ciudad
            INNER JOIN provincias pr ON pr.id_provincia = c.id_provincia
            INNER JOIN zonas z ON z.id_zona = p.id_zona
            ORDER BY p.fecha_registro DESC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       ACTUALIZAR PACIENTE
    ===================================================== */
    public function actualizar(int $idPaciente, array $data): bool
    {
        $sql = "
            UPDATE pacientes SET
                cedula = :cedula,
                nombres = :nombres,
                apellidos = :apellidos,
                sexo = :sexo,
                fecha_nacimiento = :fecha_nacimiento,
                id_ciudad = :id_ciudad,
                id_zona = :id_zona,
                direccion = :direccion,
                telefono = :telefono
            WHERE id_paciente = :id_paciente
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':cedula'           => $data['cedula'],
            ':nombres'          => $data['nombres'],
            ':apellidos'        => $data['apellidos'],
            ':sexo'             => $data['sexo'],
            ':fecha_nacimiento' => $data['fecha_nacimiento'],
            ':id_ciudad'        => $data['id_ciudad'],
            ':id_zona'          => $data['id_zona'],
            ':direccion'        => $data['direccion'] ?? null,
            ':telefono'         => $data['telefono'] ?? null,
            ':id_paciente'      => $idPaciente
        ]);
    }

    /* =====================================================
       ELIMINAR PACIENTE
       (Solo si no tiene consultas)
    ===================================================== */
    public function eliminar(int $idPaciente): bool
    {
        $sql = "DELETE FROM pacientes WHERE id_paciente = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idPaciente]);
    }
}


 ?>
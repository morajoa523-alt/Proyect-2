<?php

class DaoControlesAntropometricos
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /* =====================================================
       CÁLCULO DE IMC
    ===================================================== */
    private function calcularIMC(float $pesoKg, float $alturaM): float
    {
        if ($alturaM <= 0) {
            return 0;
        }
        return round($pesoKg / ($alturaM * $alturaM), 2);
    }

    /* =====================================================
       DIAGNÓSTICO NUTRICIONAL BÁSICO (REFERENCIAL)
    ===================================================== */
    private function diagnosticoNutricional(float $imc): string
    {
        if ($imc < 18.5) {
            return 'Bajo peso';
        } elseif ($imc >= 18.5 && $imc < 25) {
            return 'Peso normal';
        } elseif ($imc >= 25 && $imc < 30) {
            return 'Sobrepeso';
        } else {
            return 'Obesidad';
        }
    }

    /* =====================================================
       REGISTRAR CONTROL ANTROPOMÉTRICO
    ===================================================== */
    public function crear(array $data): bool
    {
        $imc = $this->calcularIMC($data['peso_kg'], $data['altura_m']);
        $diagnostico = $this->diagnosticoNutricional($imc);

        $sql = "
            INSERT INTO controles_antropometricos (
                id_consulta,
                edad,
                peso_kg,
                altura_m,
                imc,
                diagnostico_nutricional
            ) VALUES (
                :id_consulta,
                :edad,
                :peso_kg,
                :altura_m,
                :imc,
                :diagnostico
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id_consulta'  => $data['id_consulta'],
            ':edad'         => $data['edad'],
            ':peso_kg'      => $data['peso_kg'],
            ':altura_m'     => $data['altura_m'],
            ':imc'          => $imc,
            ':diagnostico'  => $diagnostico
        ]);
    }

    /* =====================================================
       OBTENER CONTROL POR ID
    ===================================================== */
    public function obtenerPorId(int $idControl): ?array
    {
        $sql = "
            SELECT *
            FROM controles_antropometricos
            WHERE id_control = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idControl]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /* =====================================================
       OBTENER CONTROL POR CONSULTA
    ===================================================== */
    public function obtenerPorConsulta(int $idConsulta): ?array
    {
        $sql = "
            SELECT *
            FROM controles_antropometricos
            WHERE id_consulta = ?
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idConsulta]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /* =====================================================
       LISTAR CONTROLES (CON PACIENTE Y PERSONAL)
    ===================================================== */
    public function listar(): array
    {
        $sql = "
            SELECT 
                ca.id_control,
                ca.edad,
                ca.peso_kg,
                ca.altura_m,
                ca.imc,
                ca.diagnostico_nutricional,
                c.fecha_consulta,
                p.nombres AS paciente_nombres,
                p.apellidos AS paciente_apellidos,
                ps.nombres AS personal_nombres,
                ps.apellidos AS personal_apellidos,
                ps.cargo
            FROM controles_antropometricos ca
            INNER JOIN consultas c ON c.id_consulta = ca.id_consulta
            INNER JOIN pacientes p ON p.id_paciente = c.id_paciente
            INNER JOIN personal_salud ps ON ps.id_personal = c.id_personal
            ORDER BY c.fecha_consulta DESC
        ";

        return $this->pdo
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       ACTUALIZAR CONTROL
    ===================================================== */
    public function actualizar(int $idControl, array $data): bool
    {
        $imc = $this->calcularIMC($data['peso_kg'], $data['altura_m']);
        $diagnostico = $this->diagnosticoNutricional($imc);

        $sql = "
            UPDATE controles_antropometricos SET
                edad = :edad,
                peso_kg = :peso_kg,
                altura_m = :altura_m,
                imc = :imc,
                diagnostico_nutricional = :diagnostico
            WHERE id_control = :id_control
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':edad'        => $data['edad'],
            ':peso_kg'     => $data['peso_kg'],
            ':altura_m'    => $data['altura_m'],
            ':imc'         => $imc,
            ':diagnostico' => $diagnostico,
            ':id_control'  => $idControl
        ]);
    }

    /* =====================================================
       ELIMINAR CONTROL
    ===================================================== */
    public function eliminar(int $idControl): bool
    {
        $sql = "DELETE FROM controles_antropometricos WHERE id_control = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$idControl]);
    }
}
?>
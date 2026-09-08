
<?php

class DaoGeneral
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /* =========================================
       TOTAL PACIENTES
       ========================================= */
    public function getTotalPacientes()
    {
        return $this->db->query("SELECT COUNT(*) FROM pacientes")->fetchColumn();
    }

    /* =========================================
       CONTROLES MENORES DE 5 AÑOS
       ========================================= */
    public function getControlesMenores5()
    {
        $sql = "
            SELECT 
                ca.peso_kg,
                ca.altura_m,
                ca.edad,
                p.sexo,
                pr.nombre AS provincia,
                ci.nombre AS ciudad,
                z.tipo AS zona,
                p.id_paciente
            FROM controles_antropometricos ca
            JOIN consultas c ON ca.id_consulta = c.id_consulta
            JOIN pacientes p ON c.id_paciente = p.id_paciente
            JOIN ciudades ci ON p.id_ciudad = ci.id_ciudad
            JOIN provincias pr ON ci.id_provincia = pr.id_provincia
            JOIN zonas z ON p.id_zona = z.id_zona
            WHERE ca.edad < 5
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =========================================
       TOTAL NIÑOS MENORES DE 5
       ========================================= */
    public function getNinosMenores5()
    {
        return count($this->getControlesMenores5());
    }

    /* =========================================
       NIÑOS DESNUTRIDOS (CALCULADO)
       ========================================= */
    public function getNinosDesnutridosCalculado()
    {
        $helper = new funcionesIdentificadoras();
        $controles = $this->getControlesMenores5();

        $total = 0;

        foreach ($controles as $c) {

            if (
                !isset($c['peso_kg'], $c['altura_m'], $c['edad'], $c['sexo']) ||
                $c['peso_kg'] <= 0 ||
                $c['altura_m'] <= 0 ||
                $c['edad'] < 0
            ) continue;

            $sexo = ($c['sexo'] === 'F') ? 1 : 0;

            $resultado = $helper->evaluar(
                floatval($c['peso_kg']),
                floatval($c['altura_m']),
                floatval($c['edad']),
                $sexo
            );

            if ($resultado === "Desnutricion") {
                $total++;
            }
        }

        return $total;
    }

    /* =========================================
       MÉTODO COMPATIBLE (ANTES USABA BD)
       ========================================= */
    public function getNinosDesnutridos()
    {
        return $this->getNinosDesnutridosCalculado();
    }

    /* =========================================
       PORCENTAJE DESNUTRICIÓN
       ========================================= */
    public function getPorcentajeDesnutricion()
    {
        $total = $this->getNinosMenores5();
        if ($total == 0) return 0;

        $desnutridos = $this->getNinosDesnutridosCalculado();

        return round(($desnutridos / $total) * 100, 2);
    }

    /* =========================================
       DESNUTRICIÓN POR ZONA
       ========================================= */
    public function getDesnutricionPorZona($zonaBuscada)
    {
        $helper = new funcionesIdentificadoras();
        $controles = $this->getControlesMenores5();

        $total = 0;
        $desnutridos = 0;

        foreach ($controles as $c) {

            if ($c['zona'] !== $zonaBuscada) continue;
            if ($c['peso_kg'] <= 0 || $c['altura_m'] <= 0) continue;

            $total++;

            $sexo = ($c['sexo'] === 'F') ? 1 : 0;

            $resultado = $helper->evaluar(
                floatval($c['peso_kg']),
                floatval($c['altura_m']),
                floatval($c['edad']),
                $sexo
            );

            if ($resultado === "Desnutricion") {
                $desnutridos++;
            }
        }

        return [
            "cantidad" => $total,
            "porcentaje" => $total > 0
                ? round(($desnutridos / $total) * 100, 2)
                : 0
        ];
    }

    /* =========================================
       CIUDAD CON MAYOR DESNUTRICIÓN
       ========================================= */
    public function getCiudadMayorDesnutricion()
    {
        $helper = new funcionesIdentificadoras();
        $controles = $this->getControlesMenores5();

        $estadisticas = [];

        foreach ($controles as $c) {

            if ($c['peso_kg'] <= 0 || $c['altura_m'] <= 0) continue;

            $clave = $c['provincia'] . "|" . $c['ciudad'] . "|" . $c['zona'];

            if (!isset($estadisticas[$clave])) {
                $estadisticas[$clave] = [
                    "provincia" => $c['provincia'],
                    "ciudad" => $c['ciudad'],
                    "zona" => $c['zona'],
                    "total" => 0,
                    "desnutridos" => 0
                ];
            }

            $estadisticas[$clave]["total"]++;

            $sexo = ($c['sexo'] === 'F') ? 1 : 0;

            $resultado = $helper->evaluar(
                floatval($c['peso_kg']),
                floatval($c['altura_m']),
                floatval($c['edad']),
                $sexo
            );

            if ($resultado === "Desnutricion") {
                $estadisticas[$clave]["desnutridos"]++;
            }
        }

        $max = null;
        $mayor = 0;

        foreach ($estadisticas as $e) {
            $porcentaje = $e["total"] > 0
                ? ($e["desnutridos"] / $e["total"]) * 100
                : 0;

            if ($porcentaje > $mayor) {
                $mayor = $porcentaje;
                $max = $e + ["porcentaje" => round($porcentaje, 2)];
            }
        }

        return $max;
    }

    /* =========================================
       INDICADORES POR LOCALIDAD
       ========================================= */
    public function getIndicadoresPorLocalidad()
    {
        $helper = new funcionesIdentificadoras();
        $controles = $this->getControlesMenores5();

        $estadisticas = [];

        foreach ($controles as $c) {

            if ($c['peso_kg'] <= 0 || $c['altura_m'] <= 0) continue;

            $clave = $c['provincia'] . "|" . $c['ciudad'] . "|" . $c['zona'];

            if (!isset($estadisticas[$clave])) {
                $estadisticas[$clave] = [
                    "provincia" => $c['provincia'],
                    "ciudad" => $c['ciudad'],
                    "zona" => $c['zona'],
                    "total_ninos" => 0,
                    "ninos_desnutridos" => 0
                ];
            }

            $estadisticas[$clave]["total_ninos"]++;

            $sexo = ($c['sexo'] === 'F') ? 1 : 0;

            $resultado = $helper->evaluar(
                floatval($c['peso_kg']),
                floatval($c['altura_m']),
                floatval($c['edad']),
                $sexo
            );

            if ($resultado === "Desnutricion") {
                $estadisticas[$clave]["ninos_desnutridos"]++;
            }
        }

        foreach ($estadisticas as &$e) {
            $e["porcentaje_desnutricion"] = $e["total_ninos"] > 0
                ? round(($e["ninos_desnutridos"] / $e["total_ninos"]) * 100, 2)
                : 0;
        }

        usort($estadisticas, fn($a, $b) =>
            $b["porcentaje_desnutricion"] <=> $a["porcentaje_desnutricion"]
        );

        return $estadisticas;
    }



    public function getControlesMenores5m()
{
    $sql = "
        SELECT 
            ca.peso_kg,
            ca.altura_m,
            ca.edad,
            p.sexo
        FROM controles_antropometricos ca
        JOIN consultas c ON ca.id_consulta = c.id_consulta
        JOIN pacientes p ON c.id_paciente = p.id_paciente
        WHERE ca.edad < 5
    ";

    return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

public function getNinosDesnutridosCalculadom()
{
    $helper = new funcionesIdentificadoras();
    $controles = $this->getControlesMenores5();

    $total = 0;

    foreach ($controles as $c) {

        // Validar datos mínimos
        if (
            !isset($c['peso_kg'], $c['altura_m'], $c['edad'], $c['sexo']) ||
            $c['peso_kg'] <= 0 ||
            $c['altura_m'] <= 0 ||
            $c['edad'] < 0
        ) {
            continue; // Saltar registro inválido
        }

        // Edad en años (NO convertir a meses)
        $edadAnios = floatval($c['edad']);

        // Sexo: 0 = niño, 1 = niña
        $sexo = ($c['sexo'] === 'F') ? 1 : 0;

        $resultado = $helper->evaluar(
            floatval($c['peso_kg']),
            floatval($c['altura_m']),
            $edadAnios,
            $sexo
        );

        if ($resultado === "Desnutricion") {
            $total++;
        }
    }

    return $total;
}


}

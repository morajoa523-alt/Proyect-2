<?php

class funcionesIdentificadoras
{
    /* =========================
       PESO PARA LA EDAD
       ========================= */
    public static function pesoEdad($peso, $edadMeses, $sexo)
    {
        $peso = floatval($peso);
        $edadMeses = intval($edadMeses);
        $sexo = intval($sexo);

        $tabla = [
            12 => [9.6, 8.9],   // niño, niña
            24 => [12.2, 11.5],
            36 => [14.3, 13.9],
            48 => [16.3, 15.4],
            60 => [18.3, 17.4]
        ];

        $edadRef = self::medidaMasCercana($tabla, $edadMeses);
        $mediana = $tabla[$edadRef][$sexo] ?? null;

        return self::evaluarZ($peso, $mediana);
    }

    /* =========================
       TALLA PARA LA EDAD
       ========================= */
    public static function tallaEdad($talla, $edadMeses, $sexo)
    {
        $talla = floatval($talla);
        $edadMeses = intval($edadMeses);
        $sexo = intval($sexo);

        $tabla = [
            12 => [75.7, 74.0],
            24 => [87.1, 85.7],
            36 => [96.1, 95.1],
            48 => [103.3, 102.7],
            60 => [110.0, 109.4]
        ];

        $edadRef = self::medidaMasCercana($tabla, $edadMeses);
        $mediana = $tabla[$edadRef][$sexo] ?? null;

        return self::evaluarZ($talla, $mediana);
    }

    /* =========================
       PESO PARA LA TALLA
       ========================= */
    public static function pesoTalla($peso, $talla)
    {
        $peso = floatval($peso);
        $talla = intval($talla);

        $tabla = [
            70 => 8.0,
            80 => 10.0,
            90 => 12.5,
            100 => 15.5,
            110 => 18.5,
            120 => 22.0
        ];

        $tallaRef = self::medidaMasCercana($tabla, $talla);
        $mediana = $tabla[$tallaRef] ?? null;

        return self::evaluarZ($peso, $mediana);
    }

    /* =========================
       FUNCIÓN GENERAL Z-SCORE
       ========================= */
    private static function evaluarZ($valor, $mediana)
    {
        if ($mediana === null || $mediana <= 0) {
            return ["diagnostico" => "Datos insuficientes"];
        }
        // promedio del peso de todos los niños, el promedio de la estatura, y promedio de edad, 
        // se compara esos valores en las tablas de medidas de desviación estandar de la OMS y se optiene la aproximación
        $sd = $mediana * 0.13; // Aproximación
        if ($sd == 0) {
            return ["diagnostico" => "Datos insuficientes"];
        }

        $z = ($valor - $mediana) / $sd;
        
        if ($z < -3) return ["diagnostico" => "Desnutrición severa"];
        if ($z < -2) return ["diagnostico" => "Desnutrición moderada"];
        if ($z > 2) return ["diagnostico" => "Sobrepeso"];

        return ["diagnostico" => "Normal"];
    }

    /* =========================
       CLAVE MÁS CERCANA
       ========================= */
    private static function medidaMasCercana($tabla, $valor)
    {
        $claves = array_keys($tabla);
        $masCercana = $claves[0];

        foreach ($claves as $k) {
            
            if (abs($valor - $k) < abs($valor - $masCercana)) {
                $masCercana = $k;
               
            }
             
        }

        return $masCercana;
    }

    /* =========================
       DIAGNÓSTICO GLOBAL
       ========================= */
    public function evaluar($peso, $talla_m, $edadAnios, $sexo)
    {
        $edadMeses = intval($edadAnios * 12);
        $talla_cm = floatval($talla_m) * 100;

        $datos = [
            self::pesoEdad($peso, $edadMeses, $sexo),
            self::tallaEdad($talla_cm, $edadMeses, $sexo),
            self::pesoTalla($peso, $talla_cm)
        ];

        foreach ($datos as $d) {
            if (
                $d["diagnostico"] == "Desnutrición moderada" ||
                $d["diagnostico"] == "Desnutrición severa"
            ) {
                return "Desnutricion";
            }
        }

        return "Normal";
    }
}
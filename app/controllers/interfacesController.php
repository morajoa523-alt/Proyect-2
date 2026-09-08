<?php

class interfacesController extends Controller
{
  private $anio;
  private $dao;
  private $daoDia;
  private $daoUsuario;
   // private $daoSegm;
    private $por;
    private $dia;
    private $mes;
    //private $anio;

    public function __construct() {
        
       
        $this->dao = new DaoGeneral();
        $this->daoUsuario = new DaoUsuario();
        $this->daoHelp = new funcionesIdentificadoras();
       
       
    }



    

    public function inicio(){

     $ninosDesnutridos = $this->dao->getNinosDesnutridosCalculado();

      $data = [
            'totalPacientes'         => $this->dao->getTotalPacientes(),
            'ninosMenores5'          => $this->dao->getNinosMenores5(),
            //'ninosDesnutridos'       => $this->dao->getNinosDesnutridos(),
            'ninosDesnutridos'  =>      $ninosDesnutridos,
            'porcentajeDesnutricion' => $this->dao->getPorcentajeDesnutricion(),
            'zonaRural'              => $this->dao->getDesnutricionPorZona('Rural'),
            'zonaUrbana'             => $this->dao->getDesnutricionPorZona('Urbana'),
            'localidad'              => $this->dao->getCiudadMayorDesnutricion(),
        ];

         


        // Condición futura para notificación
        $data['alertaDesnutricion'] = $data['porcentajeDesnutricion'] > 20;

        $this->render('inicio', $data);
    }



     public function nutricionPorLocalidad()
    {
        $indicadores = $this->dao->getIndicadoresPorLocalidad();

        $datos = [
            'indicadores' => $indicadores
        ];
        $this->render('porsentajeDesnutricionUbicacion', $datos);
    }






  

 



  public function users()
  {

    $datosRolUser = $this->daoUsuario->selectTiposUsuario();
    $datosUsers = $this->daoUsuario->selectUsuarios();
    $datosUserLogueado = $this->daoUsuario->selectUsuario(1);

      $datos = [
      "tiposUsuario" => $datosRolUser,
      "usuarioLogueado" => $datosUserLogueado,
      "usuarios"  => $datosUsers 

      

    ];
    //print_r($datos['meses']);
    $this->render('userAndRegisterUser', $datos);
  }


 public function cerrarSesion()
{
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Vaciar variables de sesión
    $_SESSION = [];

    // Eliminar cookie de sesión (MUY IMPORTANTE)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruir la sesión
    session_destroy();

    // Redirigir al login
    header('Location: /plataformaDect/inicio/login');
    exit;
}


  public function login()
  {
      $datos = [
      "anio" => ""
      

    ];
    //print_r($datos['meses']);
    $this->render('login', $datos);
  }


public function panelUser()
  {
      $datos = [
      "usuario" => ""
      

    ];
    //print_r($datos['meses']);
    $this->render('panelUserNoAdmin', $datos);
  }

public function iniciarSesion()
{
  
    if (isset($_SESSION['usuario'])) {
        header('Location: /plataformaDect/inicio/inicio');
        exit;
    }

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $correo     = $_POST['correo'] ?? null;
        $contrasena = $_POST['contrasena'] ?? null;

        if (!$correo || !$contrasena) {
            $error = 'Debe completar todos los campos';
        } else {

            $usuario = $this->daoUsuario->selectPorEmail($correo);

            if (
                !$usuario ||
                !password_verify($contrasena, $usuario['password_hash'])
            ) {
                $error = 'Correo o contraseña incorrectos';
            } else {

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['usuario'] = [
                    'id_usuario' => $usuario['id_usuario'],
                    'correo'     => $usuario['correo'],
                    'rol'        => $usuario['rol'] ?? '',
                    'id_tipo'    => $usuario['id_tipo'] ?? ''
                ];

                    header('Location: /plataformaDect/inicio/inicio');
                    exit;
                
            }
        }
    }

    $this->render('login', ['mensaje' => $error]);
}




 public function editarUsuario(){

  $id = $_POST['id_usuario'] ?? 0;
  $datosUser = $this->daoUsuario->selectUsuario($id);
  $correo = $_POST['correo'] ?? '';
  $verCampoPass = $_POST['verCampoPass'] ?? '';

  session_start();
  
  $_SESSION['user'] = [
    "idUsuario" => $id,
    "correo" => $correo,
    "datosUsuario" => $datosUser,
    "verCampoPass" => $verCampoPass
  ];


  $this->render('edicionDatosUser', []);
 }

  

  public function update($id)
  {
     if(empty($id)){
            exit("No se estableció el parametro 'id'");
        }else{
            echo $id[2];
        }
  }

}

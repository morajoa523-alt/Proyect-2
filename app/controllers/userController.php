<?php
require_once __DIR__ . '/../models/DaoUsuario.php';

class UserController extends Controller
{
    private DaoUsuario $dao;
    private $datos = [];

    public function __construct()
    {
        $this->dao = new DaoUsuario();
        
    }

    /* ===============================
       INSERTAR USUARIO
       POST /user/insert
    =============================== */

    public function datos(){

        $datosRolUser = $this->dao->selectTiposUsuario();
        $datosUsers = $this->dao->selectUsuarios();
        $datosUserLogueado = $this->dao->selectUsuario(1);

      $this->datos = [
      "tiposUsuario" => $datosRolUser,
      "usuarioLogueado" => $datosUserLogueado,
      "usuarios"  => $datosUsers 
       ];
      
       $this->render('admin/userAndRegisterUser', $this->datos);
    


    }



    public function insertUser(): void
    {
        $correo     = $_POST['correo'] ?? null;
        $contrasena = $_POST['contrasena'] ?? null;
        $id_tipo    = $_POST['id_tipo'] ?? null;

        if (!$correo || !$contrasena || !$id_tipo) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Campos requeridos: correo, contrasena, id_tipo'
            ]);
            return;
        }

        // Hash seguro
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        $id = $this->dao->insertUsuario([
            'correo'     => $correo,
            'contrasena' => $hash,
            'id_tipo'    => (int)$id_tipo
        ]);

        
         $this->datos();
    
    }

    /* ===============================
       LISTAR USUARIOS
       GET /user/list
    =============================== */
    public function selectUsers(): void
    {
        $usuarios = $this->dao->selectUsuarios();
        echo json_encode($usuarios);
    }

    /* ===============================
       OBTENER USUARIO POR ID
       GET /user/{id}
    =============================== */
    public function selectUser(int $id): void
    {
        $usuario = $this->dao->selectUsuario($id);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        $this->datos();
    }

    /* ===============================
       ACTUALIZAR USUARIO
       PUT /user/{id}
    =============================== */
    public function updateUser(): void
    {
        

        $correo  = $_POST['correo'] ?? null;
        $contrasena  = $_POST['contrasena'] ?? null;
        $id_tipo =$_POST['id_tipo'] ?? null;
        $id =$_POST['id_usuario'] ?? null;
        $nombres =$_POST['nombres'] ?? null;
        $apellidos =$_POST['apellidos'] ?? null;
        $cedula =$_POST['cedula'] ?? null;
        $celular =$_POST['celular'] ?? null;
        

        

        $data = [
            'correo'  => $correo,
            'id_tipo' => $id_tipo,
            'id' => $id,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'cedula' => $cedula,
            'celular' => $celular
            
        ];

        // Contraseña opcional
        if (!empty($_POST['contrasena'])) {
            $data['contrasena'] = password_hash(
                $_POST['contrasena'],
                PASSWORD_BCRYPT
            );
        }

        $ok = $this->dao->updateUsuario($id, $data);

        $this->datos();
    }

    /* ===============================
       ELIMINAR USUARIO
       DELETE /user/{id}
    =============================== */
    public function deleteUser(): void
    {
        if(isset($_POST['submit'])){
        $id = $_POST['id_usuario'] ?? 0;
        $ok = $this->dao->deleteUsuario($id);

         $this->datos();
        }



    }



    public function insertUserReporte()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /Dashboards/inicio/reportes');
        exit;
    }

    $idUsuario        = $_POST['id_usuario'] ?? null;
    $idAccesoUsuario  = $_POST['id_acceso_usuario'] ?? null;
    $rutaReporte      = $_POST['ruta_reporte'] ?? null;

    if (!$idUsuario || !$idAccesoUsuario) {
        header('Location: /Dashboards/inicio/reportes');
        exit;
    }

    $this->dao->crearUsuarioReporte(
        (int)$idUsuario,
        (int)$idAccesoUsuario,
        $rutaReporte
    );

    // PRG → evitar ERR_CACHE_MISS
    header('Location: /Dashboards/inicio/reportes');
    exit;
}


    public function updateUserReporte()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /Dashboards/inicio/reportes');
        exit;
    }

    $idUsuarioReporte = $_POST['id_usuario_reporte'] ?? null;
    $idUsuario        = $_POST['id_usuario'] ?? null;
    $idAccesoUsuario  = $_POST['id_acceso_usuario'] ?? null;
    $rutaReporte      = $_POST['ruta_reporte'] ?? null;

    if (!$idUsuarioReporte || !$idUsuario || !$idAccesoUsuario) {
        header('Location: /Dashboards/inicio/reportes');
        exit;
    }

    $this->dao->actualizarUsuarioReporte(
        (int)$idUsuarioReporte,
        (int)$idUsuario,
        (int)$idAccesoUsuario,
        $rutaReporte
    );

    header('Location: /Dashboards/inicio/reportes');
    exit;
}

public function eliminarUserReporte()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /Dashboards/interfaces/reportes');
        exit;
    }

    $idUsuarioReporte = $_POST['id_usuario_reporte'] ?? null;

    if (!$idUsuarioReporte) {
        header('Location: /Dashboards/inicio/reportes');
        exit;
    }

    $this->dao->eliminarUsuarioReporte((int)$idUsuarioReporte);

    header('Location: /Dashboards/inicio/reportes');
    exit;
}



}

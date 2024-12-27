<?php
// Clase que representa a un usuario
class Usuario {
    private $id; // Identificador único
    private $nombre; // Nombre del usuario
    private $clave; // Contraseña (encriptada)
    private $email; // Correo electrónico

    public function __construct($nombre, $clave, $email, $id = null) {
        $this->id = $id; // Opcional, si el usuario ya existe
        $this->nombre = $nombre;
        $this->clave = password_hash($clave, PASSWORD_DEFAULT); // Encripta la contraseña
        $this->email = $email;
    }

    // Getters: Para obtener los valores de las propiedades
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    // Setters: Para cambiar los valores de las propiedades
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setClave($clave) {
        $this->clave = password_hash($clave, PASSWORD_DEFAULT);
    }

    public function setEmail($email) {
        $this->email = $email;
    }
}
?>

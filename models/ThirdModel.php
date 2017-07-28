<?php
/* 1. Creo el nuevo modelo que conectara a la base de datos
 * 2. Posteriormente, dentro del controlador del modelo (controllers/Usuarios.php) 
 * genero la función index() que me traerá los datos
 * 3. Genero la nueva vista dónde se mostraran los usuarios
 */
class ThirdModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    public function getUser($active){
        $sql="SELECT 
                   users.id,
                   users.nickname,
                   users.days_holidays_year,
                   state.taxonomy_modified as state_modified,
                   state.taxonomy as state,
                   rol.taxonomy_modified as rol_modified,
                   rol.taxonomy as rol,
                   users.id_contact,
                   contacts_business.id_business,
                   business.tradename,
                   contacts.firstname,
                   contacts.lastname,
                   users.highdate
                FROM users
                INNER JOIN typedata as state ON users.id_state=state.id
                INNER JOIN typedata as rol ON users.id_rol=rol.id
                INNER JOIN contacts_business ON contacts_business.id_contact=users.id_contact
                INNER JOIN business ON business.id=contacts_business.id_business
                INNER JOIN contacts ON users.id_contact=contacts.id";
       if($active){
            $sql=$sql." WHERE state.taxonomy='user_state_active'";
        };
        $pst=$this->db->query($sql);
        $pst->execute();
        return $pst->fetchAll();
   }
   
}

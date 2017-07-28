<?php
class LoginModel extends Model{
    public function __Construct(){
        parent::__construct();
    }

    public function getUser($name, $password){
        $pst=$this->db->prepare("SELECT 
                   users.id,
                   users.nickname, 
                   users.id_contact,
                   users.password_md5, 
                   users.url_image,
                   users.days_holidays_year,
                   users.highdate,
                   users.weekly_schedule,
                   typedata_state.taxonomy as state,
                   typedata_rol.taxonomy as rol,
                   contacts.firstname,
                   contacts.lastname,
                   contacts_business.id_business,
                   business.tradename
                FROM users
				INNER JOIN typedata as typedata_state ON users.id_state=typedata_state.id
                INNER JOIN typedata as typedata_rol ON users.id_rol=typedata_rol.id
                INNER JOIN contacts_business ON contacts_business.id_contact=users.id_contact
                INNER JOIN business ON business.id=contacts_business.id_business
                INNER JOIN contacts ON users.id_contact=contacts.id
                WHERE users.nickname = :name AND users.password_md5 = :password");
       $pst->bindParam(":name", $name, PDO::PARAM_STR);
       $pst->bindParam(":password", $password, PDO::PARAM_STR);
       $pst->execute();
       $data=$pst->fetchAll();
       $data[0]['weekly_schedule']=stringToArray($data[0]['weekly_schedule']);
       return $data; 
    }
}

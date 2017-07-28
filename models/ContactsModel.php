<?php
class ContactsModel extends Model{
   public function getContacts(){
       $pst=$this->db->prepare("SELECT 
               contact.id, 
               contact.firstname, 
               contact.lastname, 
               contact.id_sex, 
               typedata.type, 
               contact.birthdate, 
               contact.highdate, 
               email.email 
               FROM contact 
               INNER JOIN typedata ON contact.id_sex = typedata.id 
               INNER JOIN email ON email.id_contact = contact.id");
       $pst->bindParam(":name", $name, PDO::PARAM_STR);
       $pst->bindParam(":password", $password, PDO::PARAM_STR);
       $pst->execute();
       return $pst->fetchAll(); 
   }
}


<?php
class ConfigurationModel extends Model{
    public function getTaxonomiesCategory(){
       $sql="SELECT 
                   typedata_category.id,
                   typedata_category.typedata_category,
                   typedata_category.typedata_category_modified
                FROM typedata_category";
        $pst=$this->db->query($sql);
        $pst->execute();
        $categories=$pst->fetchAll();
        $categoriesNum=count($categories);
        $getTaxonomiesCategory=array();
        foreach($categories as $elements => $values){
           $getTaxonomiesCategory[$elements+1] =$values;
        }
        return $getTaxonomiesCategory;
    }
    public Function getDataTaxonomy($category){

        $sql="SELECT 
                   typedata.taxonomy,
                   typedata.taxonomy_modified,
                   typedata.id
                    FROM typedata
                    INNER JOIN typedata_category ON typedata.id_typedata_category=typedata_category.id
                    WHERE typedata_category.typedata_category =:category ";
        $pst=$this->db->prepare($sql);
        $pst->bindParam(":category", $category, PDO::PARAM_STR);
        $pst->execute();
        $typesTaxonomies=$pst->fetchAll();
        return $typesTaxonomies;
    }

    public function getTaxonomies(){
        $typesTaxonomies=$this->getTaxonomiesCategory();
        foreach($typesTaxonomies as $category =>$valores){
            $sql="SELECT 
                   typedata.taxonomy,
                   typedata.taxonomy_modified
                    FROM typedata
                    WHERE typedata.id_typedata_category =:category 
                    ORDER BY typedata.taxonomy_modified ASC";
            $pst=$this->db->prepare($sql);
            $pst->bindParam(":category", $category, PDO::PARAM_INT);
            $pst->execute();
            $typesTaxonomies[$category]['list_taxonomies']=$pst->fetchAll();
        }
        return $typesTaxonomies;
   }
}

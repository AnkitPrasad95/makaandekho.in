<?php 
class query{

    function __construct() {
        include 'config2.php';
    }
    public function getCategory()
    {
        $statement = $this->db->prepare("select * from tbl_product_category order by index_value asc");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getMainProducts()
    {
        $statement = $this->db->prepare("select * from tbl_product_list where show_on_index = 1 order by id desc");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCatDetailsByUrl($cat_url)
    {
        $statement = $this->db->prepare("select * from tbl_product_category where slug =?");
        $statement->execute(array($cat_url));
        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function getProductByCat($cat_id){
        $query = "select * from tbl_product_list where cat_id LIKE '%$cat_id%' order by id desc";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function enquirySave($value) {
        $statement = $this->db->prepare("insert into enquiry (name, email, phone, subject, message) VALUES (?,?,?,?,?)");
        $statement->execute($value);
    }

    public function getProductDetails($prod_url) {
        $statement = $this->db->prepare("select * from tbl_product_list where slug =?");
        $statement->execute(array($prod_url));
        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function getMarketAreas() {
        $statement = $this->db->prepare("select * from tbl_market_area order by name asc");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function gallary_list() {
        $statement = $this->db->prepare("select * from tbl_gallery_list order by id desc");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductImages($product_id) {
        $statement = $this->db->prepare("select * from tbl_gallery where p_category_id =? order by photo_id desc");
        $statement->execute(array($product_id));
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

}

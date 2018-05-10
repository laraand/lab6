<?php
    function getDatabaseConnection() {
        // $host = "localhost";
        // $username = "alara";
        // $password = "Love@life1";
        // $dbname = "shopping_cart_cst336";
        
        // mysql://bbfd913fa5082f:a62c8534@us-cdbr-iron-east-05.cleardb.net/heroku_bfccc23dd1cae88?reconnect=true
        $host = "us-cdbr-iron-east-05.cleardb.net";
        $username = "bbfd913fa5082f";
        $password = "a62c8534";
        $dbname = "heroku_bfccc23dd1cae88";
        
        
        // Create connection
        $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        return $dbConn; 
    }
    
  // $db = getDatabaseConnection();
  function insertItemsIntoDB($items) {
        if (!$items) return; 
        
        $db = getDatabaseConnection(); 
        
        foreach ($items as $item) {
            $itemName = $item['name']; 
            $itemPrice = $item['salePrice']; 
            $itemImage = $item['thumbnailImage']; 
            $itemId = $item['itemId'];
           
            $sql = "INSERT INTO item (item_id, name, price, image_url) VALUES (NULL, :itemName, :itemPrice, :itemURL)";
            $statement = $db->prepare($sql);
            $statement->execute(array(
                itemName => $itemName,
                itemPrice => $itemPrice,
                itemURL => $itemImage
                ));
        }
  }
    function getMatchingItems($query, $category, $priceFrom, $priceTo, $ordering, $showImages){
        $db = getDatabaseConnection(); 
        $imgSQL =$showImages ? ', item.image_url' : '';  
        
        $sql = "SELECT * FROM item WHERE name LIKE '%$query%'"; 
         
        $statement = $db->prepare($sql); 
        
        $statement->execute(); 
        
        $items = $statement->fetchAll(); 
        return $items;
    
    }
    function getCategoriesHTML() {
        $db = getDatabaseConnection(); 
        $categoriesHTML = "<option value=''></option>";  // User can opt to not select a category 
        
        $sql = "SELECT category_name FROM category"; 
        
        $statement = $db->prepare($sql); 
        
        $statement->execute(); 
        
        $records = $statement->fetchAll(PDO::FETCH_ASSOC); 
        
        foreach ($records as $record) {
            $category = $record['category_name']; 
            $categoriesHTML .= "<option value='$category'>$category</option>"; 
        }
        
        return $categoriesHTML; 
    }
    function addCategoriesForItems($itemStart, $itemEnd, $category_id) {
        $db = getDatabaseConnection(); 
        
        for ($i = $itemStart; $i <= $itemEnd; $i++) {
            $sql = "INSERT INTO item_category (grouping_id, item_id, category_id) VALUES (NULL, '$i', '$category_id')";
            $db->exec($sql);
        }
    }



?>
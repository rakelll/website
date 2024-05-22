<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SESSION['user'] == "") {
    header("location:./login.php");
    exit; // Ensure script stops execution after redirection
}

require_once("connection/connectionConfig.php");

if ($_FILES['items_csvFile']['name']) {
    $filename = explode(".", $_FILES['items_csvFile']['name']);
    if ($filename[1] == 'csv') {
        $file = fopen($_FILES['items_csvFile']['tmp_name'], "r");

        while (!feof($file)) {
            $_line = fgetcsv($file);

            // Skip empty lines and header row
            if (empty($_line) || $_line[0] == "Item_id") {
                continue;
            }

            $sql_stmt = "INSERT INTO `items` (`Item_Code`, `Item_Description`, `Currency_code`, `Item_SellingPrice1`, `Item_SellingPrice2`, `Item_SellingPrice3`, `Item_SellingPrice4`, `Item_SellingPrice5`, `Item_QuantityInitiate`, `Item_ValueInitiate`, `Item_QuantityPurchase`, `Item_ValuePurchase`, `Item_QuantityProdIn`, `Item_ValueProdIn`, `Item_QuantityDelivered`, `Item_QuantitySales`, `Item_ValueSales`, `Item_QuantityProdOUT`, `Item_ValueProdOut`, `Item_Vat`, `Category_code`, `Item_Active`, `Item_SysDateTime`, `Project_Code`, `item_DepositAmt`)
            VALUES (:item_code, :item_description, :currency_code, :selling_price1, :selling_price2, :selling_price3, :selling_price4, :selling_price5, :quantity_initiate, :value_initiate, :quantity_purchase, :value_purchase, :quantity_prod_in, :value_prod_in, :quantity_delivered, :quantity_sales, :value_sales, :quantity_prod_out, :value_prod_out, :item_vat, :category_code, :item_active, NOW(), :project_code, :deposit_amt)";

            $stmt =  $dbh->prepare($sql_stmt);

            $stmt->bindValue(':item_code', $_line[0], PDO::PARAM_INT);
            $stmt->bindValue(':item_description', $_line[1] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':currency_code', $_line[2] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':selling_price1', $_line[3] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':selling_price2', $_line[4] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':selling_price3', $_line[5] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':selling_price4', $_line[6] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':selling_price5', $_line[7] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':quantity_initiate', $_line[8] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':value_initiate', $_line[9] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':quantity_purchase', $_line[10] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':value_purchase', $_line[11] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':quantity_prod_in', $_line[12] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':value_prod_in', $_line[13] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':quantity_delivered', $_line[14] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':quantity_sales', $_line[15] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':value_sales', $_line[16] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':quantity_prod_out', $_line[17] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':value_prod_out', $_line[18] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':item_vat', $_line[19] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':category_code', $_line[20] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':item_active', $_line[21] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':project_code', $_line[22] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':deposit_amt', $_line[23] ?? 0, PDO::PARAM_INT);

            $stmt->execute();
        }

        fclose($file);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; // Ensure script stops execution after redirection
    } else {
        echo "Invalid file format. Please upload a CSV file.";
    }
} else {
    echo "No file uploaded.";
}
?>
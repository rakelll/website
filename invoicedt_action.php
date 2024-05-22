<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SESSION['user'] == "") {
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

if ($_FILES['invoiceDtCsvFile']['name']) {

    $filename = explode(".", $_FILES['invoiceDtCsvFile']['name']);
    if ($filename[1] == 'csv') {

        $file = fopen($_FILES['invoiceDtCsvFile']['tmp_name'], "r");

        while (!feof($file)) {
            $_line = fgetcsv($file);

            if (!isset($_line[0]) || $_line[0] == "InvoiceDt_Id" || empty($_line[0])) {
                continue;
            }

            $sql_stmt = "INSERT INTO `invoicedt` (`InvoiceDt_Id`, `Invoice_Id`, `item_code`, `InvoiceDt_Quantity`, `InvoiceDt_DiscPer`, `InvoiceDt_Discount`, `InvoiceDt_DiscountBase1`, `InvoiceDt_DiscountBase2`, `InvoiceDt_Total`, `InvoiceDt_TotalBase1`, `InvoiceDt_TotalBase2`, `Client_Code`, `Ledger_Number`, `Warehouse_Code`, `InvoiceDt_Description`, `Invoicedt_PriceBase1`, `Invoicedt_PriceBase2`, `item_vat`, `Invoicedt_price`, `Invoicedt_discountper`, `InvoiceDT_Printed`, `Invoicedt_OrderNb`, `Waiter_code`, `invoiceDt_remark`, `size_code`, `color_code`, `Vendor_Code`, `invoicedt_DlvDate`, `Invoicedt_Memo`, `invoicedt_QtyCharge`, `Invoicedt_TotalPrice`, `POSAPPL_ID`, `Invoicedt_Source`, `invoicedt_OrderType`, `I_BranchID`, `project_code`, `invoiceDt_CostBase1`, `invoiceDt_CostBase2`, `Invoicedt_DiscAmt`, `InvDT_ISGift`, `Invdt_Project`, `invDT_Date`, `Item_ForPrint`)
            VALUES (:InvoiceDt_Id, :Invoice_Id, :item_code, :InvoiceDt_Quantity, :InvoiceDt_DiscPer, :InvoiceDt_Discount, :InvoiceDt_DiscountBase1, :InvoiceDt_DiscountBase2, :InvoiceDt_Total, :InvoiceDt_TotalBase1, :InvoiceDt_TotalBase2, :Client_Code, :Ledger_Number, :Warehouse_Code, :InvoiceDt_Description, :Invoicedt_PriceBase1, :Invoicedt_PriceBase2, :item_vat, :Invoicedt_price, :Invoicedt_discountper, :InvoiceDT_Printed, :Invoicedt_OrderNb, :Waiter_code, :invoiceDt_remark, :size_code, :color_code, :Vendor_Code, :invoicedt_DlvDate, :Invoicedt_Memo, :invoicedt_QtyCharge, :Invoicedt_TotalPrice, :POSAPPL_ID, :Invoicedt_Source, :invoicedt_OrderType, :I_BranchID, :project_code, :invoiceDt_CostBase1, :invoiceDt_CostBase2, :Invoicedt_DiscAmt, :InvDT_ISGift, :Invdt_Project, :invDT_Date, :Item_ForPrint)";

            $stmt =  $dbh->prepare($sql_stmt);

            $stmt->bindValue(':InvoiceDt_Id', $_line[0] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoice_Id', $_line[1] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':item_code', $_line[2] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':InvoiceDt_Quantity', $_line[3] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_DiscPer', $_line[4] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_Discount', $_line[5] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_DiscountBase1', $_line[6] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_DiscountBase2', $_line[7] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_Total', $_line[8] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_TotalBase1', $_line[9] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDt_TotalBase2', $_line[10] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Client_Code', $_line[11] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':Ledger_Number', $_line[12] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':Warehouse_Code', $_line[13] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':InvoiceDt_Description', $_line[14] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':Invoicedt_PriceBase1', $_line[15] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_PriceBase2', $_line[16] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':item_vat', $_line[17] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_price', $_line[18] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_discountper', $_line[19] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvoiceDT_Printed', $_line[20] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_OrderNb', $_line[21] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Waiter_code', $_line[22] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':invoiceDt_remark', $_line[23] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':size_code', $_line[24] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':color_code', $_line[25] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':Vendor_Code', $_line[26] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':invoicedt_DlvDate', $_line[27] ?? null, PDO::PARAM_STR); // Assuming it's a string in the format 'YYYY-MM-DD'
            $stmt->bindValue(':Invoicedt_Memo', $_line[28] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':invoicedt_QtyCharge', $_line[29] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_TotalPrice', $_line[30] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':POSAPPL_ID', $_line[31] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_Source', $_line[32] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':invoicedt_OrderType', $_line[33] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':I_BranchID', $_line[34] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':project_code', $_line[35] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':invoiceDt_CostBase1', $_line[36] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':invoiceDt_CostBase2', $_line[37] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invoicedt_DiscAmt', $_line[38] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':InvDT_ISGift', $_line[39] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':Invdt_Project', $_line[40] ?? null, PDO::PARAM_STR);
            $stmt->bindValue(':invDT_Date', $_line[41] ?? null, PDO::PARAM_STR); // Assuming it's a string in the format 'YYYY-MM-DD'
            $stmt->bindValue(':Item_ForPrint', $_line[42] ?? null, PDO::PARAM_INT);

            $stmt->execute();
        }

        fclose($file);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
?>

<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SESSION['user'] == "") {
    header("location:./login.php");
}

require_once("connection/connectionConfig.php");

if ($_FILES['invoice_csvFile']['name']) {

    $filename = explode(".", $_FILES['invoice_csvFile']['name']);
    if ($filename[1] == 'csv') {

        $file = fopen($_FILES['invoice_csvFile']['tmp_name'], "r");

        while (!feof($file)) {
            $_line = fgetcsv($file);

            if (!isset($_line[0]) || $_line[0] == "Invoice_id" || empty($_line[0])) {
                continue;
            }

            $sql_stmt = "INSERT INTO `invoice` (
                `Invoice_id`, `Invoice_Paid`, `Table_code`, `Invoice_Reference`, `Invoice_Date`, `Client_Code`,
                `Waiter_Code`, `Driver_Code`, `Invoice_DeliveredTo`, `Invoice_Remark`, `invoice_totalbase1`, 
                `invoice_totalbase2`, `invoice_discountbase1`, `invoice_discountbase2`, `invoice_cashinbase1`, 
                `invoice_cashinbase2`, `invoice_cashoutbase1`, `invoice_cashoutbase2`, `Invoice_TipsBase1`, 
                `invoice_discountper`, `currency_code`, `invoice_vat`, `invoice_vatbase1`, `invoice_vatbase2`, 
                `CashRegister_Name`, `invoice_taxable`, `invoice_taxablebase1`, `invoice_taxablebase2`, 
                `invoice_nontaxable`, `invoice_nontaxablebase1`, `invoice_nontaxablebase2`, `invoice_nettotal`, 
                `invoice_nettotalbase1`, `Invoice_netTotalbase2`, `invoice_discount`, `user_account`, `delivery_id`, 
                `invoice_Posted`, `invoice_total`, `customer_code`, `invoice_Type`, `Item_VAT`, `invoice_CheckAmt`, 
                `invoice_CheckCur`, `invoice_CheckBank`, `invoice_CheckVDate`, `invoice_CreditAcc`, `invoice_CheckNB`, 
                `invoice_CreditAmount`, `invoice_CreditCur`, `invoice_IsCredit`, `invoice_SettlementDate`, 
                `invoice_IsDownPaymt`, `invoice_Settled`, `Invoice_DPCashInBase1`, `Invoice_DPCashInBase2`, 
                `Invoice_DPCashOUTBase1`, `Invoice_DPCashOUTBase2`, `Invoice_DPTipsBase1`, `invoice_DPCheckAmt`, 
                `invoice_DPCheckCur`, `invoice_DPCheckNB`, `invoice_DPCheckVDate`, `invoice_DPCheckBank`, 
                `invoice_DPCreditAmount`, `DPClient_Code`, `invoice_CouponRef`, `invoice_CouponAmount`, 
                `invoice_CouponUsed`, `invoice_DPCreditCur`, `invoice_DPIsCredit`, `project_code`, `Invoice_GiftAmt`, 
                `Invoice_GiftAmtbase1`, `Invoice_GiftAmtbase2`, `Invoice_GiftCur`, `warehouse_code`, `cust_FidelityCard`, 
                `Invoice_IsFormula`, `invoice_PersonNb`, `invoice_IsExchange`, `invoice_couponSource`, `invoice_couponUSedAmt`, 
                `costcenter_Code`, `Adjustment_ID`, `JournalJC_ID`, `Invoice_BelongsToGroup`, `invoice_CheckAmt1`, 
                `invoice_CheckAmt2`, `invoice_creditamount1`, `invoice_creditamount2`, `invoice_nontaxable1`, 
                `invoice_nontaxable2`, `inv_totalqty`, `Invoice_CouponID`, `Inv_CAN`, `I_BranchID`, `invoice_PendingDate`, 
                `IS_Printed`, `Inv_exchangeID`, `Inv_exchangeAmt`, `journal_Id`, `inv_exchangecost`, `inv_cost`, 
                `inv_temporaryDel`
            ) VALUES (
                :Invoice_id, :Invoice_Paid, :Table_code, :Invoice_Reference, :Invoice_Date, :Client_Code,
                :Waiter_Code, :Driver_Code, :Invoice_DeliveredTo, :Invoice_Remark, :invoice_totalbase1, 
                :invoice_totalbase2, :invoice_discountbase1, :invoice_discountbase2, :invoice_cashinbase1, 
                :invoice_cashinbase2, :invoice_cashoutbase1, :invoice_cashoutbase2, :Invoice_TipsBase1, 
                :invoice_discountper, :currency_code, :invoice_vat, :invoice_vatbase1, :invoice_vatbase2, 
                :CashRegister_Name, :invoice_taxable, :invoice_taxablebase1, :invoice_taxablebase2, 
                :invoice_nontaxable, :invoice_nontaxablebase1, :invoice_nontaxablebase2, :invoice_nettotal, 
                :invoice_nettotalbase1, :Invoice_netTotalbase2, :invoice_discount, :user_account, :delivery_id, 
                :invoice_Posted, :invoice_total, :customer_code, :invoice_Type, :Item_VAT, :invoice_CheckAmt, 
                :invoice_CheckCur, :invoice_CheckBank, :invoice_CheckVDate, :invoice_CreditAcc, :invoice_CheckNB, 
                :invoice_CreditAmount, :invoice_CreditCur, :invoice_IsCredit, :invoice_SettlementDate, 
                :invoice_IsDownPaymt, :invoice_Settled, :Invoice_DPCashInBase1, :Invoice_DPCashInBase2, 
                :Invoice_DPCashOUTBase1, :Invoice_DPCashOUTBase2, :Invoice_DPTipsBase1, :invoice_DPCheckAmt, 
                :invoice_DPCheckCur, :invoice_DPCheckNB, :invoice_DPCheckVDate, :invoice_DPCheckBank, 
                :invoice_DPCreditAmount, :DPClient_Code, :invoice_CouponRef, :invoice_CouponAmount, 
                :invoice_CouponUsed, :invoice_DPCreditCur, :invoice_DPIsCredit, :project_code, :Invoice_GiftAmt, 
                :Invoice_GiftAmtbase1, :Invoice_GiftAmtbase2, :Invoice_GiftCur, :warehouse_code, :cust_FidelityCard, 
                :Invoice_IsFormula, :invoice_PersonNb, :invoice_IsExchange, :invoice_couponSource, :invoice_couponUSedAmt, 
                :costcenter_Code, :Adjustment_ID, :JournalJC_ID, :Invoice_BelongsToGroup, :invoice_CheckAmt1, 
                :invoice_CheckAmt2, :invoice_creditamount1, :invoice_creditamount2, :invoice_nontaxable1, 
                :invoice_nontaxable2, :inv_totalqty, :Invoice_CouponID, :Inv_CAN, :I_BranchID, :invoice_PendingDate, 
                :IS_Printed, :Inv_exchangeID, :Inv_exchangeAmt, :journal_Id, :inv_exchangecost, :inv_cost, 
                :inv_temporaryDel
            )";

            $stmt =  $dbh->prepare($sql_stmt);

            $stmt->bindValue(':Invoice_id', $_line[0] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_Paid', $_line[1] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Table_code', $_line[2] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Invoice_Reference', $_line[3] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_Date', $_line[4] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Client_Code', $_line[5] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Waiter_Code', $_line[6] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Driver_Code', $_line[7] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Invoice_DeliveredTo', $_line[8] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Invoice_Remark', $_line[9] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_totalbase1', $_line[10] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_totalbase2', $_line[11] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_discountbase1', $_line[12] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_discountbase2', $_line[13] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_cashinbase1', $_line[14] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_cashinbase2', $_line[15] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_cashoutbase1', $_line[16] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_cashoutbase2', $_line[17] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_TipsBase1', $_line[18] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_discountper', $_line[19] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':currency_code', $_line[20] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_vat', $_line[21] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_vatbase1', $_line[22] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_vatbase2', $_line[23] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':CashRegister_Name', $_line[24] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_taxable', $_line[25] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_taxablebase1', $_line[26] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_taxablebase2', $_line[27] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nontaxable', $_line[28] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nontaxablebase1', $_line[29] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nontaxablebase2', $_line[30] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nettotal', $_line[31] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nettotalbase1', $_line[32] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_netTotalbase2', $_line[33] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_discount', $_line[34] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':user_account', $_line[35] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':delivery_id', $_line[36] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_Posted', $_line[37] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_total', $_line[38] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':customer_code', $_line[39] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_Type', $_line[40] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Item_VAT', $_line[41] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CheckAmt', $_line[42] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CheckCur', $_line[43] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_CheckBank', $_line[44] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_CheckVDate', $_line[45] ?? null, PDO::PARAM_STR); 
$stmt->bindValue(':invoice_CreditAcc', $_line[46] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_CheckNB', $_line[47] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_CreditAmount', $_line[48] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CreditCur', $_line[49] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_IsCredit', $_line[50] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_SettlementDate', $_line[51] ?? null, PDO::PARAM_STR); 
$stmt->bindValue(':invoice_IsDownPaymt', $_line[52] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_Settled', $_line[53] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_DPCashInBase1', $_line[54] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_DPCashInBase2', $_line[55] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_DPCashOUTBase1', $_line[56] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_DPCashOUTBase2', $_line[57] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_DPTipsBase1', $_line[58] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_DPCheckAmt', $_line[59] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_DPCheckCur', $_line[60] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_DPCheckNB', $_line[61] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_DPCheckVDate', $_line[62] ?? null, PDO::PARAM_STR); 
$stmt->bindValue(':invoice_DPCheckBank', $_line[63] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_DPCreditAmount', $_line[64] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':DPClient_Code', $_line[65] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_CouponRef', $_line[66] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CouponAmount', $_line[67] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CouponUsed', $_line[68] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_DPCreditCur', $_line[69] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_DPIsCredit', $_line[70] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':project_code', $_line[71] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Invoice_GiftAmt', $_line[72] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_GiftAmtbase1', $_line[73] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_GiftAmtbase2', $_line[74] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_GiftCur', $_line[75] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':warehouse_code', $_line[76] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':cust_FidelityCard', $_line[77] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Invoice_IsFormula', $_line[78] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_PersonNb', $_line[79] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_IsExchange', $_line[80] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_couponSource', $_line[81] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':invoice_couponUSedAmt', $_line[82] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':costcenter_Code', $_line[83] ?? null, PDO::PARAM_STR);
$stmt->bindValue(':Adjustment_ID', $_line[84] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':JournalJC_ID', $_line[85] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_BelongsToGroup', $_line[86] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CheckAmt1', $_line[87] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_CheckAmt2', $_line[88] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_creditamount1', $_line[89] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_creditamount2', $_line[90] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nontaxable1', $_line[91] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_nontaxable2', $_line[92] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':inv_totalqty', $_line[93] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Invoice_CouponID', $_line[94] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Inv_CAN', $_line[95] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':I_BranchID', $_line[96] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':invoice_PendingDate', $_line[97] ?? null, PDO::PARAM_STR); 
$stmt->bindValue(':IS_Printed', $_line[98] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Inv_exchangeID', $_line[99] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':Inv_exchangeAmt', $_line[100] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':journal_Id', $_line[101] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':inv_exchangecost', $_line[102] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':inv_cost', $_line[103] ?? null, PDO::PARAM_INT);
$stmt->bindValue(':inv_temporaryDel', $_line[104] ?? null, PDO::PARAM_INT);

            $stmt->execute();
        }

        fclose($file);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
?>

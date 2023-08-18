<?php

namespace App\Query;

class JobType
{
    const Load_Item = "Load_Item";
    const Load_customer = "Load_customer";
    const Load_Item_Inventory = "Load_item_inventory";
    const Load_Item_Services_Inventory = "Load_item_Services_inventory";
    const Load_Item_Non_Inventory = "Load_item_Non_inventory";
    const Load_Item_Other_Charges = "Load_item_Otner_Charges";
    const Load_Item_Group_Data = "Load_item_Group_Data";
    const Load_Item_Inventory_Assembly = "Load_item_Inventory_Assembly";
    const Load_Invoice = "Load_Invoice";
    const Load_Deleted_Invoices = "Load_Deleted_Invoices";
    const Load_SalesRep = "Load_SalesRep";
    const Load_Sales_Receipt_Payment = "Load_Sales_Receipt_Payment";
    const Load_Txn_Deleted = "Load_Txn_Deleted";
    const Load_Credit_Memo = "Load_Credit_Memo";
}

?>

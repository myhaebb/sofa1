<!DOCTYPE html>
<html>
    <head>
        <title>PHP</title>
        <link rel="stylesheet" href="showInfo.css" >
    </head>
        <body>
<?php
include "connect.php";
$Doc_sale_ID = $_GET["id"];

$data = "SELECT `document_header_sale`.Doc_sale_ID , Invoice_cus_ID , Customer_name ,Invoice_date , customers.Customer_ID, Address_ship, Telephone_ship, Email_ship
            FROM customers JOIN `invoice_to_customers` USING(Customer_ID)
            JOIN document_header_sale USING(Doc_sale_ID)
            JOIN `line_item_sales` ON `line_item_sales`.Doc_sale_ID = `document_header_sale`.Ref_ID
            JOIN sofa_finished USING(Sofa_ID)
            WHERE document_header_sale.`Doc_type_ID` = 6
            GROUP BY Ref_ID
            ORDER BY Invoice_cus_ID
        ";
$result4 = mysqli_query($connect, $data);
$row4 = mysqli_fetch_assoc($result4);

$sql= "
    SELECT *
    FROM customers
    join document_header_sale USING (Customer_ID)
    where Doc_sale_ID = $Doc_sale_ID
  ";
$sql1= "SELECT * FROM org_data";
  
$rs = mysqli_query($connect, $sql) or die(mysqli_error($connect));
$rs1 = mysqli_query($connect, $sql1) or die(mysqli_error($connect));
$ds = mysqli_fetch_assoc($rs);
$ds1 = mysqli_fetch_assoc($rs1);
$qa=$ds["Ref_ID"];

$sql2="
        SELECT *
        FROM document_header_sale
        JOIN line_item_sales USING (Doc_sale_ID)
        JOIN sofa_finished USING (Sofa_ID)
        WHERE Doc_sale_ID = '$qa' 
              ";
$rs2 = mysqli_query($connect, $sql2) or die(mysqli_error($connect));
$ds2 = mysqli_fetch_assoc($rs2);
?>

<!--================ Web Page =================-->

<body>
    <br><br>
    <div class="showInfo">
        <center>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="img/images.png" style="width:200px; height: :200px;">
                            </td>
                            
                            <td class="r">
                                <br>
                                <h1>INVOICE</h1>
                                <br>
                                <b>Invoice ID :</b>IN#00<?=$row4["Invoice_cus_ID"]?><br>
                                <b>Sale Order ID :</b>SO#00<?=$row4["Doc_sale_ID"]?><br>
                                <b>Created :</b> <?=$row4["Invoice_date"]?><br>
                                <b>Customer ID :</b> <?=$row4["Customer_ID"]?>
                            </td>
                        </tr>
                </td>
            </tr>

            </table>
            
            <br>            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>

                             <td id="Address">
                                <?=$ds1["Org_name"]?><br>
                                <?=$ds1["Address"]?>,<?=$ds1["City"]?>,<?=$ds1["Country"]?><br>
                                <?=$ds1["Email"]?><br>
                                Tel. <?=$ds1["Telephone"]?>
                            </td>
                            
                        
                        </tr>
                </td>
            </tr>
            </table>
            <br>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>

                            <td id="Address">
                                <b>Bill To :</b><br>
                                <?=$row4["Customer_name"]?><br>
                                <?=$row4["Address_ship"]?><br>
                                <?=$row4["Email_ship"]?><br>
                                Tel. <?=$row4["Telephone_ship"]?>
                            </td>

                             <td id="Address">
                                <b>Ship To :</b><br>
                                <?=$row4["Customer_name"]?><br>
                                <?=$row4["Address_ship"]?><br>
                                <?=$row4["Email_ship"]?><br>
                                Tel. <?=$row4["Telephone_ship"]?>
                            </td>
                            
                        
                        </tr>
                </td>
            </tr>
            </table>
            <br>

            <table id="t03" border="1" bordercolor="#ddd">
            <tr class="heading">
                <th>
                    Payment Term
                </th>
                <th class="r">
                    Ship Term
                </th>
                 <th class="r">
                    Delivery Date
                </th>
                
            </tr>
            
            <tr class="details">
                <td>
                    <?=$ds["Payment_terms"]?>
                </td>
                <td class="r">
                    <?=$ds["Ship_term"]?>
                </td>
                <td class="r">
                    <?=$ds["Delivery_date"]?>
                </td>
            </tr>
            </table>

            <br>


            <table cellpadding="0" cellspacing="0" id="t04" border="1" bordercolor="#ddd">
            <tr class="heading" style="text-align: center;">
                <th>Sofa ID</th>
                <th>Sofa Name</th>
                <th>Sofa Description</th>
                <th>Quantity</th> 
                <th>Price</th>
                <th>Discount</th>
                <th>Total</th>
            </tr>
            
             <?php
            include "connect.php";
            $mat=0;
            $cal=0;
            $sql4="
                SELECT *
                FROM document_header_sale
                JOIN line_item_sales USING (Doc_sale_ID)
                JOIN sofa_finished USING (Sofa_ID)
                WHERE Doc_sale_ID = '$qa'
                
              ";
              $tempName = array();
              $result= mysqli_query($connect, $sql4)
                or die(mysqli_error($connect));
              $c=mysqli_num_rows($result);  
                if(mysqli_num_rows($result))
            {
              while($row = mysqli_fetch_assoc($result))
              {
                $adis=$row["Total_price"];
                $tt='-';
                if($row["Condition_ID"]==2){
                    $temp=0;
                    $adis=0;
                    $tt=$row["Total_price"]*0.05;
                    $adis=$row["Total_price"]-$tt;
                    $mat=1;
                    array_push($tempName, $row["Sofa_name"]);
                }
                
?>

            <tr class="item">
                <td><?=$row["Sofa_ID"]?></td>
                <td><?=$row["Sofa_name"]?></td>
                <td><?=$row["Sofa_Des"]?></td>
                <td><?=$row["Quatity"]?></td>
                <td><?=$row["PricePerUnit"]?></td>
                <td><?=$tt?></td>
                <td><?=$adis?></td>
            </tr>

            <?php 
                $cal+=$adis;

            ?>
            
<?php
              }
            }else{
              "<tr><td colspan='3'>data not found</td></tr>";
            }
            mysqli_close($connect);
            
            ?>

        </table>
           <br>
            <div class="tableSum">
            <table id="t05" border="1" bordercolor="#ddd">

            <tr>
                <th class="sum">Subtotal</th>
                <td class="sum1 r"><?=$cal?></td>
            </tr>
             <?php
            
                    if($ds["Cus_type"]==2){
                            $discount="Discount 10%";
                            $dis=$cal*0.1;
                            $cal-=$dis;
                    }else if($ds["Cus_type"]==1){
                            $discount="Discount 0%";
                            $dis=0;
                            $cal-=$dis;
                     }   
                ?>
                    
            <tr>
                <th class="sum"><?=$discount?></th>
                <td class="sum1 r"><?=$dis?></td>
            </tr>

            <tr>
                <th class="sum">TAX 7%</th>
                <td class="sum1 r">INCLUDE</td>
            </tr>

            <tr>
                <th class="sum">Shipping Fees</th>
                <td class="sum1 r">INCLUDE</td>
            </tr>

            <tr>
                <th class="sum">GRAND Total</th>               
                <td class="sum1 r"><?=$cal?></td>
            </tr>

            </table>
            <br>
            
                <?php
                 if($ds["Cus_type"]==2){
                           echo "Our company give you 10% for Member"; 
                    } 
                if($mat==1){
                    echo "<br>Our company give you 5% of each Material";
                }

                     ?>
                <br>
            Our company give you 10% per order <br>



          <br><br>
      
        </center>
    </div><br><br>
</body>
</html>
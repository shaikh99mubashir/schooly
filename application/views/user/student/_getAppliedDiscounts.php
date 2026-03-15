<style>
         .d-flex {
             display: flex;
         }

         .justify-content-between {
             justify-content: space-between;
         }

         .align-items-center {
             align-items: center;
         }
         .checkbox-fees{
            
            
            padding: 5px 0px 0px 1px;
         }
     </style>
<div class="row">

    <?php 
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line("date");?></th>
                <th><?php echo $this->lang->line("payment_id");?></th>
                <th><?php echo $this->lang->line("fees_discount");?></th>
                <th class="float-end"><?php echo $this->lang->line("value");?></th>
            </tr>
        </thead>
    <?php
    $count=1;
        if(!empty($fees_discount)){
  foreach ($fees_discount as $discount_index => $discount_value) {
      ?>
<tr>
<th>
   <?php echo $count;?>
  </th>  
    <th>
    <strong><?php echo $this->customlib->dateformat($discount_value->date);?></strong>

  </th>  
  
    <th>
        <strong><?php echo $discount_value->invoice_id."/".$discount_value->sub_invoice_id;?></strong>

  </th>     
    <th>
        <strong><?php echo $discount_value->name;?></strong>

  </th>     
   <td>

   <div class="float-end"><?php echo ($discount_value->type == "fix") ? $currency_symbol.(amountFormat($discount_value->amount)) : ($discount_value->percentage)."%";?></div>
   </td>
                            
</tr>
      <?php 
      $count++;
}

}else{
        echo "<tr> <td colspan = '5' class='text-danger'><center>".$this->lang->line('no_record_found')."</center></td>   </tr>";
    }
?>
</table>
<?php
?>
</div>
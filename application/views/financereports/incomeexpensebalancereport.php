<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php echo $this->lang->line('income_expense_balance_report'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>   
                    <div class="">
                        <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
						
						<form role="form" action="<?php echo site_url('financereports/incomeexpensebalancereport') ?>" method="post" class="">
							<div class="box-body row">
								<?php echo $this->customlib->getCSRF(); ?>
								<div class="col-sm-6 col-md-3" >
									<div class="form-group">
										<label><?php echo $this->lang->line('search_type'); ?><small class="req"> *</small></label>
										<select class="form-control" name="search_type" onchange="showdate(this.value)">

											<?php foreach ($searchlist as $key => $search) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {

                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                                <?php } ?>
										</select>
										<span class="text-danger"><?php echo form_error('search_type'); ?></span>
									</div>
								</div>
								<div id='date_result'>

								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
									</div>
								</div>
							</div>
						</form>					
						
                        <div class="">
							<div class="box-header ptbnull"></div>
							<div class="box-header ptbnull">
								<h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('income_expense_balance_report'); ?></h3>
							</div>
							<div class="box-body table-responsive">
								<div class="download_label"> <?php echo $this->lang->line('income_expense_balance_report'); ?></div>
								<table class="table table-striped table-bordered table-hover example"  data-export-title="<?php echo $this->lang->line('income_expense_balance_report');?>">
									<thead>
										<tr>
											<th><?php echo $this->lang->line('date'); ?></th>
											<th><?php echo $this->lang->line('name'); ?></th>
											<th><?php echo $this->lang->line('income_expense_head'); ?></th>
											<th width="20%"><?php echo $this->lang->line('description'); ?></th>
											<th class="text-right" width="12%"><?php echo $this->lang->line('income_money_in')." (".$currency_symbol.")"; ?></th>
											<th class="text-right" width="12%"><?php echo $this->lang->line('expense_money_out')." (".$currency_symbol.")"; ?></th>
											<th class="text-right" width="12%"><?php echo $this->lang->line('overall_balance')." (".$currency_symbol.")"; ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
                                    
										if(!empty($incomeexpensebalancereport)){$balance =0;$income =0;$expenses =0;
										foreach ($incomeexpensebalancereport as $key => $value) {
                                        ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                            <td><?php echo $value['name']; ?></td>
                                            <td><?php echo $value['category']; ?></td>
                                            <td><?php echo $value['note']; ?></td>
                                            <td class="text-right"><?php if($value['source'] == 'income'){ echo $value['amount']; $balance +=$value['amount']; $income += $value['amount'];}?></td>
                                            <td class="text-right"><?php if($value['source'] == 'expenses'){ echo $value['amount']; $balance -=$value['amount']; $expenses += $value['amount'];}?></td>
                                            <th class="text-right"><?php echo $balance; ?></th> 
                                        </tr>
                                        <?php                                       
										}
										}
										?>                                     
									</tbody>
									<?php if(!empty($incomeexpensebalancereport)){ ?>
                                    <tr>
										<td><b> </b></td>
										<td><b> </b></td>
										<td><b> </b></td>
										<td><b><?php echo $this->lang->line('total'); ?></b></td>									
										<td class="text-right"><b><?php echo $currency_symbol.$income; ?></b></td>
										<td class="text-right"><b><?php echo $currency_symbol.$expenses; ?></b></td>
										<td class="text-right"><b><?php echo $currency_symbol.$balance; ?></b></td>
									</tr>
									<?php } ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>   
		</div>  
	</section>
</div>
<script>
<?php
if ($search_type == 'period') {
    ?>

        $(document).ready(function () {
            showdate('period');
        });

    <?php
}
?>

</script>
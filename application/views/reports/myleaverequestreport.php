<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
    .text-left{text-align: left !important;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-bus"></i></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('reports/_human_resource'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('report/myleaverequestreport') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('from_date'); ?></label>
                                    <input type="text" name="from_date" value="<?php echo set_value('from_date', $from_date); ?>" class="form-control date">

                                    <span class="text-danger"><?php echo form_error('from_date'); ?></span>
                                </div>
                            </div>
							<div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('to_date'); ?></label>
                                    <input type="text" name="to_date" value="<?php echo set_value('to_date', $to_date); ?>" class="form-control date">

                                    <span class="text-danger"><?php echo form_error('to_date'); ?></span>
                                </div>
                            </div>

                            <?php
                            $selected_staff_name = set_value('staff_name', $staff_name);
                            $selected_leave_status = set_value('leave_status', $leave_status);
                            ?>                        

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('status'); ?></label>
                                    <select class="form-control" id="leave_status" name="leave_status">
                                      <option value=""><?php echo $this->lang->line('select');?></option>
                                        <?php 
                                        foreach($status as $key=>$svalue){ ?>
                                            <option value="<?php echo $svalue;?>"  <?php echo ($selected_leave_status == $svalue) ? 'selected' : ''; ?>><?php echo $svalue;?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('leave_status'); ?></span>
                                </div>
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
                            <h3 class="box-title titlefix"><i class="fa fa-money"></i> <?php echo $this->lang->line('my_leave_request_report'); ?></h3>
                        </div>
                        <div class="box-body table-responsive">
                            
                            <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('my_leave_request_report'); ?>">
                                <thead>
									<tr>
                                        <th><?php echo $this->lang->line('staff'); ?></th>
                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <th><?php echo $this->lang->line('half_day'); ?></th>
                                        <th><?php echo $this->lang->line('apply_date'); ?></th>
                                        <th><?php echo $this->lang->line('leave_date'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
									</tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($resultlist as $key => $value) { ?>
                                                <tr>
                                                    <td><?php echo $value['name'] . " " . $value['surname'] . ' (' . $value['employee_id'] . ')'; ?></td>
                                                    <td><?php echo $value["type"] ?></td>
                                                    <td><?php echo $this->lang->line($value["half_day_leave"]); ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value["date"]));  ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value["leave_from"])) ?> - <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value["leave_to"])) ?></td>

                                                    <td><?php echo $value["leave_days"]; ?></td>
                                                  
                                                    <?php
                                                        if ($value["status"] == "approved") {
                                                            $status1 = 'approved';
                                                            $label = "class='label label-success'";
                                                        } else if ($value["status"] == "pending") {
                                                            $status1 = 'pending';
                                                            $label = "class='label label-warning'";
                                                        } else if ($value["status"] == "disapprove" || $value["status"] == "disapproved") {
                                                            $status1 = 'disapproved';
                                                            $label = "class='label label-danger'";
                                                        }
                                                    ?>
                                                    <td><small <?php echo $label ?>><?php echo $this->lang->line($status1); ?></span>
                                                    </td> 
                                                </tr>
                                                <?php 
                                                $i++;
                                            } ?>
                                        </tbody>
                                    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>  
</section>
</div> 
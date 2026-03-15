<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
				<?php if ($this->rbac->hasPrivilege('holiday_type', $can_add_edit)) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div>
						<form id="form1" action="<?php echo site_url('admin/holiday/add_holiday_type') ?>"  name="add_holiday_form" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {?>
                                <?php  echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req">*</small>
                                    <input autofocus="" id="type"  name="type" placeholder="" type="text" class="form-control"  
                                    value="<?php if(isset($result)){ echo $result[0]["type"]; } ?>" />
                                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                                    <input autofocus="" id="id"  name="id" placeholder="" type="hidden" class="form-control"
                                    value="<?php if (isset($result)) { echo $result[0]["id"]; } ?>" />
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
				<?php } ?>
                <div class="col-md-<?php if ($this->rbac->hasPrivilege('holiday_type', $can_add_edit) ) { echo 8; }else{ echo 12; } ?>">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('holiday_type'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('holiday_type'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('holiday_type');?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($holiday_type as $key=>$value){  ?>
                                    <tr>
                                        <td class="mailbox-name"> <?php if($value['is_default']!=1){ echo $value['type']; } else { echo $this->lang->line(strtolower($value['type'])); } ?></td>
                                        <td class="mailbox-date pull-right no-print white-space-nowrap">
                                        <?php if($value['is_default']!=1){ ?>
											
											<?php if ($this->rbac->hasPrivilege('holiday_type', 'can_edit')) { ?>
												<a href="<?php echo base_url(); ?>admin/holiday/editholidaytype/<?php echo $value['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
											<?php } if ($this->rbac->hasPrivilege('holiday_type', 'can_delete')) { ?>
												<a href="<?php echo base_url(); ?>admin/holiday/delete_holiday_type/<?php echo $value['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";><i class="fa fa-remove"></i></a>
											<?php } ?>
											
                                        <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>                                       
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


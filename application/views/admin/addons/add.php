<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php //if ($this->rbac->hasPrivilege('restore', 'can_view')) { ?>
                <div class="col-md-12 col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('upload_your_addon'); ?></h3>
                        </div>
                        <form role="form" action="<?php echo site_url('admin/addons/add') ?>" method="post" enctype="multipart/form-data" id="local_form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="box-body">
                                <input class="filestyle form-control" data-height="30" type="file" name="file" id="exampleInputFile">
                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                            </div>
                            <div class="box-footer">
                                <button class="btn btn-primary btn-sm pull-right" type="submit" name="backup" value="upload"><i class="fa fa-upload"></i> <?php echo $this->lang->line('upload'); ?></button>
                            </div>
                        </form>
                    </div>

                </div><!--./col-md-4-->
            <?php
            // }
            ?>

        </div>
    </section>
</div>
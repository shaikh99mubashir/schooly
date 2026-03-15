<div class="row d-flex flex-wrap">
    <?php
    if (!empty($all_contents)) {
        foreach ($all_contents as $content_key => $content_value) {
    ?>
            <div class="col-md-4 col-lg-4 col-sm-6 d-flex align-items-stretch mb10">
                <div class="card addon-services text-sm-center">
                    <div class="card-body">
                        <div class="row m-b-30">
                            <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
                                <div class="new-arrival-product mb-sm-1">
                                    <div class="new-arrivals-img-contnent">
                                        <img class="img-fluid" src="<?php echo base_url($content_value->image); ?>" alt="">
										<?php if (!IsNullOrEmptyString($content_value->current_version)) { ?>
                                        <h6 class="mb5">Version <span class="price-num ps-1"><?php echo $content_value->current_version; ?></span></h6>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 col-lg-9 col-sm-12 col-xs-12">
                                <div class="mt-n1-1 addon-card">
                                    <div class="addon-card-body">
                                    <div class="position-relative d-flex align-items-center justify-sm-center">
                                        <div>
                                            <a href="javascript:void(0)">
                                                <h5 class="bmedium mt-lg-0 mt-sm-0 mb5 mt-sm-2"><?php echo $content_value->name; ?></h5>
                                            </a>
                                        </div>
                                    </div>
                                 </div>     
                                    <p class="ellipsis max-lines"><?php echo $content_value->description; ?></p>
                                  
                                    
                                </div>    
                            </div>
                        </div>
                        <div class="rate2">
                                        <div class="d-flex align-items-start mb-xl-3 mb-2">
                                            <div class="ms-auto mr-sm-auto">
                                                <?php

                                                $check_folder_exists = FCPATH . "./addons/" . $content_value->directory;
                                                if (!is_dir($check_folder_exists)) {
                                                ?>
                                                    <a target="_blank" href="<?php echo $content_value->article_link ; ?>" class="btn btn-primary btn-xs "><?php echo $this->lang->line('buy_now');?></a>
                                                <?php
                                                } elseif (is_dir($check_folder_exists) && IsNullOrEmptyString($content_value->current_version)) {
                                                ?>
                                                    <button type="button" data-product-id="<?php echo $content_value->product_id; ?>" data-directory="<?php echo $content_value->directory; ?>" class="btn btn-primary  btn-xs install" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('install');?></button>

                                                    <?php
                                                } elseif (is_dir($check_folder_exists) && !IsNullOrEmptyString($content_value->current_version) && !IsNullOrEmptyString($content_value->update_version)) {
                                                    $version = $this->config->item('addon_ver', $content_value->config_name);

                                                  if (!$this->auth->addonchk($content_value->short_name, false)) {
                                                    ?>
                                                        <a href="#" class="btn btn btn-primary btn-xs" data-addon-version="<?php echo $version; ?>" data-addon="<?php echo $content_value->short_name;?>" data-toggle="modal" data-target="#addonModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> <?php echo $this->lang->line('register');?></a>
                                                        <button type="button" data-product-id="<?php echo $content_value->product_id; ?>" data-directory="<?php echo $content_value->directory; ?>" class="btn btn-danger btn-xs uninstall" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('unistall'); ?></button>
                                                    <?php
                                                   } else {
                                                    ?>
                                                 
                                                        <button type="button" class="btn btn btn-primary  btn-xs" data-addon-name="<?php echo $content_value->short_name;?>" data-product-id="<?php echo $content_value->product_id; ?>" data-directory="<?php echo $content_value->directory; ?>"  data-toggle="modal" data-target="#addonUpdateModal"><?php echo $this->lang->line('update'); ?></button>

                                                        <button type="button" data-product-id="<?php echo $content_value->product_id; ?>" data-directory="<?php echo $content_value->directory; ?>" class="btn btn-danger btn-xs uninstall" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('unistall'); ?></button>
                                                        
                                                    <?php
                                                    }
                                                    ?>
                                                    <?php
                                                } elseif (is_dir($check_folder_exists) && !IsNullOrEmptyString($content_value->current_version) && IsNullOrEmptyString($content_value->update_version)) {
                                                    $version = $this->config->item('addon_ver', $content_value->config_name);
                                                   if (!$this->auth->addonchk($content_value->short_name, false)) {
                                                    ?>
                                                        <a href="#" class="btn btn btn-primary btn-xs" data-addon-version="<?php echo $version; ?>" data-addon="<?php echo $content_value->short_name;?>" data-toggle="modal" data-target="#addonModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> <?php echo $this->lang->line('register');?></a>
                                                        <button type="button" data-product-id="<?php echo $content_value->product_id; ?>" data-directory="<?php echo $content_value->directory; ?>" class="btn btn-danger  btn-xs uninstall" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('unistall'); ?></button>
                                                    <?php
                                                   } else {
                                                    ?>
                                                        <button type="button" data-product-id="<?php echo $content_value->product_id; ?>" data-directory="<?php echo $content_value->directory; ?>" class="btn btn-danger btn-xs uninstall" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('unistall'); ?></button>
                                                <?php
                                                   }
                                              }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                    </div>
                </div>
            </div>

        <?php

        }
    } else {
        ?>
        <div class="col-12 col-sm-6 col-md-12">
            <div class="alert alert-info">
                <?php echo $this->lang->line('no_record_found'); ?>
            </div>
        </div>
    <?php
    }
    ?>
</div>
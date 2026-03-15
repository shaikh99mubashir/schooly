<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/idcard.css">
<?php if ($this->customlib->getRTL() != "") { ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/idcard-rtl.css" />
<?php } ?>
<?php
$school = $sch_setting[0];
$i = 0;

?>
<?php 
if($id_card[0]->enable_vertical_card)
{
?>


<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <?php
foreach ($staffs as $staff_key => $staff_value) {
        $i++;
        ?>
            <td valign="top" class="width32">
            <table cellpadding="0" cellspacing="0" width="100%" style="background: <?php echo $id_card[0]->header_color; ?>;">
       <tr>
        <td valign="top" style="text-align: center;color: #fff;padding: 5px 5px;min-height: 98px;display: block; text-align: center">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                   <td valign="top">
                        <div style="color: #fff;position: relative; z-index: 1; text-align: center;vertical-align: top">
                            <div class="sttext1" style="font-size: 16px;line-height: 8px;">
								<?php if($id_card[0]->logo){ ?>
								<img style="vertical-align: middle; width: 30px;" src="<?php echo base_url('uploads/staff_id_card/logo/' . $id_card[0]->logo); ?>" width="30" height="30"> 
								<?php } ?>
								<?php echo $id_card[0]->school_name; ?>
                          </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="color: #fff;text-align: center;"><?php echo $id_card[0]->school_address; ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" style="background: #fff">
           <table cellpadding="0" cellspacing="0" width="100%" style="margin-top: -50px; position: relative;z-index: 1;">
               <tr>
                    <td valign="top">
                        <div class="stimg center-block">
                            <img src="<?php
if (!empty($staff_value->image)) {
            echo base_url() . "uploads/staff_images/" . $staff_value->image;
        } else {

            if ($staff_value->gender == 'Female') {
                echo base_url() . "uploads/staff_images/default_female.jpg";
            } elseif ($staff_value->gender == 'Male') {
                echo base_url() . "uploads/staff_images/default_male.jpg";
            }

        }
        ?>" class="img-responsive img-circle block-center" style="border-radius: 8px; border:3px solid <?php echo $id_card[0]->header_color; ?>">
                        </div>

                    </td>
                </tr>
                <tr>
                    <td valign="top" style="text-align: center;">
                        <h4 style="margin:0; text-transform: uppercase;font-weight: bold; margin-top: 10px;"><?php echo $staff_value->name . " " . $staff_value->surname; ?></h4>

                           <?php if ($id_card[0]->enable_designation == 1) {
            ?>
                             <p style="font-size: 15px;color: #9b1818;"><?php echo $staff_value->designation; ?></p>
                        <?php
}
        ?>
                    </td>
                </tr>
           </table>
        </td>
    </tr>
    <tr>
        <td valign="top" >
            <table cellpadding="0" cellpadding="0" width="90%" align="center" style="background: #fff;padding: 5px 5px;display: block;width: 90%;margin:0 auto">
                <tr>
                    <td valign="top">
                        <ul class="vertlist">
                                        <?php if ($id_card[0]->enable_staff_id == 1) {?><li><?php echo $this->lang->line('staff_id'); ?><span> <?php echo $staff_value->employee_id; ?></span></li><?php }?>

                                        <?php if ($id_card[0]->enable_staff_department == 1) {?><li><?php echo $this->lang->line('department'); ?><span> <?php echo $staff_value->department; ?></span></li><?php }?>
                                         <?php if ($id_card[0]->enable_fathers_name == 1) {?><li><?php echo $this->lang->line('father_name'); ?><span><?php echo $staff_value->father_name; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_mothers_name == 1) {?><li><?php echo $this->lang->line('mother_name'); ?><span><?php echo $staff_value->mother_name; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_date_of_joining == 1) {?><li><?php echo $this->lang->line('date_of_joining'); ?><span><?php if (!empty($staff_value->date_of_joining) && $staff_value->date_of_joining != '0000-00-00') {echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($staff_value->date_of_joining));}?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_permanent_address == 1) {?><li class="stred"><?php echo $this->lang->line('address'); ?><span><?php echo $staff_value->local_address; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_staff_phone == 1) {?><li><?php echo $this->lang->line('phone'); ?><span><?php echo $staff_value->contact_no; ?></span></li><?php }?>
                                        <?php
if ($id_card[0]->enable_staff_dob == 1) {
            ?>
                                            <li><?php echo $this->lang->line('date_of_birth'); ?>
                                                <span>
                                                    <?php
echo $dob = "";
            if ($staff_value->dob != "0000-00-00") {
                $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($staff_value->dob));
            }
            echo $dob;
            ?>
                                                </span></li>
                                            <?php
}
        ?>
                        </ul>
						
						<?php if($id_card[0]->sign_image){ ?>
                        <div class="signature"><img src="<?php echo base_url('uploads/staff_id_card/signature/' . $id_card[0]->sign_image); ?>" width="150" height="24" style="width: 150px; height: 24px;" /></div>
						<?php } ?>

                            <?php if ($id_card[0]->enable_staff_barcode == 1) {
?> <div class="signature">
<img src="<?php echo $this->media_storage->getImageURL($staff_value->barcode); ?>" style="max-width: 80px; max-height:80px;"/>
</div>
<?php

                            }?>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
            </td>
            <?php
if ($i == 3) {
            // three items in a row. Edit this to get more or less items on a row
            ?></tr><tr><?php
$i = 0;
        }
    }
    ?>
    </tr>
</table>
<?php
} else {
    ?>
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <?php
foreach ($staffs as $staff_key => $staff_value) {
        $i++;
        ?>
            <td valign="top" class="width32">
                <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #efefef;">
                    <?php if($id_card[0]->background){ ?>
					<tr>
                        <td valign="top"><img src="<?php echo base_url('uploads/staff_id_card/background/' . $id_card[0]->background); ?>" class="tcmybg" /></td>
                    </tr>
					<?php } ?>
                    <tr>
                        <td valign="top">
                            <div class="studenttop" style="overflow: visible; background: <?php echo $id_card[0]->header_color ?>">
                                <div class="sttext1">
									<?php if($id_card[0]->logo){ ?>
									<img src="<?php echo base_url('uploads/staff_id_card/logo/' . $id_card[0]->logo); ?>" width="30" height="30" />
									<?php } ?>
                                    <?php echo $id_card[0]->school_name ?>
								</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="center" style="padding: 1px 0; position: relative; z-index: 1">
                            <p>  <?php echo $id_card[0]->school_address ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="color: #fff;font-size: 16px; text-align:center; padding: 2px 0 0; position: relative; z-index: 1;background: <?php echo $id_card[0]->header_color ?>;text-transform: uppercase;"><?php echo $id_card[0]->title ?></td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <div class="staround" style="overflow: visible;">
                                <div class="cardleft">
                                    <div class="stimg">

                                     <img src="<?php
if (!empty($staff_value->image)) {
            echo base_url() . "uploads/staff_images/" . $staff_value->image;
        } else {

            if ($staff_value->gender == 'Female') {
                echo base_url() . "uploads/staff_images/default_female.jpg";
            } elseif ($staff_value->gender == 'Male') {
                echo base_url() . "uploads/staff_images/default_male.jpg";
            }

        }
        ?>" class="img-responsive" />
        
        <?php if ($id_card[0]->enable_staff_barcode == 1) {?>
                               
                                <div class="barcodeimg center-block" style="width: 90%;margin:0 auto"><img src="<?php echo $this->media_storage->getImageURL($staff_value->barcode); ?>" style="max-width: 65px; margin: 0 auto; height:auto;"/></div>
                           <?php }?>

                                    </div>
                                </div><!--./cardleft-->
                                <div class="cardright">
                                    <ul class="stlist">
                                        <?php if ($id_card[0]->enable_name == 1) {?><li><?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('name'); ?><span> <?php echo $staff_value->name; ?> <?php echo $staff_value->surname; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_staff_id == 1) {?><li><?php echo $this->lang->line('staff_id'); ?><span> <?php echo $staff_value->employee_id; ?></span></li><?php }?>
                                         <?php if ($id_card[0]->enable_designation == 1) {?><li><?php echo $this->lang->line('designation'); ?><span><?php echo $staff_value->designation; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_staff_department == 1) {?><li><?php echo $this->lang->line('department'); ?><span> <?php echo $staff_value->department; ?></span></li><?php }?>
                                         <?php if ($id_card[0]->enable_fathers_name == 1) {?><li><?php echo $this->lang->line('father_name'); ?><span><?php echo $staff_value->father_name; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_mothers_name == 1) {?><li><?php echo $this->lang->line('mother_name'); ?><span><?php echo $staff_value->mother_name; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_date_of_joining == 1) {?><li><?php echo $this->lang->line('date_of_joining'); ?><span><?php if (!empty($staff_value->date_of_joining) && $staff_value->date_of_joining != '0000-00-00') {echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($staff_value->date_of_joining));}?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_permanent_address == 1) {?><li class="stred"><?php echo $this->lang->line('address'); ?><span><?php echo $staff_value->local_address; ?></span></li><?php }?>
                                        <?php if ($id_card[0]->enable_staff_phone == 1) {?><li><?php echo $this->lang->line('phone'); ?><span><?php echo $staff_value->contact_no; ?></span></li><?php }?>
                                        <?php
if ($id_card[0]->enable_staff_dob == 1) {
            ?>
                                            <li><?php echo $this->lang->line('date_of_birth'); ?>
                                                <span>
                                                    <?php
echo $dob = "";
            if ($staff_value->dob != "0000-00-00") {
                $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateYYYYMMDDtoStrtotime($staff_value->dob));
            }
            echo $dob;
            ?>
                                                </span></li>
                                            <?php
}
        ?>
                                    </ul>
                                </div><!--./cardright-->
                            </div><!--./staround-->
                        </td>
                    </tr>
                    <tr><td><p class="height-60"></p></td></tr>
					<?php if($id_card[0]->sign_image){ ?>
                    <tr>
                        <td valign="top" class="principal"><img src="<?php echo base_url('uploads/staff_id_card/signature/' . $id_card[0]->sign_image); ?>" width="66" height="40" /></td>
                    </tr>
					<?php } ?>
                </table>
            </td>
            <?php
if ($i == 3) {
            // three items in a row. Edit this to get more or less items on a row
            ?></tr><tr><?php
$i = 0;
        }
    }
    ?>
    </tr>
</table>
<?php
}
?>

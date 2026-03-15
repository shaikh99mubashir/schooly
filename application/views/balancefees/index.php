<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
 
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i><small></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php $this->load->view('financereports/_finance'); ?> 
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('balancefees/index') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                      <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                                <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($section_list as $value) {
                                                ?>
                                                <option  <?php
                                                if ($value['section_id'] == $section_id) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $value['section_id']; ?>"><?php echo $value['section']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <input type="hidden" id="search_type" name="search_type" value="all">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>   </div>
                    </form>
                    <div class="row">
                        <?php
                        if (isset($student_due_fee) && !empty($student_due_fee)) { ?>
                            <div class="" id="transfee">
                                <div class="box-header ptbnull">
                                    <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('due_fees_report'); ?></h3>
                                </div>                              
                                <div class="box-body table-responsive"  id="headerTable_dataa">
                                    <div class="download_label"><?php echo $this->lang->line('due_fees_report'); ?>
                                    <?php  $this->customlib->get_postmessage(); ?></div> 
                                    <div class='d-flex gap-0-5 text-right float-right'>
                                    <a class="btn btn-primary btn-xs pull-right" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line("print"); ?>" id="print" onclick="printDiv()" ><i class="fa fa-print"></i></a> 
                                    <button class="btn btn-primary btn-xs pull-right"  data-toggle="tooltip" data-original-title="<?php echo $this->lang->line("download_excel"); ?>"  id="btnExport" onclick="fnExcelReport_new();"> <i class="fa fa-file-excel-o"></i> </button>  
                                </div>
                                    <table class="table table-striped table-hover" id="headerTable">
                                        <thead>
                                            <tr id="transfee_header">
                                                <th class="text text-left check_btn"><input type="checkbox" id="checkAll" name="select_all"></th>
                                                <th class="text text-left"><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('student_name'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('father_name'); ?></th>
                                                <th class="text text-left"><?php echo $this->lang->line('class'); ?></th>
                                                <th class="text-right"><?php echo $this->lang->line('due_amount'); ?></th>      
                                                <th class="text-right"><?php echo $this->lang->line('total_due_amount'); ?></th>      
                                                <th class="text text-right"><?php echo $this->lang->line('mobile_no'); ?></th>
                                            </tr>
                                        </thead>  
                                        <tbody> 
                                            <?php
                                            if (!empty($resultarray)) {
                                                        $totalfeelabel = 0;
                                                        $depositfeelabel = 0;
                                                        $discountlabel = 0;
                                                        $finelabel = 0;
                                                        $balancelabel = 0; 

                                                        $final_total_amount=0 ;                                     
                                                        $final_fine_amount=0 ;
                                                        $final_total_due_amount=0;  

                                                    foreach($resultarray as $key => $section) {                                                   
                                                        foreach ($section['result'] as $students) {                                                            
                                                            $totalfeelabel += $students->totalfee;
                                                            $depositfeelabel += $students->deposit;
                                                            $discountlabel += $students->discount;
                                                            $finelabel += $students->fine;
                                                            $balancelabel += $students->balance;

                                                            $total_due_amount = 0;
                                                            $total_due_amount = $students->balance+$students->grand_fine_amount;
                                                            if($total_due_amount > 0){
                                                        ?>                                            
                                                      <tr>
                                                            <th class="text text-left check_btn"><input type="checkbox"  class="check_box_btn" id="select_all" name="select_all"></th>
                                                            <td><?php echo $students->admission_no;?></td>
                                                            <td><?php echo $students->name;?></td>
                                                            <?php  if ($sch_setting->father_name) { ?>
                                                            <td><?php echo $students->father_name;?></td>
                                                            <?php } ?>
                                                            <td><?php echo $students->class." (".$students->section.")";?></td>
                                                            <td class="text text-right"><?php 
                                                            $final_total_amount+=$students->balance;
                                                            $final_fine_amount+=$students->grand_fine_amount;

                                                            echo $currency_symbol.amountFormat($students->balance)."<span data-toggle='popover' class='text-danger'    data-content=' <span class=\"text-danger\">".$this->lang->line('fine')."</span>'> + ".$currency_symbol.amountFormat(($students->grand_fine_amount))."</span>";
                                                          
                                                            ?></td>
                                                            <td class="text text-right"><?php 
                                                            $final_total_due_amount+=($students->balance+$students->grand_fine_amount);
                                                            echo $currency_symbol.amountFormat($total_due_amount);
                                                            ?></td>

                                                         <td class="text text-right"><?php echo $students->mobileno;?></td>
                                                        </tr>
                                                            <?php
                                                        } }
                                                            ?>
                                                               <tr class="box box-solid total-bg">
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td><?php echo $this->lang->line('total'); ?></td>
                                                               
                                                                <td class="text text-right"><?php echo $currency_symbol.amountFormat($final_total_amount)."<span  class='text-danger'  data-toggle='popover'  data-content='<span class=\"text-danger\">".$this->lang->line('fine')."</span>'> + ".$currency_symbol.amountFormat(($final_fine_amount))."</span>"; ?>                                                              

                                                                </td>

                                                                <td class="text text-right"><?php echo $currency_symbol.amountFormat($final_total_due_amount); ?></td>
                                                                <td></td>
                                                            </tr>
                                                        <?php                                
                                                          } ?>
                                            </tbody> 
                                        </table>
                                    </div>                            
                                </div>  
 <?php
                        }
                    }else{ 
?>
                            <div class="col-md-12" ><div class="col-md-12" ><div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div></div></div>
                            
                    <?php } ?>                                
                            </div>
                </div>
            </div>
    </section>
</div>


<script type="text/javascript">
    function removeElement() {
        document.getElementById("imgbox1").style.display = "block";
    }
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').html(div_data);
                }
            });
        }
    }
    $(document).ready(function () {
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });

                    $('#section_id').html(div_data);
                }
            });
        });
        $(document).on('change', '#section_id', function (e) {
            getStudentsByClassAndSection();
        });
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
    });
    function getStudentsByClassAndSection() {
        $('#student_id').html("");
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var base_url = '<?php echo base_url() ?>';
        var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
        $.ajax({
            type: "GET",
            url: base_url + "student/getByClassAndSection",
            data: {'class_id': class_id, 'section_id': section_id},
            dataType: "json",
            success: function (data) {
                $.each(data, function (i, obj)
                {
                    div_data += "<option value=" + obj.id + ">" + obj.firstname + " " + obj.lastname + "</option>";
                });
                $('#student_id').append(div_data);
            }
        });
    }

    $(document).ready(function () {
        $.extend($.fn.dataTable.defaults, {
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });
</script>

<script>
$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
</script>

<script>
    document.getElementById("print").style.display = "block";
    document.getElementById("btnExport").style.display = "block";

     function printDiv() {
        document.getElementById("print").style.display = "none";
        document.getElementById("btnExport").style.display = "none";
        document.querySelector(".check_btn").style.display = "none";
        var divElements = "";
        var divElements = document.getElementById('transfee_header').innerHTML;
        $('table input:checked.check_box_btn').each(function() {
            $(".check_btn").hide();
            divElements += "<tr>";
            divElements += $(this).closest('tr').html();
            divElements += "</tr>";
        });

        if($('table input:checked.check_box_btn').length==0){
            $(".check_btn").hide();
            var divElements = document.getElementById('headerTable').innerHTML;
        }
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
                "<html><head><title></title></head><body><center><h5><b><?php echo $this->lang->line('due_fees_report'); ?></b></h5></center><table class='table table-striped table-hover'>"+ divElements+"</table></body>";
        window.print();
        document.body.innerHTML = oldPage;
        location.reload(true);
    }    

    function fnExcelReport_new()
    {
        var textRange;
        var j = 0;
        tab = document.getElementById('headerTable'); 
        var divElements = document.getElementById('transfee_header').innerHTML;
        var tab_text = "<table border='2px'>"+divElements+"<tr >";

        $('table input:checked.check_box_btn').each(function() {
            var rowIndex = parseInt($(this).closest('tr').index())+parseInt(1);
            tab_text = tab_text + tab.rows[rowIndex].innerHTML + "</tr>";
        });

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        $("#visible").removeClass("hide");
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        $("#visible").addClass("hide");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
        } else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return (sa);
    }
</script>
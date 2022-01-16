<?php
//echo $test_id;
$test_details = $this->crud_model->get_test_by_id($test_id)->row_array();

if($test_details['test_date'] !='' && $test_details['test_date'] !='0000-00-00' ){
    $testDate = $test_details['test_date'];
   $test_date_edit = date("m/d/Y", strtotime($testDate));
}else{
    $test_date_edit = '';
}

?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('Update') . ': ' . $test_details['test_name']; ?></h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="header-title my-1"><?php echo get_phrase('test_manager'); ?></h4>
                    </div>
                    <div class="col-md-6">
                        
                        <a href="<?php echo site_url('admin/test_master'); ?>" class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm my-1"> <i class=" mdi mdi-keyboard-backspace"></i> <?php echo get_phrase('back_to_test_list'); ?></a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <form class="required-form" action="<?php echo site_url('admin/test_actions/edit/' . $test_id); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div id="basicwizard">
                               
                            <div class="row justify-content-center">
                                <div class="col-xl-8">
                                   
                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_name"><?php echo get_phrase('test_name'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="test_name" name="test_name" placeholder="<?php echo get_phrase('enter_test_name'); ?>" value="<?=$test_details['test_name'];?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_date"><?php echo get_phrase('test_date'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="test_date" name="test_date" placeholder="<?php echo 'MM/DD/YYYY'; ?>" value="<?=$test_date_edit ?>" autocomplete="off" readonly required>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_stream"><?php echo get_phrase('test_stream'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                            <select required="required" name="test_stream" id="test_stream" class="custom-select">
                                          <option value="" >Select Stream</option>
                                           <?php
                                            foreach ($this->user_model->get_stream()->result() as $row)
                                            {    
                                                $select_active = '';
                                                if ($test_details['test_stream'] == $row->id){
                                                    $select_active = 'selected="select"';
                                                }
                                              echo '<option value="'.$row->id.'" '.$select_active.'>'.$row->stream_name.'</option>';
                                            }
                                            ?>
                                      </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_subject"><?php echo get_phrase('test_subject'); ?> <span class="required"></span> </label>
                                        <div class="col-md-10">
                                           

                                        <select required="required" name="test_subject" id="test_subject" class="custom-select">
                                            <option value="">Select Subject</option>
                                               <?php
                                                foreach ($this->crud_model->get_subject_master()->result_array() as $row)
                                                {    

                                                    $select_active = '';
                                                    if ($test_details['test_subject'] == $row['id']){
                                                        $select_active = 'selected="select"';
                                                    }

                                                  echo '<option value="'.$row['id'].'" '.$select_active.' >'.$row['subject_name'].'</option>';
                                                }
                                                ?>
                                        </select>


                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_total_marks"><?php echo get_phrase('test_total_marks'); ?> <span class="required"></span> </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="test_total_marks" name="test_total_marks" placeholder="<?php echo get_phrase('enter_test_mark'); ?>" value="<?=$test_details['test_total_marks'];?>">
                                        </div>
                                    </div>

                                    <div class="mb-3 mt-3">
                                        <button type="button" class="btn btn-primary text-center" onclick="checkRequiredFields()"><?php echo get_phrase('submit'); ?></button>
                                      </div>

                                </div> <!-- tab-content -->
                            </div> <!-- end #progressbarwizard-->
                        </form>
                    </div>
                </div><!-- end row-->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>

<script type="text/javascript">
$(function() {
    $("#test_date").datepicker({ dateFormat: "yy-mm-dd" }).val()
});

    $(document).ready(function() {
        initSummerNote(['#description']);
        togglePriceFields('is_free_course');
    });
</script>

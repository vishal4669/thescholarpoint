<?php
$subject_details = $this->crud_model->get_subject_by_id($subject_id)->row_array();

?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('Update') . ': ' . $subject_details['subject_name']; ?></h4>
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
                        <h4 class="header-title my-1"><?php echo get_phrase('subject_manager'); ?></h4>
                    </div>
                    <div class="col-md-6">
                        
                        <a href="<?php echo site_url('admin/subject_master'); ?>" class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm my-1"> <i class=" mdi mdi-keyboard-backspace"></i> <?php echo get_phrase('back_to_subject_list'); ?></a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <form class="required-form" action="<?php echo site_url('admin/subject_actions/edit/' . $subject_id); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div id="basicwizard">
                               
                            <div class="row justify-content-center">
                                <div class="col-xl-9">
                                   
                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="subject_name"><?php echo get_phrase('subject_name'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="<?php echo get_phrase('enter_subject_name'); ?>" value="<?=$subject_details['subject_name'];?>" required>
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

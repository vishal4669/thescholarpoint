<?php
    $details = $this->crud_model->get_student_test_by_id($student_test_id)->row_array();
?>
<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php
                $student_name = $this->user_model->get_user( $details['student_id'])->result_array();
                echo get_phrase('Update') . ': ' . $student_name[0]['first_name'].' '.$student_name[0]['last_name']; ?></h4>
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
                        <h4 class="header-title my-1"><?php echo get_phrase('student_test_manager'); ?></h4>
                    </div>
                    <div class="col-md-6">
                        
                        <a href="<?php echo site_url('admin/student_test_master'); ?>" class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm my-1"> <i class=" mdi mdi-keyboard-backspace"></i> <?php echo get_phrase('back_to_student_test_list'); ?></a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <form class="required-form" action="<?php echo site_url('admin/student_test_actions/edit/' . $student_test_id); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                            <div id="basicwizard">
                               
                            <div class="row justify-content-center">
                                <div class="col-xl-10">
                                   
                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_stream"><?php echo get_phrase('select_stream'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                        <select required="required" name="stream_id" id="stream_id" class="custom-select" onchange="get_students(this.value);">
                                          <option value="">Select Stream</option>
                                           <?php
                                            foreach ($this->user_model->get_stream()->result() as $row)
                                            {  
                                                $select_active = '';
                                                if ($details['stream_id'] == $row->id){
                                                    $select_active = 'selected="select"';
                                                }
                                              echo '<option value="'.$row->id.'" '.$select_active.'>'.$row->stream_name.'</option>';
                                            }
                                            ?>
                                      </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="student_id"><?php echo get_phrase('select_student'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                        <select required="required" name="student_id" id="student_id" class="custom-select">
                                          <option value="">Select Student</option>
                                         </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="test_id"><?php echo get_phrase('select_test'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                            <select required="required" name="test_id" id="test_id" class="custom-select">
                                            <option value="">Select Test</option>
                                               <?php
                                               if( count($get_test_master->result()) > 0 ){
                                                    foreach ($get_test_master->result() as $row)
                                                    {    
                                                        $select_active = '';
                                                        if ($details['test_id'] == $row->id){
                                                            $select_active = 'selected="select"';
                                                        }
                                                      echo '<option value="'.$row->id.'" '.$select_active.'>'.$row->test_name.'</option>';
                                                    }
                                                }
                                                ?>
                                        </select>
                                        </div>
                                    </div>
                            
                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="marks"><?php echo get_phrase('marks'); ?> <span class="required">*</span> </label>
                                        <div class="col-md-10">
                                            <input type="text" class="form-control" id="marks" name="marks" placeholder="<?php echo get_phrase('enter_test_mark'); ?>" value="<?php echo $details['marks'];?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3">
                                        <label class="col-md-2 col-form-label" for="comment"><?php echo get_phrase('comments'); ?> <span class="required"></span> </label>
                                        <div class="col-md-10">
                                            <textarea name="comment" id="comment" class="form-control"><?php echo $details['comment'];?></textarea>
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
    $(document).ready(function() {
           get_students(<?php echo $details['stream_id'];?>,<?php echo $details['student_id'];?>);
    });
</script>

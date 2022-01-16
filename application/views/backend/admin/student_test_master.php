<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i>Student Test Master
                    <a href="<?php echo site_url('admin/student_test_form/student_test_master_add'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo "Add New Student Test"; ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('student_test_list'); ?></h4>            

                <div class="table-responsive-sm mt-4">
                    <?php 
                    //echo count(($student_test_master->result()));
                    //print_r($student_test_master->result_array());

                    if (count($student_test_master->result_array()) > 0) : ?>
                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo get_phrase('stream_name'); ?></th>
                                    <th><?php echo get_phrase('student'); ?></th>
                                    <th><?php echo get_phrase('test'); ?></th>
                                    <th><?php echo get_phrase('marks'); ?></th>
                                    <th><?php echo get_phrase('comment'); ?></th>
                                    <th><?php echo get_phrase('actions'); ?></th>
                                </tr>
                            </thead>

                           <?php
                            foreach ($student_test_master->result_array() as $key => $data) : 

                                $edit_url = site_url('admin/student_test_form/student_test_master_edit/'.$data['id']);
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php $stream_list = $this->user_model->get_stream($data['stream_id'])->result_array();
                                        echo $stream_list[0]['stream_name'] ?></td>
                                <td><?php
                                        $student_name = $this->user_model->get_user($data['student_id'])->result_array();
                                        echo $student_name[0]['first_name'].' '.$student_name[0]['last_name'];
                                ?></td>
                                <td><?php 
                                    $test_info = $this->crud_model->get_test_by_id($data['test_id'])->result_array();
                                    echo $test_info[0]['test_name'];
                                ?></td>
                                <td><?php echo $data['marks']; ?></td>
                                <td><?php echo $data['comment'];?></td>

                                <td><a class="dropdown-item" href=<?=$edit_url;?> ><?php echo get_phrase('edit'); ?></a>
                                    <a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('admin/student_test_actions/delete/' . $data['id']); ?>');"><?php echo get_phrase('delete'); ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>

                    <?php if (count($student_test_master->result_array()) == 0) : ?>
                        <div class="img-fluid w-100 text-center">
                            <img style="opacity: 1; width: 100px;" src="<?php echo base_url('assets/backend/images/file-search.svg'); ?>"><br>
                            <?php echo get_phrase('no_data_found'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
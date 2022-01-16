<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i>Test Master
                    <a href="<?php echo site_url('admin/test_form/add_test_master'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo "Add New Test"; ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('test_list'); ?></h4>            

                <div class="table-responsive-sm mt-4">
                    <?php 
                    //echo count(($test_master->result()));
                    //print_r($test_master->result_array());

                    if (count($test_master->result_array()) > 0) : ?>
                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo get_phrase('test_name'); ?></th>
                                    <th><?php echo get_phrase('test_date'); ?></th>
                                    <th><?php echo get_phrase('test_stream'); ?></th>
                                    <th><?php echo get_phrase('test_subject'); ?></th>
                                    <th><?php echo get_phrase('test_total_marks'); ?></th>
                                    
                                    <th><?php echo get_phrase('actions'); ?></th>
                                </tr>
                            </thead>

                           <?php
                            foreach ($test_master->result_array() as $key => $data) : 

                                $edit_url = site_url('admin/test_form/edit_test_master/'.$data['id']);
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?=$data['test_name']; ?></td>
                                <td><?php
                                        if($data['test_date'] !='' && $data['test_date'] !='0000-00-00' ){
                                            $testDate = $data['test_date'];
                                           $test_date_display = date("m/d/Y", strtotime($testDate));
                                        }else{
                                            $test_date_display = '00/00/0000';
                                        }
                                        echo $test_date_display;
                                ?></td>
                                <td><?php $stream = $this->user_model->get_stream($data['test_stream'])->result_array(); echo $stream[0]['stream_name'];?></td>
                                <td><?php
                                if($data['test_subject'] !='' && $data['test_subject'] >0) {
                                    $test_subject_name = $this->crud_model->get_subject_master($data['test_subject'])->result_array();
                                       echo $test_subject_name[0]['subject_name'];
                                }else{
                                        echo "-";

                                }
                                 ?></td>
                                <td><?php echo $data['test_total_marks'];?></td>
                                <td><a class="dropdown-item" href=<?=$edit_url;?> ><?php echo get_phrase('edit'); ?></a>
                                    <a class="dropdown-item" href="#" onclick="confirm_modal('<?php echo site_url('admin/test_actions/delete/' . $data['id']); ?>');"><?php echo get_phrase('delete'); ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>

                    <?php if (count($test_master->result_array()) == 0) : ?>
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
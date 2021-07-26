<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i>Subject Master
                    <a href="<?php echo site_url('admin/subject_form/subject_master_add'); ?>" class="btn btn-outline-primary btn-rounded alignToTitle"><i class="mdi mdi-plus"></i><?php echo "Add New Subject"; ?></a>
                </h4>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('subject_list'); ?></h4>            

                <div class="table-responsive-sm mt-4">
                    <?php 
                    //echo count(($subject_master->result()));
                    //print_r($test_master->result_array());

                    if (count($subject_master->result_array()) > 0) : ?>
                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap" width="100%" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo get_phrase('subject_name'); ?></th>
                                    <th><?php echo get_phrase('actions'); ?></th>
                                </tr>
                            </thead>

                           <?php
                            foreach ($subject_master->result_array() as $key => $data) : 

                                $edit_url = site_url('admin/subject_form/subject_master_edit/'.$data['id']);
                            ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?=$data['subject_name']; ?></td>
                                <td><a class="dropdown-item"  href=<?=$edit_url;?> ><?php echo get_phrase('edit'); ?></a> <a class="dropdown-item"  href="#" onclick="confirm_modal('<?php echo site_url('admin/subject_actions/delete/' . $data['id']); ?>');"><?php echo get_phrase('delete'); ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>

                    <?php if (count($subject_master->result_array()) == 0) : ?>
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
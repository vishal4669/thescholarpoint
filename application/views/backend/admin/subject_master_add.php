<div class="row ">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title"> <i class="mdi mdi-apple-keyboard-command title_icon"></i> <?php echo get_phrase('add_new_subject'); ?></h4>
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
                        <h4 class="header-title my-1"><?php echo get_phrase('subject_adding_form'); ?></h4>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo site_url('admin/subject_master'); ?>" class="alignToTitle btn btn-outline-secondary btn-rounded btn-sm my-1"> <i class=" mdi mdi-keyboard-backspace"></i> <?php echo get_phrase('back_to_subject_list'); ?></a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <form class="required-form" action="<?php echo site_url('admin/subject_actions/add'); ?>" method="post" enctype="multipart/form-data">
                            <div id="basicwizard">
                        
                            <div class="row justify-content-center">
                                <div class="col-xl-10">
                                   
                                    <div class="form-group row mb-9">
                                        <label class="col-md-2 col-form-label" for="subject_name"><?php echo get_phrase('subject_name'); ?> <span class="required">*</span> </label>
                                        
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="<?php echo get_phrase('enter_subject_name'); ?>" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 mt-3">
                                        <button type="button" class="btn btn-primary text-center" onclick="checkRequiredFields()"><?php echo get_phrase('submit'); ?></button>
                                    </div>

                            </div> <!-- end col -->
                              

                            </div> <!-- tab-content -->
                        </div> <!-- end #progressbarwizard-->
                    </form>
                </div>
            </div><!-- end row-->
        </div> <!-- end card-body-->
    </div> <!-- end card-->
</div>
</div>

<style media="screen">
body {
  overflow-x: hidden;
}
</style>

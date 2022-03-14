
<?php include "profile_menus.php"; ?>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3 header-title"><?php echo get_phrase('my_referrals'); ?></h4>
                <div class="table-responsive-sm mt-4">
                    <table id="basic-datatable" class="table table-striped table-centered mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo get_phrase('name'); ?></th>
                                <th><?php echo get_phrase('email'); ?></th>
                                <th><?php echo get_phrase('mobile'); ?></th>
                                <th><?php echo get_phrase('registered_date'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($users->result_array() as $key => $user) : ?>
                                <tr>
                                    <td><?php echo $key + 1; ?></td>
                                    
                                    <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                        
                                    </td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['mobile']; ?></td>
                                    <td><?php echo date('d-M-Y', $user['date_added']); ?></td>

                                   
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
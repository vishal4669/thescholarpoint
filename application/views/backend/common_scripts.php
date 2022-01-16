<script type="text/javascript">
  function togglePriceFields(elem) {
    if($("#"+elem).is(':checked')){
      $('.paid-course-stuffs').slideUp();
    }else
      $('.paid-course-stuffs').slideDown();
    }
</script>

<?php if ($page_name == 'courses-server-side'): ?>
  <script type="text/javascript">
  jQuery(document).ready(function($) {
        $.fn.dataTable.ext.errMode = 'throw';
        $('#course-datatable-server-side').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": "<?php echo site_url(strtolower($this->session->userdata('role')).'/get_courses') ?>",
                "dataType": "json",
                "type": "POST",
                "data" : {selected_category_id : '<?php echo $selected_category_id; ?>',
                          selected_status : '<?php echo $selected_status ?>',
                          selected_instructor_id : '<?php echo $selected_instructor_id ?>',
                          selected_price : '<?php echo $selected_price ?>'}
            },
            "columns": [
                { "data": "#" },
                { "data": "title" },
                { "data": "category" },
                { "data": "lesson_and_section" },
                { "data": "enrolled_student" },
                { "data": "status" },
                { "data": "price" },
                { "data": "actions" },
            ],
            "columnDefs": [{
                targets: "_all",
                orderable: false
             }]
        });
    });
  </script>
<?php endif; ?>

<?php if ($page_name == 'student_test_master_add' || $page_name == 'student_test_master_edit' ): ?>
<script type="text/javascript">
function get_students(stream_id, student_id = ''){
   $.ajax({
           type: "POST",
           url: "<?php echo site_url(strtolower($this->session->userdata('role')).'/ajax_student_test_master') ?>",
           data: {action:'get_students', current_stream_id :stream_id, selected_student_id:student_id }, 
           beforeSend: function(){
               //$("#loader").show();
            },
           success: function(data)
           {
              //console.log(data);
              var $student_list = $('#student_id');
              $student_list.empty();
              $student_list.append(data);  
                
           },
            complete: function(){
                //Completed console.

            }
         });

}

  </script>
<?php endif; ?>



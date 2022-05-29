<section class="category-course-list-area">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="sign-up-form">
          <div class="row mb-4 mt-3">
            <div class="col-md-12 text-center">
              <h1 class="fw-700"><?php echo site_phrase('account_verification'); ?></h1>
              <p class="text-14px"><?php echo site_phrase('let_us_know_that_this_account_belongs_to_you.'); ?> <?php echo site_phrase('Enter_the_otp_code_to_get_on_your_mobile'); ?></p>
            </div>
          </div>
          <form action="javascript:;" method="post" id="email_verification">
            <div class="form-group">                

                <input type="hidden" id="mobile" name="mobile" value="+91<?php echo $mobile = $this->session->userdata('mobile'); ?>">               
                <div id="recaptcha-container"></div>
              <label for="verification_code"><?php echo site_phrase('verification_code'); ?></label>
              <div class="input-group">
                <span class="input-group-text bg-white" for="verification_code"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" placeholder="<?php echo site_phrase('enter_the_otp_verification_code'); ?>" aria-label="<?php echo site_phrase('verification_code'); ?>" aria-describedby="<?php echo site_phrase('verification_code'); ?>" id="verification_code" required>
              </div>

              <a href="javascript:;" class="text-14px fw-500 text-muted" id="resend_mail_button" onclick="phoneAuth()">
                <div class="float-start"><?php echo 'Send OTP'; ?></div>
                <div id="resend_mail_loader" class="float-start ps-1"></div>
              </a>

            </div>
            <div class="form-group">
              <button type="button" onclick="codeverify()" class="btn red radius-10 mt-4 w-100"><?php echo site_phrase('continue'); ?></button>
            </div>

            <div class="form-group mt-4 mb-0 text-center">
              <?php echo site_phrase('want_to_go_back'); ?>?
              <a class="text-15px fw-700" href="<?php echo site_url('home/login') ?>"><?php echo site_phrase('login'); ?></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://www.gstatic.com/firebasejs/8.3.1/firebase.js"></script>
<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
    apiKey: "AIzaSyDDe4WkSypbCwbIyiNrMfGBBBr8eS5z78M",
    authDomain: "thescholarpoint-92c1e.firebaseapp.com",
    projectId: "thescholarpoint-92c1e",
    storageBucket: "thescholarpoint-92c1e.appspot.com",
    messagingSenderId: "529681315992",
    appId: "1:529681315992:web:d5e50d8650c5d5e68838ed",
    measurementId: "G-SXPCG676DZ"
    };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    //firebase.analytics();
</script>

<script type="text/javascript">

/*******FireBase Js*********************************************/
window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        'size': 'invisible',
        'callback': function(response) {           
            console.log('recaptcha resolved');
            
        }
    });

$(document).ready(function() {
    phoneAuth();
});

function phoneAuth() {
    //get the number
    var number = document.getElementById('mobile').value;
    //it takes two parameter first one is number and second one is recaptcha
    firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function(confirmationResult) {
        //s is in lowercase
        window.confirmationResult = confirmationResult;
        coderesult = confirmationResult;
        console.log(coderesult);
        toastr.success('<?php echo site_phrase('Otp_successfully_sent_on_your_mobile');?>');

    }).catch(function(error) {
        //alert(error.message);
        toastr.error(error.message);

    });
}

function codeverify() {
    var code = document.getElementById('verification_code').value;
    var email = '<?= $this->session->userdata('register_email'); ?>';

    coderesult.confirm(code).then(function(result) {
        console.log("Successfully registered");
        var user = result.user;
        console.log(user);

         $.ajax({
          type: 'post',
          url: '<?php echo site_url('login/verify_email_address/'); ?>',
          data: {verification_code : code, email : email},
          success: function(response){
            if(response){
              console.log(response);
              window.location.replace(response);
            }else{
              location.reload();
            }
          }
        });

    }).catch(function(error) {
        alert(error.message);
    });

}


</script>

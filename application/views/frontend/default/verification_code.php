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


              <div id="recaptcha-container"></div>
                                        <div class="form-group mt-4">
                                            <input type="text" name="" id="codeToVerify" name="getcode"
                                                class="form-control" placejolder="Enter Code" placeholder="OTP">
                                                
                                            <Input type="hidden" class="form-control" name="phone_no" id="number"
                                                placeholder="Ph Number(code) *************"
                                                value="{{ $user_phone }}"></Input>
                                            <a href="#" id="getcode" class="link-success">Resend</a>
                                            <br>
                                            <br>
                                                <button type="submit"
                                                class="btn btn-fill-out btn-block hover-up font-weight-bold" id="verifPhNum">Verify OTP</button>
                                        </div>


              <label for="verification_code"><?php echo site_phrase('verification_code'); ?></label>
              <div class="input-group">
                <span class="input-group-text bg-white" for="verification_code"><i class="fas fa-user"></i></span>

                <div id="recaptcha-container"></div>


                <input type="text" class="form-control" placeholder="<?php echo site_phrase('enter_the_verification_code'); ?>" aria-label="<?php echo site_phrase('verification_code'); ?>" aria-describedby="<?php echo site_phrase('verification_code'); ?>" id="verification_code" required>
              </div>
              <a href="javascript:;" class="text-14px fw-500 text-muted" id="resend_mail_button" onclick="resend_verification_code()">
                <div class="float-start"><?= site_phrase('resend_otp') ?></div>
                <div id="resend_mail_loader" class="float-start ps-1"></div>
              </a>
            </div>

            <div class="form-group">
              <button type="button" onclick="continue_verify()" class="btn red radius-10 mt-4 w-100"><?php echo site_phrase('continue'); ?></button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.0.1/firebase.js"></script>

<script type="text/javascript">

 // Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";

  function continue_verify() {
    var email = '<?= $this->session->userdata('register_email'); ?>';
    var verification_code = $('#verification_code').val();
    $.ajax({
      type: 'post',
      url: '<?php echo site_url('login/verify_email_address/'); ?>',
      data: {verification_code : verification_code, email : email},
      success: function(response){
        if(response){
          window.location.replace('<?= site_url('home/login'); ?>');
        }else{
          location.reload();
        }
      }
    });
  }
  
  function resend_verification_code() {
    $("#resend_mail_loader").html('<img src="<?= base_url('assets/global/gif/page-loader-3.gif'); ?>" style="width: 25px;">');
    var email = '<?= $this->session->userdata('register_email'); ?>';
    $.ajax({
      type: 'post',
      url: '<?php echo site_url('login/resend_verification_code/'); ?>',
      data: {email : email},
      success: function(response){
        toastr.success('<?php echo site_phrase('wait_for_20_to_25_sec_and_otp_successfully_sent_on_your_mobile');?>');
        $("#resend_mail_loader").html('');
      }
    });
  }



  $(document).ready(function() {
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyDDe4WkSypbCwbIyiNrMfGBBBr8eS5z78M",
        authDomain: "thescholarpoint-92c1e.firebaseapp.com",
        projectId: "thescholarpoint-92c1e",
        storageBucket: "thescholarpoint-92c1e.appspot.com",
        messagingSenderId: "529681315992",
        appId: "1:529681315992:web:d5e50d8650c5d5e68838ed",
        measurementId: "G-SXPCG676DZ"
    };

    // Initialize Firebase
    const app = firebase.initializeApp(firebaseConfig);

    console.log('Initialised'+app);
    

    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        'size': 'invisible',
        'callback': function(response) {
            // reCAPTCHA solved, allow signInWithPhoneNumber.
            console.log('recaptcha resolved');
        }
    });

    //send otp
    // code here
    var phoneNo = '9377073717';
    console.log(phoneNo);
    // getCode(phoneNo);
    var appVerifier = window.recaptchaVerifier;

    firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
        .then(function(confirmationResult) {

            window.confirmationResult = confirmationResult;
            coderesult = confirmationResult;
            console.log(coderesult);
        }).catch(function(error) {
            console.log(error.message);

        });

    //send otp
    $('#getcode').on('click', function () {

        var phoneNo = '9377073717';

        //console.log(phoneNo);
        // getCode(phoneNo);
        var appVerifier = window.recaptchaVerifier;

      console.log("App Verified::"+appVerifier);

        firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
        .then(function (confirmationResult) {

          console.log(confirmationResult);

            window.confirmationResult=confirmationResult;

            coderesult=confirmationResult;

            console.log(coderesult);

        }).catch(function (error) {
            console.log(error.message);

        });
    });

});

</script>

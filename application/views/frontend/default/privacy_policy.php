<section class="category-header-area" style="
    
    background-image: url('<?= base_url("uploads/page_banners/privacy_policy.jpg"); ?>');
    ">
    <div class="image-placeholder-1"></div>
    <div class="container-lg breadcrumb-container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item display-6 fw-bold">
               &nbsp;
            </li>
            <li class="breadcrumb-item active text-light display-6 fw-bold">
                &nbsp;
            </li>
          </ol>
        </nav>
    </div>
</section>

<section class="category-course-list-area">
    <div class="container">
        <div class="row">
            <div class="col" style="padding: 35px;">
                <?php echo get_frontend_settings('privacy_policy'); ?>
            </div>
        </div>
    </div>
</section>

<style type="text/css">
.breadcrumb .breadcrumb-item.active, .breadcrumb-item+.breadcrumb-item.active::before {
    color: #D1D2D3 !important;
} 
.breadcrumb-item+.breadcrumb-item::before {
    content:none;
} 
</style>

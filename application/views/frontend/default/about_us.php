<section class="category-header-area" style="
    
    background-image: url('<?= base_url("uploads/page_banners/about_us.jpg"); ?>');
    ">

    <div class="image-placeholder-1"></div>
    <div class="container-lg breadcrumb-container">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item display-6 fw-bold">
                <a href="<?php echo site_url('home'); ?>">
                    <?php//echo site_phrase('home'); ?>
                </a>
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
                <?php echo get_frontend_settings('about_us'); ?>
            </div>
        </div>
    </div>
</section>


<style type="text/css">
.breadcrumb-item+.breadcrumb-item::before {
    content:none;
} 
</style>



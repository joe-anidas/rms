<?php if (isset($validation_errors) && !empty($validation_errors)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Please correct the following errors:</strong>
    </div>
    
    <ul class="mb-0 ps-3">
        <?php if (is_array($validation_errors)): ?>
            <?php foreach ($validation_errors as $field => $error): ?>
                <li><?php echo is_array($error) ? implode(', ', $error) : $error; ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><?php echo $validation_errors; ?></li>
        <?php endif; ?>
    </ul>
    
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('validation_errors')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center mb-2">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Please correct the following errors:</strong>
    </div>
    
    <ul class="mb-0 ps-3">
        <?php 
        $flash_errors = $this->session->flashdata('validation_errors');
        if (is_array($flash_errors)): 
        ?>
            <?php foreach ($flash_errors as $field => $error): ?>
                <li><?php echo is_array($error) ? implode(', ', $error) : $error; ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><?php echo $flash_errors; ?></li>
        <?php endif; ?>
    </ul>
    
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('error_message')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span><?php echo $this->session->flashdata('error_message'); ?></span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('success_message')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-check-circle me-2"></i>
        <span><?php echo $this->session->flashdata('success_message'); ?></span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('warning_message')): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span><?php echo $this->session->flashdata('warning_message'); ?></span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($this->session->flashdata('info_message')): ?>
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle me-2"></i>
        <span><?php echo $this->session->flashdata('info_message'); ?></span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
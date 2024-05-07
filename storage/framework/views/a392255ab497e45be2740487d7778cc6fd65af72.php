<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>World Clock Map- Admin</title>
    <?php echo $__env->make('admin.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body class="toggle-sidebar">
    <?php echo $__env->make('admin.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div id="app">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php echo $__env->make('admin.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH /home3/cyblasjd/wixwebsitesbuilder.com/world-map-clock/resources/views/admin/layouts/default.blade.php ENDPATH**/ ?>
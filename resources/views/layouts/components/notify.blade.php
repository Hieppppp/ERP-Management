<script src="{{ asset('assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script>
    function notification(type, message) {
        if (type == 'success') {
            return $.growl.notice({
                title: trans('message.success'),
                message: message
            });
        }

        if (type == 'error') {
            return $.growl.error({
                title: trans('message.error'),
                message: message
            });
        }
    }
</script>

<?php if (session('success')): ?>
<script>
    $(function (e) {
        notification('success', '<?php echo session('success'); ?>');
    });
</script>
<?php endif ?>

<?php if (session('error')): ?>
<script>
    $(function (e) {
        notification('error', '<?php echo session('error'); ?>');
    });
</script>
<?php endif ?>

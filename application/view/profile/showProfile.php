<div class="container">
        <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <?php if ($this->user) { ?>
        <div>
            <table class="overview-table">
                <thead>
                <tr>
                    <td>gebruikersnaam</td>
                </tr>
                </thead>
                <tbody>
                    <tr class="<?= ($this->user->user_active == 0 ? 'inactive' : 'active'); ?>">
                        <td><?= $this->user->user_name; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

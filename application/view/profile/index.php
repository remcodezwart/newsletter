<div class="container">
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <table class="overview-table">
        <thead>
        <tr>
            <td>gebruikersnaam</td>
            <td>Link to user's profile</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->users as $user) { ?>
            <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                <td><?= $user->user_name; ?></td>
                <td>
                    <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>">link naar deze gebruiks profiel</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

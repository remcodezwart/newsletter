<div class="container">
        <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>
    <table class="overview-table">
        <thead>
        <tr>
            <td>Username</td>
            <td>User's email</td>
            <td>Activated ?</td>
            <td>Link to user's profile</td>
            <td>suspension Time in days</td>
            <td>Soft delete</td>
            <td>Submit</td>
        </tr>
        </thead>
        <?php foreach ($this->users as $user) { ?>
            <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                <td><?= $user->user_name; ?></td>
                <td><?= $user->user_email; ?></td>
                <td><?= ($user->user_active == 0 ? 'No' : 'Yes'); ?></td>
                <td>
                    <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>">Profile</a>
                </td>
                <form action="<?= config::get("URL"); ?>admin/actionAccountSettings" method="post">
                    <td><input type="number" name="suspension" /></td>
                    <td><input type="checkbox" id="<?= $user->user_id ?>"name="softDelete" <?php if ($user->user_deleted) { ?> checked <?php } ?> /><label for="<?= $user->user_id ?>"> </label></td>
                    <td>
                        <input type="hidden" name="user_id" value="<?= $user->user_id; ?>" />
                        <input type="submit" />
                    </td>
                </form>
            </tr>
        <?php } ?>
    </table>
</div>

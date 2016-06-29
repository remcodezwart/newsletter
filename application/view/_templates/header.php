<!--styling done via matriliazie css framework to make it look decent without being a strugle :) -->
<!doctype html>
<html>
<head>
    <title>news letter</title>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- send empty favicon fallback to prevent user's browser hitting the server for lots of favicon requests resulting in 404s -->
    <link rel="icon" href="data:;base64,=">
    <!-- CSS -->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="<?php echo Config::get('URL')?>css/materialize.css"  media="screen,projection"/>
    <!--javascripts-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo Config::get('URL')?>js/materialize.min.js"></script>

</head>
<body>
        <!-- navigation -->
        <nav class="indigo darken-1">
            <ul>
                <li <?php if (View::checkForActiveController($filename, "profile")) { echo ' class="active" '; } ?> >
                <a href="<?php echo Config::get('URL'); ?>profile/index">Profiles</a>
                </li>
                <?php if (!Session::userIsLoggedIn()) { ?>
                    <!-- for not logged in users -->
                    <li<?php if (View::checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>login/index">inloggen</a>
                    </li>
                    <li<?php if (View::checkForActiveControllerAndAction($filename, "register/index")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>register/index">registreren</a>
                    </li>
                <?php } ?>
        <?php if (!Session::userIsLoggedIn()) : ?>
            </ul>
        </nav>
        <?php endif ?>
        <!-- my account -->
        <?php if (Session::userIsLoggedIn()) : ?>
                    <li <?php if (View::checkForActiveControllerAndAction($filename, "user/index")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>News/index">inschrijven voor een nieuwsbrief</a>
                    </li>
                    <li <?php if (View::checkForActiveControllerAndAction($filename, "user/index")) { echo ' class="active" '; } ?> >
                        <a href="<?php echo Config::get('URL'); ?>user/index">mijn Account</a>
                    </li>
                    <li <?php if (View::checkForActiveController($filename, "user/editusername")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>user/editusername">wiijzig mijn gebruisnaam</a>
                    </li>
                    <li <?php if (View::checkForActiveController($filename, "user/changePassword")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>user/changePassword">wijzig mijn wachtwoord</a>
                    </li>
                    <li <?php if (View::checkForActiveController($filename, "login")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>login/logout">Logout</a>
                    </li>
                </ul>
            <?php if (Session::get("user_account_type") != 7) : ?><!-- cheks if the user is an admin if he is do not close the nav tag else just close it as these are all the menu item -->
                </nav>
            <?php endif; ?>
            <?php if (Session::get("user_account_type") == 7) : ?><!-- cheks if the user is an admin if he is show the admin options in the nav bar -->
                    <ul>
                        <li <?php if (View::checkForActiveController($filename, "admin")) {
                            echo ' class="active" ';
                        } ?> >
                            <a href="<?php echo Config::get('URL'); ?>admin/">Admin</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
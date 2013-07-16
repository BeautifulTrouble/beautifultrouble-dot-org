<?php
/*
Plugin Name: Email aliases
Author: Adrian
Description: A simple thing for editing the /etc/aliases file
Version: 0.2
*/

define('EA_FILENAME', '/etc/aliases');
define('EA_USERNAME', 'beautifultrouble');

function ea_get_domain() {
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $_SERVER['HTTP_HOST'], $regs)) {
        return $regs['domain'];
    }
}

function ea_read_aliases() {
    $lines = file(EA_FILENAME, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    sort($lines);
    return $lines;
}

function ea_write_aliases($email_aliases) {
    $aliases = array('MAILER-DAEMON' => EA_USERNAME, 'postmaster' => EA_USERNAME, 'root' => EA_USERNAME);
    foreach ($email_aliases['local'] as $n => $local) {
        if (empty($local)) continue;
        if (!preg_match('/^[0-9A-Za-z.]+$/', $local)) {
            ea_msg('the name '.$local.' was removed (use only alphanumerics and dots)', true);
            continue;
        }
        $remotes = preg_split('/(,|\s)\s*/', $email_aliases['remotes'][$n]);
        foreach ($remotes as $remote) {
            if (filter_var($remote, FILTER_VALIDATE_EMAIL)) {
                if (isset($aliases[$local])) {
                    $aliases[$local] .= ', ' . $remote;
                } else {
                    $aliases[$local] = $remote;
                }
            } elseif (!empty($remote)) {
                ea_msg('rule for forwarding '.$local.' to '.$remote.' was removed (invalid email address)', true);
            }
        }
    }
    $output = '';
    foreach ($aliases as $local => $remotes) {
        $output .= $local . ': ' . $remotes . "\n";
    }
    if (file_put_contents(EA_FILENAME, $output)) {
        ea_msg('Changes saved');
    } else {
        ea_msg('changes were not saved! Check the permissions on '.EA_FILENAME, true);
    }
}

function ea_msg($message, $error=false) {
    echo '<div id="message" class=' . ($error ? '"error">Warning: ' : '"updated">') . $message . '</div>';
}

function ea_form_line($local, $remotes) {
    return '
    <tr>
        <td><input type="text" name="email_aliases[local][]" value="'.$local.'" style="width:10em;text-align:right" /></td>
        <td>@'.ea_get_domain().'&nbsp;&rarr;&nbsp;</td>
        <td><input type="text" name="email_aliases[remotes][]" value="'.$remotes.'" style="width:40em;" /></td>
    </tr>
    ';
}

function ea_form($inner_form) {
    return '
    <div class="wrap">
        <h2>Email Aliases</h2>
        <form method="post" action="">
            <table>
                <tr>
                    <td alignt="right"><small>email name</small></td>
                    <td></td>
                    <td><small>is an alias which forwards to... (one or more email addresses)</small></td>
                </tr>
            '.$inner_form . ea_form_line('', '').'
            </table>
            <p class="submit">
                <input type="submit" name="submit_email_aliases" class="button-primary" value="Save Changes" />
            </p>
        </form>
    </div>';
}

function ea_main() {
    if (!current_user_can('edit_users'))
        wp_die('You do not have sufficient permissions to access this page');
    if (isset($_POST['submit_email_aliases']))
        ea_write_aliases($_POST['email_aliases']);

    $inner_form = '';
    $lines = ea_read_aliases();
    foreach ($lines as $line) {
        if (preg_match('/^(?P<local>[^:]+):\s*(?P<remotes>.+)$/', $line, $regs)) {
            if (!preg_match('/^(mailer-daemon|postmaster|root)$/i', $regs['local'])) {
                $inner_form .= ea_form_line($regs['local'], $regs['remotes']);
            }
        }
    }
    echo ea_form($inner_form);
}

function ea_add_menu() {
    current_user_can('edit_users') && add_menu_page('Email Aliases', 'Email Aliases', 'edit_users', 'email-aliases', 'ea_main');
}

add_action('admin_menu', 'ea_add_menu');
?>

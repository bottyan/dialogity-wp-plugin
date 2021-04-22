<?php
/**
 * Plugin Name: Dialogity Website Chat
 * Plugin URI: https://www.dialogity.com/wordpress-plugin/
 * Description: Simple integration of Dialogity chat to your website. It enables your customers to connect your business easily.
 * Version:           1.0.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Dialogity
 * Author URI:        https://dialogity.com/
 * License:           GPL v2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       dialogity-plugin
 */

/*
Dialogity Website Chat WordPress plugin
Copyright (C) 2020, Dialogity.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Activate the plugin.
 */
function dialogity_activate() { 
    // nothing to do
}
register_activation_hook( __FILE__, 'dialogity_activate' );

/**
 * Deactivation hook.
 */
function dialogity_deactivate() {
    // nothing to do
}
register_deactivation_hook( __FILE__, 'dialogity_deactivate' );

/**
 * Uninstall plugin.
 */
function dialogity_uninstall() {
    // deletiong options from the database
    delete_option('dialogity_options');
}
register_uninstall_hook( __FILE__, 'dialogity_uninstall' );

/**
 * Inserting code to the head of every page
 */
function dialogity_hook_javascript() {
    // loading the code to be inserted
    $options = get_option( 'dialogity_options' );
    if ( isset( $options )  && isset( $options["dialogity_field_script_preview"] ) ) {
        // inserting the code
        echo $options["dialogity_field_script_preview"];
    }
}
add_action('wp_head', 'dialogity_hook_javascript');


// TODO: load appropriate text domain: add_action( 'init', 'wpdocs_load_textdomain' ); 
/**
 * Load plugin textdomain.
 *      function wpdocs_load_textdomain() {
 *          load_plugin_textdomain( 'wpdocs_textdomain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
 *      }
 */


/**
 * custom option and settings
 */
function dialogity_settings_init() {
    // register a new setting for "dialogiy" page
    register_setting( 'dialogity', 'dialogity_options' );
    
    // register a new section in the "dialogity" page
    add_settings_section(
        'dialogity_section_accountid',
        __( 'Dialogity account settings', 'dialogity' ),
        'dialogity_section_accountid_cb',
        'dialogity'
    );
    
    // register a new field in the "dialogity_section_accountid" section, inside the "dialogity" page
    add_settings_field(
        'dialogity_field_uuid', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Account ID', 'dialogity' ),
        'dialogity_field_uuid_cb',
        'dialogity',
        'dialogity_section_accountid',
        [
            'label_for' => 'dialogity_field_uuid',
            'class' => 'dialogity_row',
            'dialogity_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'dialogity_field_custom_script', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Custom script (optional)', 'dialogity' ),
        'dialogity_field_custom_script_cb',
        'dialogity',
        'dialogity_section_accountid',
        [
            'label_for' => 'dialogity_field_custom_script',
            'class' => 'dialogity_row',
            'dialogity_custom_data' => 'custom',
        ]
    );

    add_settings_field(
        'dialogity_field_script_preview', // as of WP 4.6 this value is used only internally
        // use $args' label_for to populate the id inside the callback
        __( 'Code preview (generated)', 'dialogity' ),
        'dialogity_field_script_preview_cb',
        'dialogity',
        'dialogity_section_accountid',
        [
            'label_for' => 'dialogity_field_script_preview',
            'class' => 'dialogity_row',
            'dialogity_custom_data' => 'custom',
        ]
    );
}

/**
 * register our dialogity_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'dialogity_settings_init' );

function dialogity_section_accountid_cb( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>">
        <img width="200" src="<?php echo plugin_dir_url(__FILE__) . 'images/logo_hor.svg' ?>"></img><br>
        <?php esc_html_e( 'To integrate Dialogity chat into your Wordpress site just enter your account id. If You aldeady have an account it can be found on Dialogity admin page:', 'dialogity' ); ?>
        <a target="_blank" href="https://app.dialogity.com/install">https://app.dialogity.com/install</a>
        <?php esc_html_e( 'Otherwise You can create an account here for FREE:', 'dialogity' ); ?>
        <a target="_blank" href="https://app.dialogity.com/registration">https://app.dialogity.com/registration</a>
    </p>
    <?php
}

function dialogity_field_custom_script_cb( $args ) {
    $options = get_option( 'dialogity_options' );
    ?>
        <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>"
            name="dialogity_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            rows="5" class="widefat textarea"><?php echo $options[ $args['label_for'] ]; ?></textarea>
        <p class="description">
            <?php esc_html_e( 'Optionally add custom logic for language selection.', 'dialogity' ); ?>
            For example, you can set the language of the chat box based on the URL like this:
            <pre style="border: 1px solid #666666; border-radius: 3px;">
    window._chb_lang_code = 'HUN';
    if (window.location.href.includes('en_US')) {
        window._chb_lang_code = 'ENG';
    }</pre>
        </p>
    <?php
}

function dialogity_field_script_preview_cb( $args ) {
    $options = get_option( 'dialogity_options' );
    ?>
        <textarea id="<?php echo esc_attr( $args['label_for'] ); ?>"
            name="dialogity_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
            rows="5" class="widefat textarea" readonly="true"><?php echo $options[ $args['label_for'] ]; ?></textarea>
        <p class="description">
            <?php esc_html_e( 'Preview of the script which will be inserted into the head of your site. Just to let you know how your site is modified.', 'dialogity' ); ?>
        </p>
    <?php
}

function dialogity_field_uuid_cb( $args ) {
    $options = get_option( 'dialogity_options' );
    ?>
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>"
                data-custom="<?php echo esc_attr( $args['dialogity_custom_data'] ); ?>"
                name="dialogity_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
                type="text" size="32"
                value="<?php echo $options[ $args['label_for'] ]; ?>"/>
        <p class="error">
            <div id="uuid_error_msg" style="color: #ff0000;"></div>
        </p>
        <p class="description">
            <?php esc_html_e( 'Copy the UUID from the top of the install page, it looks something like this "d00fff66d22ccccc2222c2a8f8f2222d".', 'dialogity' ); ?>
        </p>
    <?php
}

/**
 * top level menu
 */
function dialogity_options_page() {
    // add top level menu page
    add_menu_page(
        'Dialogity',
        'Dialogity Chat',
        'manage_options',
        'dialogity',
        'dialogity_options_page_html',
        plugin_dir_url(__FILE__) . 'images/logo_notext.png',
        20
    );
}

/**
 * register our dialogity_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'dialogity_options_page' );

/**
 * top level menu:
 * callback functions
 */
function dialogity_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // add error/update messages
    
    // check if the user have submitted the settings
    // wordpress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'dialogity_messages', 'dialogity_message', __( 'Settings Saved', 'dialogity' ), 'updated' );
    }
    
    // show error/update messages
    settings_errors( 'dialogity_messages' );

    ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                // output security fields for the registered setting "dialogity"
                settings_fields( 'dialogity' );
                // output setting sections and their fields
                // (sections are registered for "dialogity", each field is registered to a specific section)
                do_settings_sections( 'dialogity' );
                // output save settings button
                submit_button( 'Save Settings' );
                ?>
            </form>
        </div>
    <?php
    
    // loading the admin Javascript to load the code snippet from the server
    wp_enqueue_script( 'dialogity_js', plugins_url( '/dialogity.js', __FILE__ ));
}


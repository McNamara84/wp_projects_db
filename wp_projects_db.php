<?php
/*
Plugin Name: wp_projects_db
Description: Dieses Plugin erstellt eine neue Tabelle "Projekte" in der WordPress-Datenbank.
Version: 0.2
Author: Katja Ebermann, Holger Ehrmann
*/
define('TABLE_NAME_MAIN', 'projects');
define('TABLE_NAME_HELP', 'project_categories');

function wp_projects_db_make_help_table()
{
    global $wpdb; // Das $wpdb-Objekt beinhaltet die DB-Einstellungen von WP.
    $charset_collate = $wpdb->get_charset_collate(); // $charset_collate enthält den von der WP aktuell genutzten Zeichensatz.
    $table_name_help = $wpdb->prefix . TABLE_NAME_HELP;
    $sql = "CREATE TABLE IF NOT EXISTS $table_name_help (
        'project_category_id' INT NOT NULL,
        'project_categorie_name' VARCHAR(45) NOT NULL,
        PRIMARY KEY (`project_category_id`)
        ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function wp_projects_db_make_main_table()
{
    global $wpdb; // Das $wpdb-Objekt beinhaltet die DB-Einstellungen von WP.
    $charset_collate = $wpdb->get_charset_collate(); // $charset_collate enthält den von der WP aktuell genutzten Zeichensatz.
    $table_name_main = $wpdb->prefix . TABLE_NAME_MAIN;
    $table_name_help = $wpdb->prefix . TABLE_NAME_HELP;
    $sql = "CREATE TABLE IF NOT EXISTS $table_name_main (
        'project_id' int NOT NULL AUTO_INCREMENT,
        'name' VARCHAR(50) NOT NULL,
        'email' VARCHAR(998) NOT NULL,
        'university_organisation' VARCHAR(50) NULL,
        'status' VARCHAR(10) NULL,
        'project_name' VARCHAR(150) NOT NULL,
        'hyperlink_project_webpage' VARCHAR(2083) NULL,
        'short_describtion' VARCHAR(800) NOT NULL,
        'illustration' MEDIUMBLOB NULL,
        'project_content' VARCHAR(800) NOT NULL,
        'project_team' VARCHAR(300) NULL,
        'promoted_by' VARCHAR(300) NULL,
        'project_start_date' DATE NOT NULL,
        'project_visible' TINYINT NOT NULL,
        'phone_number' VARCHAR(15) NULL,
        'project_categories_id' INT NOT NULL,
        PRIMARY KEY (project_id)
        FOREIGN KEY (project_category_id)
        REFERENCES $table_name_help (project_category_id)
        ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Funktion wird ausgeführt, wenn Plugin im Backend installiert wird
function wp_projects_db_install()
{
    wp_projects_db_make_help_table();
    wp_projects_db_make_main_table();
}
register_activation_hook(__FILE__, 'wp_projects_db_install');

// Funktion zur Anzeige des Eingabeformulars
function wp_projects_db_show_form()
{
    // TODO: HTML-Formular als Return.
}

// Funktion zur Anzeige der Tabelle mit allen veröffentlichten Projekten
function wp_projects_db_show_table()
{
    // TODO: HTML-Tabelle als Return
}

// Funktion zur Anzeige der Tabelle im Backend
function wp_projects_db_show_table_backend()
{
    // Wird erst erstellt, wenn show_table() funktioniert zwecks Code-Übernahme.
}

?>
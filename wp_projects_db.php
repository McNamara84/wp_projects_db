<?php
/*
Plugin Name: wp_projects_db
Description: Dieses Plugin erstellt eine neue Tabelle "Projekte" in der WordPress-Datenbank.
Version: 0.1
Author: Katja Ebermann, Holger Ehrmann
*/

// WordPress verwendet das $wpdb-Objekt, um mit der Datenbank zu interagieren.
//global $wpdb;

// Funktion wird ausgeführt, wenn Plugin im Backend installiert wird
function wp_projects_db_install()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'projects';
    $sql = "CREATE TABLE $table_name (
        project_id int NOT NULL AUTO_INCREMENT,
        // TODO: Hier Katjas SQL-Export einfügen
        PRIMARY KEY (project_id)
        $charset_collate;";
}
register_activation_hook(__FILE__, 'wp_projects_db_install');

// Funktion zur Anzeige des Eingabeformulars
function wp_projects_db_show_form()
{
    // TODO: Tabelle als Return 
}

// Funktion zur Anzeige der Tabelle mit allen veröffentlichten Projekten
function wp_projects_db_show_table()
{

}

// Funktion zur Anzeige der Tabelle im Backend
function wp_projects_db_show_table_backend()
{

}

?>
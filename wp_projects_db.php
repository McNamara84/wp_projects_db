<?php
/*
Plugin Name: wp_projects_db
Description: Dieses Plugin erstellt eine neue Tabelle "Projekte" in der WordPress-Datenbank.
Version: 0.3
Author: Katja Ebermann, Holger Ehrmann
*/

// Konstanten
define('TABLE_NAME_MAIN', 'projects');
define('TABLE_NAME_HELP', 'project_categories');

// Verarbeitung gesendeter Daten über das Formular
function wp_projects_db_send_form() {
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['wp_projects_db_form'])) {

        // Überprüfen der eingegangenen Daten
        // TODO: Hier kommt gaaanz viel RegEx rein. ;-)

        // Einfügen der Daten in die Datenbank
        $table_name_main = $wpdb->prefix . TABLE_NAME_MAIN;
        $wpdb->insert(
            $table_name_main,
            array(
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'university_organisation' => $_POST['university_organisation'],
                'status' => $_POST['status'],
                'project_name' => $_POST['project_name'],
                'hyperlink_project_webpage' => $_POST['hyperlink_project_webpage'],
                'short_describtion' => $_POST['short_describtion'],
                'illustration' => $_POST['illustration'], // TODO: Vorverarbeiten???
                'project_content' => $_POST['project_content'],
                'project_team' => $_POST['project_team'],
                'promoted_by' => $_POST['promoted_by'],
                'project_start_date' => $_POST['project_start_date'],
                'phone_number' => $_POST['phone_number'],
                'project_categories_id' => $_POST['project_categories_id'],
            )
        );
    }
}

// Erstellt Hilfstabelle für Plugin
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

// Erstellt Haupttabelle des Plugins
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
    // Hier müssten Sie Ihren Datenbankzugriff einrichten und die Werte für die Dropdown-Optionen auslesen
    $status_options = array('beendet', 'laufend', 'geplant');
    $project_categories_options = array( '1', '2', '3');

    $form = '
    <form action="/path/to/your/script" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name"><br>

        <label for="email">E-Mail:</label><br>
        <input type="email" id="email" name="email"><br>

        <label for="university_organisation">Universität/Hochschule/Organisation:</label><br>
        <input type="text" id="university_organisation" name="university_organisation"><br>

        <label for="status">Status:</label><br>
        <select id="status" name="status">';

    foreach ($status_options as $option) {
        $form .= "<option value='$option'>$option</option>";
    }

    $form .= '</select><br>

        <label for="project_name">Projektname:</label><br>
        <input type="text" id="project_name" name="project_name"><br>

        <label for="hyperlink_project_webpage">Hyperlink zur Projektwebsite:</label><br>
        <input type="url" id="hyperlink_project_webpage" name="hyperlink_project_webpage"><br>

        <label for="short_describtion">Kurzbeschreibung des Projektes:</label><br>
        <input type="text" id="short_describtion" name="short_describtion"><br>

        <label for="illustration">Grafik/Abbildung:</label><br>
        <input type="file" id="illustration" name="illustration"><br>

        <label for="project_content">Projektinhalt:</label><br>
        <textarea id="project_content" name="project_content"></textarea><br>

        <label for="project_team">Projektmitarbeiter:</label><br>
        <input type="text" id="project_team" name="project_team"><br>

        <label for="promoted_by">Gefördert durch:</label><br>
        <input type="text" id="promoted_by" name="promoted_by"><br>

        <label for="project_start_date">Projektstartdatum:</label><br>
        <input type="date" id="project_start_date" name="project_start_date"><br>

        <label for="phone_number">Telefon:</label><br>
        <input type="tel" id="phone_number" name="phone_number"><br>

        <label for="project_categories_id">Projektkategorie:</label><br>
        <select id="project_categories_id" name="project_categories_id">';

    foreach ($project_categories_options as $option) {
        $form .= "<option value='$option'>$option</option>";
    }

    $form .= '</select><br>
        <input type="submit" value="Absenden">
    </form>';

    return $form;
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
<?php
/**
 * Plugin Name: Compteur de Visiteurs
 * Description: Un plugin simple pour compter les visiteurs du site.
 * Version: 1.0
 * Author: Groupe Typescript
 */

// Créer une option dans la base de données à l'activation
function vc_install() {
    if (get_option('vc_visitor_count') === false) {
        add_option('vc_visitor_count', 0);
    }
}
register_activation_hook(__FILE__, 'vc_install');

// Incrémenter le compteur à chaque visite
function vc_count_visits() {
    $count = get_option('vc_visitor_count');
    $count++;
    update_option('vc_visitor_count', $count);
}
add_action('wp_head', 'vc_count_visits');

// Ajouter un widget dans le tableau de bord admin
function vc_add_dashboard_widget() {
    wp_add_dashboard_widget('vc_dashboard_widget', 'Compteur de Visiteurs', 'vc_display_count');
}

function vc_display_count() {
    $count = get_option('vc_visitor_count');
    $image_url = plugin_dir_url(__FILE__) . 'assets/logo.png';

    echo "<img src='$image_url' alt='Logo' style='width:100px;margin-bottom:10px;'>";
    echo "<h3>Total des visiteurs : <strong>$count</strong></h3>";

    if (isset($_POST['reset_visitor_count'])) {
    update_option('vc_visitor_count', 0);
    echo "<p>Compteur réinitialisé !</p>";
    }
    echo '<form method="post"><button type="submit" name="reset_visitor_count">Réinitialiser</button></form>';

}
add_action('wp_dashboard_setup', 'vc_add_dashboard_widget');

// Shortcode pour afficher le nombre de visiteurs sur une page
function vc_display_visitor_count_shortcode() {
    $count = get_option('vc_visitor_count');
    return "<div>$count</div>";
}
add_shortcode('visitor_count', 'vc_display_visitor_count_shortcode');

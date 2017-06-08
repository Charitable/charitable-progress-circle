<?php
/**
 * Plugin Name:         Charitable - Progress Circle
 * Plugin URI:
 * Description:         Add a progress circle to the campaign page for campaigns with a goal. This will replace the usual campaign summary block with a customized "card" style block for the campaign.
 * Version:             0.1
 * Author:              WP Charitable
 * Author URI:          https://www.wpcharitable.com/
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

function charitable_template_campaign_percentage_raised( $campaign ) {
    if ( ! $campaign->has_goal() ) {
        return false;
    }

    wp_enqueue_script( 'jquery-easypiechart', plugin_dir_url( __FILE__ ) . 'jquery.easypiechart.min.js', array( 'jquery-core' ), '2.1.7', false );
    wp_add_inline_script( 'jquery-easypiechart', '(function($){$(".charitable-percent-raised-chart").easyPieChart({ size: 190 })})(jQuery)' );
?>
<style>
.campaign-summary {
    box-sizing: content-box;
    float: left;
    max-width: 190px;
    margin-right: 1em;
}
.campaign-raised.campaign-summary-item {
    width: 100%;
}
.charitable-percent-raised-chart {
    position: relative;
    float: left;
    width: 190px;
    height: 190px;
    line-height: 190px;
    font-size: 2em;    
    font-weight: bolder;
    text-align: center;
}
.charitable-percent-raised-chart canvas {
    position: absolute;
    top: 0;
    left: 0;
}
.campaign-raised .charitable-amount-raised {
    float: left;
    text-align: left;
    padding: 20px 0 0;
}
.campaign-donors.campaign-summary-item,
.campaign-time-left.campaign-summary-item {
    border: none;
    font-size: 0.9em;
}
</style>
<div class="campaign-raised campaign-summary-item">
    <div class="charitable-percent-raised-chart" data-percent="<?php echo esc_attr( $campaign->get_percent_donated_raw() ) ?>" data-scale-color="false"><span><?php echo $campaign->get_percent_donated_raw() ?></span>%</div>
    <div class="charitable-amount-raised"><?php echo $campaign->get_donation_summary() ?></div>
</div>
<?php

    return true;
}

function ed_unhook_default_template_functions() {
    /** 
     * This hook is defined on line 70 of charitable/includes/public/charitable-template-hooks.php
     * 
     * The original `add_action()` call looks like this: 
     * add_action( 'charitable_campaign_summary', 'charitable_template_campaign_donor_count', 8 ); 
     *
     * To unhook it, we copy this line entirely and change 'add' to 'remove'.
     */
    remove_action( 'charitable_campaign_summary', 'charitable_template_campaign_donation_summary', 6 );
}
add_action( 'after_setup_theme', 'ed_unhook_default_template_functions', 11 ); 

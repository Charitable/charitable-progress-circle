<?php
/**
 * Plugin Name: Charitable - Progress Circle
 * Plugin URI:  https://github.com/Charitable/charitable-progress-circle
 * Description: Add a progress circle to the campaign page for campaigns with a goal. This will replace the usual campaign summary block with a customized "card" style block for the campaign.
 * Version:     0.3
 * Author:      WP Charitable
 * Author URI:  https://www.wpcharitable.com/
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'charitable_template_campaign_percentage_raised' ) ) {

	function charitable_template_campaign_percentage_raised( $campaign ) {
		if ( ! $campaign->has_goal() ) {
			return false;
		}

		$percent   = $campaign->get_percent_donated_raw();
		$formatted = number_format( $percent, 2, charitable_get_currency_helper()->get_decimal_separator(), '' );

		wp_enqueue_script( 'jquery-easypiechart', plugin_dir_url( __FILE__ ) . 'jquery.easypiechart.min.js', array( 'jquery' ), '2.1.7', false );
		wp_add_inline_script( 'jquery-easypiechart', '(function($){$(".charitable-percent-raised-chart").easyPieChart({ size: 190 })})(jQuery)' );
		?>
<style>
.campaign-summary {
	box-sizing: border-box;
	min-width: 190px;
	margin-right: 1em;
	width: 100%;
}
.campaign-raised.campaign-summary-item {
	position: relative;
	width: 100%;
	text-align: center;
}
.charitable-percent-raised-chart {
	position: relative;
	display: inline-block;
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
	display: inline-block;
	margin-left: 20px;
	text-align: left;
}
.campaign-donors.campaign-summary-item,
.campaign-time-left.campaign-summary-item {
	border: none;
	font-size: 0.9em;
	text-align: center;
}
</style>
<div class="campaign-raised campaign-summary-item">
	<div class="charitable-percent-raised-chart" data-percent="<?php echo esc_attr( $percent ); ?>" data-scale-color="false"><span><?php echo $formatted; ?></span>%</div>
	<div class="charitable-amount-raised"><?php echo $campaign->get_donation_summary(); ?></div>
</div>
		<?php

		return true;
	}
}

function ed_unhook_default_template_functions() {
	/**
	 * This hook is defined on line 46 of charitable/includes/public/charitable-template-hooks.php
	 *
	 * The original `add_action()` call looks like this:
	 * add_action( 'charitable_campaign_summary', 'charitable_template_campaign_donation_summary', 6 );
	 *
	 * To unhook it, we copy this line entirely and change 'add' to 'remove'.
	 */
	remove_action( 'charitable_campaign_summary', 'charitable_template_campaign_donation_summary', 6 );
}

add_action( 'after_setup_theme', 'ed_unhook_default_template_functions', 11 );

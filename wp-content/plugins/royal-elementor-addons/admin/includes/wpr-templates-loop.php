<?php

namespace WprAddons\Admin\Includes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use WprAddons\Classes\Utilities;

/**
** WPR_Templates_Loop setup
*/
class WPR_Templates_Loop {

	/**
	** Loop Through Custom Templates
	*/
	public static function render_theme_builder_templates( $template ) {
		// WP_Query arguments
		$args = array (
			'post_type'   => array( 'wpr_templates' ),
			'post_status' => array( 'publish' ),
			'posts_per_page' => -1,
			'tax_query'   => array(
				array(
					'taxonomy' => 'wpr_template_type',
					'field'    => 'slug',
					'terms'    => [ $template, 'user' ],
					'operator' => 'AND'
				)
			)
		);

		// The Query
		$user_templates = get_posts( $args );

		// The Loop
		echo '<ul class="wpr-'. esc_attr($template) .'-templates-list wpr-my-templates-list" data-pro="'. esc_attr(wpr_fs()->can_use_premium_code()) .'">';

			if ( ! empty( $user_templates ) ) {
				foreach ( $user_templates as $user_template ) {
					$slug = $user_template->post_name;
					$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );
					$show_on_canvas = get_post_meta(Utilities::get_template_id($slug), 'wpr_'. $template .'_show_on_canvas', true);

					echo '<li>';
				        echo '<h3 class="wpr-title">'. esc_html($user_template->post_title) .'</h3>';

				        echo '<div class="wpr-action-buttons">';
							// Activate
							echo '<span class="wpr-template-conditions button button-primary" data-slug="'. esc_attr($slug) .'" data-show-on-canvas="'. esc_attr($show_on_canvas) .'">'. esc_html__( 'Manage Conditions', 'wpr-addons' ) .'</span>';
							// Edit
							echo '<a href="'. esc_url($edit_url) .'" class="wpr-edit-template button button-primary">'. esc_html__( 'Edit Template', 'wpr-addons' ) .'</a>';
							// Delete
							echo '<span class="wpr-delete-template button button-primary" data-slug="'. esc_attr($slug) .'" data-warning="'. esc_html__( 'Are you sure you want to delete this template?', 'wpr-addons' ) .'"><span class="dashicons dashicons-no-alt"></span></span>';
				        echo '</div>';
					echo '</li>';
				}
			} else {
				echo '<li class="wpr-no-templates">You don\'t have any headers yet!</li>';
			}

		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();

	}

	/**
	** Loop Through My Templates
	*/
	public static function render_elementor_saved_templates() {

		// WP_Query arguments
		$args = array (
			'post_type'   => array( 'elementor_library' ),
			'post_status' => array( 'publish' ),
			'meta_key' 	  => '_elementor_template_type',
			'meta_value'  => ['page', 'section'],
			'numberposts'  => -1
		);

		// The Query
		$user_templates = get_posts( $args );

		// My Templates List
		echo '<ul class="wpr-my-templates-list striped">';

		// The Loop
		if ( ! empty( $user_templates ) ) {
			foreach ( $user_templates as $user_template ) {
				// Edit URL
				$edit_url = str_replace( 'edit', 'elementor', get_edit_post_link( $user_template->ID ) );

				// List
				echo '<li>';
					echo '<h3>'. $user_template->post_title .'</h3>';
					echo '<span class="wpr-action-buttons">';
						echo '<a href="'. esc_url($edit_url) .'" class="button button-primary">'. esc_html__( 'Edit', 'wpr-addons' ) .'</a>';
						echo '<span class="wpr-delete-template button button-primary" data-slug="'. esc_attr($user_template->post_name) .'" data-warning="'. esc_html__( 'Are you sure you want to delete this template?', 'wpr-addons' ) .'"><span class="dashicons dashicons-no-alt"></span></span>';
					echo '</span>';
				echo '</li>';
			}
		}
		
		echo '</ul>';

		// Restore original Post Data
		wp_reset_postdata();
	}

	/**
	** Render Conditions Popup
	*/
	public static function render_conditions_popup( $canvas = false ) {
		
	?>

    <div class="wpr-condition-popup-wrap wpr-admin-popup-wrap">
        <div class="wpr-condition-popup wpr-admin-popup">
            <header>
                <h2><?php esc_html_e( 'Where Do You Want to Display Your Template?', 'wpr-addons' ); ?></h2>
                <p>
                    <?php esc_html_e( 'Set the conditions that determine where your Template is used throughout your site.', 'wpr-addons' ); ?><br>
                    <?php esc_html_e( 'For example, choose \'Entire Site\' to display the template across your site.', 'wpr-addons' ); ?>
                </p>
            </header>
            <span class="close-popup dashicons dashicons-no-alt"></span>

            <!-- Conditions -->
            <div class="wpr-conditions-wrap">
                <div class="wpr-conditions-sample">
                	<?php if ( wpr_fs()->can_use_premium_code() ) : ?>
                    <!-- Global -->
                    <select name="global_condition_select" class="global-condition-select">
                        <option value="global"><?php esc_html_e( 'Entire Site', 'wpr-addons' ); ?></option>
                        <option value="archive"><?php esc_html_e( 'Archives', 'wpr-addons' ); ?></option>
                        <option value="single"><?php esc_html_e( 'Singular', 'wpr-addons' ); ?></option>
                    </select>
                    <!-- Archive -->
                    <select name="archives_condition_select" class="archives-condition-select">
                        <option value="posts"><?php esc_html_e( 'Posts Archive', 'wpr-addons' ); ?></option>
                        <option value="author"><?php esc_html_e( 'Author Archive', 'wpr-addons' ); ?></option>
                        <option value="date"><?php esc_html_e( 'Date Archive', 'wpr-addons' ); ?></option>
                        <option value="search"><?php esc_html_e( 'Search Results', 'wpr-addons' ); ?></option>
                        <option value="categories" class="custom-ids"><?php esc_html_e( 'Post Categories', 'wpr-addons' ); ?></option>
                        <option value="tags" class="custom-ids"><?php esc_html_e( 'Post Tags', 'wpr-addons' ); ?></option>
                        <?php // Custom Taxonomies
                            $custom_taxonomies = Utilities::get_custom_types_of( 'tax', true );
                            foreach ($custom_taxonomies as $key => $value) {
                                // Add Shop Archives
                                if ( 'product_cat' === $key ) {
                                    echo '<option value="products">'. esc_html__( 'Products Archive', 'wpr-addons' ) .'</option>';
                                }
                                // List Taxonomies
                                echo '<option value="'. esc_attr($key) .'" class="custom-type-ids">'. esc_html($value) .'</option>';
                            }
                        ?>
                    </select>
                    <!-- Single -->
                    <select name="singles_condition_select" class="singles-condition-select">
                        <option value="front_page"><?php esc_html_e( 'Front Page', 'wpr-addons' ); ?></option>
                        <option value="page_404"><?php esc_html_e( '404 Page', 'wpr-addons' ); ?></option>
                        <option value="pages" class="custom-ids"><?php esc_html_e( 'Pages', 'wpr-addons' ); ?></option>
                        <option value="posts" class="custom-ids"><?php esc_html_e( 'Posts', 'wpr-addons' ); ?></option>
                        <?php // Custom Post Types
                            $custom_taxonomies = Utilities::get_custom_types_of( 'post', true );
                            foreach ($custom_taxonomies as $key => $value) {
                                echo '<option value="'. esc_attr($key) .'" class="custom-type-ids">'. esc_html($value) .'</option>';
                            }
                        ?>
                    </select>

                    <input type="text" placeholder="<?php esc_html_e( 'Enter comma separated IDs', 'wpr-addons' ); ?>" name="condition_input_ids" class="wpr-condition-input-ids">
                    <span class="wpr-delete-template-conditions dashicons dashicons-no-alt"></span>

	                <?php else: ?>

                    <!-- Global -->
                    <select name="global_condition_select" class="global-condition-select">
                        <option value="global"><?php esc_html_e( 'Entire Site', 'wpr-addons' ); ?></option>
                        <option value="archive"><?php esc_html_e( 'Archives (Pro)', 'wpr-addons' ); ?></option>
                        <option value="single"><?php esc_html_e( 'Singular (Pro)', 'wpr-addons' ); ?></option>
                    </select>
                    <!-- Archive -->
                    <select name="archives_condition_select" class="archives-condition-select">
                        <option value="posts"><?php esc_html_e( 'Posts Archive (Pro)', 'wpr-addons' ); ?></option>
                        <option value="author"><?php esc_html_e( 'Author Archive (Pro)', 'wpr-addons' ); ?></option>
                        <option value="date"><?php esc_html_e( 'Date Archive (Pro)', 'wpr-addons' ); ?></option>
                        <option value="search"><?php esc_html_e( 'Search Results (Pro)', 'wpr-addons' ); ?></option>
                        <option value="categories" class="custom-ids"><?php esc_html_e( 'Post Categories (Pro)', 'wpr-addons' ); ?></option>
                        <option value="tags" class="custom-ids"><?php esc_html_e( 'Post Tags (Pro)', 'wpr-addons' ); ?></option>
                        <?php // Custom Taxonomies
                            $custom_taxonomies = Utilities::get_custom_types_of( 'tax', true );
                            foreach ($custom_taxonomies as $key => $value) {
                                // Add Shop Archives
                                if ( 'product_cat' === $key ) {
                                    echo '<option value="products">'. esc_html__( 'Products Archive (Pro)', 'wpr-addons' ) .'</option>';
                                }
                                // List Taxonomies
                                echo '<option value="'. esc_attr($key) .'" class="custom-type-ids">'. esc_html($value) .' (Pro)</option>';
                            }
                        ?>
                    </select>
                    <!-- Single -->
                    <select name="singles_condition_select" class="singles-condition-select">
                        <option value="front_page"><?php esc_html_e( 'Front Page (Pro)', 'wpr-addons' ); ?></option>
                        <option value="page_404"><?php esc_html_e( '404 Page (Pro)', 'wpr-addons' ); ?></option>
                        <option value="pages" class="custom-ids"><?php esc_html_e( 'Pages (Pro)', 'wpr-addons' ); ?></option>
                        <option value="posts" class="custom-ids"><?php esc_html_e( 'Posts (Pro)', 'wpr-addons' ); ?></option>
                        <?php // Custom Post Types
                            $custom_taxonomies = Utilities::get_custom_types_of( 'post', true );
                            foreach ($custom_taxonomies as $key => $value) {
                                echo '<option value="'. esc_attr($key) .'" class="custom-type-ids">'. esc_html($value) .' (Pro)</option>';
                            }
                        ?>
                    </select>

                    <input type="text" placeholder="<?php esc_html_e( 'Enter comma separated IDs (Pro)', 'wpr-addons' ); ?>" name="condition_input_ids" class="wpr-condition-input-ids">
                    <span class="wpr-delete-template-conditions dashicons dashicons-no-alt"></span>
	                	
	                <?php endif; ?>
                </div>
            </div>

			<?php if ( $canvas ) : ?>
			<div class="wpr-canvas-condition wpr-setting-custom-ckbox">
				<span><?php esc_html_e( 'Show this template on Elementor Canvas pages', 'wpr-addons' ); ?></span>
            	<input type="checkbox" name="wpr-show-on-canvas" id="wpr-show-on-canvas">
            	<label for="wpr-show-on-canvas"></label>
            </div>
            <?php endif; ?>

            <?php


           	// Pro Notice
			if ( ! wpr_fs()->can_use_premium_code() ) {
				echo '<span style="color: #7f8b96;"><br>Conditions are fully suppoted in the <strong><a href="https://royal-elementor-addons.com/?ref=rea-plugin-backend-conditions-upgrade-pro#purchasepro" target="_blank">Pro version</a></strong></span>';
				// echo '<span style="color: #7f8b96;"><br>Conditions are fully suppoted in the <strong><a href="'. admin_url('admin.php?page=wpr-addons-pricing') .'" target="_blank">Pro version</a></strong></span>';
			}

            ?>
            
            <!-- Action Buttons -->
            <span class="wpr-add-conditions"><?php esc_html_e( 'Add Conditions', 'wpr-addons' ); ?></span>
            <span class="wpr-save-conditions"><?php esc_html_e( 'Save Conditions', 'wpr-addons' ); ?></span>

        </div>
    </div>

	<?php
	}


	/**
	** Render Create Template Popup
	*/
	public static function render_create_template_popup() {
	?>

    <!-- Custom Template Popup -->
    <div class="wpr-user-template-popup-wrap wpr-admin-popup-wrap">
        <div class="wpr-user-template-popup wpr-admin-popup">
        	<header>
	            <h2><?php esc_html_e( 'Templates Help You Work Efficiently!', 'wpr-addons' ); ?></h2>
	            <p><?php esc_html_e( 'Use templates to create the different pieces of your site, and reuse them with one click whenever needed.', 'wpr-addons' ); ?></p>
			</header>

            <input type="text" name="user_template_title" class="wpr-user-template-title" placeholder="<?php esc_html_e( 'Enter Template Title', 'wpr-addons' ); ?>">
            <input type="hidden" name="user_template_type" class="user-template-type">
            <span class="wpr-create-template"><?php esc_html_e( 'Create Template', 'wpr-addons' ); ?></span>
            <span class="close-popup dashicons dashicons-no-alt"></span>
        </div>
    </div>

	<?php
	}

	/**
	** Check if Library Template Exists
	*/
	public static function template_exists( $slug ) {
		$result = false;
		$wpr_templates = get_posts( ['post_type' => 'wpr_templates', 'posts_per_page' => '-1'] );

		foreach ( $wpr_templates as $post ) {

			if ( $slug === $post->post_name ) {
				$result = true;
			}
		}

		return $result;
	}

}
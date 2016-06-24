<?php
/**
* Custom Widgets file
**/

// Exit if Accessed Directly
if(!defined('ABSPATH')){
	exit;
}

// Register Widget
function miga_register_newsletter(){
	register_widget('Newsletter_Widget');
}

add_action('widgets_init', 'miga_register_newsletter');


class Newsletter_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		// widget actual processes
		parent::__construct(
			'newsletter_widget', // Base ID
			__( 'Newsletter Widget', 'miga' ), // Name
			array( 'description' => __( 'A simple newsletter widget to stay in touch with your target audience.', 'miga' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		echo $args['before_widget'];

		echo $args['before_title'];
		if(!empty($instance['title'])){
			echo $instance['title'];
		}
		echo $args['after_title'];
		?>

			<div id="form-msg"></div>
			<form id="subscriber-form" method="post" action="">
				<div class="form-group">
					<input type="text" id="name-newsletter" name="name" placeholder="Name:" class="form-control" required>
				</div>
				<div class="form-group">
					<input type="text" id="email-newsletter" name="email" placeholder="Email:" class="form-control" required>
				</div>
				<input type="hidden" name="recipient" value="<?php echo $instance['recipient']; ?>">
				<input type="hidden" name="subject" value="<?php echo $instance['subject']; ?>">
				<input type="submit" class="btn btn-primary" name="subscriber_submit" value="Subscribe">
				<br><br>
			</form>
		<?php

		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title = !empty($instance['title']) ? $instance['title'] : __('Sign up for more news', 'miga');
		$recipient = $instance['recipient'];
		$subject = !empty($instance['subject']) ? $instance['subject'] : __('You have a new subscriber', 'miga');
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'miga'); ?></label><br>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('recipient'); ?>"><?php _e('Recipient:', 'miga'); ?></label><br>
			<input type="text" id="<?php echo $this->get_field_id('recipient'); ?>" name="<?php echo $this->get_field_name('recipient'); ?>" value="<?php echo esc_attr($recipient); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('subject'); ?>"><?php _e('Subject:', 'miga'); ?></label><br>
			<input type="text" id="<?php echo $this->get_field_id('subject'); ?>" name="<?php echo $this->get_field_name('subject'); ?>" value="<?php echo esc_attr($subject); ?>">
		</p>
	<?php
	}
	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array(
			'title' => (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '',
			'recipient' => (!empty($new_instance['recipient'])) ? strip_tags($new_instance['recipient']) : '',
			'subject' => (!empty($new_instance['subject'])) ? strip_tags($new_instance['subject']) : ''
		);

		return $instance;
	}
}

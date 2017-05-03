<?php
/**
 * Class Nutrifox_Test
 *
 * @package Nutrifox
 */

/**
 * Test Nutrifox plugin functionality
 */
class Nutrifox_Test extends WP_UnitTestCase {

	/**
	 * Ensure the shortcode renders as expected
	 */
	public function test_shortcode_render() {
		$output = <<<EOT
<div class="nutrifox-label" data-recipe-id="7500"></div>
<script async src="https://nutrifox.com/embed.js" charset="utf-8"></script>
EOT;
		$this->assertEquals( $output, do_shortcode( '[nutrifox id="7500"]' ) );
	}

	/**
	 * Shortcode with an empty id should return empty string
	 */
	public function test_shortcode_render_no_id() {
		$this->assertEquals( '', do_shortcode( '[nutrifox id=""]' ) );
	}

	/**
	 * Ensure oEmbed handlers return expected output
	 */
	public function test_oembed_handler_label() {
		$input = <<<EOT
My favorite WordPress feature

https://nutrifox.com/embed/label/7500

Don't you live it too?
EOT;
		$output = <<<EOT
<p>My favorite WordPress feature</p>
<div class="nutrifox-label" data-recipe-id="7500"></div>
<p><script async src="https://nutrifox.com/embed.js" charset="utf-8"></script></p>
<p>Don&#8217;t you live it too?</p>
EOT;
		$this->assertEquals( $output, trim( apply_filters( 'the_content', $input ) ) );
	}

	/**
	 * Ensure oEmbed handlers return expected output
	 */
	public function test_oembed_handler_standard() {
		$input = <<<EOT
My favorite WordPress feature

https://nutrifox.com/recipes/7500

Don't you live it too?
EOT;
		$output = <<<EOT
<p>My favorite WordPress feature</p>
<div class="nutrifox-label" data-recipe-id="7500"></div>
<p><script async src="https://nutrifox.com/embed.js" charset="utf-8"></script></p>
<p>Don&#8217;t you live it too?</p>
EOT;
		$this->assertEquals( $output, trim( apply_filters( 'the_content', $input ) ) );
	}

	/**
	 * Ensure oEmbed handlers return expected output
	 */
	public function test_oembed_handler_standard_edit() {
		$input = <<<EOT
My favorite WordPress feature

https://nutrifox.com/recipes/7500/edit

Don't you live it too?
EOT;
		$output = <<<EOT
<p>My favorite WordPress feature</p>
<div class="nutrifox-label" data-recipe-id="7500"></div>
<p><script async src="https://nutrifox.com/embed.js" charset="utf-8"></script></p>
<p>Don&#8217;t you live it too?</p>
EOT;
		$this->assertEquals( $output, trim( apply_filters( 'the_content', $input ) ) );
	}

	/**
	 * Ensure sortcode reversal works as expected
	 */
	public function test_shortcode_reversal() {
		$post_id = $this->factory->post->create();
		$input = <<<EOT
My favorite WordPress feature

<div class="nutrifox-label" data-recipe-id="7500"></div>
<script async src="https://nutrifox.com/embed.js" charset="utf-8"></script>

Don't you live it too?
EOT;
		$output = <<<EOT
My favorite WordPress feature

[nutrifox id="7500"]

Don't you live it too?
EOT;
		wp_update_post( array(
			'ID' => $post_id,
			'post_content' => wp_slash( $input ),
		) );
		$this->assertEquals( $output, get_post( $post_id )->post_content );
	}

	/**
	 * Ensure sortcode reversal works as expected
	 */
	public function test_shortcode_reversal_format_two() {
		$post_id = $this->factory->post->create();
		$input = <<<EOT
Test test
<div class="nutrifox-label" data-recipe-id="8480">&nbsp;</div>
<script async="" src="https://nutrifox.com/embed.js" charset="utf-8"></script>

Don't you live it too?
EOT;
		$output = <<<EOT
Test test
[nutrifox id="8480"]

Don't you live it too?
EOT;
		wp_update_post( array(
			'ID' => $post_id,
			'post_content' => wp_slash( $input ),
		) );
		$this->assertEquals( $output, get_post( $post_id )->post_content );
	}

	/**
	 * Ensure sortcode reversal works as expected
	 */
	public function test_shortcode_reversal_format_three() {
		$post_id = $this->factory->post->create();
		$input = <<<EOT
Test test
<div class="nutrifox-label" data-recipe-id="8480"></div>
<script async src="https://nutrifox.com/embed.js" charset="utf-8"></script>

Don't you live it too?
EOT;
		$output = <<<EOT
Test test
[nutrifox id="8480"]

Don't you live it too?
EOT;
		wp_update_post( array(
			'ID' => $post_id,
			'post_content' => wp_slash( $input ),
		) );
		$this->assertEquals( $output, get_post( $post_id )->post_content );
	}

}
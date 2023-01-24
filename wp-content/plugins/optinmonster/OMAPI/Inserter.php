<?php
/**
 * Content Inserter class.
 *
 * @since 2.0.0
 *
 * @package OMAPI
 * @author  Justin Sternberg
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Content Inserter class.
 *
 * @since 2.0.0
 */
class OMAPI_Inserter {

	/**
	 * Content to insert.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $to_insert = '';

	/**
	 * Original content.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $content = '';

	/**
	 * Any tags which we should not insert content in.
	 * The order is important ("a" tag should be after any other tags starting with "a") in order
	 * to avoid false positives (where "a" tag is "found", but it's really an "abbr" tag).
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	const INSERT_AFTER_TAGS = 'address,abbr,acronym,area,audio,a,bdo,big,button,b,caption,cite,code,col,colgroup,del,dfn,dl,dt,em,figure,figcaption,font,h1,h2,h3,h4,h5,h6,hgroup,ins,i,kbd,label,legend,map,mark,menu,pre,samp,small,strike,strong,sub,sup,s,table,tbody,textarea,tfoot,thead,title,track,tt,tr,ul,u,ol,var,video';

	/**
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param string $content   The original content.
	 * @param string $to_insert The content to insert into the original content.
	 */
	public function __construct( $content, $to_insert ) {
		$this->content   = $content;
		$this->to_insert = $to_insert;
	}

	/**
	 * Prepend the original content with the content to insert.
	 *
	 * @since  2.0.0
	 *
	 * @return string The combined content.
	 */
	public function prepend() {
		return $this->to_insert . $this->content;
	}

	/**
	 * Append the original content with the content to insert.
	 *
	 * @since  2.0.0
	 *
	 * @return string The combined content.
	 */
	public function append() {
		return $this->content . $this->to_insert;
	}

	/**
	 * Inserts the insert-content after X paragraphs in the original content.
	 *
	 * @since  2.0.0
	 *
	 * @param  int $paragraph_number The paragraph number to insert after.
	 *
	 * @return string The combined content.
	 */
	public function after_paragraph( $paragraph_number ) {

		// If "0", then prepend.
		if ( empty( $paragraph_number ) ) {
			return $this->prepend();
		}

		$closing_p  = '</p>';
		$paragraphs = explode( $closing_p, $this->content );
		$count      = count( $paragraphs );

		// If the number of paragraphs in the content is less than
		// the number we asked for, just append it to the end.
		if ( $count < $paragraph_number ) {
			return $this->append();
		}

		foreach ( $paragraphs as $index => $paragraph ) {

			// Only add closing tag to non-empty paragraphs.
			if ( trim( $paragraph ) ) {

				// Adding closing markup now, rather than at implode, means insertion
				// is outside of the paragraph markup, and not just inside of it.
				$paragraphs[ $index ] .= $closing_p;
			}

			// + 1 allows for considering the first paragraph as #1, not #0.
			if ( ( $index + 1 ) === $paragraph_number ) {

				// We found our paragraph, so append after it.
				$paragraphs[ $index ] .= $this->to_insert;
			}
		}

		return implode( '', $paragraphs );
	}

	/**
	 * Inserts the insert-content after X words in the original content.
	 *
	 * @since  2.0.0
	 *
	 * @param  int $word_number The word number to insert after.
	 *
	 * @return string The combined content.
	 */
	public function after_words( $word_number ) {

		// If "0", then prepend.
		if ( empty( $word_number ) ) {
			return $this->prepend();
		}

		// The following splitting into words code is copied from the wp_trim_words function.

		$rawtext = wp_strip_all_tags( $this->content );

		/*
		 * translators: If your word count is based on single characters (e.g. East Asian characters),
		 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
		 * Do not translate into your own language.
		 */
		if ( strpos( _x( 'words', 'Word count type. Do not translate!', 'optin-monster-api' ), 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
			$rawtext = trim( preg_replace( "/[\n\r\t ]+/", ' ', $rawtext ), ' ' );
			preg_match_all( '/./u', $rawtext, $words_array );
			$words_array = array_slice( $words_array[0], 0, $word_number + 1 );
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $rawtext, $word_number + 1, PREG_SPLIT_NO_EMPTY );
		}

		// If the number of words in the content is less than
		// the number we asked for, just append it to the end.
		if ( count( $words_array ) <= $word_number ) {
			return $this->append();
		}

		// Now we need to clean up the words, removing punctuation,
		// so our chances of matching are greater.
		foreach ( $words_array as $index => $word ) {
			$words_array[ $index ] = preg_replace( '~[^\w\s]~', '', $word );
		}

		$after_word         = $words_array[ $word_number - 1 ];
		$number_occurrences = 0;

		$rest = array_pop( $words_array );

		foreach ( $words_array as $word ) {
			if ( ! empty( $after_word ) && false !== strpos( $word, $after_word ) ) {
				$number_occurrences++;
			}
		}

		$to_replace = $this->content;

		// We need to loop through the number of occurrences...
		while ( $number_occurrences-- ) {

			// Then find the word in the content to replace...
			$pos = strpos( $to_replace, $after_word ) + strlen( $after_word );

			// And split that content where the word was found...
			$to_replace = substr( $to_replace, $pos );

			// And keep doing that until we've reached our final occurrence.
		}

		// Ok, no we know where we want to insert, but we can't insert inside any
		// of our self::INSERT_AFTER_TAGS tags, so we need to insert _after_ them.
		$to_replace = self::after_tags( $to_replace );

		// Now insert into the content.
		$updated_content = str_replace( $to_replace, $this->to_insert . $to_replace, $this->content );

		return $updated_content;
	}

	/**
	 * Takes given content and returns first acceptable content outside of any
	 * self::INSERT_AFTER_TAGS tags.
	 *
	 * @since  2.0.0
	 *
	 * @param  string $content Content to replace/find.
	 *
	 * @return string           Updated content.
	 */
	protected static function after_tags( $content ) {
		$before = $content;
		foreach ( explode( ',', self::INSERT_AFTER_TAGS ) as $tag ) {
			$opening_tag = '<' . $tag;
			$closing_tag = '</' . $tag;

			$closing_pos = strpos( $content, $closing_tag );

			// If no closing tag found, nothing to do with this tag.
			if ( false === $closing_pos ) {
				continue;
			}

			$opening_pos = strpos( $content, $opening_tag );

			// If closing tag found _after_ opening tag, nothing to do with this tag.
			if ( false !== $opening_pos && $opening_pos < $closing_pos ) {
				continue;
			}

			// Ok... we found a tag that we should scoot behind.
			$split   = substr( $content, $closing_pos + strlen( $closing_tag ) );
			$content = substr( $split, strpos( $split, '>' ) + 1 );
		}

		return $before !== $content
			// Recursive checks until we've gotten them all.
			? self::after_tags( $content )
			: $content;
	}

}

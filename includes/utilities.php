<?php


	/**
	 * Get all of the courses
	 * @param  boolean $any If true, get all courses (including unpublished)
	 * @return [type]       [description]
	 */
	function gmt_courses_get_courses( $any = false ) {
		return get_posts(array(
			'posts_per_page'   => -1,
			'post_type'        => 'gmt_courses',
			'post_status'      => ( $any ? 'any' : 'publish' ),
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
		));
	}



	/**
	 * Get all of the lessons associated with a course
	 * @param  number  $module_id The course ID
	 * @param  boolean $any       If true, get all lessons (including unpublished)
	 * @return array              An array of post objects
	 */
	function gmt_courses_get_lessons( $course_id, $any = false ) {
		return get_posts(array(
			'posts_per_page'   => -1,
			'post_type'        => 'gmt_lessons',
			'post_status'      => ( $any ? 'any' : 'publish' ),
			'meta_key'         => 'gmt_courses_course',
			'meta_value'       => $course_id,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
		));
	}



	/**
	 * Get the next lesson that's not a module
	 * @param  array  $lessons An array of lesson objects
	 * @param  number $current The current lesson ID
	 * @return object          The next lesson object
	 */
	function gmt_courses_get_next_lesson( $lessons, $current ) {
		if ( array_key_exists( $current + 1, $lessons ) ) {
			if ( get_post_meta( $lessons[$current + 1]->ID, 'gmt_courses_lesson_type', true ) === 'module' ) {
				return gmt_courses_get_next_lesson( $lessons, $current + 1 );
			}
			return $lessons[$current + 1];
		}
	}



	/**
	 * Get the previous lesson that's not a module
	 * @param  array  $lessons An array of lesson objects
	 * @param  number $current The current lesson ID
	 * @return object          The previous lesson object
	 */
	function gmt_courses_get_previous_lesson( $lessons, $current ) {
		if ( array_key_exists( $current - 1, $lessons ) ) {
			if ( get_post_meta( $lessons[$current - 1]->ID, 'gmt_courses_lesson_type', true ) === 'module' ) {
				return gmt_courses_get_previous_lesson( $lessons, $current - 1 );
			}
			return $lessons[$current - 1];
		}
	}



	/**
	 * Get the next and previous lessons that are not modules
	 * @param  number $post_id The current lesson ID
	 * @return array           The next and previous lessons
	 */
	function gmt_courses_get_next_and_previous_lessons( $post_id ) {

		// Variables
		$lessons = gmt_courses_get_lessons( get_post_meta( $post_id, 'gmt_courses_course', true ) );
		$current = null;

		// Find the current lesson
		foreach( $lessons as $key => $lesson ) {
			if ( $lesson->ID === $post_id ) {
				$current = $key;
				break;
			}
		}

		if ( is_null( $current ) ) return;

		return array(
			'next' => gmt_courses_get_next_lesson( $lessons, $current ),
			'previous' => gmt_courses_get_previous_lesson( $lessons, $current ),
		);

	}



	/**
	 * Get the first lesson in a course that isn't a module
	 * @todo   Make sure the first lessons isn't a module...
	 * @param  number  $module_id The course ID
	 * @param  boolean $any       If true, get all lessons (including unpublished)
	 * @return object             The course
	 */
	function gmt_courses_get_first_lesson( $course_id, $any = false ) {
		$lessons = gmt_courses_get_lessons( $course_id, $any );
		foreach( $lessons as $lesson ) {
			if ( get_post_meta( $lesson->ID, 'gmt_courses_lesson_type', true ) === 'lesson' ) return $lesson;
		}
	}



	/**
	 * Get the first module in a course
	 * @todo   Make sure the first lessons isn't a module...
	 * @param  number  $module_id The course ID
	 * @param  boolean $any       If true, get all lessons (including unpublished)
	 * @return object             The course
	 */
	function gmt_courses_get_first_module( $course_id, $any = false ) {
		$lessons = gmt_courses_get_lessons( $course_id, $any );
		foreach( $lessons as $lesson ) {
			if ( get_post_meta( $lesson->ID, 'gmt_courses_lesson_type', true ) === 'module' ) return $lesson;
		}
	}
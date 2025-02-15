<?php
/**
 * The main template file
 *
 * @package Mitosis
 */

get_header();

mitosis_content_wrapper_start();
mitosis_archive_header();
mitosis_loop_content();
mitosis_content_wrapper_end();

get_footer();
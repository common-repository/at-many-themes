<?php

class InstallerThema{

	static function install_thema() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		//main theme setup
		$table_name = $wpdb->prefix . 'thema_main';
		$sql = "CREATE TABLE $table_name (
			main_theme_template VARCHAR(255) DEFAULT '' NOT NULL,
			main_theme_stylesheet VARCHAR(255) DEFAULT '' NOT NULL
		) $charset_collate;
		";
		$wpdb->query( $sql );

		$def_theme = wp_get_theme();
		$sql = "
			INSERT INTO $table_name
				(main_theme_template, main_theme_stylesheet)
			VALUES
				('".$def_theme->template."', '".$def_theme->stylesheet."');
		";
		$wpdb->query( $sql );

		//expressions
		$table_name = $wpdb->prefix . 'thema_expressions_settings';
		$sql = "CREATE TABLE $table_name (
			expression_theme_template VARCHAR(255) DEFAULT '' NOT NULL,
			expression_theme_stylesheet VARCHAR(255) DEFAULT '' NOT NULL,
			thema_expression TEXT DEFAULT '' NOT NULL
		) $charset_collate;
		";
		$wpdb->query( $sql );

		//categories
		$table_name = $wpdb->prefix . 'thema_category_settings';
		$sql = "CREATE TABLE $table_name (
			category_id INT NOT NULL,
			for_all TINYINT(1) NOT NULL,
			category_theme_template VARCHAR(255) DEFAULT '' NOT NULL,
			category_theme_stylesheet VARCHAR(255) DEFAULT '' NOT NULL,
			INDEX `category_id` (`category_id`)
		) $charset_collate;
		";
		$wpdb->query( $sql );

		//posts
		$table_name = $wpdb->prefix . 'thema_post_settings';
		$sql = "CREATE TABLE $table_name (
			post_id INT NOT NULL,
			post_theme_template VARCHAR(255) DEFAULT '' NOT NULL,
			post_theme_stylesheet VARCHAR(255) DEFAULT '' NOT NULL,
			INDEX `post_id` (`post_id`)
		) $charset_collate;
		";
		$wpdb->query( $sql );

		//pages
		$table_name = $wpdb->prefix . 'thema_page_settings';
		$sql = "CREATE TABLE $table_name (
			page_id INT NOT NULL,
			page_theme_template VARCHAR(255) DEFAULT '' NOT NULL,
			page_theme_stylesheet VARCHAR(255) DEFAULT '' NOT NULL,
			INDEX `page_id` (`page_id`)
		) $charset_collate;
		";
		$wpdb->query( $sql );

	}

	static function uninstall_thema() {
		global $wpdb;
		$sql = "
			DROP TABLE
				" . $wpdb->prefix . "thema_main,
				" . $wpdb->prefix . "thema_expressions_settings,
				" . $wpdb->prefix . "thema_category_settings,
				" . $wpdb->prefix . "thema_post_settings,
				" . $wpdb->prefix . "thema_page_settings
				;
			";
		$wpdb->query( $sql );
	}
}
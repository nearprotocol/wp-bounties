<?php

function wpb_get_bounties_data() {
	$transient_name = 'wpb_bounties';
	$data = get_transient($transient_name);

	if ($data === false) {
		$request = wp_remote_get('https://api.github.com/repos/near/bounties/issues');

		if (is_wp_error($request)) {
			return false;
		}

		$data = json_decode($request['body']);

		set_transient($transient_name, $data, 15 * MINUTE_IN_SECONDS);
	}

	return $data;
}

function wpb_get_bounties() {
	$bounties = wpb_get_bounties_data();

	// exclude pull requests
	$bounties = array_filter($bounties, function($b) {
		return ! isset($b->pull_request);
	});

	return $bounties;
}

function wpb_get_bounties_labels() {
	$labels = [];

	foreach (wpb_get_bounties() as $bounty) {
		$labels = array_merge($labels, array_column($bounty->labels, 'name'));
	}

	$labels = array_unique($labels);
	$labels = array_values($labels);

	return $labels;
}

function wpb_parse_bounty_title($title = '') {
	$title = explode('| Bounty:', $title);
	$title = array_map('trim', $title);

	return $title;
}

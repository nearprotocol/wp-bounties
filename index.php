<!DOCTYPE html>
<html>
<head>
	<?php wp_head(); ?>
	<style>[x-cloak] { display: none; }</style>
</head>

<body>
	<?php $bounties = wpb_get_bounties(); ?>
	<?php $labels = wpb_get_bounties_labels(); ?>

	<h1>Bounties</h1>

	<div x-data="bounties(<?php echo esc_attr(json_encode($labels)); ?>)">
		<input type="search" x-model="query" placeholder="Search by title …">

		<button x-on:click="filtersOpen = ! filtersOpen">
			<span>Filter:</span>
			<span x-text="filtersLabel()"></span>
		</button>

		<div x-show="filtersOpen" @click.away="filtersOpen = false" x-cloak>
			<?php foreach ($labels as $label) : ?>
				<div>
					<input type="checkbox" id="bounties-filter-<?php echo $label; ?>" x-on:change="toggleType('<?php echo $label; ?>')" checked>
					<label for="bounties-filter-<?php echo $label; ?>"><?php echo $label; ?></label>
				</div>
			<?php endforeach; ?>
		</div>

		<table>
			<thead>
				<tr>
					<th>Issue</th>
					<th>Title</th>
					<th>Bounty</th>
					<th>Claimed By</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($bounties as $bounty) : ?>
					<?php $bounty_labels = array_column($bounty->labels, 'name'); ?>
					<?php $bounty_title_amount = wpb_parse_bounty_title($bounty->title); ?>

					<tr x-show='showBounty(<?php echo esc_attr(json_encode($bounty_labels)); ?>, "<?php echo esc_attr($bounty->title); ?>")' x-cloak>
						<td><?php echo $bounty->number; ?></td>

						<td>
							<h3>
								<a href="<?php echo $bounty->html_url; ?>" target="_blank">
									<span>#<?php echo $bounty->number; ?> </span>
									<?php echo $bounty_title_amount[0]; ?>
								</a>
							</h3>

							<?php if ($bounty->labels ?? false) : ?>
								<ul>
									<?php foreach ($bounty->labels as $label) : ?>
										<li><?php echo $label->name; ?></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</td>

						<td><?php echo $bounty_title_amount[1] ?? '—'; ?></td>

						<td>
							<?php if ($bounty->assignee) : ?>
								<a href="<?php echo $bounty->assignee->html_url; ?>">
									@<?php echo $bounty->assignee->login; ?>
								</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
	<script>
		function bounties(types) {
			return {
				types: types,
				query: '',
				filtersOpen: false,
				filtersLabel: function() {
					var count = this.types.length;
					return count + ' filter' + (count === 1 ? '' : 's');
				},
				toggleType: function(type) {
					var index = this.types.indexOf(type);

					if (index === -1) {
						this.types.push(type);
					} else {
						this.types.splice(index, 1);
					}
				},
				showBounty: function(bountyTypes, title) {
					var filterMatch = this.types.filter(function(v) {
						return bountyTypes.includes(v);
					}).length;

					searchMatch = this.query
						? title.toLowerCase().includes(this.query.toLowerCase())
						: true;

					return filterMatch && searchMatch;
				},
			};
		}
	</script>

	<?php wp_footer(); ?>
</body>
</html>

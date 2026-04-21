<?php

defined('ABSPATH') || exit;

get_header();
?>

	<main class="mx-auto max-w-[1440px] px-4 py-10 md:px-8 lg:px-10">
		<?php if (have_posts()) : ?>
			<div class="grid gap-10">
				<?php while (have_posts()) : the_post(); ?>
					<article <?php post_class('rounded-[24px] bg-white p-6'); ?>>
						<h1 class="text-3xl"><?php the_title(); ?></h1>
						<div class="prose mt-4 max-w-none">
							<?php the_content(); ?>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</main>
<?php
get_footer();

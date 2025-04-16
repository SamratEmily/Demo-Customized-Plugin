<?php
/*
Template Name: Books List
*/
get_header();
?>

<main class="container">
	<h1>Books List</h1>

	<?php
	$args  = array(
		'post_type'      => 'book',
		'posts_per_page' => 10,
		'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
	);
	$books = new WP_Query( $args );

	if ( $books->have_posts() ) :
		?>
		<div class="book-list">
			<?php
            $cnt = 1;
			while ( $books->have_posts() ) :
				$books->the_post();
				?>
				<article class="book-item">
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="book-thumbnail">
								<?php the_post_thumbnail( 'medium' ); ?>
							</div>
						<?php endif; ?>
						<h2> <span><?php $cnt; ?></span><?php the_title(); ?></h2>
						<!-- <p><strong>Genres:</strong> <?php the_terms( get_the_ID(), 'book_genre' ); ?></p>
						<p><strong>Author:</strong> <?php the_terms( get_the_ID(), 'book_author' ); ?></p> -->
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<!-- Pagination -->
		<div class="pagination">
			<?php
			echo paginate_links(
				array(
					'total' => $books->max_num_pages,
				)
			);
			?>
		</div>

		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<p>No books found.</p>
	<?php endif; ?>
</main>


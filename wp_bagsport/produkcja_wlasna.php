<?php /* Template Name: Strona Produkcja własna */ ?>

<?php get_header(); ?>
<div id='wlasna' class=''>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="title stdHeader">
					<?php the_title(); ?>
				</div>
				<div class="basic-content">
					<p>
						<?php if ( have_posts() ):
							the_post();
							the_content();
						else: ?>
							<p>Sorry, no posts matched your criteria.</p>
						<?php endif; ?>
					</p>
				</div>
			</div>
		<?php printProducts("Produkcja własna"); ?>
		</div>
		<!-- /.row -->
	</div>
</div>
<!-- /.container -->
<?php get_footer(); ?>
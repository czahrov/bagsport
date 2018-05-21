<?php /* Template Name: Strona Produkcja własna */ ?>
 
<?php get_header(); ?>
 
<div class="container">
	<div class="row">

        <div class="col-lg-12">



<h1 title="<?php the_title_attribute(); ?>" class="my-4"><?php the_title(); ?></h1>

<div class="basic-content">
        <p>
        	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
the_content();
endwhile; else: ?>
<p>Sorry, no posts matched your criteria.</p>
<?php endif; ?>		</p></div></div>

            <?php printProducts("produkty"); ?>


    </div>
    <!-- /.row -->
</div>
<!-- /.container -->





 
<?php get_footer(); ?>
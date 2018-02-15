<?php get_header(); ?>

<main>
  <h2>this is single.php<h2>
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <h2><?php the_title(); ?></h2>
  <p>category: <?php $categories = get_categories(); foreach($categories as $category) { echo $category->name;} ?></p>
  <p><?php the_time('F jS, Y'); ?></p>
  <div>
    <?php the_content(); ?>
  </div>

  <?php get_template_part('parts/pager', 'single'); ?>
  <?php endwhile;  endif; ?>

  <!-- pagenation -->
</main>
<?php get_footer(); ?>
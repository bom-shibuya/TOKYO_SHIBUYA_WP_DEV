<?php get_header(); ?>

<main>
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
  <a href="<?php the_permalink(); ?>">
    <h2><?php the_title(); ?></h2>
    <p><?php the_time('F jS, Y'); ?></p>
    <div>
      <?php the_content(); ?>
    </div>
  </a>
  <?php endwhile; else: ?>
    <p> Sorry.., we have any post... </p>
  <?php endif; ?>

  <!-- pagenation -->
  <?php get_template_part('parts/pager', 'archive'); ?>
</main>
<?php get_footer(); ?>
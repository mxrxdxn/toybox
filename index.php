<?php get_header(); ?>
    <main>
        <?php if(is_front_page()) {
            the_content();
        } elseif(is_home() || is_category()) {
            get_template_part('archive');
        } else {
            the_content();
        }?>
    </main>
<?php get_footer(); ?>
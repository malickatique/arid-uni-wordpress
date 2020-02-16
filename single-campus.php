<!-- To display individual Events -->

<?php get_header(); 

    while( have_posts() )
    {
        the_post(); 
        page_banner();
        ?>
        
        <div class="container container--narrow page-section">
            
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> 
                        Back to All Campuses
                    </a> 
                    <span class="metabox__main"> <?php the_title(); ?> </span>
                </p>
            </div>

            <div class="generic-content">
                <?php the_content(); ?>
                
                <div class="acf-map">
                    <?php $mapLocation = get_field('map_location'); ?>
                    <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
                        <h3> <?php the_title(); ?> </h3>
                        <?php echo $mapLocation['address'] ?>
                    </div>
                </div>

            </div>

            <?php

                $relatedPrograms = new WP_Query(array(
                    'posts_per_page' => -1,
                    'post_type' => 'program',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'related_campus',
                            'compare' => 'LIKE',
                            'value' => '"'.get_the_ID().'"',
                        ),
                    ),
                ));

                if( $relatedPrograms->have_posts() )
                {
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Programs Available At '. get_the_title() .': </h2>';
                    echo '<ul class="professor-cards">';
                    while( $relatedPrograms->have_posts() )
                    {
                        $relatedPrograms->the_post();
                    ?>

                    <ul class="min-list link-list"> 
                        <a href="<?php the_permalink(); ?>">
                            <li> <?php the_title(); ?> </li> 
                        </a> 
                    </ul>

                <?php
                    echo '</ul>';
                    wp_reset_postdata();
                } 
                }

                $today = date('Ymd');
                $result = new WP_Query(array(
                    'posts_per_page' => 3,
                    'post_type' => 'event',
                    'orderby' => 'meta_value_num',
                    'meta_key' => 'event_date',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'event_date',
                            'compare' => '>=',
                            'value' => $today,
                            'type' => 'numberic',
                        ),
                        array(
                            'key' => 'related_programs',
                            'compare' => 'LIKE',
                            'value' => '"'.get_the_ID().'"',
                        ),
                    ),
                ));

                if( $result->have_posts() )
                {
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Upcoming '. get_the_title() .' Events</h2>';
                    while( $result->have_posts() )
                    {
                        $result->the_post();
                        get_template_part('template-parts/content', 'event');
                    wp_reset_postdata();
                } 
            }?> 


        </div>

    <?php
    }

    get_footer();
?>

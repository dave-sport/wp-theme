<?php
/**
 * Homepage Category Section — Clean reusable template part.
 * 
 * Shows a parent category's top N subcategories as tabs,
 * each with 1 featured post + 4 smaller posts.
 * Falls back to parent category directly if no subcategories.
 * 
 * @param array $args['parent_slug'] Parent category slug
 * @param array $args['sub_count']   Number of subcategory tabs (default 3)
 */

$parent_slug = $args['parent_slug'] ?? '';
$sub_count = $args['sub_count'] ?? 3;

if (empty($parent_slug))
    return;

$parent_cat = get_category_by_slug($parent_slug);
if (!$parent_cat)
    return;

$parent_cat_id = $parent_cat->term_id;

$subcategories = get_categories([
    'parent' => $parent_cat_id,
    'orderby' => 'count',
    'order' => 'DESC',
    'number' => $sub_count,
    'hide_empty' => true,
]);

if (empty($subcategories)) {
    $subcategories = [$parent_cat];
}

$section_id = 'section-' . $parent_slug;
?>

<section class="section-category" id="<?php echo esc_attr($section_id); ?>">
    <div class="container">
        <div class="section-category__header">
            <h2 class="section-category__title"><?php echo esc_html($parent_cat->name); ?></h2>
            <?php if (count($subcategories) > 1): ?>
                <div class="section-category__tabs">
                    <?php foreach ($subcategories as $i => $subcat): ?>
                        <button class="section-category__tab <?php echo $i === 0 ? 'active' : ''; ?>"
                            data-filter=".cat-<?php echo esc_attr($subcat->slug); ?>">
                            <?php echo esc_html($subcat->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php foreach ($subcategories as $i => $subcat):
            $posts = get_posts([
                'category' => $subcat->term_id,
                'numberposts' => 5,
                'post_status' => 'publish',
            ]);

            if (empty($posts))
                continue;
            $featured = $posts[0];
            $small_posts = array_slice($posts, 1, 4);
            ?>
            <div class="section-category__content cat-<?php echo esc_attr($subcat->slug); ?>"
                style="<?php echo $i > 0 ? 'display:none;' : ''; ?>">
                <div class="section-category__grid">
                    <!-- Featured post -->
                    <article class="section-card section-card--large">
                        <a href="<?php echo get_permalink($featured); ?>" class="section-card__link">
                            <div class="section-card__img">
                                <?php if (has_post_thumbnail($featured)): ?>
                                    <img src="<?php echo get_the_post_thumbnail_url($featured, 'large'); ?>"
                                        alt="<?php echo esc_attr($featured->post_title); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="section-card__body">
                                <span class="section-card__cat"><?php echo esc_html($subcat->name); ?></span>
                                <h3 class="section-card__title"><?php echo esc_html($featured->post_title); ?></h3>
                                <div class="section-card__meta">
                                    <span><?php echo get_the_author_meta('display_name', $featured->post_author); ?></span>
                                    <span>&middot;</span>
                                    <span><?php echo moodco_time_ago($featured->ID); ?></span>
                                </div>
                            </div>
                        </a>
                    </article>

                    <!-- Smaller posts -->
                    <div class="section-card__small-grid">
                        <?php foreach ($small_posts as $post): ?>
                            <article class="section-card section-card--small">
                                <a href="<?php echo get_permalink($post); ?>" class="section-card__link">
                                    <div class="section-card__img">
                                        <?php if (has_post_thumbnail($post)): ?>
                                            <img src="<?php echo get_the_post_thumbnail_url($post, 'medium'); ?>"
                                                alt="<?php echo esc_attr($post->post_title); ?>">
                                        <?php endif; ?>
                                        <span
                                            class="section-card__cat mergeWithThumb"><?php echo esc_html($subcat->name); ?></span>
                                    </div>
                                    <div class="section-card__body">

                                        <h4 class="section-card__title"><?php echo esc_html($post->post_title); ?></h4>
                                        <div class="section-card__meta">
                                            <span
                                                class="section-card__time"><?php echo get_the_author_meta('display_name', $featured->post_author); ?></span>
                                            <span>&middot;</span>
                                            <span
                                                class="section-card__time"><?php echo moodco_time_ago($featured->ID); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
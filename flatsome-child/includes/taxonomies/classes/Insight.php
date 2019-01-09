<?php
namespace includes\taxonomies;

class Insight extends \includes\classes\Taxonomy {
    public $name='cat-insight';
    public function getAttributes()
    {
        return  array(
            'hierarchical'          => true,
            'labels'                => $this->getConfigs(),
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array( 'slug' => $this->name ),
        );
    }


    public function getConfigs()
    {
        return [
            'name'                       => translate_i18n('Insights'),
            'singular_name'              => translate_i18n('Insight'),
            'search_items'               => translate_i18n('Search Insights'),
            'popular_items'              => translate_i18n('Popular Insights'),
            'all_items'                  => translate_i18n('All Insights'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => translate_i18n('Edit Insight'),
            'update_item'                => translate_i18n('Update Insight'),
            'add_new_item'               => translate_i18n('Add New Insight'),
            'new_item_name'              => translate_i18n('New Insight Name'),
            'separate_items_with_commas' => translate_i18n('Separate insights with commas'),
            'add_or_remove_items'        => translate_i18n('Add or remove insights'),
            'choose_from_most_used'      => translate_i18n('Choose from the most used insights\''),
            'not_found'                  => translate_i18n('No insights found.'),
            'menu_name'                  => translate_i18n('Insights'),
        ];
    }
}
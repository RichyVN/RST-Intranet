<p>
  <small><?php Echo $this->t('Please select the taxonomies you need to classify your galleries.') ?></small>
</p>

<?php
$active_taxonomies = (Array) $this->get_option('gallery_taxonomies');
ForEach ($this->arr_taxonomies AS $taxonomy => $tax_args) : ?>
<p>
  <input type="checkbox" name="gallery_taxonomies[<?php Echo $taxonomy ?>][name]" id="gallery_taxonomies_<?php Echo $taxonomy ?>" value="<?php Echo $taxonomy ?>" <?php Checked(IsSet($active_taxonomies[$taxonomy])) ?> />
  <label for="gallery_taxonomies_<?php Echo $taxonomy ?>"><?php Echo $tax_args['labels']['name'] ?></label>
  (<input type="checkbox" name="gallery_taxonomies[<?php Echo $taxonomy ?>][hierarchical]" id="gallery_taxonomies_<?php Echo $taxonomy ?>_hierarchical" <?php Checked(IsSet($active_taxonomies[$taxonomy]['hierarchical'])) ?>>
  <label for="gallery_taxonomies_<?php Echo $taxonomy ?>_hierarchical"><?php Echo $this->t('hierarchical') ?></label>)
</p>
<?php EndForEach;
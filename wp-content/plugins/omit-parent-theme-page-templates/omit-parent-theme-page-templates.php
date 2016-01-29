<?php
/**
 * Plugin Name: Omit Parent Theme Page Templates
 * Plugin URI: http://wordpress.org/extend/plugins/omit-parent-theme-page-templates
 * Description: Simply omits parent page templates from the page edit screen in the WordPress admin.
 * Version: 1.2a
 * Author: NewClarity
 * Author URI: http://newclarity.net
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Copyright 2013 NewClarity LLC.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 */

/**
 * Add the instantiate() method on plugins_loaded hook to trigger addition of actions and filters required by this plugin.
 */
add_action( 'plugins_loaded', array( 'Omit_Parent_Theme_Page_Templates', 'instantiate' ) );
/**
 * Class Omit_Parent_Theme_Page_Templates
 */
class Omit_Parent_Theme_Page_Templates {

  /**
   * @var Omit_Parent_Theme_Page_Templates $_instance
   */
  private static $_instance;

  /**
   * @var array $_child_page_templates
   */
  private static $_child_page_templates;

  /**
   * Provides the site owner access to the instance of this singleton class in case they need it.
   *
   * @return Omit_Parent_Theme_Page_Templates
   */
  static function self() {
    return self::$_instance;
  }

  /**
   * Allow site owner to remove or chain the plugins_loaded hook.
   *
   * @example:
   *
   *         remove_action( 'plugins_loaded', array( 'Omit_Parent_Theme_Page_Templates', 'instantiate' ) );
   */
  static function instantiate() {
    self::$_instance = new Omit_Parent_Theme_Page_Templates();
  }

  /**
   * Sets up the hooks needed to strip parent page templates.
   */
  function __construct() {
    add_action( 'submitpage_box', array( $this, '_submitpage_box' ) );
    add_action( 'edit_page_form', array( $this, '_edit_page_form' ) );
    add_filter( 'quick_edit_dropdown_pages_args', array( $this, '_quick_edit_dropdown_pages_args' ) );
    add_action( 'in_admin_footer', array( $this, '_in_admin_footer' ) );

  }

  /**
   * Hook used for Quick Edit to initiate page buffering and/or to remove the parent page templates.
   *
   * @example URL: http://{$your_site_domain}/wp-admin/edit.php?post_type=page
   *
   * @note This hook is called once before the page template dropdown we need to modify, for quickedits
   *       for individual pages, and a second time for the bulk quickedits. That's why we don't strip until
   *       the second time through, and that's why we buffer after the second time but only after the second
   *       time.
   *
   *
   */
  function _quick_edit_dropdown_pages_args() {
    static $counter = 1;
    if ( 2 == $counter ) {
      /*
       * If 2nd time through then strip parent page templates for individual quickedit use-case.
       */
      $this->_quick_edit_strip();
    }
    /*
     * First time buffering inidividual quickedit strippage in this function, and
     * second time buffering will be for 'bulk edit' strippage in $this->_in_admin_footer().
     */
    ob_start();
    $counter++;
  }

  /**
   * Strips parent page template for the 'bulk edit' use-case.
   *
   * Make sure that we only do this on the "All Pages" admin edit list where Quick Edit is available.
   */
  function _in_admin_footer() {
    global $pagenow, $typenow;
    if ( $pagenow == 'edit.php' && 'page' == $typenow ) {
      /*
       * If we are in the 'All Pages' admin list that has quick edit
       * the strip parent page templates for the 'bulk edit' use-case.
       */
      $this->_quick_edit_strip();
    }
  }

  /**
   * Strip parent page templates for the 'All Pages' admin list that has quick edit
   */
  private function _quick_edit_strip() {
    $html = ob_get_clean();
    $regex = '<select\s*?name="page_template">';
    $html = $this->_remove_parent_templates( $html, $regex );
    echo $html;
  }
  /**
   * Starts buffering for $this->_edit_page_form() on an 'Add Page' or 'Edit Page' page in the admin.
   *
   * @example URLs:
   *          http://{$your_site_domain}/wp-admin/post-new.php?post_type=page
   *          http://{$your_site_domain}/wp-admin/post.php?post={$post_id}&action=edit
   */
  function _submitpage_box() {
    ob_start();
  }

  /**
   * Removes Parent Templates from an 'Add Page' or 'Edit Page' page in the admin.
   *
   * @example URLs:
   *          http://{$your_site_domain}/wp-admin/post-new.php?post_type=page
   *          http://{$your_site_domain}/wp-admin/post.php?post={$post_id}&action=edit
   */
  function _edit_page_form() {
    $html = ob_get_clean();
    $regex = '<select\s*name="page_template"\s*id="page_template".*?>';
    $html = $this->_remove_parent_templates( $html, $regex );
    echo $html;
  }

  /**
   * Removes Parent Templates using fragile regex logic.
   *
   * Why do we use fragile regex logic? Because WordPress core doesn't provide any hooks to let us do so robustly.
   *
   * @param string $html
   * @param string $regex
   *
*@return string
   */
  private function _remove_parent_templates( $html, $regex ) {
    if ( preg_match( "#^(.*{$regex})(.*?)(</select>.*)$#sm", $html, $outer_match ) ) {
      preg_match_all( "#(<option\s*value=\s*['\"]([^']+?|[^\"]+?)['\"].*?>(.*?)</option>)#sm", $outer_match[2], $inner_matches, PREG_SET_ORDER );
      $child_page_templates = array( 'default' => __( 'Default Template' ) );
      foreach( $this->_get_child_page_templates() as $file => $name ) {
        $child_page_templates[$file] = $name;
      }
      foreach( $inner_matches as $index => $matches ) {
        if ( isset( $child_page_templates[$matches[2]] ) ) {
          $child_page_templates[$matches[2]] = $inner_matches[$index][0];
        }
      }
      $html = $outer_match[1] . implode( "\n", $child_page_templates ). $outer_match[3];
    }
    return $html;
  }

  /**
   * Return just the child page templates as an associate array.
   *
   * Uses WordPress WP_Theme->get_page_templates() on child, which gets the list of parent themes too,
   * and then uses WP_Theme->parent()->get_page_templates() to get the parent's template and finally
   * removes the parent's templates from the child's list of templates.
   *
   * @return array Keys = File path, Values = Readable description
   */
  private function _get_child_page_templates() {
    if ( ! isset( self::$_child_page_templates ) ) {
      /**
       * @var WP_Theme $theme
       */
      $theme = wp_get_theme();
      $page_templates = $theme->get_page_templates();
      if ( $parent = $theme->parent() ) {
        $parent_page_templates = $parent->get_page_templates();
        self::$_child_page_templates = array_diff_key( $page_templates, $parent_page_templates );
      } else {
        self::$_child_page_templates = $page_templates;
      }
    }
    return self::$_child_page_templates;
  }

}
